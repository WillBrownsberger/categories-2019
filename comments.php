<?php
/*
 * File: comments.php
 * Description: display comments list and form for items with comments open 
 * 
 * Derived from legacy Wordpress comments.php
 * @package responsive-tabs
 *
 *
 */

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );

echo '<!-- responsive-tabs comments.php -->';

if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'responsive-tabs' ); ?></p>
		<?php	return;
	}

if ( have_comments() ) { ?>

	<ol class="commentlist">
		<?php wp_list_comments();?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link( '&laquo; ' .__('older comments', 'responsive-tabs' ) ) ?></div>
		<div class="alignright"><?php next_comments_link( __('newer comments', 'responsive-tabs' ) . ' &raquo;' ) ?></div>
	</div>
	<div class = "horbar-clear-fix"></div><?php
}

if ( comments_open() ) { ?>

	<div id="respond">
	
		<h3><?php comment_form_title( __('Make a Comment', 'responsive-tabs' ), __('Reply to %s', 'responsive-tabs' ), true ); ?></h3>
	
		<div id="cancel-comment-reply">
			<small><?php cancel_comment_reply_link() ?></small>
		</div>
	
		<?php if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) { ?>
			<p><?php printf(__('You must be <a href="%s">logged in</a> to comment.', 'responsive-tabs' ), wp_login_url( get_permalink() )); ?></p>
		<?php } else {	?>

				<?php $email_author_reminder = ''; // filled in below subject to conditions ?>	
		
				<form action="<?php echo site_url(); ?>/wp-comments-post.php" method="post" id="commentform" name="commentform">
				
				<?php if ( is_user_logged_in() ) { ?>
				
					<p><?php printf( __('Logged in as <a href="%1$s">%2$s</a>.', 'responsive-tabs' ), get_edit_user_link(), $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php esc_attr_e( 'Log out of this account', 'responsive-tabs' ); ?>"><?php _e( 'Log out &raquo;', 'responsive-tabs' ); ?></a></p>
				
				<?php } else { ?>
				
					<?php if ( get_option ( 'require_name_email' ) ) {
						$email_author_reminder = 'onBlur="checkNameEmailOnComments()"';
					} 				
					/* The onBlur function for the comment element alerts the user who has entered comment that s/he has not entered name or email -- 
					*  user can proceed to submit form anyway.  This is a courtesy -- some browsers allow input to be lost if edits failed and must use back button. 
					*/ 
					?>
				
				<p><input type="text" name="author" id="author" value="<?php echo esc_attr( $comment_author ); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
					<label for="author"><small><?php _e( 'Name', 'responsive-tabs' ); ?> <?php if ($req) _e('(required)', 'responsive-tabs' ); ?></small></label></p>
					
					<p><input type="text" name="email" id="email" value="<?php echo esc_attr( $comment_author_email ) ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
					<label for="email"><small><?php _e( 'Mail (will not be published)', 'responsive-tabs' ); ?> <?php if ($req) _e( 'required', 'responsive-tabs' ); ?></small></label></p>
					
					<p><input type="text" name="url" id="url" value="<?php echo  esc_attr( $comment_author_url ); ?>" size="22" tabindex="3" />
					<label for="url"><small><?php _e( 'Website', 'responsive-tabs' ); ?></small></label></p>
						
				<?php } ?>
										
				<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" <?php echo $email_author_reminder; ?>></textarea></p>
				
				<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e( 'Submit Comment', 'responsive-tabs') ; ?>" />
				<?php comment_id_fields(); ?>
				</p>
				
				<?php do_action( 'comment_form', $post->ID ); ?>
		
			</form>
	
		<?php } // If not registration required or logged in ?>
	</div>

<?php } // comments open?>
