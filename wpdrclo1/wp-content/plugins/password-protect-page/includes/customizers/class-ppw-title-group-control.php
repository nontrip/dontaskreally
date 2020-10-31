<?php
/**
 * Toggle Customizer Control
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if WP_Customize_Control does not exsist.
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * This class is for the toggle control in the Customizer.
 *
 * @access public
 */

class PPW_Title_Group_Control extends WP_Customize_Control {

	public $type = 'control_title';

	public function enqueue() {
		wp_enqueue_style( 'control-title-group-styles', PPW_DIR_URL . 'includes/customizers/assets/ppw-title-group-control.css', false, PPW_VERSION, 'all' );
	}

	public function render_content() {}

	protected function content_template() {
        ?>
            <div class="control-title--wrapper">
                <span class="customize-control-title">{{ data.label }}</span>
            </div>
		<?php
	}
}