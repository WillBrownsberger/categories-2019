<?php
/**
* File: loop-single-reply.php
*
* Description: this template displays replies in bbPress 
* 
* @package responsive-tabs 
*
* DERIVED FROM BBPRESS DEFAULT TEMPLATE -- RESPONSIVE-TABS CHANGES AS FOLLOWS:
* 1) added author information in meta section 
* 2) removed author name from avatar display block
* 3) added identifying html comment
* 4) added die on direct access line
*
*
*/


?>

<!--/responsive-tabs/bbpress/loop-single-reply.php -->
<div id="post-<?php bbp_reply_id(); ?>" class="bbp-reply-header">
    
	<div class="bbp-meta"> 
		<!-- added author to bbp-meta line -->
                <span class="bbp-reply-post-author-wb"><?php bbp_reply_author_link( array( 'type' => 'name','show_role' => false ) ); ?> -- </span>
	
		<span class="bbp-reply-post-date"><?php bbp_reply_post_date(); ?></span>

		<?php if ( bbp_is_single_user_replies() ) : ?>

			<span class="bbp-header">
				<?php _e( 'in reply to: ', 'bbpress' ); ?>
				<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
			</span>

		<?php endif; ?>

		<a href="<?php bbp_reply_url(); ?>" class="bbp-reply-permalink">#<?php bbp_reply_id(); ?></a>

		<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

		<?php bbp_reply_admin_links(); ?>

		<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>

	</div><!-- .bbp-meta -->

</div><!-- #post-<?php bbp_reply_id(); ?> -->

<div <?php bbp_reply_class(); ?>>

<div class="bbp-reply-author">  

		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>
                <!-- note: modified this call to show only the avatar since added name above; css display: none on iphone -->
		<?php bbp_reply_author_link( array( 'type' => 'avatar', 'size' => 80, 'show_role' => false ) ); ?>

		<?php if ( bbp_is_user_keymaster() ) : ?>

			<?php do_action( 'bbp_theme_before_reply_author_admin_details' ); ?>

			<div class="bbp-reply-ip"><?php bbp_author_ip( bbp_get_reply_id() ); ?></div>

			<?php do_action( 'bbp_theme_after_reply_author_admin_details' ); ?>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

	</div><!-- .bbp-reply-author -->  

	<div class="bbp-reply-content">
	        
		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->

</div><!-- .reply -->