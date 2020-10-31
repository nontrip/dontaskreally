<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Supro
 */

?>
	<?php

	/*
	 *  supro_site_content_close - 100
	 */
	do_action( 'supro_site_content_close' );
	?>
</div><!-- #content -->

	<?php
	/*
	 * supro_footer_newsletter - 10
	 * supro_footer_newsletter_fix -20
	 */
	do_action( 'supro_before_footer' );
	?>

	<footer id="colophon" class="site-footer">
		<?php do_action( 'supro_footer' ) ?>
	</footer><!-- #colophon -->

	<?php do_action( 'supro_after_footer' ) ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
