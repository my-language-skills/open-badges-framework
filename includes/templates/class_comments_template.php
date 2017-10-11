<?php
	/**
	 * The template for displaying Comments
	 *
	 * @since 0.6.1
	 */

	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';
	global $current_user;
	wp_get_current_user();

	global $post;
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

    <h2>Comments</h2><br/>
	<?php if ( have_comments() ) : ?>

        <ol class="commentlist">
			<?php wp_list_comments( array( 'style' => 'ol', 'callback' => 'custom_comment' ) ); ?>
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

		if ( can_student_write_comment( $current_user->user_login, $post->ID ) || can_user_reply( $current_user->user_login, get_the_ID() ) ) {
			global $current_user;
			wp_get_current_user();

			$comments_args = array();

			$student_infos = get_student_infos_in_class( $current_user->user_login, $post->ID );

			if ( $student_infos ) {
				$comments_args = array(
					'comment_field' => '<input type="hidden" name="student_level" value="' . $student_infos['level'] . '" /><input type="hidden" name="student_language" value="' . $student_infos['language'] . '" /><input type="hidden" name="student_date" value="' . $student_infos['date'] . '" /><p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
				);
			}

			comment_form( $comments_args );
		} else {
			echo "You cannot write a comment for this class.";
		}
	?>

</div><!-- #comments .comments-area -->


<?php

	/**
	 * Override of the Wordpress function custom_comment in order to manage the rights to reply to a comment.
	 *
	 * @since 0.6.1
	 */
	function custom_comment( $comment, $args, $depth ) {
		global $current_user;
		wp_get_current_user();

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
				$student_level    = get_comment_meta( $comment->comment_ID, 'student_level' )[0];
				$student_language = get_comment_meta( $comment->comment_ID, 'student_language' )[0];
				$student_date     = get_comment_meta( $comment->comment_ID, 'student_date' )[0];

				if ( $student_level && $student_language && $student_date ) {
					echo "Level : " . $student_level . ", Language : " . $student_language . ", Badge date : " . $student_date;
				}
			?>
        </div>

		<?php comment_text();

		if ( can_user_reply( $current_user->user_login, get_the_ID() ) ) {
			?>
            <div class="reply">
				<?php comment_reply_link( array_merge( $args, array(
					'add_below' => $add_below,
					'depth'     => $depth,
					'max_depth' => $args['max_depth']
				) ) ); ?>
            </div>
			<?php
		}
		?>
		<?php if ( 'div' != $args['style'] ) : ?>
            </div>
		<?php endif; ?>
		<?php
	}

?>
