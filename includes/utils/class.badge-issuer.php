<?php
	/**
	 * Class Badge Issuer which allows interactions with the badge issuer information in the plugin.
	 *
	 * @author     Nicolas TORION
	 * @package    badges-issuer-for-wp
	 * @subpackage includes/utils
	 * @since      0.3
	 */

	class BadgeIssuer {
		/**
		 *  $name The name of the badges issuer (The name of the company).
		 */
		var $name;
		/**
		 * $image The image URL of the badges issuer.
		 */
		var $image;
		/**
		 * $url The URL of the badges issuer's website.
		 */
		var $url;
		/**
		 * $email The mail adress corresponding to the Mozilla Backpack account of the badges issuer.
		 */
		var $email;

    /**
     * The constructor of the BadgeIssuer Object. The constructor loads himself the values of the badges issuer in
     * the JSON file.
     *
     * @author Nicolas TORION
     * @since  0.3
     */
    function __construct() {
        $content = file_get_contents(plugin_dir_path(dirname(__FILE__)) . '../../../uploads/badges-issuer/json/badge-issuer.json');
        $badge_issuer_information = json_decode($content, true);

        $this->name = $badge_issuer_information['name'];
        $this->image = $badge_issuer_information['image'];
        $this->url = $badge_issuer_information['url'];
        $this->email = $badge_issuer_information['email'];
    }

		/**
		 * Changes the informations of the badges issuer by the new ones.
		 *
		 * @author Nicolas TORION
		 * @since  0.3
		 *
		 * @param $name  The new name of the badges issuer.
		 * @param $image The new image URL of the badges issuer.
		 * @param $url   The new URL of the badges issuer's website.
		 * @param $email The new mail adress corresponding to the Mozilla Backpack account of the badges issuer.
		 */
		function change_informations( $name, $image, $url, $email ) {
			$this->name  = $name;
			$this->image = $image;
			$this->url   = $url;
			$this->email = $email;

			$this->save_informations();
		}

		/**
		 * Saves the informations of the badges issuer in the JSON file used by Backpack to send badges.
		 *
		 * @author Nicolas TORION
		 * @since  0.3
		 */
		function save_informations() {
			$badges_issuer_file_content = array(
				"name"  => $this->name,
				"image" => urldecode( str_replace( "\\", "", $this->image ) ),
				"url"   => $this->url,
				"email" => $this->email
			);

			file_put_contents( plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/badge-issuer.json', json_encode( $badges_issuer_file_content, JSON_UNESCAPED_SLASHES ) );
		}

	}
