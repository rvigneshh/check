<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       support@bitwiseacademy.com
 * @since      1.0.0
 *
 * @package    Bitwise_Grade_Prerequities
 * @subpackage Bitwise_Grade/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bitwise_Grade_Prerequities
 * @subpackage Bitwise_Grade_Prerequities/public
 * @author     MOULI GANDHI <mouli.gandhi12@gmail.com>
 */

class Bitwise_Grade_Prerequisite_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function grade_by_pass($bypass_course_limits_admin_users, $user_id, $course_id, $post ){

          $grade_validation = get_user_meta($user_id,'grade',true);

	  if($grade_validation == '0') return false;

          $course_id_value = get_user_meta($course_id,'grade',true);
          $pieces = explode(" ", $grade_validation);
          //error_log(print_r($pieces, true));
	  /**********************************************************************************/
          //Modified by Vignesh on March 30th 2020 - Start
	  $check=(isset($pieces[0])) ? isset($pieces[1]) ? $pieces[1] : '' : '';
          //Modified by Vignesh on March 30th 2020 - End
          /**********************************************************************************/

          $beginner= get_option('bitwise_grade_prerequisties_settings_beginer');
          $intermediate= get_option('bitwise_grade_prerequisties_settings_intermediate');
          $advanced= get_option('bitwise_grade_prerequisties_settings_advanced');
          $course_nam = get_the_category( $course_id );

	  if(count($course_nam) > 0)
          {

          $nam = esc_html($course_nam[0]->name);
          $cat= '';
          if($check>$intermediate)
          {
               $cat='Advance';
          }
          elseif(($check>$beginner)&&($check<=$intermediate)&&($nam=='Intermediate'))
          {
              $cat='Intermediate';
          }

           switch($cat)
           {
              case "Intermediate":
                    return true;
                    break;
              case "Advance":
                    return true;
                    break;
              default :
           }
	  }
	}
}
