<?php

namespace Inc\Base;

use Inc\Pages\Admin;
use templates\SettingsTemp;

/**
 * Here are stored all callback functions for the meta-box.
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Metabox {
    const META_FIELD_CERT = "certified";
    const META_FIELD_NOT_CERT = "not_certified";
    const META_FIELD_STUDENT = "student";
    const META_FIELD_TEACHER = "teacher";
	const META_FIELD_ALIGNMENT_NAME = "name";
	const META_FIELD_ALIGNMENT_URL = "url";
	const META_FIELD_ALIGNMENT_DESCRIPTION = "description";

    /**
     * Calling the save_post hook.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public function __construct() {
        add_action('save_post', array($this, 'saveMetaboxes'));
		add_action('save_post', array($this, 'saveMetabox_align'));		
    }
	
	
    function saveMetaboxes($post_ID) {
        if (isset($_POST['certification_input'])) {
            update_post_meta($post_ID, '_certification', esc_html($_POST['certification_input']));
        }

        if (isset($_POST['target_input'])) {
            update_post_meta($post_ID, '_target', esc_html($_POST['target_input']));
        }
	}
	
	function saveMetabox_align($post_ID){
		//if (isset($_POST['align_field'])) {
			if (!isset($_POST['alignment_nonce'])) {return;}
			if (!wp_verify_nonce($_POST['alignment_nonce'],'saveMetabox_align')){return;}
			if (defined('DOING_AUTOSAVE')&& DOING_AUTOSAVE){return;}
			if (!current_user_can('edit_post',$post_ID)){return;}
			
			if (!isset($_POST['align_name_field'])){return;}
			$my_data_name = sanitize_text_field($_POST['align_name_field']);
			update_post_meta($post_ID,'_align_name_key',$my_data_name);
			
			if (!isset($_POST['align_url_field'])){return;}
			$my_data_url = sanitize_text_field($_POST['align_url_field']);
			update_post_meta($post_ID,'_align_url_key',$my_data_url);
			
			if (!isset($_POST['align_desc_field'])){return;}
			$my_data_desc = sanitize_text_field($_POST['align_desc_field']);
			update_post_meta($post_ID,'_align_desc_key',$my_data_desc);
        //}
	}
	
	/**
     * Alignment meta-box.
     * @todo control the function of the @function check()
     *
     * @author @Ioanna
     * @since  1.0.0
     *
     * @param $post
     */
    
	public static function alignment($post){
		wp_nonce_field('saveMetabox_align','alignment_nonce');
		$value_name = get_post_meta($post->ID,'_align_name_key',true);
		$value_url = get_post_meta($post->ID,'_align_url_key',true);
		$value_desc = get_post_meta($post->ID,'_align_desc_key',true);
		
		/**if (SettingsTemp::checkerCallback()->$iwanna=="checked")
		{
			echo '<label for="align_name_field">Name</label><br>';
			echo '<input type="text" name="align_name_field" value="Education" readonly /><br>';
			
			echo '<label for="align_url_field">Url</label><br>';
			echo '<input type="text" name="align_url_field" value="Education" readonly /><br>';
			
			echo '<label for="align_desc_field">Description</label><br>';
			echo '<input type="text" name="align_desc_field" value="Education" readonly />';
		}
		else
		{ **/
			echo '<label for="align_name_field">Name</label><br>';
			echo '<input type="text" name="align_name_field" value="'. esc_attr( $value_name).'" /><br>';
			
			echo '<label for="align_url_field">Url</label><br>';
			echo '<input type="text" name="align_url_field" value="'. esc_attr( $value_url).'" /><br>';
			
			echo '<label for="align_desc_field">Description</label><br>';
			echo '<input type="text" name="align_desc_field" value="'. esc_attr( $value_desc).'" />';
		
		//}
	}
	
	
    /**
     * Certification meta-box.
     * @todo control the function of the @function check()
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param $post
     */
    public static function certification($post) {
        $val = get_post_meta($post->ID, '_certification', true);

        echo '<input type="radio" value="' . self::META_FIELD_CERT . '" name="certification_input"';
        self::check($val, self::META_FIELD_CERT);
        printf(__('> Certified<br>', 'open-badges-framework'));

        echo '<input type="radio" value="' . self::META_FIELD_NOT_CERT . '" name="certification_input"';
        self::check($val, self::META_FIELD_NOT_CERT);
        printf(__('> Not certified<br>', 'open-badges-framework'));
    }
	

    /**
     * Target meta-box.
     * @todo control the function of the @function check()
     *
     * @author @AleRiccardi
     * @since  1.0.0
     */
    public static function target($post) {
        $val = get_post_meta($post->ID, '_target', true);

			echo '<input type="radio" value="' . self::META_FIELD_STUDENT . '" name="target_input"';
			self::check($val, self::META_FIELD_STUDENT);
			printf(__('> Student<br>', 'open-badges-framework'));

			echo '<input type="radio" value="' . self::META_FIELD_TEACHER . '" name="target_input"';
			self::check($val, self::META_FIELD_TEACHER);
			printf(__('> Teacher<br>', 'open-badges-framework'));
    }


    /**
     * Check if the $val is equal to the $expected value.
     *
     * @author Nicolas TORION
     * @since  0.4
     *
     * @param mixed $val      value to verify
     * @param mixed $expected value that is confronted with the first param.
     */
    function check($val, $expected) {
        if ($val == $expected) {
            echo " checked";
        }
    }
}