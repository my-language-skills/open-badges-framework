<?php

/**
 * Template Name : Class template
 *
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

get_header(); ?>

<div class="main page">
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post">
        <center><h1 class="post-title"><?php the_title(); ?></h1></center>
        <div class="post-content" style="width:70%; margin:0 auto;">
            <?php the_content(); ?>
            <?php

            add_filter( 'comments_template', function ( $template ) {
      				return plugin_dir_path( dirname( __FILE__ ) ) . 'templates/class_comments_template.php';
      			});

			      comments_template();
            ?>
        </div>
    </div>
    <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
