<?php

/**
 *
 * Class PPW_Category
 */
class PPW_Category_Service {
	const COOKIE_NAME = 'ppw_cat-';
	const OPTION_NAME = 'ppwp_category_options';
	const SHARED_CATEGORY_TYPE = 'shared_category';

	/**
	 * @var null|integer Category ID.
	 */
	private $category_id = null;

	/**
	 * Is using post password required
	 * @var bool
	 */
	private $unlocked = false;

	/**
	 * @var null|PPW_Category_Service Instance.
	 */
	protected static $instance = null;

	private $is_pro_activated;

	/**
	 * Get instance.
	 *
	 * @return PPW_Category_Service
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Register hooks.
	 *
	 * @param bool $is_pro_activated Is pro activated
	 */
	public function register( $is_pro_activated = false ) {
		$this->is_pro_activated = $is_pro_activated;
		$this->unlocked         = $this->is_pro_activated;

		add_filter( 'post_password_required', array( $this, 'category_password_required' ), 15, 2 );
		add_filter( 'ppw_is_valid_password', array( $this, 'check_valid_password' ), 15, 3 );
		add_action( 'category_pre_add_form', array( $this, 'display_category_ui' ) );

		// Check post is protected before.
		add_filter( 'ppw_is_valid_cookie', array( $this, 'ppw_is_valid_cookie' ) );
		add_filter( 'ppwp_post_password_required', array( $this, 'ppwp_post_password_required' ), 100 );
	}

	/**
	 * Check if content is protected and unlocked password form.
	 *
	 * @param boolean $is_valid
	 *
	 * @return bool True is valid cookie.
	 */
	public function ppw_is_valid_cookie( $is_valid ) {
		$this->unlocked = $is_valid;

		return $is_valid;
	}

	/**
	 * If post is protected and
	 *
	 * @param array $data Unlock data from PPWP Fro.
	 *
	 * @return mixed
	 */
	public function ppwp_post_password_required( $data ) {
		if ( ! isset( $data['is_content_unlocked'] ) ) {
			return $data;
		}
		$this->unlocked = $data['is_post_protected'] && $data['is_content_unlocked'];

		return $data;
	}

	/**
	 * Display category UI.
	 */
	public function display_category_ui() {
		$categories           = get_categories(
			array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			)
		);
		$is_protect           = ppw_core_get_setting_type_bool_by_option_name( 'ppwp_is_protect_category', self::OPTION_NAME );
		$protected_categories = ppw_core_get_setting_type_array_by_option_name( 'ppwp_protected_categories_selected', self::OPTION_NAME );

		// Get first password to display to user.
		$passwords = PPW_Repository_Passwords::get_instance()->get_all_shared_categories_password();
		if ( count( $passwords ) > 0 ) {
			$password = $passwords[0]->password;
		} else {
			$password = '';
		}

		ob_start();
		include PPW_DIR_PATH . 'includes/views/category/view-option.php';
		echo ob_get_clean();
	}

	/**
	 * Filters whether a category requires the user to supply a password.
	 *
	 * @param bool Whether the user needs to supply a password.
	 *             True if password has not been provided or is incorrect,
	 *             false if password has been supplied or is not required.
	 *
	 * @return bool false if a password is not required or the correct password cookie is present, true otherwise.
	 */
	public function category_password_required( $required, $post ) {
		$post_id = ! empty( $post ) ? $post->ID : false;
		if ( ! $post_id ) {
			return $required;
		}

		// Get all protected categories of a Post.
		$protected_categories = $this->get_protected_categories( $post_id );
		if ( empty( $protected_categories ) ) {
			return $required;
		}

		if ( $this->is_valid_cookie() ) {
			return false;
		}

		add_filter( 'ppwp_ppf_action_url', array( $this, 'get_action_url' ), 9999 );

		// Unlocked by PPWP Free and PPWP Pro
		// Include Single, AL, Group.
		if ( apply_filters( 'ppw_category_unlocked', $this->unlocked, $post_id, $protected_categories ) ) {
			$this->unlocked = $this->is_pro_activated;

			return $required;
		}

		return true;
	}

	/**
	 * Get protected categories if user turn on Option.
	 * 
	 * @param integer $post_id Post ID.
	 * 
	 * @return array Empty if user turn off option or post ID not include protected categories.
	 */
	public function get_protected_categories( $post_id ) {
		// Check user has turn on option.
		$enabled = ppw_core_get_setting_type_bool_by_option_name( 'ppwp_is_protect_category', self::OPTION_NAME );
		$enabled = apply_filters( 'ppw_category_is_enabled', $enabled, $post_id );
		if ( ! $enabled ) {
			return array();
		}

		$categories_id = $this->get_categories_by_post_id( $post_id );

		// Not handle with categories of post are empty.
		if ( empty( $categories_id ) ) {
			return array();
		}

		// Get protected categories of current post.
		$protected_categories = ppw_core_get_setting_type_array_by_option_name( 'ppwp_protected_categories_selected', self::OPTION_NAME );;
		$protected_categories = array_intersect( $categories_id, $protected_categories );

		// Reorder index of array.
		return array_values( $protected_categories );
	}

	/**
	 * Replace current action by action URL of category to check password.
	 */
	public function get_action_url( $url ) {
		if ( is_category() ) {
			$callback_url = get_category_link( get_queried_object_id() );
		} elseif ( is_singular() ) {
			$callback_url = get_the_permalink();
		} else {
			global $wp;
			$callback_url = home_url( $wp->request );
		}

		$callback_url = rawurlencode( $callback_url );

		return add_query_arg(
			array(
				PPW_Constants::CALL_BACK_URL_PARAM => $callback_url,
			),
			$url
		);
	}

	/**
	 * Check password is valid after show message if it is wrong password.
	 *
	 * @param bool   $is_valid Is valid password.
	 * @param int    $post_id  Post ID.
	 * @param string $password Password.
	 *
	 * @return bool True if password is valid.
	 */
	public function check_valid_password( $is_valid, $post_id, $password ) {
		$protected_categories = $this->get_protected_categories( $post_id );
		if ( empty( $protected_categories ) ) {
			return $is_valid;
		}

		return apply_filters( 'ppw_category_is_valid_password', $this->is_valid_password( $protected_categories, $password, $post_id ), $is_valid, $protected_categories, $post_id, $password ) || $is_valid;
	}

	/**
	 * Set password to cookie
	 *
	 * @param string    $password    Password string.
	 * @param string    $cookie_name Cookie name.
	 * @param false|int $password_id Password ID.
	 *
	 * @return bool
	 */
	public function set_password_to_cookie( $password, $cookie_name, $password_id = false ) {
		$password_hashed         = wp_hash_password( $password );
		$expire                  = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + 7 * DAY_IN_SECONDS );
		$password_cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
		if ( ! empty( $password_cookie_expired ) ) {
			$time = explode( ' ', $password_cookie_expired )[0];
			$unit = ppw_core_get_unit_time( $password_cookie_expired );
			if ( 0 !== $unit ) {
				$expire = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + (int) $time * $unit );
			}
		}

		$referer = wp_get_referer();
		if ( $referer ) {
			$secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
		} else {
			$secure = false;
		}

		if ( $password_id ) {
			$password_hashed = $password_id . '|' . $password_hashed;
		}

		$expire = apply_filters( 'ppw_cookie_expire', $expire );
		return setcookie( $cookie_name . COOKIEHASH, $password_hashed, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );
	}

	/**
	 * Handle password with data sent by user.
	 *
	 * @param array   $categories_id Categories ID.
	 * @param string  $password      Password.
	 * @param integer $post_id       Post ID.
	 *
	 * @return bool
	 */
	public function is_valid_password( $categories_id, $password, $post_id ) {
		// Get current roles will empty if user using subdomain because path of cookie.
		$password_info = PPW_Repository_Passwords::get_instance()->find_by_shared_category_password( $password );
		if ( ! $password_info ) {
			return false;
		}
		$this->set_password_to_cookie( $password, self::COOKIE_NAME, $password_info->id );
		$this->set_password_to_cookie( $password, PPW_Constants::WP_POST_PASS );

		do_action( 'ppw_category_after_set_password_to_cookie', $categories_id, $password_info, $post_id );

		return true;
	}

	/**
	 * Is valid cookie.
	 *
	 * @return bool
	 */
	public function is_valid_cookie() {
		if ( ! isset( $_COOKIE[ self::COOKIE_NAME . COOKIEHASH ] ) ) {
			return false;
		}

		$cookie_value = $_COOKIE[ self::COOKIE_NAME . COOKIEHASH ];
		$cookie_value = explode( '|', $cookie_value );
		if ( count( $cookie_value ) < 2 ) {
			return false;
		}
		$password_id     = (int) $cookie_value[0];
		$password_hashed = $cookie_value[1];

		$password_info = PPW_Repository_Passwords::get_instance()->get_shared_category_password( $password_id );
		if ( ! $password_info ) {
			return false;
		}

		if ( ppw_free_check_password( $password_info->password, $password_hashed ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get categories by post ID.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array|WP_Error The requested term data or empty array if no terms found. WP_Error if any of the
	 *                        taxonomies don't exist.
	 * @link   https://developer.wordpress.org/reference/functions/wp_get_post_categories/
	 */
	public function get_categories_by_post_id( $post_id ) {
		$terms = get_the_terms( $post_id, $this->get_current_taxonomy( $post_id ) );

		if ( empty( $terms ) ) {
			return array();
		}

		return array_map(
			function ( $term ) {
				return $term->term_id;
			},
			$terms
		);
	}

	/**
	 * Get current taxonomy from Post ID.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string Current taxonomy.
	 */
	public function get_current_taxonomy( $post_id ) {
		return apply_filters( 'ppw_category_get_taxonomy', 'category', $post_id );
	}
}

