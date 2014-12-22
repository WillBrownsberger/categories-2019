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



echo '<!-- responsive-tabs comments.php -->';

if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'responsive-tabs' ); ?></p>
		<?php	return;
	}

if ( comments_open() ) { 
	/* set up standard wordpress comment form (which does its own condition processing) */
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$required_text = __( '(required)', 'responsive-tabs' );
	$email_author_reminder =  ( $req ? ' onBlur="checkNameEmailOnComments()" ' : '' );
		/* The onBlur function for the comment element alerts the user who has entered comment that s/he has not entered name or email without leaving form -- 
		*  user can proceed to submit form anyway.  This is a courtesy -- some browsers allow input to be lost if edits failed and must use back button. 
		*/
	
	$args = array(
	  'id_form'           => 'commentform',
	  'id_submit'         => 'submit',
	  'title_reply'       => __( 'Make a Comment', 'responsive-tabs' ),
	  'title_reply_to'    => __( 'Reply to %s', 'responsive-tabs' ),
	  'cancel_reply_link' => __( 'Cancel reply', 'responsive-tabs' ),
	  'label_submit'      => __( 'Post Comment', 'responsive-tabs' ),
	
	  'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="40" rows="10" aria-required="true"' .
	  		$email_author_reminder . '>' .
	    '</textarea></p>',
	
	  'must_log_in' => '<p class="must-log-in">' .
	    sprintf(
	      __( 'Please <a href="%s">log in</a> to post a comment.', 'responsive-tabs' ),
	      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
	    ) . '</p>',
	
	  'logged_in_as' => '<p class="logged-in-as">' .
	    sprintf(
	    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'responsive-tabs' ),
	      admin_url( 'profile.php' ),
	      esc_html( $user_identity ),
	      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
	    ) . '</p>',
	
	  'comment_notes_before' => '<p class="comment-notes">' .
	    __( 'Your email address will not be published.' , 'responsive-tabs' ) .
	    '</p>',
	
	  'comment_notes_after' => '<p class="form-allowed-tags">' .
	    __( 'You can make the comment area bigger by pulling the arrow. If you are techie, you can use basic <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes to format your comment.', 'responsive-tabs' ) .
	 	'</p>',
	
	  'fields' => apply_filters( 'comment_form_default_fields', array(
	
	    'author' =>
	      '<p class="comment-form-author">' . 
	      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
	      '" size="22"' . $aria_req . ' /> ' .
	      '<label for="author">' . __( 'Name ', 'responsive-tabs' ) . '</label> ' .
	      ( $req ? '<span class="required">' . $required_text . '</span>' : '' ) . '</p>',
	
	    'email' =>
	      '<p class="comment-form-email">'.  
	      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
	      '" size="22"' . $aria_req . ' /> ' . 
	      ' <label for="email">' . __( 'Email ', 'responsive-tabs' ) . '</label> ' .
	      ( $req ? '<span class="required">' . $required_text . '</span>' : '' ) . '</p>',
	
	    'url' => 
	      '<p class="comment-form-url">' .
	      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
	      '" size="22" /> <label for="url">' .
	      __( 'Website ', 'responsive-tabs' ) . '</label>' . '</p>'
	    )
	  ),
	);
	
	/* do comment form */
	comment_form($args); 
} else {
	echo '<h4>' . __( 'Please note, this thread is not open for comment at this time.', 'responsive-tabs' ) . '</h4>';
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
