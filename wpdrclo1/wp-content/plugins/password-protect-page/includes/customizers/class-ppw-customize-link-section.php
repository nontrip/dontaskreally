<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( ABSPATH . '/wp-includes/class-wp-customize-section.php' );

if ( class_exists( 'WP_Customize_Section' ) && ! class_exists( 'PPW_Customize_Link_Section' ) ) {

	/**
	 * PPW_Customize_Link_Section Initial setup
	 */
	class PPW_Customize_Link_Section extends WP_Customize_Section {
		public $type = 'ppwp-upsell-section';
		public $ppwp_url = '';
		public $ppwp_text = '';
		public $id = '';

		public function json() {
			$json              = parent::json();
			$json['ppwp_text'] = $this->ppwp_text;
			$json['ppwp_url']  = esc_url( $this->ppwp_url );
			$json['id']        = $this->id;

			return $json;
		}

		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}"
			    class="ppwp-accordion-section accordion-section control-section control-panel control-panel-default">
				<h3 class="ppwp-accordion-section-title accordion-section-title"><a href="{{{ data.ppwp_url }}}"
				                                                                    target="_blank">{{ data.ppwp_text
						}}</a></h3>
			</li>
			<?php
		}
	}

}
