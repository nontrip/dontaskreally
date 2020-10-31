<?php
/**
 * Template part for displaying header v2.
 *
 * @package Supro
 */

?>
<div class="container">
	<div class="header-main">
		<div class="header-row">
			<div class="menu-extra menu-search">
				<ul><?php supro_extra_search(); ?></ul>
			</div>
			<div class="menu-logo">
				<div class="site-logo">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
			</div>
			<div class="menu-extra">
				<ul>
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