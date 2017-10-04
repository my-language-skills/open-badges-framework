<?php

/**
 * Template Name : Badge template
 *
 * @since 0.6.1
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

?>


<?php
get_header(); ?>

<div class="container post">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="col-md-3">
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>">
            </div>
            <div class="col-md-9">
                <h1 class="post-title"><?php the_title(); ?></h1>
	            <?php the_content();  ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
