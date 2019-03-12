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

	$args = array(
	  'id_form'           => 'commentform',
	  'id_submit'         => 'submit',
	  'title_reply'       => __( 'Make a Comment', 'responsive-tabs' ),
	  'title_reply_to'    => __( 'Reply to %s', 'responsive-tabs' ),
	  'cancel_reply_link' => __( 'Cancel reply', 'responsive-tabs' ),
	  'label_submit'      => __( 'Post Comment', 'responsive-tabs' ),
	
	  'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="40" rows="10" aria-required="true">' .
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

if ( have_comments() ) {
	
	// if doing infinite scroll, set up parms
	$scroll_marker= '';
	$responsive_tabs_infinite_scroll_ajax_parms = '';
	$scroll_marker = ' id="responsive-tabs-ajax-insert" ';	
	$widget_parms = new Widget_Ajax_Parms ( 
		'comment_query', 	// widget_type
		$post->ID, 			// $include_string,
		0, 					// initial offset from first page -- is incremented after retrieval
		''					// $query_type
	);
	$responsive_tabs_infinite_scroll_ajax_parms = json_encode( $widget_parms );	
	 
	// comment list with id marker
	echo '<ol class="commentlist"' . $scroll_marker .  '></ol>';
	
	// if doing infinite scroll, send the scroll parms and the loader gif
	echo '<span id = "responsive-tabs-post-list-ajax-loader">' .
		'<img src="' . get_stylesheet_directory_uri() . '/images/ajax-loader.gif' .
	'"></span>'; 
	echo '<div class="responsive_tabs_infinite_scroll_parms" id="responsive_tabs_infinite_scroll_parms">' . $responsive_tabs_infinite_scroll_ajax_parms . '</div>';	
} // closes have comments conditional
