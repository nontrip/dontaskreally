<?php
/**
 * Template part for displaying header 5.
 *
 * @package Supro
 */

?>
<div class="container">
	<div class="header-main">
		<div class="header-row">
			<div class="menu-logo s-left">
				<div class="site-logo">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
			</div>
			<div class="s-center menu-main">
				<div class="menu-nav">
					<nav class="primary-nav nav">
						<?php supro_nav_menu(); ?>
					</nav>
				</div>
			</div>
			<div class="menu-extra s-right">
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
