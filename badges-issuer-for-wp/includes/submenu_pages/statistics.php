<?php
/**
 * Create a submenu page in the administration menu to change statistics of the Badge School plugin.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/submenu_pages
 * @since 1.0.0
*/

wp_enqueue_script("jquery");
wp_enqueue_script('jquery-ui');
wp_enqueue_script('jquery-ui-tabs');
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

    /**
    * Adds b4l_send_badges_one_student_submenu_page to the admin menu.
    */
    add_action('admin_menu', 'statistics_submenu_page');

    /**
    * Creates the submenu page.
    *
    * The capability allows superadmin, admin, editor and author to see this submenu.
    * If you change to change the permissions, use manage_options as capability (for
    * superadmin and admin).
    */
    function statistics_submenu_page() {

        add_submenu_page(
            'edit.php?post_type=badge',
            'Statistics',
            'Statistics',
            'capability_statistics',
            'statistics',
            'statistics_callback'
        );

    }

    /**
     * Displays the content of the submenu page
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function statistics_callback() {
      ?>

      <script type="text/javascript" src="<?php echo plugins_url('../utils/jqplot/jquery.jqplot.js', __FILE__); ?>"></script>

      <script type="text/javascript" src="<?php echo plugins_url('../utils/jqplot/plugins/jqplot.barRenderer.js', __FILE__); ?>"></script>
      <script type="text/javascript" src="<?php echo plugins_url('../utils/jqplot/plugins/jqplot.categoryAxisRenderer.js', __FILE__); ?>"></script>
      <script type="text/javascript" src="<?php echo plugins_url('../utils/jqplot/plugins/jqplot.pointLabels.js', __FILE__); ?>"></script>
      <script type="text/javascript" src="<?php echo plugins_url('../utils/jqplot/plugins/jqplot.pieRenderer.js', __FILE__); ?>"></script>
      <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('../utils/jqplot/jquery.jqplot.css', __FILE__); ?>" />
      <h1>Statistics</h1>
      <br>
      <h2>Total sended badges</h2>
      <p style="font-size : 30px"><?php echo nb_sended_badges(); ?></p>

      <h2>Repartition of sended badges</h2><br />
      <div id="pie1"></div>
      <div id="info1"></div>
      <?php display_bar_chart(sended_badges_by_type(), "pie1", "info1"); ?>

      <!-- <h2>Repartition of sended badges</h2><br />
      <div id="pie1"></div>
      <?php display_pie_chart(sended_badges_by_type()); ?> -->

      <?php

    }

?>
