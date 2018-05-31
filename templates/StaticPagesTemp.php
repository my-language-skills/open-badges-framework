<?php

/**
	* Templates for the Static Pages of the Plugin.
	*
	* All the content to show in the front-end is wrapped in the __() function
	* for internationalization purposes
	*
	* @author      @Kongat
	* @since       1.0.0
	*@package     OpenBadgesFramework
 */

namespace templates;





final class StaticPagesTemp {

	/**
     * The function that shows the 'About us' page template.
     *
     * @author      @Kongat
     * @since       1.0.0
     *
     * @return void
     */

	public static function aboutTab() {
        ?>
        <div class="container admin">
			 <h1><?php _e('About us','open-badges-framework');?></h1>
            <div class="intro-dash">
                <div class="cont-title-dash">

                    <p class="lead">
                       <?php _e('The main function of this plugin is to create, manage and send badges. <br>
                        Once a badge has been sent, the receiver has the possibility to take his badge through a link
                        that is inserted in the email.<br>
                        After successive steps, the user can get his own badge and also has the possibility to send
                        it to the','open-badges-framework');?> <a href="https://openbadges.org/">Mozilla Open Badges</a> <?php _e('platform.','open-badges-framework');?>
                    </p>
                    <H2><?php _e('Shortcodes','open-badges-framework');?></h2>
                    <p class="lead">
                        <?php _e('The front-end function can be used in any page with the shortcode','open-badges-framework');?>  <b>[send_badge]</b>.<br><br>

                        <?php _e('If you need to show just one of the 3 types, you can use the following shortcodes:','open-badges-framework');?><br>
                        <b>[send-badge form="a"] </b><?php _e('to send the badge to yourself.','open-badges-framework');?><br>
                        <b>[send-badge form="b"] </b><?php _e('to send the badge to one user at a time.','open-badges-framework');?><br>
                        <b>[send-badge form="c"] </b><?php _e('to send the badge to multiple users at a time.','open-badges-framework');?>
												<b>[send_badge] or [send-badge form="all"] </b>:<?php _e('to have all types available.','open-badges-framework');?>
												<b>[send-badge ... sec-form="..."] </b>:<?php _e('add a second form (a/b/c) that will be show with the first.','open-badges-framework');?>

                    </p>
                    <H2><?php _e('Documentation','open-badges-framework');?></h2>
                    <p class="lead">
                        <?php _e('The official documentation can be found at','open-badges-framework');?> <a href="https://github.com/my-language-skills/open-badges-framework/blob/master/README.md/">GitHub.</a><br>
                        <ul>
                          <li><a href="https://github.com/my-language-skills/open-badges-framework/blob/master/doc/badges-for-languages.md">Badges4Languages</a></li>
                          <li><a href="https://github.com/my-language-skills/open-badges-framework/blob/master/doc/documentation-general.md"><?php _e('General documentation','open-badges-framework');?></a></li>
                          <li><a href="https://github.com/my-language-skills/open-badges-framework/blob/master/doc/documentation-technical.md"><?php _e('Technical documentation','open-badges-framework');?></a></li>
                          <li><a href="https://github.com/my-language-skills/open-badges-framework/blob/master/doc/documentation-integrations.md"><?php _e('Integrations','open-badges-framework');?></a></li>
                          <li><a href="https://github.com/my-language-skills/open-badges-framework/blob/master/doc/folder-structure.md"><?php _e('The folder structure','open-badges-framework');?></a></li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }



}
