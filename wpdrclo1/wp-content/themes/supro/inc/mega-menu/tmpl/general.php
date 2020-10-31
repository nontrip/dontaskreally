<?php
global $wp_widget_factory;
?>
<div id="tamm-panel-general" class="tamm-panel-general tamm-panel">
	<div class="mr-tamm-panel-box">
		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'hideText', data.data['menu-item-db-id'] ) }}" value="1" {{  data.megaData.hideText ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'Hide Text', 'supro' ) ?>
			</label>
		</p>
	</div>
	<div class="mr-tamm-panel-box">
		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'hot', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.hot ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'Hot', 'supro' ) ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'new', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.new ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'New', 'supro' ) ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'trending', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.trending ? 'checked="checked"': '' }}>
				<?php esc_html_e( 'Trending', 'supro' ) ?>
			</label>
		</p>
	</div>
</div>