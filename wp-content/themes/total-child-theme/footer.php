<?php
/**
 * The template for displaying the footer.
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
		<?php if( 11998 != get_the_ID() ) { ?>
			<?php wpex_hook_main_bottom(); ?>
		<?php } ?>
		</main><!-- #main-content -->

		<?php wpex_hook_main_after(); ?>

		<?php wpex_hook_wrap_bottom(); ?>

	</div><!-- #wrap -->

	<?php wpex_hook_wrap_after(); ?>

</div><!-- #outer-wrap -->

<?php wpex_outer_wrap_after(); ?>

<?php wp_footer(); ?>

</body>
</html>