<?php
/**
 * Template part for displaying header v1.
 *
 * @package Supro
 */

?>
<div class="supro-container">
	<div class="header-main">
		<div class="header-row">
			<div class="menu-socials s-left">
				<?php
				$header_social = supro_get_option( 'header_socials' );

				ob_start();
				supro_get_socials_html( $header_social, 'icon' );
				$socials = ob_get_clean();

				echo apply_filters('supro_menu_socials',$socials);
				?>
			</div>
			<div class="container s-center menu-main">
				<div class="menu-nav">
					<div class="menu-extra menu-search">
						<ul>
							<?php supro_extra_search(); ?>
						</ul>
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

						</ul>
					</div>
				</div>
			</div>
			<div class="menu-extra s-right">
				<ul>
					<?php supro_extra_sidebar(); ?>
					<?php supro_menu_mobile(); ?>
				</ul>
			</div>
		</div>
	</div>
</div>
