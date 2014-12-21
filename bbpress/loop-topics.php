<?php
/**
* File: loop-topics.php
*
* Description: displays list of topics in bbPress 
* 
* @package responsive-tabs 
*
* DERIVED FROM BBPRESS DEFAULT TEMPLATE -- RESPONSIVE-TABS CHANGES AS FOLLOWS:
* 1) added die on direct access line 
* 2) added identifying html comment
* 3) altered header formatting for one-topic-per-line, responsive formatting (both widest screens and narrowest)
*    	-- consistent with loop-single-topic changes
*		-- add header for author (was under title)
* 		-- add header for forum  (was under title) and always display it
*		-- remove header for voices count (unreliable and clutter for most users anyway)
*
*/


?>
<!-- responsive-tabs/bbpress/loop-topics.php -->
<?php do_action( 'bbp_template_before_topics_loop' ); ?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">

	<li class="bbp-header">

		<ul class="forum-titles">
			<li class="bbp-topic-title"><?php _e( 'Topic', 'bbpress' ); ?></li>
			<li class="bbp-topic-started-by-in-list"><?php _e( 'Started by', 'responsive-tabs' ); // added ?></li> 
			<li class="bbp-topic-in-forum-in-list"><?php _e( 'in Forum', 'responsive-tabs' );  // added ?></li> 
			<!-- removed bad voice count --> 
			<li class="bbp-topic-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'responsive-tabs' ) : _e( 'Posts', 'responsive-tabs' ); ?></li>
			<li class="bbp-topic-freshness"><?php _e( 'Freshness', 'responsive-tabs' ); ?></li>
		</ul>

	</li>

	<li class="bbp-body">

		<?php while ( bbp_topics() ) : bbp_the_topic(); ?>

			<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>

		<?php endwhile; ?>

	</li>

	<li class="bbp-footer">

		<div class="tr">
			<p>
				<span class="td colspan<?php echo ( bbp_is_user_home() && ( bbp_is_favorites() || bbp_is_subscriptions() ) ) ? '5' : '4'; ?>">&nbsp;</span>
			</p>
		</div><!-- .tr -->

	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' ); ?>
