<?php

/**
	* Templates for the Static Pages of the Plugin.
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
			 <h1>About us</h1>
            <div class="intro-dash">
                <div class="cont-title-dash">
                   
                    <p class="lead">
                        The main function of this plugin is to create, manage and send badges. <br>
                        Once a badge has been sent, the receiver has the possibility to take his badge through a link
                        that is inserted in the email.<br>
                        After successive steps, the user can get his own badge also he has the possibility to send
                        it to the <a href="https://openbadges.org/">Mozilla Open Badges</a> platform.
                    </p>
                    <H2>Shortcodes</h2>
                    <p class="lead">
                        The front-end function can be used in any page with the shortcode  <b>[send_badge]</b>.<br><br>

                        If we need to show just one of the 3 types, we can use the following shortcodes:<br>
                        <b>[send-badge form="a"] </b>: to send the badge to yourself.<br>
                        <b>[send-badge form="b"] </b>: to send the badge to one user at a time.<br>
                        <b>[send-badge form="c"] </b>: to send the badge to multiple users at a time.
                    </p>
                    <H2>Documentation</h2>
                    <p class="lead">
                        The official documentation can be found at <a href="https://github.com/Badges4Languages/OpenBadgesFramework/">GitHub.</a><br>
                        <ul>
                          <li><a href="https://github.com/Badges4Languages/OpenBadgesFramework/blob/master/doc/badges-for-languages.md">Badges4Languages.</a></li>
                          <li><a href="https://github.com/Badges4Languages/OpenBadgesFramework/blob/master/doc/documentation-general.md">General documentation.</a></li>
                          <li><a href="https://github.com/Badges4Languages/OpenBadgesFramework/blob/master/doc/documentation-technical.md">Technical documentation.</a></li>
                          <li><a href="https://github.com/Badges4Languages/OpenBadgesFramework/blob/master/doc/documentation-integrations.md">Integrations.</a></li>
                          <li><a href="https://github.com/Badges4Languages/OpenBadgesFramework/blob/master/doc/folder-structure.md">The folder structure.</a></li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
	
	
	
}