<?php
require(plugin_dir_path( __DIR__ ).'core/CurlClient.php');
require(plugin_dir_path( __DIR__ ).'RabbitMQ/RabbitMQ.php');
/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.bitwise.academy
 * @since      1.0.0
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/public
 */
Use Core\CurlClient as CurlClient;
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/public
 * @author     MadhanKarthik Ramasamy <madhan.k@bitwiseacademy.com>
 */
class Bitwise_Ai_Service_Public {

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

		//Custom function.
		$this->curlcl = new CurlClient();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bitwise_Ai_Service_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bitwise_Ai_Service_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bitwise-ai-service-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name.'-sweetalert', plugin_dir_url( __FILE__ ) . 'css/sweetalert.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bitwise_Ai_Service_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bitwise_Ai_Service_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$show_msg = get_user_meta(get_current_user_id(), 'show_alert', true);
                /*Modified by Vignesh R on August 28th 2020*/
                if(!$show_msg)
                {
                        $show_msg = "hide";
                }

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bitwise-ai-service-public.js', 
			array('jquery'), $this->version, false );

		wp_register_script( $this->plugin_name.'services-js', plugin_dir_url( __FILE__ ) . 'js/bitwise-ml-services-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name.'services-js', 'show_msg', $show_msg);
		
		wp_enqueue_script( $this->plugin_name.'services-js');

		wp_enqueue_script( $this->plugin_name.'-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js', array( $this->plugin_name.'services-js'), $this->version, false );

		update_user_meta(get_current_user_id(), 'show_alert', 'hide');
	}

	/**
	 * [sendAssessmentScore sends the assessment score to the RabbitMQ for the KCG graph]
	 */
	public function sendAssessmentScore()
	{
           $user = wp_get_current_user();
	   $user_id = $user->ID;
	   $quiz_data = get_user_meta( $user_id, '_sfwd-quizzes', true );

           if(in_array('subscriber', $user->roles)){

		$result = array();
		foreach ($quiz_data as $datum) {

			$course_id = get_post_meta($datum['quiz'], 'course_id', true);
			$lesson_id = get_post_meta($datum['quiz'], 'lesson_id', true);
			$course = get_the_title($course_id);
			$lesson = get_the_title($lesson_id);
			$result[$course_id][$lesson] = $datum['points'];
		}

		$msg = array('user_id' => $user_id, 'assessment' => $result);

		$this->sendQueue($msg);
	      }
	     return false;
	 }


	/**
	 * [sendQueue - send the msg to the queue]
	 * @param  string $msg  sends the string msg to the rabbitMQ
	 * @return bool         returns the response as bool
	 */
	private function sendQueue($msg='')
	{
		$host = get_option('bitwise_rabbitMQ_settings_host');
		$port = get_option('bitwise_rabbitMQ_settings_port');
		$username = get_option('bitwise_rabbitMQ_settings_username');
		$password = get_option('bitwise_rabbitMQ_settings_password');

		$connection = new RabbitMQ( $host, $port, $username, $password);
		$channel = $connection->Channel();
		$channel = $connection->ExchangeDeclration('kcg_graph_orig', 'topic');
		$channel = $connection->QueueDeclration('kcg_graph_orig_queue');

		$binding_key = "#.kcg_graph_orig.#";
		$channel = $connection->QueueBind('kcg_graph_orig_queue', 'kcg_graph_orig', $binding_key);

		$route_key = "kcg_graph_orig";
	
		$msg = json_encode($msg);
		$msg = (string)$msg;
		$connection->BasicPublish($msg, 'kcg_graph_orig', $route_key);
	}
     

     public function myplugin_cookie_expiration( $expiration, $user_id, $remember ) {
      
        return $remember ? $expiration : 6;

          // add_filter( 'auth_cookie_expiration', 'myplugin_cookie_expiration', 99, 3 );      
      }


	/**
	 * Function is used to get the rentention of details of the subscriders based on the user_id.
	 * @param  string $user_login Return the logged in user name
	 * @param  object $user       Object holds the user related information
	 */
	public function wp_login_function($user_login='', $user='')
	{

		global $wpdb;
		$retention_score = 0;

		//If the user is subscriber perform the following actions in it.
		if(in_array('subscriber',$user->roles)) {

			//Get the course ID
			$course_id = $this->getIncompleted($user->ID);

			if( !isset( $course_id ) ) return false;

			//Get the latest retention score for the student.
			$ret_table  = $wpdb->prefix . 'student_retention_score';
			$ret_result = $wpdb->get_row("SELECT * FROM $ret_table WHERE user_id = $user->ID AND course_id = $course_id ORDER BY id DESC LIMIT 1", ARRAY_A);
			$wpdb->flush();
			if( !is_NULL( $ret_result ) ) {

				//Get the elapsed days.
				$course_id = (int)$ret_result['course_id'];
				$cur_login  = new DateTime('now');
				$last_login = new DateTime($ret_result['created_on']);

				$date_diff       = $last_login->diff($cur_login);
				$elapsed_days    = (int)$date_diff->format("%a");

				//Get the existing retention score.
				$retention_score = $ret_result['retention_score'];
				
				//Update the existing rentention score,If the elapsed days is more than week.
				if($elapsed_days > 7) {

					//Get the lateset rentention score and store it.
					$retention_details = $this->getRententionScore($user->ID, $course_id, $elapsed_days);
					$retention_details = json_decode($retention_details);
					$retention_score   = $retention_details->retention_score;

					$ret_sql = $wpdb->query(
									$wpdb->prepare("UPDATE $ret_table SET 
													retention_score = %f,
													retention_category = %s,
													status = %s
													WHERE 
													course_id = %d AND
													user_id = %d",
													$retention_details->retention_score, 
													$retention_details->retention_category,
													$retention_details->status,
													$course_id,
													$user->ID)
												);
									$wpdb->flush();
				}

			} else {

				//Insert the very first retention score for the  student 
				$retention_details = $this->getRententionScore($user->ID, $course_id);
				$retention_details = json_decode($retention_details);
				if( !is_NULL($retention_details)) {
					$retention_score   = $retention_details->retention_score;
					$ret_sql = $wpdb->query(
									$wpdb->prepare("INSERT INTO $ret_table (
												retention_score, 
												retention_category, 
												status, 
												course_id, 
												user_id) VALUES 
												(%f, %s, %s, %d, %d)",
												$retention_details->retention_score, 
												$retention_details->retention_category, 
												$retention_details->status, 
												$retention_details->course_id,
												$user->ID)
								);

								$wpdb->flush();
				}

			}

			$progress = get_user_meta( $user->ID, '_sfwd-course_progress', true );//Get the student progress
			$lessons  = $progress[$course_id]['topics']; // List out the lessons
			$topics = end($lessons); //Get the topics from most recently lesson list.
			$recent_lesson = end(array_keys($topics)); //Get the recent topic from the lesson.

			if ($retention_score > 0 && $retention_score < 0.6 ) {

				$completed = $progress[$course_id]['completed'];
				$redirection_id = $topics_id = key($lessons); //Get the topics from most recently lesson list.
				$lessons[$topics_id] = array();
				$progress[$course_id]['topics'] = $lessons;
				$progress[$course_id]['completed'] = $completed - count($topics);
				$topics = end($lessons); //Get the topics from most recently lesson list.
				update_user_meta( $user->ID, '_sfwd-course_progress', $progress );//Update get the student progress
			}

			$link = learndash_get_course_progress($user->ID, $recent_lesson);//Get the student progress.

			$curr_link = ( $link['this'] != '' ) ? get_post($link['this'])->ID: $course_id;
			$next_link = ( $link['next'] != '' ) ? get_post($link['next'])->ID: $course_id;
			$prev_link = ( $link['prev'] != '' ) ? get_post($link['prev'])->ID: $course_id;


			//Based on the retention score direct the students to the levels
			if ( $retention_score > 0.8 ) 
			{	
				//Go to next level.
				wp_redirect( site_url() .'/?p='. $next_link );exit;
			}
			else if ($retention_score > 0 && $retention_score < 0.6 ) 
			{
				update_user_meta( $user->ID, 'show_alert', 'show');
				//Go to previous level.
				wp_redirect( site_url() .'/?p='. $redirection_id);exit;
			}				
			else 
			{
				//Repeat the current topic.
				wp_redirect( site_url() .'/?p='. $curr_link );exit;
			}

		}
	}

	/**
	 * [getRententionScore - Function used to get the rentention score of the given user based non the course and days]
	 * @param  integer  $id           [User id of the student]
	 * @param  integer  $course_id    [Course id of the student]
	 * @param  integer  $elapsed_days [Elpased days of the rentention]
	 * @return json/bool              [returns the rentention score if it is true, False otherwise]
	 */
	private function getRententionScore($id=0, $course_id=0, $elapsed_days=0)
	{

		$cumulative_session_time = 0;
		$last_result = 0;

		$last_result = $this->getLatestAssessment($id); //Get the latest retention score here.
		$cumulative_session_time = $this->cumulativeCourse($id, $course_id); //Get the Cumulative session time for the user based on the course id.

		$method = 'POST';

		$url = "course.retention/R/get.retention.score/print";

		$params = array('input' => array( "Anon_Student_Id" 			=> $id, 
		                                  "Course_Id" 					=> $course_id, 
		                                  "Elapsed_Days" 				=> $elapsed_days, 
		                                  "Cumulative_Session_Time_sec" => (float)$cumulative_session_time, 
		                                  "Immediate_Prev_Attempt" 		=> (float)$last_result
		                                )
		                );
		$params = json_encode($params);
		return $this->curlcl->exeCurl($url, $method, $params);
	}


	/**
	 * [getLatestAssessment -   To get the latest quiz socre based on the user_id]
	 * @param  string $user_id ID of the student
	 * @return integer         Returns the score of the user
	 */
	public function getLatestAssessment($user_id='')
	{
		global $wpdb;

		$learndash_user_activity  = $wpdb->prefix . 'learndash_user_activity';
		$result = $wpdb->get_row(" SELECT * FROM $learndash_user_activity 
								   WHERE user_id = $user_id AND activity_type = 'quiz'
								   ORDER BY activity_id DESC LIMIT 1", ARRAY_A);
		$activity_id = $result['activity_id'];

		$learndash_user_activity_meta  = $wpdb->prefix . 'learndash_user_activity_meta';
		$score_query = $wpdb->prepare("SELECT activity_meta_value 
									   FROM $learndash_user_activity_meta
							           WHERE  `activity_meta_key` =  'score' 
							           AND activity_id = %d", $activity_id);
		$latest_score  = $wpdb->get_var($score_query);

		return $latest_score;
	}


	/**
	 * [getIncompleted - Get the incompleted course]
	 * @param  string $user_id [Pass the user_id to get the particular record]
	 * @return [bool/int]          [return the course_id if it is true, otherwise false]
	 *
	 * @since 1.0.0
	 */
	private function getIncompleted($user_id='')
	{
		global $wpdb;

		$learndash_user_activity  = $wpdb->prefix . 'learndash_user_activity';

		$get_course_id = $wpdb->prepare("SELECT post_id FROM
									   $learndash_user_activity
							           WHERE  `user_id` = %d
							           AND `activity_type` LIKE 'course' 
							           AND `activity_completed` = 0 ORDER BY `activity_id` DESC LIMIT 1", $user_id);

		$course_id = $wpdb->get_var($get_course_id);
		return $course_id;
	}

	/**
	 * [cumulativeCourse - Cumlative timespent by the course based on the user id]
	 * @param  int 	  $user_id    User id to get the timespent.
	 * @param  int 	  $course_id  Course id of the particular user.
	 * @return int    returns the time spent based on the course id.
	 */
	private function cumulativeCourse($user_id, $course_id='')
	{
		global $wpdb;

		$concept_table  = $wpdb->prefix . 'corn_concept_mastery';

		$cumulative_session_query = $wpdb->prepare("SELECT SUM(`time_spent`) AS cumulative_session FROM
									   $concept_table
							           WHERE  `user_id` = %d
							           AND `course_id` = %d
							           GROUP BY course_id", $user_id, $course_id);

		$cumulative_session = $wpdb->get_var($cumulative_session_query);
		return $cumulative_session;
	}

	public function wp_login_process( $user_login, $user )
	{
	    //If logged out user is subscriber
	    if ( in_array( 'subscriber', (array) $user->roles ) ) 
	    {
		$uid = $user->ID;
		//Adding login count for subscribers
		if ( ! empty( get_user_meta( $uid, 'bit_login_count', true ) ) )
        	{
                	$login_count = get_user_meta( $uid, 'bit_login_count', true );
                	$login_cnt = (int) $login_count + 1;
                	update_user_meta( $uid, 'bit_login_count', $login_cnt );

        	} else {
                	add_user_meta( $uid, 'bit_login_count', 1 );
        	}
	        /*try
	        {
	            $host = get_option('bitwise_rabbitMQ_settings_host');
	            $port = get_option('bitwise_rabbitMQ_settings_port');
	            $username = get_option('bitwise_rabbitMQ_settings_username');
	            $password = get_option('bitwise_rabbitMQ_settings_password');

                    $rm_queue = get_option('login_rm_queue');

	            $connection = new RabbitMQ( $host, $port, $username, $password );
	            $channel = $connection->Channel();
	            $channel = $connection->QueueDeclration($rm_queue);

	            $date = date("Y-m-d");
	            $userdata = [ 'user_id'=> $user->ID, 'log_date'=> $date ];
	            $msg = json_encode($userdata);
	            $connection->BasicPublish($msg, '', $rm_queue);  
	        }
	        //catch exception
	        catch(Exception $e) {
	            $exp = $e->getMessage();
	            $userid = $user->ID;
	            $message = $exp." Rabbitmq process failed during user login #userid ".$userid;   
	            $file = fopen("../../rabbitmq.log","a"); 
	            fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message); 
	            fclose($file);
	        }*/ 
	    }
	}

	public function wp_logout_process()
	{
	    //If logged out user is subscriber
	    $user = wp_get_current_user();
	    if ( in_array( 'subscriber', (array) $user->roles ) ) 
	    {
	        try
	        {
	            $host = get_option('bitwise_rabbitMQ_settings_host');
	            $port = get_option('bitwise_rabbitMQ_settings_port');
	            $username = get_option('bitwise_rabbitMQ_settings_username');
	            $password = get_option('bitwise_rabbitMQ_settings_password');

                    $rm_queue = get_option('logout_rm_queue');

	            $connection = new RabbitMQ( $host, $port, $username, $password );
	            $channel = $connection->Channel();
	            $channel = $connection->QueueDeclration($rm_queue);

	            $date = date("Y-m-d");
	            $userdata = [ 'user_id'=> $user->ID, 'log_date'=> $date ];
	            $msg = json_encode($userdata);
	            $connection->BasicPublish($msg, '', $rm_queue);
	        }
	        //catch exception
	        catch(Exception $e) {
	            $exp = $e->getMessage();
	            $userid = $user->ID;
	            $message = $exp." Rabbitmq process failed during user logout #userid ".$userid;   
	            $file = fopen("../../rabbitmq.log","a"); 
	            fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message); 
	            fclose($file);
	        }   
	    }
	}

}
