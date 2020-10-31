<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/25/19
 * Time: 11:35
 */

defined ( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Async_Request', false ) ) {
	include_once PPW_DIR_PATH . '/includes/libs/wp-background-process/wp-async-request.php';
}

if ( ! class_exists( 'WP_Background_Process', false ) ) {
	include_once PPW_DIR_PATH . '/includes/libs/wp-background-process/wp-background-process.php';
}

if ( ! class_exists( 'PPW_Pro_Background_Task' ) ) {
	/**
	 * Class PPW_Background_Task
	 */
	abstract class PPW_Background_Task extends WP_Background_Process {
		protected $current_item;

		protected $manager;

		public function __construct( $manager ) {
			$this->manager = $manager;
			$this->prefix  = 'ppw_' . get_current_blog_id();
			$this->action  = $this->manager->get_action();
			parent::__construct();
		}

		/**
		 * Dispatch updater
		 *
		 * Updater will still run via cron job if this fails for any reason.
		 */
		public function dispatch() {
			$dispatched = parent::dispatch();

			if ( is_wp_error( $dispatched ) ) {
				wp_die( $dispatched );
			}
		}

		public function query_col( $sql ) {
			global $wpdb;

			// Add Calc.
			$item = $this->get_current_item();
			if ( empty( $item['total'] ) ) {
				$sql = preg_replace( '/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql );
			}

			// Add offset & limit.
			$sql = preg_replace( '/;$/', '', $sql );
			$sql .= ' LIMIT %d, %d;';

			$results = $wpdb->get_col( $wpdb->prepare( $sql, $this->get_current_offset(), $this->get_limit() ) ); // WPCS: unprepared SQL OK.

			if ( ! empty( $results ) ) {
				$this->set_total();
			}

			return $results;
		}

		/**
		 * @return mixed
		 */
		public function get_current_item() {
			error_log( 'Current item: ' . wp_json_encode( $this->current_item ) );
			return $this->current_item;
		}

		/**
		 * Get batch.
		 *
		 * @return \stdClass Return the first batch from the queue.
		 */
		protected function get_batch() {
			$batch       = parent::get_batch();
			$batch->data = array_filter( (array) $batch->data );

			return $batch;
		}


		public function get_current_offset() {
			$limit = $this->get_limit();

			return ( $this->current_item['iterate_num'] - 1 ) * $limit;
		}

		public function get_limit() {
			return $this->manager->get_query_limit();
		}

		public function set_total() {
			global $wpdb;

			if ( empty( $this->current_item['total'] ) ) {
				$total_rows                  = $wpdb->get_var( 'SELECT FOUND_ROWS();' );
				$total_iterates              = ceil( $total_rows / $this->get_limit() );
				$this->current_item['total'] = $total_iterates;
			}
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 */
		protected function complete() {
			$this->manager->on_runner_complete( true );

			parent::complete();
		}

		public function continue_run() {
			// Used to fire an action added in WP_Background_Process::_construct() that calls WP_Background_Process::handle_cron_healthcheck().
			// This method will make sure the database updates are executed even if cron is disabled. Nothing will happen if the updates are already running.
			do_action( $this->cron_hook_identifier );
		}

		protected function task( $item ) {
			$result = false;

			if ( is_callable( $item['callback'] ) ) {
				$result = (bool) call_user_func( $item['callback'], $this );
			}

			return $result ? $item : false;
		}

		/**
		 * See if the batch limit has been exceeded.
		 *
		 * @return bool
		 */
		protected function batch_limit_exceeded() {
			return $this->time_exceeded() || $this->memory_exceeded();
		}

		/**
		 * Handle.
		 *
		 * Pass each queue item to the task handler, while remaining
		 * within server memory and time limit constraints.
		 */
		protected function handle() {
			$this->manager->on_runner_start();

			$this->lock_process();

			do {
				$batch = $this->get_batch();

				foreach ( $batch->data as $key => $value ) {
					$task = $this->task( $value );

					if ( false !== $task ) {
						$batch->data[ $key ] = $task;
					} else {
						unset( $batch->data[ $key ] );
					}

					if ( $this->batch_limit_exceeded() ) {
						// Batch limits reached.
						break;
					}
				}

				// Update or delete current batch.
				if ( ! empty( $batch->data ) ) {
					$this->update( $batch->key, $batch->data );
				} else {
					$this->delete( $batch->key );
				}
			} while ( ! $this->batch_limit_exceeded() && ! $this->is_queue_empty() );

			$this->unlock_process();

			// Start next batch or complete process.
			if ( ! $this->is_queue_empty() ) {
				$this->dispatch();
			} else {
				$this->complete();
			}
		}

		/**
		 * Use the protected `is_process_running` method as a public method.
		 * @return bool
		 */
		public function is_process_locked() {
			return $this->is_process_running();
		}

		/**
		 * Is running?
		 *
		 * @return boolean
		 */
		public function is_running() {
			return false === $this->is_queue_empty();
		}

		/**
		 * Schedule cron healthcheck.
		 *
		 * @param array $schedules Schedules.
		 *
		 * @return array
		 */
		public function schedule_cron_healthcheck( $schedules ) {
			$interval = apply_filters( $this->identifier . '_cron_interval', 5 );

			// Adds every 5 minutes to the existing schedules.
			$schedules[ $this->identifier . '_cron_interval' ] = array(
				'interval' => MINUTE_IN_SECONDS * $interval,
				/* translators: %d: interval */
				'display'  => sprintf( __( 'Every %d minutes', 'password-protect-page' ), $interval ),
			);

			return $schedules;
		}

		/**
		 * Delete all batches.
		 *
		 * @return self
		 */
		public function delete_all_batches() {
			global $wpdb;

			$table  = $wpdb->options;
			$column = 'option_name';

			if ( is_multisite() ) {
				$table  = $wpdb->sitemeta;
				$column = 'meta_key';
			}

			$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

			$wpdb->query( $wpdb->prepare( "DELETE FROM {$table} WHERE {$column} LIKE %s", $key ) ); // @codingStandardsIgnoreLine.

			return $this;
		}

		/**
		 * Kill process.
		 *
		 * Stop processing queue items, clear cronjob and delete all batches.
		 */
		public function kill_process() {
			if ( ! $this->is_queue_empty() ) {
				$this->delete_all_batches();
				wp_clear_scheduled_hook( $this->cron_hook_identifier );
			}
		}

		/**
		 * Handle cron healthcheck
		 *
		 * Restart the background process if not already running
		 * and data exists in the queue.
		 */
		public function handle_cron_healthcheck() {
			error_log( 'Checking health-check: ' . wp_json_encode( 'Hello World' ) );
			if ( $this->is_process_running() ) {
				error_log( 'PPWP - Background process is running' );
				// Background process already running.
				return;
			}

			if ( $this->is_queue_empty() ) {
				// No data to process.
				$this->clear_scheduled_event();
				return;
			}

			$this->handle();
		}

		protected function schedule_event() {
			if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
				wp_schedule_event( time() + 20, $this->cron_interval_identifier, $this->cron_hook_identifier );
			}
		}
	}
}

