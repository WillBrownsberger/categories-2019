<?php
/**
* File: loop-single-topic.php
*
* Description: this template displays topics as line items in bbPress topic listings 
* 
* @package responsive-tabs 
*
* DERIVED FROM BBPRESS DEFAULT TEMPLATE -- RESPONSIVE-TABS CHANGES AS FOLLOWS:
* 1) added die on direct access line 
* 2) added identifying html comment
* 3) altered formatting so that each title gets a single line to support single-line, responsive formatting (both widest screens and narrowest)
* 		-- cut off title list item to exclude meta information (in horizontal unordered list corresponding to the topic)
* 		-- made author into its own list item (in horizontal unordered list corresponding to the topic)
*		-- made forum into its own list item (in horizontal unordered list corresponding to the topic) and always display it
*		-- dropped the voice count listing as too much clutter and unreliable anyway
* 
*
*/


?>
<!-- responsive-tabs/bbpress/loop-single-topic.php -->
<ul id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

	<li class="bbp-topic-title">

		<?php if ( bbp_is_user_home() ) : ?>

			<?php if ( bbp_is_favorites() ) : ?>

				<span class="bbp-row-actions">

					<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>

					<?php bbp_topic_favorite_link( array( 'before' => '', 'favorite' => '+', 'favorited' => '&times;' ) ); ?>

					<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>

				</span>

			<?php elseif ( bbp_is_subscriptions() ) : ?>

				<span class="bbp-row-actions">

					<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>

					<?php bbp_topic_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

					<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>

				</span>

			<?php endif; ?>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_before_topic_title' ); ?>

		<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>

		<?php do_action( 'bbp_theme_after_topic_title' ); ?>

		<?php bbp_topic_pagination(); ?>

		<?php do_action( 'bbp_theme_before_topic_meta' ); ?>
</li><!-- put title in separate li-->	
	<li class="bbp-topic-started-by-in-list"><!--make author an li instead of a and change class</p> -->

			<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>

			<span class="bbp-topic-started-by"><?php printf( __( '%1$s', 'bbpress' ), bbp_get_topic_author_link( array( 'size' => '14' ) ) ); ?></span>

			<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>
</li><!-- end li for author -->
			<?php // don't do this test, keep in all lists for consistency if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>

				<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>
<!-- if showing it, make forum  it's own li too (was span)-->
				<li class="bbp-topic-in-forum-in-list"><?php printf( __( '<a href="%1$s">%2$s</a>', 'bbpress' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?>

				<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>
</li><!-- end li for forum -->
			<?php // endif; ?>

		<!--was a paragraph mark here closing meta -->

		<?php do_action( 'bbp_theme_after_topic_meta' ); ?>

		<?php bbp_topic_row_actions(); ?>

	<!-- was here the /li for title and dropped the voice count ) -->

	<li class="bbp-topic-reply-count"><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></li>

	<li class="bbp-topic-freshness">

		<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

		<?php bbp_topic_freshness_link(); ?>

		<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>

		<p class="bbp-topic-meta">

			<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

			<span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'size' => 14 ) ); ?></span>

			<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>

		</p>
	</li>

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->