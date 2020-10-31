<?php
/**
 * Functions and Hooks for product meta box data
 *
 * @package Supro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * supro_Meta_Box_Product_Data class.
 */
class Supro_Meta_Box_Product_Data {

	/**
	 * Constructor.
	 */
	public function __construct() {

		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Add form
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_meta_fields' ) );
		add_action( 'woocommerce_product_data_tabs', array( $this, 'product_meta_tab' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'product_meta_fields_save' ) );

		add_action( 'wp_ajax_product_meta_fields', array( $this, 'instance_product_meta_fields' ) );
		add_action( 'wp_ajax_nopriv_product_meta_fields', array( $this, 'instance_product_meta_fields' ) );
	}

	public function enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) && $screen->post_type == 'product' ) {
			wp_enqueue_script( 'supro_wc_settings_js', get_template_directory_uri() . '/js/backend/woocommerce.js', array( 'jquery' ), '20190717', true );
			wp_enqueue_style( 'supro_wc_settings_style', get_template_directory_uri() . "/css/woocommerce-settings.css", array(), '20190717' );
		}
	}

	/**
	 * Get product data fields
	 *
	 */
	public function instance_product_meta_fields() {
		$post_id = $_POST['post_id'];
		ob_start();
		$this->create_product_extra_fields( $post_id );
		$response = ob_get_clean();
		wp_send_json_success( $response );
		die();
	}

	/**
	 * Product data tab
	 */
	public function product_meta_tab( $product_data_tabs ) {

		$product_data_tabs['supro_attributes_extra'] = array(
			'label'  => esc_html__( 'Extra', 'supro' ),
			'target' => 'product_attributes_extra',
			'class'  => 'product-attributes-extra'
		);

		return $product_data_tabs;
	}

	/**
	 * Add product data fields
	 *
	 */
	public function product_meta_fields() {
		global $post;
		echo '<div id="product_attributes_extra" class="panel woocommerce_options_panel">';
		$this->create_product_extra_fields( $post->ID );
		echo '</div>';
	}

	/**
	 * product_meta_fields_save function.
	 *
	 * @param mixed $post_id
	 */
	public function product_meta_fields_save( $post_id ) {
		if ( isset( $_POST['product_instagram_hashtag'] ) ) {
			$woo_data = $_POST['product_instagram_hashtag'];
			update_post_meta( $post_id, 'product_instagram_hashtag', $woo_data );
		}

		if ( isset( $_POST['attributes_extra'] ) ) {
			$woo_data = $_POST['attributes_extra'];
			update_post_meta( $post_id, 'attributes_extra', $woo_data );
		}

		if ( isset( $_POST['_supro_custom_badges_text'] ) ) {
			$woo_data = $_POST['_supro_custom_badges_text'];
			update_post_meta( $post_id, '_supro_custom_badges_text', $woo_data );
		}

		if ( isset( $_POST['_supro_custom_badges_bg'] ) ) {
			$woo_data = $_POST['_supro_custom_badges_bg'];
			update_post_meta( $post_id, '_supro_custom_badges_bg', $woo_data );
		}

		if ( isset( $_POST['_supro_custom_badges_color'] ) ) {
			$woo_data = $_POST['_supro_custom_badges_color'];
			update_post_meta( $post_id, '_supro_custom_badges_color', $woo_data );
		}

		if ( isset( $_POST['_is_new'] ) ) {
			$woo_data = $_POST['_is_new'];
			update_post_meta( $post_id, '_is_new', $woo_data );
		} else {
			update_post_meta( $post_id, '_is_new', 0 );
		}
	}


	/**
	 * Create product meta fields
	 *
	 * @param $post_id
	 */
	public function create_product_extra_fields( $post_id ) {
		$post_custom = get_post_custom( $post_id );
		$attributes = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );

		if ( ! $attributes ) : ?>
			<div id="message" class="inline notice woocommerce-message">
				<p><?php esc_html_e( 'You need to add attributes on the Attributes tab.', 'supro' ); ?></p>
			</div>

		<?php else :
			$options         = array();
			$options['']     = esc_html__( 'Default', 'supro' );
			$options['none'] = esc_html__( 'None', 'supro' );
			foreach ( $attributes as $attribute ) {
				$options[sanitize_title( $attribute['name'] )] = wc_attribute_label( $attribute['name'] );
			}
			woocommerce_wp_select(
				array(
					'id'       => 'attributes_extra',
					'label'    => esc_html__( 'Product Attribute', 'supro' ),
					'desc_tip' => esc_html__( 'Show product attribute for each item listed under the item name.', 'supro' ),
					'options'  => $options
				)
			);

		endif;

		woocommerce_wp_text_input(
			array(
				'id'       => '_supro_custom_badges_text',
				'label'    => esc_html__( 'Custom Badge Text', 'supro' ),
				'desc_tip' => esc_html__( 'Enter this optional to show your badges.', 'supro' ),
			)
		);

		$bg_color       = ( isset( $post_custom['_supro_custom_badges_bg'][0] ) ) ? $post_custom['_supro_custom_badges_bg'][0] : '';
		woocommerce_wp_text_input(
			array(
				'id'       => '_supro_custom_badges_bg',
				'label'    => esc_html__( 'Custom Badge Background', 'supro' ),
				'desc_tip' => esc_html__( 'Pick background color for your badge', 'supro' ),
				'value'    => $bg_color,
			)
		);

		$color       = ( isset( $post_custom['_supro_custom_badges_color'][0] ) ) ? $post_custom['_supro_custom_badges_color'][0] : '';
		woocommerce_wp_text_input(
			array(
				'id'       => '_supro_custom_badges_color',
				'label'    => esc_html__( 'Custom Badge Color', 'supro' ),
				'desc_tip' => esc_html__( 'Pick color for your badge', 'supro' ),
				'value'    => $color,
			)
		);

		woocommerce_wp_checkbox(
			array(
				'id'          => '_is_new',
				'label'       => esc_html__( 'New product?', 'supro' ),
				'description' => esc_html__( 'Enable to set this product as a new product. A "New" badge will be added to this product.', 'supro' ),
			)
		);

	}
}