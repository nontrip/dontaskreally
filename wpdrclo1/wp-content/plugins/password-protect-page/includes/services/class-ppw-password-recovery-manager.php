<?php

class PPW_Password_Recovery_Manager extends PPW_Background_Task_Manager {
	private $completed;

	public function __construct() {
		$this->handle_admin_notices();
		parent::__construct();
	}

	protected function handle_admin_notices() {
		if ( ! is_admin() ) {
			return;
		}
		$action = 'admin_notices';

		if ( $this->is_completed() ) {
			add_action( $action, [ $this, 'admin_notice_upgrade_is_completed' ] );
		}

		if ( $this->is_running() ) {
			add_action( $action, [ $this, 'admin_notice_upgrade_is_running' ] );
		}
	}

	public function is_running() {
		$task_runner = $this->get_task_runner();

		return $task_runner->is_running();
	}

	public function is_completed() {
		if ( isset( $this->completed ) ) {
			return $this->completed;
		}
		$this->completed = $this->get_flag( 'completed' );

		return $this->completed;
	}

	public function get_task_runner_class() {
		return 'PPW_Password_Recovery';
	}

	public function get_query_limit() {
		return 100;
	}

	public function on_runner_complete( $did_tasks = false ) {
		// Implement log here
		if ( $did_tasks ) {
			$this->add_flag( 'completed' );
		}
	}

	public function start_run() {
		$updater = $this->get_task_runner();

		if ( $updater->is_running() ) {
			return;
		}

		$restore_password_callbacks = $this->get_restore_password_callbacks();

		if ( empty( $restore_password_callbacks ) ) {
			$this->on_runner_complete();

			return;
		}

		foreach ( $restore_password_callbacks as $callback ) {
			$updater->push_to_queue( [
				'callback' => $callback
			] );
		}

		$updater->save()->dispatch();
	}

	public function get_restore_password_callbacks() {
		$callbacks[] = [ 'PPW_Password_Recovery_Manager', 'restore_passwords' ];

		return $callbacks;
	}

	public static function restore_passwords() {
		return ( new PPW_Password_Services() )->restore_wp_post_password();
	}

	public function admin_notice_upgrade_is_running() {
		$continue_action = $this->get_continue_action_url();
		$message         = '<p>'
		                   . __( 'Password recovery process is running in the background.', 'password-protect-page' )
		                   . '</p>'
		                   . '<p>'
		                   . sprintf( 'Taking a while? <a href="%s" class="button-primary">Click here to run it now</a>', $continue_action )
		                   . '</p>';
		echo '<div class="notice notice-warning">' . $message . '</div>';
	}

	public function admin_notice_upgrade_is_completed() {
		$this->delete_flag( 'completed' );
		$message = $this->get_success_message();
		if ( ! empty( $message ) ) {
			echo '<div class="notice notice-success">' . $message . '</div>';
		}
	}

	public function get_action() {
		return 'password_recovery_process';
	}

	public function get_plugin_name() {
		return 'ppw';
	}

	public function get_plugin_label() {
		return __( PPW_PLUGIN_NAME, 'password-protect-page' );
	}

	public function get_name() {
		return 'ppw-password-recovery';
	}

	public function get_success_message() {
		return '<p>' . sprintf( __( '%s <a href="https://passwordprotectwp.com/docs/password-migration/#backup" target="_blank" rel="noopener noreferrer">Password recovery process</a> is now complete.. Thank you for your patience!', 'password-protect-page' ), $this->get_updater_label() ) . '</p>';
	}

	public function get_updater_label() {
		return sprintf( '<strong>%s </strong> &#8211;', __( 'Password Protect WordPress', 'password-protect-page' ) );
	}
}
