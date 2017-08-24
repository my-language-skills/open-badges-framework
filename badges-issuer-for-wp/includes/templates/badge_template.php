<?php

/**
 * Template Name : Badge template
 *
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php'; ?>

<style>
  .post-content {
    width: 60%;
    margin: 0 auto;
  }
/*  .post{
    background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>);
    background-size: 15%;
    background-repeat: no-repeat;
    background-position: center;
    opacity:0.1;
  }*/
#title-image{
  margin-top: 20px;
}
</style>

<?php
get_header(); ?>
<?php wp_enqueue_style( 'style', get_stylesheet_uri() ); ?>
<div class="main page">
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post">
        <center id="title-image"><img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>"/></center>
        <center><h1 class="post-title"><?php the_title(); ?></h1></center>
        <div class="post-content" style="opacity:1;">
            <?php the_content();  ?>
            <?php
            add_filter( 'comments_template', function ( $template ) {
      				return plugin_dir_path( dirname( __FILE__ ) ) . 'templates/badge_comments_template.php';
      			});

			      comments_template();
            ?>
        </div>
    </div>
    <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
