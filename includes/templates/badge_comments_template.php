<?php
	/**
	 * The template for displaying Comments
	 *
	 * @since 0.6
	 */

	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';
	global $current_user;
	get_currentuserinfo();

	global $post;

	add_action( 'comment_form_logged_in_after', 'additional_fields' );
	add_action( 'comment_form_after_fields', 'additional_fields' );

	function additional_fields() {
		echo '<p class="comment-form-language">';
		echo '<input type="hidden" name="badge_translation_comment" value="true" />';
		echo show_all_the_language( $just_most_important_languages = true );
		echo '</p>';
	}

?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
        <h2>Translations of description</h2><br/>

        <ol class="commentlist">
			<?php wp_list_comments( array( 'style' => 'ol', 'callback' => 'custom_comment_badge' ) ); ?>
        </ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
            <nav id="comment-nav-below" class="navigation" role="navigation">
                <h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'badges-issuer-for-wp' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'badges-issuer-for-wp' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'badges-issuer-for-wp' ) ); ?></div>
            </nav>
		<?php endif; // check for comment navigation ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
            <p class="nocomments"><?php _e( 'Comments are closed.', 'badges-issuer-for-wp' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
		if ( in_array( "academy", $current_user->roles ) ) {
			comment_form();
		}
	?>

</div><!-- #comments .comments-area -->

<?php

	/**
	 * Override of the Wordpress function custom_comment in order to manage the rights to reply to a comment.
	 *
	 *
	 * @package WordPress
	 */
	function custom_comment_badge( $comment, $args, $depth ) {
		global $current_user;
		get_currentuserinfo();

		if ( 'div' === $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
        <<?php echo $tag ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>
        <div class="comment-author vcard">
			<?php if ( $args['avatar_size'] != 0 ) {
				echo get_avatar( $comment, $args['avatar_size'] );
			} ?>
			<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ); ?>
        </div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
            <br/>
		<?php endif; ?>

        <div class="comment-meta commentmetadata"><a
                    href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s' ), get_comment_date(), get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
			?>
        </div>

		<?php
		$translationlanguage = get_comment_meta( get_comment_ID(), '_comment_translation_language', true );
		echo '<p class="comment-language">Language of the translation : ' . $translationlanguage . '</p>';
		?>

        <div id="<?php echo $comment->comment_ID; ?>" class="comment_content">
            <div id="comment_text">
				<?php
					comment_text();
				?>
            </div>

			<?php
				if ( in_array( "academy", $current_user->roles ) && $current_user->ID == $comment->user_id ) {
					?>
                    <a href="#" id="edit_comment_link">Edit your translation</a>
					<?php
				}
			?>
        </div>

		<?php
		if ( 'div' != $args['style'] ) : ?>
            </div>
		<?php endif; ?>
		<?php
	}

?>
