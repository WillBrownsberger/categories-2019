<?php
/**
* File: form-anonymous.php
*
* Description: this template displays fields for anonymous user to enter identity information in bbPress 
* 
* @package responsive-tabs 
*
* DERIVED FROM BBPRESS DEFAULT TEMPLATE -- RESPONSIVE-TABS CHANGES AS FOLLOWS:
* 1) changed size parameters from 40 to 25 on all three fields to fit on screen-width of 320px (iphone) in portrait mode
* 2) added identifying html comment
* 3) added die on direct access line
*
*/

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );
?>



<?php if ( bbp_current_user_can_access_anonymous_user_form() ) : ?>

	<?php do_action( 'bbp_theme_before_anonymous_form' ); ?>
	<!-- responsive-tabs/bbpress/form-anonymous.php -->
	<fieldset class="bbp-form">
		<legend><?php ( bbp_is_topic_edit() || bbp_is_reply_edit() ) ? _e( 'Author Information', 'bbpress' ) : _e( 'Your information:', 'bbpress' ); ?></legend>
		<?php do_action( 'bbp_theme_anonymous_form_extras_top' ); ?>

		<p>
			<label for="bbp_anonymous_author"><?php _e( 'Name (required):', 'bbpress' ); ?></label><br />
			<input type="text" id="bbp_anonymous_author"  value="<?php bbp_author_display_name(); ?>" tabindex="<?php bbp_tab_index(); ?>" size = "25" name="bbp_anonymous_name" />
		</p>

		<p>
			<label for="bbp_anonymous_email"><?php _e( 'Mail (not published) (required):', 'bbpress' ); ?></label><br />
			<input type="text" id="bbp_anonymous_email"   value="<?php bbp_author_email(); ?>" tabindex="<?php bbp_tab_index(); ?>" size = "25" name="bbp_anonymous_email" />
		</p>

		<p>
			<label for="bbp_anonymous_website"><?php _e( 'Website:', 'bbpress' ); ?></label><br />
			<input type="text" id="bbp_anonymous_website" value="<?php bbp_author_url(); ?>" tabindex="<?php bbp_tab_index(); ?>" size = "25" name="bbp_anonymous_website" />
		</p>

		<?php do_action( 'bbp_theme_anonymous_form_extras_bottom' ); ?>
	</fieldset>

	<?php do_action( 'bbp_theme_after_anonymous_form' ); ?>

<?php endif; ?>
