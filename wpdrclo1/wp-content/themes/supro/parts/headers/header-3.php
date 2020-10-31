<?php
/**
 * Template part for displaying header v3.
 *
 * @package Supro
 */

?>
<div class="supro-container">
	<div class="header-main">
		<div class="row header-row">
			<div class="menu-logo col-lg-2 col-md-6 col-sm-6 col-xs-6">
				<div class="site-logo">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
			</div>
			<div class="menu-main col-lg-10 col-md-6 col-sm-6 col-xs-6">
				<div class="menu-extra">
					<ul>
						<?php supro_extra_search(); ?>
						<?php supro_extra_account(); ?>
						<?php supro_extra_wishlist(); ?>
						<?php supro_extra_cart(); ?>
						<?php supro_extra_sidebar(); ?>
						<?php supro_menu_mobile(); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>