<?php
namespace Inc\Base;


/**
 *This class extends the comments section and mofifies them
 *
 *
 * @package     OpenBadgesFramework
 */
class ExtendComment {

    /**
     * Function that permit to execute code only
     * when you active the plugin.
     */
    public function register() {
		
		add_filter('comment_form_default_fields', array($this,'custom_fields'));
		add_filter('comment_form_defaults', array($this,'modify_default_form'));
		add_filter('comment_form_submit_field', array($this,'change_submit_button'));
		add_filter('comment_reply_link', array($this,'remove_reply_link'), 10, 3);
		add_action( 'comment_form_logged_in_after', array($this,'additional_fields'));
		add_filter( 'preprocess_comment', array($this,'verify_comment_meta_data' ));
		add_action( 'comment_post', array($this,'save_comment_meta_data'));
	    add_filter( 'comment_text', array($this,'modify_comment'));
		
		//$thanks_for_reading = new WPJMR_Form();

		//add_action('plugins_loaded', array($this,'my_coupon_init'));

        
		
		
	}
	
	
	/**
     * This function is responsible for setting the default commnent
     * fields when the post type of the post is not open-badge
     */
	public function custom_fields($fields) {
 		 $cptype = get_post_type();
         $commenter = wp_get_current_commenter();
		 $req = get_option( 'require_name_email' );
		 $aria_req = ( $req ? " aria-required='true'" : '' );
		 
		 if ($cptype !== 'open-badge'){
			 $fields[ 'author' ] = '<p class="comment-form-author">'.
			  '<label for="author">' . __( 'Name' ) . '</label>'.
			  ( $req ? '<span class="required">*</span>' : '' ).
			  '<input id="author" name="author" type="text" value="'. esc_attr( $commenter['comment_author'] ) .
			  '" size="30" tabindex="1"' . $aria_req . ' /></p>';

			$fields[ 'email' ] = '<p class="comment-form-email">'.
			  '<label for="email">' . __( 'Email' ) . '</label>'.
			  ( $req ? '<span class="required">*</span>' : '' ).
			  '<input id="email" name="email" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) .
			  '" size="30"  tabindex="2"' . $aria_req . ' /></p>';

			$fields[ 'url' ] = '<p class="comment-form-url">'.
			  '<label for="url">' . __( 'Website' ) . '</label>'.
			  '<input id="url" name="url" type="text" value="'. esc_attr( $commenter['comment_author_url'] ) .
			  '" size="30"  tabindex="3" /></p>'; 
			  
			return $fields;
		 }

		 
	}
	
	
	
	  
	  
	/**
     * This function modifies the comment field and the reply title
     * when the post type is open-badge and the user is not an admin or academy teacher
     */
	public function modify_default_form($arg) {
		$user = wp_get_current_user();
		$allowed_roles = array('administrator', 'academy');
		$cptype = get_post_type();
		$cpid = get_the_ID();
        
		 global $wpdb;
			  
		//the workflow below checks if there is a translation for the language that the commentator selected
		
		if( !array_intersect($allowed_roles, $user->roles ) && $cptype =='open-badge' ) { 
			$arg['comment_field'] = '';
			$arg['title_reply'] = 'You dont have permission to comment';
			
			return $arg;
		}
		
	}
	
	
	/**
     * This function modifies the submit button 
     * when the post type is open-badge and the user is not an admin or academy teacher
     */
	public	function change_submit_button($submit_field) {
		 $cptype = get_post_type();
         if ($cptype=='open-badge'){
				$user = wp_get_current_user();
				$allowed_roles = array('administrator', 'academy');
				if( !array_intersect($allowed_roles, $user->roles ) ) { 
					$submit_field = '';
					return $submit_field;
				}else{
					return $submit_field;
				}
		 }else{
			 return $submit_field;
		 }
	}
	
	/**
     * This function modifies the reply link
     * when the post type is open-badge 
     */
	public function remove_reply_link($link, $args, $comment){
		 $cptype = get_post_type();
         if ($cptype=='open-badge'){		 
			$link='';
			return $link;	
		 }else{
			 return $link;
		 }	
	}
	
    /**
     * This function adds the dropdown list field for choosing a language and
     * adding a translation when the post type is open-badge and the user is an admin or academy teacher
     */
	public function additional_fields () {
		$cptype = get_post_type();
		if ($cptype =='open-badge'){
			$user = wp_get_current_user();
			$allowed_roles = array('administrator', 'academy'); 
			if( array_intersect($allowed_roles, $user->roles ) ) { 
				echo  '<p class="comment-form-language">'.
					 '<label for="url">' . __( 'Language' ) . '</label>'.
					  '<select name="language" id="language" class="required">'.
					  '<option value="">Select a language</option>';	
					  
				$languages = array(
					   'Bulgarian',
					   'Croatian',
					   'Czech',
					   'Danish',
					   'Dutch',
					   'English',
					   'Estonian',
					   'Finnish',
					   'French',
					   'German',
					   'Greek',
					   'Hungarian',
					   'Irish',
					   'Italian',
					   'Latvian',
					   'Lithuanian',
					   'Maltese',
					   'Polish',
					   'Portuguese',
					   'Romanian',
					   'Slovak',
					   'Slovenian',
					   'Spanish',
					   'Swedish',   
					);
					  
					foreach ( $languages as $value ){
						echo  '<option value="'.$value.'">'.$value.'</option>';
					  }
					echo  '</select></p>' ; 
			}
		}
	}

	
	/**
     * This function is responsible for checking if the user has select a translation language
     * and also check if there is a translation for the language that the user selected
     */	
	public function verify_comment_meta_data( $commentdata ) {
		
		  if ( (isset( $_POST['language'])) && ($_POST['language']=='')){
			  
			  wp_die( __( 'You did not choose a language translation.' ) );
			  return $commentdata;
			  
		  }else if ((isset( $_POST['language'])) && ($_POST['language']!='')){
			  
			  $langvalue = $_POST['language'];
			  global $wpdb;
			  
			  //the workflow below checks if there is a translation for the language that the commentator selected
			  $language_exists =  $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM ".$wpdb->prefix."commentmeta as a,".$wpdb->prefix."comments as b where meta_value = %s and comment_post_ID = %s and a.comment_id = b.comment_ID and comment_approved = 1",$langvalue,$commentdata['comment_post_ID']));
			  
			  if($language_exists){
				  wp_die( __( 'The transaltion for this language already exists for this badge!' ) );
				  return $commentdata;
			  }else{
				  return $commentdata;
			  }
			  
		  }else{
			  return $commentdata;
		  }
	}
	
	/**
     * This function is responsible for saving the metadate(language) of the comment
     * in the database
     */
	public function save_comment_meta_data( $comment_id ) {
	    if ( (isset( $_POST['language'])) && ($_POST['language']!='')){
			
			$language = wp_filter_nohtml_kses($_POST['language']);
			add_comment_meta( $comment_id, 'language', $language );
		}
	}
	
	/**
     * This function is responsible for showing the comment section
     * about translation and languages
     */
	public function modify_comment( $text ){

	  if( $lang = get_comment_meta( get_comment_ID(), 'language', true ) ) {
		
		$lang = '<p class="lang">  <br/><strong>Language : </strong> '. $lang .' </p>';
		$translation = '<strong> Translation :</strong><br/>';
		
		$custom = $lang . $translation;
		$text =  $custom . $text;

		return $text;
	  } else {
		return $text;
	  }
	}

} 