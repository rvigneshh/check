<?php
//require(plugin_dir_path( __DIR__ ).'RedisCache.php');
/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.bitwise.academy/
 * @since      1.0.0
 *
 * @package    Bitwise_Data_Visualisation
 * @subpackage Bitwise_Data_Visualisation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bitwise_Data_Visualisation
 * @subpackage Bitwise_Data_Visualisation/public
 * @author     MadhanKarthik <madhan.k@bitwiseacademy.com>
 */


class Bitwise_Iq_Data_Visualisation_Public {

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

        /************************************************/
        //IQ Engine KCG Search
        /************************************************/
	public function iq_kcg_search_course()
	{
		$course_id = $_REQUEST['val'];

		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {
			$user_id = $user->ID;
		}else{
			$user_id =  $_REQUEST["student_id"];
		}
	   	$courses = learndash_user_get_enrolled_courses($user_id,true);

   	   	$redis = new RedisCache();
   		$getStudentDetails =  json_decode($redis->getValue_iq($user_id), true);

   	   	$node_vars = $getStudentDetails['KCG']['graph data'][$course_id];

		if(is_null($node_vars)) {
			$getDummyDetails =  json_decode($redis->getValue_iq(1), true);
			$node_vars = $getDummyDetails['KCG']['graph data'][$course_id];
		}

		echo json_encode($node_vars); 
		wp_die();
	}

        /************************************************/
        //IQ Engine Reports page shortcode
        /************************************************/
	public function bit_iq_kcg_report_function($atts)
	{

		global $wpdb;
		$user = wp_get_current_user();
		/*Getting userid*/
		if(in_array('subscriber',$user->roles)|| in_array('college_student',$user->roles)) {
			$user_id = $user->ID;
		}else{
			$user_id = $_GET["id"];
		}

		$courses = learndash_user_get_enrolled_courses($user_id,true);
		$courses = array_reverse($courses);
		foreach($courses as $course){
			$get_datas[] = get_post($course);
		}

	   	extract(shortcode_atts(array(
	      		'width'   	=> 100,
	      		'height'  	=> 200,
	      		'user_id'	=> $user_id), $atts)
	   	);
		/*Checking paid or free users*/
                $quiz_key = get_user_meta( $user_id, 'user_quiz_expire_date', true );
                if($quiz_key) {

	   	$redis = new RedisCache();
                $checkkey = $redis->isExists_iq($user_id);
                if($checkkey == 0) {
                        //If user data does not exists in Redis
                        //Getting dummy data
                        $getDummyDetails =  json_decode($redis->getValue_iq(1), true);
                        $iqkcgChart = $getDummyDetails['KCG']['graph data'][27726];
                } else if($checkkey == 1) {

			$getStudentDetails =  json_decode($redis->getValue_iq($user_id), true);
			if(array_key_exists('KCG',$getStudentDetails))
			{
	   			$iqkcgChart = $getStudentDetails['KCG']['graph data'][27726]; //266 is course id of the intro to scracth pgm, which is very first chart to be display
	   			if (is_null($iqkcgChart)) {
					$getDummyDetails =  json_decode($redis->getValue_iq(1), true);
	   				$iqkcgChart = $getDummyDetails['KCG']['graph data'][27726];
	   			}
			}
			else
			{
				$getDummyDetails =  json_decode($redis->getValue_iq(1), true);
                        	$iqkcgChart = $getDummyDetails['KCG']['graph data'][27726];
			}
		}

		/*Displaying KCG Graph*/
		wp_enqueue_script( 'graph_report', plugin_dir_url( __FILE__ ) . 'js/graph-iq.js', array($this->plugin_name.'-nv-d3'), $this->version, false);
		wp_localize_script( 'graph_report', 'node_iqvars', $iqkcgChart );
		require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-iqdata-visualisation-public-kcg-chart.php';
		}
		/*If not purchased IQ Engine*/
		else
		{
                	$user = wp_get_current_user();
                	if(in_array('subscriber',$user->roles)) {
				echo "<h2 style='color: #000'>The IQ Engine<sup>TM</sup> report is only available to the subscribers of bitWise IQ assessment engine. You can click on the IQ Engine menu to access sample assessments. Click <a href='javascript:void(0)' style='color: red' data-uid='".$user->ID."' id='iq-mail'>here</a> to subscribe the IQ Engine<sup>TM</sup></h2>";
			}
			else
			{
				echo "<h2 style='color: #000'>The IQ Engine<sup>TM</sup> report is only available to the subscribers of bitWise IQ assessment engine. You can click on the IQ Engine menu to access sample assessments. Click <a style='color: red' href='/bitwise-upgrade/?user_id=$user->ID'>here</a> to subscribe the IQ Engine<sup>TM</sup></h2>";
			}
		}

	}

	/************************************************/
	//Mail notification to parent regarding IQ Engine
        /************************************************/
        public function bit_iq_mail() {

		$userid = $_POST['userid'];
		$iq_mail_sent = get_user_meta( $user_id, 'iq_mail_sent', true );

		/*If already mail sent*/
		if($iq_mail_sent)
		{
			echo "Mail notification already sent to parent!!";
                        wp_die();
		}
		/*If mail not sent yet*/
		else
		{
    			$message = sprintf( __( "Your child indicated interest in the bitWise IQ Engine. Kindly subscribe for your student to have access to the bitWise IQ Engine" ) ) . "\r\n\r\n";
    			$title = $wp_new_user_notification_email['subject'] = sprintf( 'bitWise IQ Mail Notification' );
    			$wp_new_user_notification_email['message'] = $message;

                	global $wpdb;

			$group_id = $wpdb->get_var("SELECT meta_value FROM `bit_usermeta` WHERE `user_id` = ".$userid." AND `meta_key` LIKE '%learndash_group_users_%'");
			$parent_id = $wpdb->get_var("SELECT user_id FROM `bit_usermeta` WHERE `meta_key` ='learndash_group_leaders_".$group_id."'");
			$user = get_user_by('id', $parent_id);
			$parent_email = $user->user_email;
			error_log( print_r( $parent_email, true ) );
			if($parent_email)
			{
    				wdm_mail_new($parent_email, $title, $message);
				update_user_meta( $user_id, 'iq_mail_sent', 1 );
                        	echo "Mail notification sent to parent!!";
			}
			else
			{
                                echo "Mail notification failed sent to parent!!";
			}
			wp_die();
		}

	}

}

