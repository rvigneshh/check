<?php
require(plugin_dir_path( __DIR__ ).'RedisCache.php');
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


class Bitwise_Data_Visualisation_Public {

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
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bitwise_Data_Visualisation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bitwise_Data_Visualisation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name.'-visalisation', plugin_dir_url( __FILE__ ) . 'css/bitwise-data-visualisation-public.css', array(), $this->version, 'all' );

		wp_enqueue_style($this->plugin_name.'-nv-d3', plugin_dir_url( __FILE__ ) . 'css/nv.d3.css', __FILE__);

		wp_enqueue_style( $this->plugin_name.'-dataTables-css', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css' );

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
		 * defined in Bitwise_Data_Visualisation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bitwise_Data_Visualisation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name.'-js', plugin_dir_url( __FILE__ ) .'js/bitwise-data-visualisation-public.js', array('jquery'),$this->version, false );

		wp_localize_script( $this->plugin_name.'-js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

		wp_enqueue_script( $this->plugin_name.'-js', 'myAjax');

		wp_enqueue_script( $this->plugin_name.'-d3-reports', '//cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js',array($this->plugin_name.'-js'), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-d3-v3-reports', '//d3js.org/d3.v3.min.js',array($this->plugin_name.'-d3-reports'), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-d3-time', '//d3js.org/d3-time.v1.min.js',array($this->plugin_name.'-d3-v3-reports'), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-nv-d3', plugin_dir_url( __FILE__ ) . 'js/nv.d3.js',array($this->plugin_name.'-d3-time'),$this->version, false );

		wp_register_script( $this->plugin_name.'-kcgChart', plugin_dir_url( __FILE__ ) . 'js/kcg-chart.js', array($this->plugin_name.'-nv-d3'), $this->version, false);

		wp_enqueue_script( $this->plugin_name.'dataTables-js', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js' );

        	wp_enqueue_script( $this->plugin_name.'dataTables-responsive-js', plugin_dir_url( __FILE__ ) . 'js/dataTables.responsive.min.js' );
		/*
		wp_register_script($this->plugin_name.'-performanceChart', plugin_dir_url( __FILE__ ) . 'js/performance-curve.js', array($this->plugin_name.'-kcgChart'), $this->version, false);
		*/
	}

	/**
	*To enqueue javascript for custom transcript code
	*/
	public function transcript_enqueue_scripts() {

                if ( is_page('report'))
                {
                        wp_enqueue_script( $this->plugin_name.'transcript-js', plugin_dir_url( __FILE__ ) . 'js/custom-transcript.js' );
                }

	}

	/**
	 * [getSessionTiming Function is used to get the timespent by the user for the particular course]
	 * @param  [int] $course_ID      [To get the course ID]
	 * @param  [int] $post_ID        [To get the Post ID]
	 * @param  [int] $timer_interval [Time spent by the user]
	 */
	public function trackStudentActivity($course_ID, $post_ID, $timer_interval)
	{
		global $wpdb;

		$activity_table = $wpdb->prefix.'bitWise_student_activity';

		$roles = wp_get_current_user()->roles;
		$id = get_current_user_id();
		$post_type = get_post_type($post_ID);

		//If the user is subscriber perform the following actions in it.
		if(in_array('subscriber',$roles)) {

			$select_query = $wpdb->prepare("SELECT * FROM $activity_table WHERE `user_id`=%d AND`course_id`=%d AND `post_id`=%d", $id, $course_ID, $post_ID);

			$result = $wpdb->query($select_query);

			if($result) {
				$select_query = $wpdb->prepare("UPDATE $activity_table SET `time_spent`=`time_spent`+%d, `created_on`=now(), `post_type`=%s WHERE `user_id`=%d AND `course_id`=%d AND `post_id`=%d", $timer_interval, $post_type, $id, $course_ID, $post_ID);

				$resut = $wpdb->query($select_query);
			} else {
				$select_query = $wpdb->prepare("INSERT INTO $activity_table(`user_id`, `course_id`, `post_id`,  `post_type`, `time_spent`, `visited`, `reported_on`, `created_on`) VALUES(%d, %d, %d, %s, %d, %d, now(),now())",$id, $course_ID, $post_ID, $post_type, $timer_interval, 1);

				$resut = $wpdb->query($select_query);
			}
		}
	}

	/**
	 * [bit_learndash_lesson_completed - Function is used to keep track of the badege's count based on the user id and course id]
	 * @return [type]       [description]
	 */
	public function bit_learndash_lesson_completed($user){
		global $wpdb;

		$badgeos_table = $wpdb->prefix.'bitWise_badgeos';
		$roles = wp_get_current_user()->roles;
		$id = get_current_user_id();
		$course_id = $user['course']->ID;

		if(in_array('subscriber',$roles)){

			$select_query = $wpdb->prepare("SELECT * FROM $badgeos_table WHERE `user_id`=%d AND`course_id`=%d", $id, $course_id);

			$result = $wpdb->query($select_query);

			if($result){

				$select_query = $wpdb->prepare("UPDATE $badgeos_table SET `badge_count` =`badge_count` + 1 WHERE `user_id`=%d AND `course_id`=%d", $id, $course_id);
				$result = $wpdb->query($select_query);

			}else{

				$select_query = $wpdb->prepare("INSERT INTO $badgeos_table(`user_id`, `course_id`,`badge_count`) VALUES(%d, %d, %d)", $id, $course_id, 1);

				$result = $wpdb->query($select_query);
			}
		}
		return false;
	}

	/**
	 * [round_off Function is used to round off the score based on the given value]
	 * @param  [float] $get_score [Score to be get round off]
	 * @return [int]              [Score after rounding off]
	 */
	private function round_off($get_score){

	$get_value = substr( $get_score, -(strlen($get_score)-(strpos($get_score, '.')+1)) );
        $num = substr($get_value, 0, 1);

        if(is_float($get_score)){
	        if($num > 5){
	        	$score = ceil($get_score);

	        }else{
	    		$score = floor($get_score);
	        }
	    }else{
	    	$score = $get_score;
	    }
		return $score;
	}

	/**
	 * [bit_get_edcite_quiz - Function is used to update scores from the Edcite]
	 * @param  [Array] $quiz_details [Quiz results]
	 */
	public function bit_get_edcite_quiz($quiz_details){

		global $wpdb;

		$quiz_id = $quiz_details['quizzes']['pro_quizid'];
		$get_score = $quiz_details['quizzes']['score'];
		$score = $this->round_off($get_score);
		$total_ques = $quiz_details['quizzes']['count'];
		$quizmeta = get_post_meta($quiz_details['quizzes']['quiz'], '_sfwd-quiz', true );
		$courseid = $quizmeta['sfwd-quiz_course'];
		$id =  $quiz_details['user']->ID;
		$roles =  $quiz_details['user']->roles;


		$quizzes_table = $wpdb->prefix.'bitWise_quizzes';

		if( in_array('subscriber', $roles) ) {

			$select_query = $wpdb->prepare("SELECT * FROM $quizzes_table WHERE `user_id`=%d AND`course_id`=%d", $id, $courseid);

			$result = $wpdb->query($select_query);

			if($result){

				$select_query = $wpdb->prepare("UPDATE $quizzes_table SET `most_ques_ans` = %d,
											  `total_ques` = %d
											  WHERE `user_id`=%d AND `course_id`=%d AND `quiz_id` =%d", $score, $total_ques, $id, $courseid, $quiz_id);
				$result = $wpdb->query($select_query);

			}else{

				$select_query = $wpdb->prepare("INSERT INTO $quizzes_table (`user_id`, `course_id`,`quiz_id`, `most_ques_ans`, `total_ques`) 
												VALUES (%d, %d, %d, %d, %d)", $id, $courseid, $quiz_id, $score, $total_ques);

				$result = $wpdb->query($select_query);
			}
		}
	}

	public function bit_ld_quiz_completed($quizdata, $current_user) {

		global $wpdb;

		$quiz_id = $quizdata['pro_quizid'];
		$total_ques = $quizdata['count'];
		$get_score = $quizdata['score'];
		$score = $this->round_off($get_score);
		$courseid = $quizdata['course']->ID;
		//error_log(print_r($quizdata, true));

		$quizzes_table = $wpdb->prefix.'bitWise_quizzes';

		$roles = $current_user->roles;
		$id = $current_user->ID;

		if( in_array('subscriber', $roles) ) {
			$select_query = $wpdb->prepare("SELECT * FROM $quizzes_table WHERE `user_id`= %d AND`course_id`= %d AND `quiz_id` =%d", $id, $courseid, $quiz_id);

			//error_log($select_query);
			$result = $wpdb->query($select_query);
			//error_log(print_r($results, true));

			if($result){

				$select_query = $wpdb->prepare("UPDATE $quizzes_table
												SET `most_ques_ans` = %d,
												`total_ques` = %d
												WHERE `user_id`=%d AND `course_id`=%d AND `quiz_id` =%d", $score, $total_ques, $id, $courseid, $quiz_id);
				$result = $wpdb->query($select_query);

			}else{

				$select_query = $wpdb->prepare("INSERT INTO $quizzes_table (`user_id`, `course_id`,`quiz_id`, `most_ques_ans`, `total_ques`) 
												VALUES (%d, %d, %d, %d, %d)", $id, $courseid, $quiz_id, $score, $total_ques);

				$result = $wpdb->query($select_query);
			}
		}
	}

	private function bit_get_quiz_score($user_id, $quiz_id){
		global $wpdb;
		$wp_pro_quiz_statistic = $wpdb->prefix.'wp_pro_quiz_statistic';
		$wp_pro_quiz_statistic_ref = $wpdb->prefix.'wp_pro_quiz_statistic_ref';

		$q_query = "SELECT SUM($wp_pro_quiz_statistic.`correct_count`) AS `correct_ans` FROM $wp_pro_quiz_statistic_ref INNER JOIN $wp_pro_quiz_statistic ON $wp_pro_quiz_statistic.`statistic_ref_id` = $wp_pro_quiz_statistic_ref.`statistic_ref_id` WHERE $wp_pro_quiz_statistic_ref.`user_id` = ".$user_id." AND $wp_pro_quiz_statistic_ref.`quiz_id` =".$quiz_id;

		$get_details = $wpdb->get_row( $q_query, ARRAY_A);
		return $get_details;

	}

	public function student_reports($atts) {

		global $wpdb;
		$student_reports_chart = true;
		$user = wp_get_current_user();

		$activity_table = $wpdb->prefix.'bitWise_student_activity';

		/***********************************************************************************************/
		//Modified by Vignesh on March 30th 2020 - Start (Reports opened without userid from parent Login
		/***********************************************************************************************/
		if(in_array('subscriber',$user->roles)) {
                        $user_id = $user->ID;
                }
 		else if(in_array('college_student',$user->roles)) {
                        $user_id = $user->ID;
                }
		else if(isset($_GET["id"]) && !$_GET["id"] == '') {
                        $user_id = $_GET["id"];
                } else {
			echo "<script>document.location = '/student-reports/';</script>";
                        exit();
                }
		/**********************************************************************************************/
		//Modified by Vignesh on March 30th 2020 - End (Reports opened without userid from parent Login)
		/**********************************************************************************************/
	   	extract(shortcode_atts(array(
	      		'width'   	=> 100,
	      		'height'  	=> 200,
	      		'user_id'	=> $user_id,
	      		'type' 		=> 'pie'), $atts)
	   	);

		$courses = learndash_user_get_enrolled_courses($user_id,true);
	        $course_id = $courses[0];

	   	$redis = new RedisCache();
		/**********************************************************************************/
		//Modified by Vignesh on March 30th 2020 - Start (Checking if data exists in Redis)
		/**********************************************************************************/
		$checkkey = $redis->isExists($user_id);
		if($checkkey == 0) {
			//If user data does not exists in Redis
			//Getting dummy data
			$results = $this->defaultChartValue();
			$forceY = array('forceY' => 0);
		} else if($checkkey == 1) {
			//If user data exists in Redis
			$getStudentDetails =  json_decode($redis->getValue($user_id));
			if(isset($getStudentDetails->studentActivityChart->all->result)) {
	   			$results = $getStudentDetails->studentActivityChart->all->result;
				$maxtime = is_null(($getStudentDetails->studentActivityChart->all->maxTimeSpent)) ? array('forceY' => 0) : $getStudentDetails->studentActivityChart->all->maxTimeSpent;
	                        $forceY = is_null($results) ? $maxtime : array('forceY' => 0);
			} else {
				$results = $this->defaultChartValue();
				$forceY = array('forceY' => 0);
			}
		}
		/**********************************************************************************/
		//Modified by Vignesh on March 30th 2020 - End (Checking if data exists in Redis)
		/**********************************************************************************/
		$courses = array_reverse($courses);
		foreach($courses as $course){
			$get_datas[] = get_post($course);
		}

		if(!empty($results && $forceY)){
			if($type == 'bar_chart') {
				$historicalBarChart = array(
					'datum' => array( array('key' => "Cumulative Return", 'values' => $results )),
					'forceY' => $forceY,
					'type' => $type
				);
		   	}

			wp_enqueue_script( 'graph_report', plugin_dir_url( __FILE__ ) . 'js/graph.js', array($this->plugin_name.'-nv-d3'), $this->version, false);
			wp_localize_script( 'graph_report', 'php_vars', $historicalBarChart );
		}

		require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-chart.php';
	}

	/*********************************************************************************************/
	//Added by Vignesh on March 30th 2020 - Start (To get dummy data if there is no data in Redis)
	/*********************************************************************************************/
	private function defaultChartValue()
	{
		//Getting From and Two months
		$d = new DateTime('now');
            	$frm_date = new DateTime("-5 months");
            	$from_date= $frm_date->format('Y-m-d');
            	$month = $frm_month= $frm_date->format('m');
            	$to_date= $d->format('Y-m-d');
            	$to_month= $month + 6;
            	$result_val = array();
            	$j = 0;
            	$values = 0;
            	$label = 0;

		//Generating Monthwise data
		for($i=$month; $i<$to_month; $i++) {

			$year = $frm_date->format('Y');
               		$year = ($month > 12) ? ($year+1) : $year;
               		$q_month = ($month > 12) ? ($month%12) : $month;

			$dateObj   = DateTime::createFromFormat('!m', $q_month);
               		$monthName = $dateObj->format('F');
               		$month++;

               		$temp_res = $result_val[] = array('value' => 0,'label' => substr($monthName,0,3).'-'.substr($year,2,3));
                	$label = $temp_res['label'];
                	$dummy_results[$j] = array('value' => $values,'label' => $label);
                	$j++;

		}
		//Returning dummy data
		return $dummy_results;
	}
	/*********************************************************************************************/
        //Added by Vignesh on March 30th 2020 - Start (To get dummy data if there is no data in Redis)
        /*********************************************************************************************/

        	public function student_courses() {
        		global $wpdb, $posts;
        		$user = wp_get_current_user();
        		$user_id = $user->ID;
        		$current_date = time();
        	        $user_expire_date = get_user_meta($user_id, 'user_expire_date', true);
        		if( ! get_user_meta($user_id, 'user_expire_date', true) ) { ?>
        		         <div class="bit_ots-message">
                                        <p class="bit-exp-std-msg"><?php esc_html_e( 'You dont have access to view this page'); ?></p>
                                 </div>
        		<?php } else {
        		if(!in_array('group_leader',$user->roles)) {
        		    if ($user_expire_date > $current_date) {
        	  		echo  do_shortcode('[bw_ld_course_list col=3  mycourses="enrolled"]'); //Update the ld course list page shortcode with our own shortcode by suresh on 18-8-2020
        		} elseif($user_expire_date==''){echo  do_shortcode('[bw_ld_course_list col=3  mycourses="enrolled"]');} else { ?>
        			<div class="bit_ots-message">
        				 <p class="bit-exp-std-msg"><?php esc_html_e( 'Your subscription has expired. To send a subscription renewal request to your parent'); ?>
        				<a data-stdnt_id="<?php echo esc_attr( $user_id ); ?>" id="bit_ots_stdt_eml" class="bit_ots-button button" href="javascript:void(0);"><?php esc_html_e( 'Click here', 'bit-ots' ); ?></a></p>
        			 </div>
        		<?php } } } }

	/**
	 * store student activity time spent by month data in RedisCache
	 */

	public function setStudentActivity(){
		global $wpdb;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {

			$user_id = $user->ID;
			$courses_id = learndash_user_get_enrolled_courses($user_id,true);
			$total_max_time_spent = array();
			$result_data = array();

			foreach ($courses_id as $course_id) {

				$d = new DateTime('now');
				$frm_date = new DateTime("-5 months");
	 			$from_date= $frm_date->format('Y-m-d');
	 			$month = $frm_month= $frm_date->format('m');
	 			$to_date= $d->format('Y-m-d');
	 			$to_month= $month + 6;
				$result_val = array();
				$j = 0;
				$values = 0;
				$label = 0;

			   	for($i=$month; $i<$to_month; $i++) {

	 				$year = $frm_date->format('Y');
					$year = ($month > 12) ? ($year+1) : $year;
					$q_month = ($month > 12) ? ($month%12) : $month;

					$query = "SELECT SUM(time_spent) AS `value`, CONCAT(SUBSTRING(MONTHNAME(reported_on), 1,3),'-',SUBSTRING(YEAR(reported_on), 3, 2)) AS `label`
						FROM  $activity_table WHERE `user_id` = ".$user_id." AND `course_id` = ".$course_id." AND MONTH(`reported_on`) = $q_month AND YEAR(`reported_on`) = $year GROUP BY MONTH(reported_on) ORDER BY YEAR(reported_on) ASC";

					$results = $wpdb->get_row( $query, ARRAY_A );

					$dateObj   = DateTime::createFromFormat('!m', $q_month);
					$monthName = $dateObj->format('F');
					$month++;

					$temp_res = $result_val[] = ($results) ? $results : array('value' => 0,'label' => substr($monthName,0,3).'-'.substr($year,2,3));
					$values = $temp_res['value'];
					$label = $temp_res['label'];
					$result_data[$j] = array('value' => $values+$result_data[$j]['value'],'label' => $label);
					$j++;
			   	}

				$q_forceY = "SELECT SUM(time_spent) as forceY
				FROM $activity_table WHERE `user_id` = ".$user_id." AND `course_id` = ".$course_id." AND `reported_on` BETWEEN '$from_date' AND '$to_date' GROUP BY MONTH(reported_on) ORDER BY YEAR(reported_on) ASC";
				$forceY = $wpdb->get_results( $q_forceY, ARRAY_A );

				$data[$course_id] = array('result' => $result_val, 'maxTimeSpent'=> max(array_values($forceY)));
				$total_max_time_spent[] = max(array_values($forceY));

			}

			$data['all'] = array('result' => $result_data, 'maxTimeSpent' => max(array_values($total_max_time_spent)));
			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($user_id));
			$get_data->studentActivityChart = $data;

			$setValues = $redis->setValue($user_id, json_encode($get_data));

		}

	}

	public function student_activity($atts){

		global $wpdb;
		$student_activity = true;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$user = wp_get_current_user();

	   	if(in_array('subscriber',$user->roles) || in_array('college_student',$user->roles)) {
			$user_id = $user->ID;
		}else{
			$user_id = $_GET["id"];
		}

	   	extract(shortcode_atts(array(
	      		'user_id'   => $user_id), $atts)
	   	);

	    $redis = new RedisCache();
	    /**********************************************************************************/
            //Modified by Vignesh on March 30th 2020 - Start (Checking if data exists in Redis)
            /**********************************************************************************/
            $checkkey = $redis->isExists($user_id);
            if($checkkey == 1) {

	    	$data = json_decode($redis->getValue($user_id),true);
                $dummy_summary= array('result'=>array('min_time_spent'=> 0,'max_time_spent'=> 0,'total_time_spent'=> 0),'get_login_count'=> 0);
	    	$get_student_summary = (isset($data['studentSummary'])) ? $data['studentSummary'] : $dummy_summary;

	    }
	    else if($checkkey == 0) {

		$get_student_summary = array('result'=>array('min_time_spent'=> 0,'max_time_spent'=> 0,'total_time_spent'=> 0),'get_login_count'=> 0);

	    }
            /**********************************************************************************/
            //Modified by Vignesh on March 30th 2020 - Start (Checking if data exists in Redis)
            /**********************************************************************************/

	    require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-display.php';
	}

	/**
	 * Sets the student summary data to RedisCache.
	 */
	public function setStudentSummary(){

		global $wpdb;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)){

			$user_id = $user->ID;
			$courses_id = learndash_user_get_enrolled_courses($user_id,true);

			$d = new DateTime('now');
			$frm_date = new DateTime("-6 months");
 			$from_date= $frm_date->format('Y-m-d');
 			$to_date= $d->format('Y-m-d');

			foreach ($courses_id as $course_id)
			{
				$query = "SELECT MIN(`time_spent`) AS `min_time_spent`, MAX(`time_spent`) AS `max_time_spent` , SUM(`time_spent`) AS `total_time_spent` FROM $activity_table WHERE `user_id` = ".$user_id." AND `reported_on` BETWEEN '$from_date' AND '$to_date'";

				$data = $wpdb->get_row( $query, ARRAY_A);

			}

		    $get_login_count = get_user_meta($user_id,'bit_login_count',true); 

			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($user_id));
			$get_data->studentSummary = array('result' => $data, 'get_login_count' => $get_login_count);
			$setValues = $redis->setValue($user_id, json_encode($get_data));

		}

	}

	/**
	 * Save user login count to Database.
	 *
	 * @param string $user_login username
	 * @param object $user WP_User object
	 */
	public function count_user_login($user_login, $user){

         if ( ! empty( get_user_meta( $user->ID, 'bit_login_count', true ) ) ) 
		{
            $login_count = get_user_meta( $user->ID, 'bit_login_count', true );
            update_user_meta( $user->ID, 'bit_login_count', ( (int) $login_count + 1 ) );

          } else {
            update_user_meta( $user->ID, 'bit_login_count', 1 );
          }
	}

	/**
	 * Fill the stat column with values.
	 *
	 * @param string $empty
	 * @param string $column_name
	 * @param int $user_id
	 *
	 * @return string|void
	 */
	public function fill_stats_columns($empty, $column_name, $user_id){

		 if ( 'login_stat' == $column_name )
		  {
            if ( get_user_meta( $user_id, 'bit_login_count', true ) !== '' ) 
            {
                $login_count = get_user_meta( $user_id, 'bit_login_count', true );

                return "<strong>$login_count</strong>";
            } else {
                return __( 'No record found.' );
            }
        }

        return $empty;
	}


	public function bit_tp_time_spent(){


		$user = wp_get_current_user();

		if((in_array('administrator', (array) $user->roles)) || (in_array('group_leader', (array) $user->roles))){

		global $wpdb;

		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$posts_table = $wpdb->prefix.'posts';

		$get_course = "SELECT $activity_table.`id`,$activity_table.`course_id`,$activity_table.`post_id`,$activity_table.`time_spent`,$posts_table.`id`,$posts_table.`post_title`,$posts_table.`post_type` FROM $activity_table INNER JOIN $posts_table ON $posts_table.`ID` = $activity_table.`post_id` WHERE $posts_table.`post_type` = 'sfwd-topic' ORDER BY $activity_table.`time_spent` DESC LIMIT 0, 5";

		$get_course_id = $wpdb->get_results( $get_course, ARRAY_A);

		if(!empty($get_course_id))
		{

		$results = "<span>
				<table id='tp_time_spent' class='table table-striped table-responsive table-responsive table-bordered bt_box_shadow'>
   				<tr>
   					<th colspan='2' class='primary-ui-color text-center font-bold text-white'>MOST TIME SPENT</th>
   				</tr>
   				<tr>
   					<th style='text-align:center;'><strong>Topic</strong></th>
   					<th style='text-align:center;'><strong>Time</strong></th>
   				</tr>";
				foreach($get_course_id as $data){

					$results .=
	   				"<tr>
	   					<td>".$data['post_title']."</td>
	   					<td>". gmdate("H:i:s", $data['time_spent'])." <span style='color:grey; font-size:10px;'>(HH:MM:SS)</span> </td>
	   				</tr>";
				}

				$results .= "</table></span>";

   			return $results;
		}
		}

	}

	public function bit_tp_visited(){


		$user = wp_get_current_user();

		if((in_array('administrator', (array) $user->roles)) || (in_array('group_leader', (array) $user->roles))){

		global $wpdb;

		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$posts_table = $wpdb->prefix.'posts';

		$get_course = "SELECT $activity_table.`id`,$activity_table.`course_id`,$activity_table.`post_id`,$activity_table.`visited`,$posts_table.`id`,$posts_table.`post_title`,$posts_table.`post_type` FROM $activity_table INNER JOIN $posts_table ON $posts_table.`ID` = $activity_table.`post_id` WHERE $posts_table.`post_type` = 'sfwd-topic' ORDER BY $activity_table.`visited` DESC LIMIT 0, 5";
	        $get_course_id = $wpdb->get_results( $get_course, ARRAY_A);

	    if(!empty($get_course_id))
		{
			$results = "<div id='tp_mt_visited' class='bt-card bt-body_1 bt-bg-blue'>
					<div class='bt-card-body'>
					<div class='font-bold bt-m-b--35'>MOST VISITED</div>
						<ul class='bt-dashboard-stat-list'>";
			foreach ($get_course_id as $data)
			{
				$results.= "<li class='bt-li-pd'>".$data['post_title']."<span class='pull-right'><b>".$data['visited']."</b><small> Vists</small></span></li>";
			}
   			$results.= "</ul></div></div>";
   			return $results;
		}
	}

	}

	/**
	 * display the most time spent by user
	 *
	 * @return     string  ( description_of_the_return_value )
	 */
	public function bit_individual_user(){

		global $wpdb;

		$single_report = TRUE;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles) || in_array('college_student',$user->roles)) {
			$user_id = $user->ID;
			$user_name = $user->display_name;
		}else{
			$user_id = $_GET["id"];
			$user_info = get_user_by('ID',$user_id);
			$user_name = $user_info->display_name;
		}

		$redis = new RedisCache();
		$courses = json_decode($redis->getValue($user_id));

		$results = "<span>
                <table class='table table-striped table-responsive table-bordered bt_box_shadow'>
                <tr class= 'tb-head'>
                    <th colspan='2' class='primary-ui-color text-center font-bold text-white'>MOST TIME SPENT - ".ucfirst($user_name)."</th>
                </tr>";

		$results .="<tr>
                     <th style='text-align:center;'><strong>Topic</strong></th>
                     <th style='text-align:center;'><strong>Time</strong></th>
                </tr>";

                if(!empty($courses->topicFrequency->timeSpentByUser->result)){

	                foreach($courses->topicFrequency->timeSpentByUser->result as $data){
	                    	$results .=
		                "<tr>
	                        <td style='padding-left:10px;padding-right:10px;'>".$data->post_title."</td>
	                        <td style='padding-right:10px;'>". gmdate("H:i:s", $data->time_spent)." <span style='color:grey; font-size:10px;'>(HH:MM:SS)</span> </td>
	                    	</tr>";
	                }
		}else{
                                /***********************************************************************/
                                //Added by Vignesh on March 30th 2020 - Start (To Remove No data caption)

		        	$results .= "<tr><td style='padding-left:10px;padding-right:10px;text-align:center'>***</td><td style='padding-right:10px;text-align:center'>***</td></tr>";

                                //Added by Vignesh on March 30th 2020 - End (To Remove No data caption)
                                /***********************************************************************/

		}

        	$results .= "</table>
            	</span>";

        	return $results;

		// require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-single-report.php';

	}

	/**
	 * set the most time spent data by student in REDIS CACHE
	 */
	public function setMostTimeSpentByUser(){

		global $wpdb;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {

			$user_id = $user->ID;
			$query = "SELECT $activity_table.`id`,$activity_table.`course_id`,$activity_table.`user_id`,$activity_table.`post_id`,$activity_table.`time_spent`,`bit_posts`.`id`,`bit_posts`.`post_title`,`bit_posts`.`post_type` FROM $activity_table INNER JOIN `bit_posts` ON `bit_posts`.`ID` = $activity_table.`post_id` WHERE `bit_posts`.`post_type` = 'sfwd-topic' AND `$activity_table`.`user_id` = $user_id ORDER BY $activity_table.`time_spent` DESC LIMIT 0 ,5";

			$courses = $wpdb->get_results( $query, ARRAY_A);
			$studentDetails['topicFrequency'] = array('result'=> $courses); 

			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($user_id));
			$get_data->topicFrequency->timeSpentByUser = array('result'=> $courses);
			$setValues = $redis->setValue($user_id, json_encode($get_data));
		}

	}
	/**
	 * Set the visited count by user in REDIS CACHE
	 */
	public function setVisitedCountByUser(){

		global $wpdb;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$posts_table = $wpdb->prefix.'posts';
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {

			$user_id = $user->ID;
			$get_course = "SELECT $activity_table.`id`,$activity_table.`course_id`,$activity_table.`user_id`,$activity_table.`post_id`,$activity_table.`visited`,$posts_table.`id`,$posts_table.`post_title`,$posts_table.`post_type` FROM $activity_table INNER JOIN $posts_table ON $posts_table.`ID` = $activity_table.`post_id` WHERE $posts_table.`post_type` = 'sfwd-topic' AND `$activity_table`.`user_id` = ".$user_id."  ORDER BY $activity_table.`visited` DESC LIMIT 0 ,5";


			$get_details = $wpdb->get_results( $get_course, ARRAY_A);

			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($user_id));
			$get_data->topicFrequency->visitedCountByUser = array('result'=> $get_details);
			$setValues = $redis->setValue($user_id, json_encode($get_data));

		}
	}


	/**
	 * Display the most visited count by user
	 *
	 * @return     string  ( description_of_the_return_value )
	 */
	public function bit_user_visited(){

		global $wpdb;

		$user_visited = true;
		$activity_table = $wpdb->prefix.'bitWise_student_activity';
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles) || in_array('college_student',$user->roles)) {
			$user_id = $user->ID;
			$user_name = $user->display_name;
		}else{
			$user_id = $_GET["id"];
			$user_info = get_user_by('ID',$user_id);
			$user_name = $user_info->display_name;
		}

		// $get_course = "SELECT $activity_table.`id`,$activity_table.`course_id`,$activity_table.`user_id`,$activity_table.`post_id`,$activity_table.`visited`,`bit_posts`.`id`,`bit_posts`.`post_title`,`bit_posts`.`post_type` FROM $activity_table INNER JOIN `bit_posts` ON `bit_posts`.`ID` = $activity_table.`post_id` WHERE `bit_posts`.`post_type` = 'sfwd-topic' AND `$activity_table`.`user_id` = ".$user_id."  ORDER BY $activity_table.`visited` DESC LIMIT 0 ,5";		
		// 	$get_details = $wpdb->get_results( $get_course, ARRAY_A);

			$redis = new RedisCache();
			$get_details = json_decode($redis->getValue($user_id));

			$results = "<span>
				<table class='table table-striped table-responsive table-bordered bt_box_shadow'>
   				<tr class= 'tb-head'>
   					<th colspan='2' class='primary-ui-color text-center font-bold text-white '>MOST VISITED - ".ucfirst($user_name)."</th>
   				</tr>";

				$results .= "<tr>
                                        <th style='text-align:center;'><strong>Topic</strong></th>
                                        <th style='text-align:center;'><strong>No.of.Visit</strong></th>
                                </tr><tbody>";

   				if(!empty($get_details->topicFrequency->visitedCountByUser->result)){

					foreach($get_details->topicFrequency->visitedCountByUser->result as $data){

						$results .=
		   				"<tr>
		   					<td style='padding-left:10px;padding-right:10px;'>".$data->post_title."</td>
		   					<td style='padding-right:10px; text-align:center;'>".$data->visited."</td>
		   				</tr>";
					}
   				}else{
					/***********************************************************************/
					//Added by Vignesh on March 30th 2020 - Start (To Remove No data caption)

					$results .= "<tr><td style='padding-left:10px;padding-right:10px;text-align:center'>***</td><td style='padding-right:10px; text-align: center'>***</td></tr>";

                                        //Added by Vignesh on March 30th 2020 - End (To Remove No data caption)
                                        /***********************************************************************/
   				}

				$results .= "</tbody></table>
   			</span>";

   			return $results;
	}

	/**
	 * [viewCount update the count and the post type of user visited page]
	 * since 1.0.0
	 */
	public function viewCount()
	{
	 	if(is_single()) {
	 		global $post;
			$roles = wp_get_current_user()->roles;
			$id = get_current_user_id();

	 		//If the user is subscriber perform the following actions in it.
	 		if(in_array('subscriber',$roles)) {

	 			$update = array('sfwd-topic', 'sfwd-lesson', 'sfwd-courses');
	 			$type = get_post_type();

		 		if ( in_array($type, $update)  ) {
		 			$this->updateCount($id, $post->ID, $type);
		 		}
		 	}
	 	}
	}

	/**
	 * [updateCount update the count and the post type of user visited page]
	 * since 1.0.0
	 */
	private function updateCount($id='', $post_ID, $type)
	{

		global $wpdb;

		$activity_table = $wpdb->prefix.'bitWise_student_activity';

		$select_query = $wpdb->prepare("UPDATE $activity_table SET `visited` =`visited` + 1, `post_type` = '$type' WHERE `user_id`=%d AND `post_id`=%d",  $id, $post_ID);

		return $wpdb->query($select_query);

	}

	private function array_sort($array, $on, $order=SORT_ASC)
	{
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}

	public function setMostQuesAnswered(){

		global $wpdb;
		$quizzes_table = $wpdb->prefix.'bitWise_quizzes'; 

		$current_user = wp_get_current_user();
		if(in_array('subscriber',$current_user->roles)) {

			$users = get_users();
			$data = array();
			$current_user_id = $current_user->ID;
			$get_user_ids = array();
			$u_data = array();


			foreach($users as $user){
				if(in_array('subscriber',$user->roles)) {
					$user_id = $user->ID;
					$q_query = "SELECT SUM(`total_ques`) AS `total_ques`, SUM(`most_ques_ans`) AS `most_ques_ans`, `course_id`  FROM $quizzes_table WHERE `user_id` =".$user_id." GROUP BY `user_id` ORDER BY `most_ques_ans` DESC LIMIT 0, 5";
					$get_details = $wpdb->get_row( $q_query, ARRAY_A);

					$results['total_questions'] = $get_details['total_ques'];
					$results['correct_ans']  = $get_details['most_ques_ans'];
					$results['user_id'] = $user_id;
					$results['course_id'] = $get_details['course_id'];
					$data[] = $results;
				}
			}


			$result_sort = $this->array_sort(array_filter($data), 'correct_ans', SORT_DESC);
			$most_ques_answered = array_slice($result_sort, 0, 5, true);

			$q_query = "SELECT * FROM $quizzes_table WHERE `user_id` =".$current_user_id;
			$get_user_data = $wpdb->get_row( $q_query, ARRAY_A);
			$res["all"] = array('result' => $most_ques_answered, $current_user_id => $get_user_data);

			$course_ids = ld_get_mycourses($current_user_id);
			//$courses = array_reverse(courses);
			foreach ($course_ids as $course_id) {

				// $course_data = array();
				// foreach ($users as $user) {
					$c_query = "SELECT `user_id`,`course_id`,`most_ques_ans` AS `correct_ans`,`total_ques` AS `total_questions` FROM $quizzes_table WHERE `course_id` =".$course_id." GROUP BY `user_id` ORDER BY `correct_ans` DESC LIMIT 0, 5";
					$get_res = $wpdb->get_results( $c_query, ARRAY_A);
					// $course_data[] = $get_res;
				// }

					// $res_sort = $this->array_sort(array_filter($course_data), 'correct_ans', SORT_DESC); 
			    	// $quiz_completed = array_slice($res_sort, 0, 5, true);

					$u_query = "SELECT `user_id`,`course_id`,`most_ques_ans` AS `correct_ans`,`total_ques` AS `total_questions` FROM $quizzes_table WHERE `course_id` =".$course_id." AND `user_id` =".$current_user_id;
					$get_user_row = $wpdb->get_row( $u_query, ARRAY_A);

					$res[$course_id] = array("result" => $get_res, $current_user_id => $get_user_row);
			}

			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($current_user_id));
			$get_data->leaderboard->mostQuesAnswered = $res;

			$setValues = $redis->setValue($current_user_id, json_encode($get_data));
		}

	}

	/**
	 * [setMostTopicCompleted - Function used to set the most topics completed by the user while logging out of the system]
	 *
	 * @since 1.0.0
	 *
	 */
	public function setMostTopicCompleted(){

		global $wpdb;

		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {
			$users = get_users();
			$current_user_id = $user->ID;
			$data = array();

			$learndash_user_activity_table = $wpdb->prefix.'learndash_user_activity';
			$posts_table = $wpdb->prefix.'postmeta';
			$course_ids = ld_get_mycourses($current_user_id);

			foreach($users as $user){
				if(in_array('subscriber',$user->roles)) {
					$user_id = $user->ID;

					$query = "SELECT COUNT(`user_id`) AS count, `user_id` FROM  $learndash_user_activity_table WHERE  `user_id` = ".$user_id." AND `activity_type` =  'topic'AND  `activity_completed` IS NOT NULL GROUP BY `user_id` ORDER BY `count` DESC LIMIT 0, 5";
					$get_details = $wpdb->get_row( $query, ARRAY_A);

					$data[] = $get_details;
				}
			}

			$result_sort = $this->array_sort(array_filter($data), 'count', SORT_DESC);
			$most_topic_completed = array_slice($result_sort, 0, 5, true);

			$u_query = "SELECT COUNT(`user_id`) AS count, `user_id` FROM $learndash_user_activity_table WHERE  `activity_type` =  'topic'AND  `activity_completed` IS NOT NULL AND `user_id` =". $current_user_id;
			$get_user_data = $wpdb->get_row( $u_query, ARRAY_A);
			$user_res['count'] = $get_user_data['count'];
			$user_res['user_id'] = $current_user_id;

			$res['all'] = array('result' => $most_topic_completed, $current_user_id => $user_res);

			foreach($course_ids as $course_id){

				$c_query = "SELECT COUNT($learndash_user_activity_table.`user_id`) AS `count`,$learndash_user_activity_table.`user_id` AS `user_id` FROM $learndash_user_activity_table INNER JOIN $posts_table ON $posts_table.`post_id` =  $learndash_user_activity_table.`post_id` WHERE $learndash_user_activity_table.`activity_type` =  'topic'  AND $posts_table.`meta_key` = 'course_id' AND $posts_table.`meta_value` = ".$course_id." AND $learndash_user_activity_table.`activity_completed` IS NOT NULL GROUP BY $learndash_user_activity_table.`user_id` ORDER BY `count` DESC LIMIT 0, 5";

				$get_res = $wpdb->get_results( $c_query, ARRAY_A);

				$i_query ="SELECT COUNT($learndash_user_activity_table.`user_id`) AS count, $learndash_user_activity_table.`user_id` FROM  $learndash_user_activity_table INNER JOIN $posts_table ON $posts_table.`post_id` = $learndash_user_activity_table.`post_id` WHERE  $learndash_user_activity_table.`activity_type` =  'topic' AND $posts_table.`meta_key` = 'course_id' AND $posts_table.`meta_value` = ".$course_id." AND $learndash_user_activity_table. `activity_completed` IS NOT NULL AND $learndash_user_activity_table.`user_id` =". $current_user_id; 
				$get_user_row = $wpdb->get_row( $i_query, ARRAY_A);

				// $user_course_res['count'] = $get_user_row['count'];
				// $user_course_res['user_id'] = $current_user_id;

				$res[$course_id] = array("result" => $get_res, $current_user_id => $get_user_row);
			}
			// var_dump($res);exit();
			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($current_user_id));

			$get_data->leaderboard->mostTopicCompleted =$res;
// echo "<pre>";
// var_dump($get_data->leaderboard->mostTopicCompleted);exit();
// var_dump($get_data);exit();
			$setValues = $redis->setValue($current_user_id, json_encode($get_data));

		}

	}

	public function setMostBadgesEarned(){
		global $wpdb;

		$badgeos_table = $wpdb->prefix.'bitWise_badgeos';
		$data = array();

		$user = wp_get_current_user();
		$current_user_id = $user->ID;

		if(in_array('subscriber',$user->roles)) {

			$users = get_users();
			$data_results = array();

			foreach($users as $user){
				if(in_array('subscriber',$user->roles)) {

					$user_id = $user->ID;

					$query = "SELECT `course_id`,SUM(`badge_count`) AS `badge_count` FROM $badgeos_table WHERE `user_id` =".$user_id." GROUP BY `user_id` ORDER BY `badge_count` DESC LIMIT 0, 5";
					$get_res = $wpdb->get_row( $query, ARRAY_A);

					$results['user_id'] = $user_id;
					$results['course_id'] = $get_res['course_id'];
					$results['badges_earned'] = $get_res['badge_count'];
					$data[] = $results;
				}
			}

			$result_sort = $this->array_sort(array_filter($data), 'badges_earned', SORT_DESC);
			$most_badges_earned = array_slice($result_sort, 0, 5, true);

			$u_query = "SELECT `user_id`,`course_id`,`badge_count` AS `badges_earned` FROM $badgeos_table WHERE `user_id` =".$current_user_id;
			$get_data = $wpdb->get_row( $u_query, ARRAY_A);

			$res['all'] = array('result' => $most_badges_earned, $current_user_id => $get_data);

			$course_ids = ld_get_mycourses($current_user_id);

			foreach($course_ids as $course_id){
				$course_data = array();
				// foreach($users as $user){
					// if(in_array('subscriber',$user->roles)) {
				$c_query = "SELECT `user_id`,`course_id`,`badge_count` AS `badges_earned` FROM $badgeos_table WHERE `course_id` =".$course_id." GROUP BY `user_id` ORDER BY `badge_count` DESC LIMIT 0, 5";

				$c_data = $wpdb->get_results( $c_query, ARRAY_A);
				// $course_data[] = $c_data;

					// }
				// }
				// $res_sort = $this->array_sort(array_filter($course_data), 'badges_earned', SORT_DESC);
				// $badges_earned = array_slice($res_sort, 0, 5, true);

				$u_query = "SELECT `user_id`,`course_id`,`badge_count` AS `badges_earned` FROM $badgeos_table WHERE `user_id` =".$current_user_id." AND `course_id` =".$course_id;
				$u_data = $wpdb->get_row( $u_query, ARRAY_A);

				$res[$course_id] = array("result" => $c_data, $current_user_id => $u_data);
			}

			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($current_user_id));

			$get_data->leaderboard->mostBadgesEarned =$res;
			// echo "<pre>";
			// var_dump($get_data);exit();
			$setValues = $redis->setValue($current_user_id, json_encode($get_data));

		}
	}

	public function setMostTimeSpent(){

		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {

			$current_user_id = $user->ID;
			global $wpdb;
			$student_activity_table = $wpdb->prefix.'bitWise_student_activity';
			$users = get_users();
			$user_id = array();
			$data = array();

			foreach ($users as $user) {

				$user_id = $user->ID;

				if(in_array('subscriber',$user->roles)) {

					// $query = "SELECT `id`,`user_id`,SUM(`time_spent`) AS `time_spent` FROM $student_activity_table GROUP BY `user_id` ORDER BY `time_spent` DESC LIMIT 0 ,5";
					$query = "SELECT `id`,`user_id`,SUM(`time_spent`) AS `time_spent` FROM $student_activity_table WHERE `user_id` = ".$user_id." GROUP BY `user_id` ORDER BY `time_spent` DESC LIMIT 0 ,5";
					$get_details = $wpdb->get_row( $query, ARRAY_A);

					$data[] = $get_details;				}
			}

			$result_sort = $this->array_sort(array_filter($data), 'time_spent', SORT_DESC); 
			$most_time_spent = array_slice($result_sort, 0, 5, true);

			$u_query = "SELECT `id`,`user_id`,SUM(`time_spent`) AS `time_spent` FROM $student_activity_table WHERE `user_id` = ".$current_user_id;
			$get_user_data = $wpdb->get_row( $u_query, ARRAY_A);
			$res['all'] = array('result' => $most_time_spent, $current_user_id => $get_user_data);

			$course_ids = ld_get_mycourses($current_user_id);

			foreach ($course_ids as $course_id) {
				// $course_data = array();

				// foreach($users as $user){
					// if(in_array('subscriber',$user->roles)) {
				$c_query = "SELECT `course_id`,`user_id`, SUM(`time_spent`) AS `time_spent` FROM $student_activity_table WHERE `course_id` = ".$course_id." GROUP BY `user_id` ORDER BY `time_spent` DESC LIMIT 0 ,5";
				$get_res = $wpdb->get_results( $c_query, ARRAY_A);

						// $course_data[] = $get_res;
					// }
				// }

				// $res_sort = $this->array_sort(array_filter($course_data), 'time_spent', SORT_DESC);
				// $time_spent = array_slice($res_sort, 0, 5, true);

				$i_query = "SELECT SUM(`time_spent`) AS `time_spent`,`course_id`, `user_id` FROM $student_activity_table WHERE `course_id` = ".$course_id." AND `user_id` =".$current_user_id;
				$get_user_row = $wpdb->get_row( $i_query, ARRAY_A);

				$res[$course_id] = array("result" => $get_res, $current_user_id => $get_user_row);

			}

			$redis = new RedisCache();
			$get_data = json_decode($redis->getValue($current_user_id));
			$get_data->leaderboard->mostTimeSpent = $res;

			$setValues = $redis->setValue($current_user_id, json_encode($get_data));

		}
	}

	public function showLeaderboard(){

		global $wpdb;
		$user = wp_get_current_user();
		$redis = new RedisCache();
		$usermeta_table = $wpdb->prefix.'usermeta';

		if(in_array('subscriber',$user->roles)) {
			$current_user_id = $user->ID;
			$get_data = json_decode($redis->getValue($current_user_id),true);
			$get_user_courses = ld_get_mycourses($current_user_id);
			$display_count = 6;
		}else{

			$parent_id = $user->ID;
		    // $get_user_courses = ld_get_mycourses(122);
		    // $get_user_courses = ld_get_mycourses($parent_id);
			$query = "SELECT `meta_value` FROM $usermeta_table WHERE `user_id` =".$parent_id." AND `meta_key` LIKE '%learndash_group_leaders%'";
			$group = $wpdb->get_row($query, ARRAY_A);
			$get_user_courses =learndash_group_enrolled_courses($group["meta_value"], true);
			$student_details = learndash_get_groups_users($group["meta_value"],true);
			$get_data = json_decode($redis->getValue($student_details[0]->ID),true);
			$student_id = array();

			foreach ($student_details as $student_detail) {
			    $student_id[] = $student_detail->ID;
			    // var_dump(json_decode($redis->getValue($student_id),true));exit();
			}
			$student_count = count($student_id);
			$display_count = 5 + $student_count;

		}

		$get_badges_earned = $get_data['leaderboard']['mostBadgesEarned']['all']['result'];
		$get_mostQuesAnswered = $get_data['leaderboard']['mostQuesAnswered']['all']['result'];
		// echo "<pre>";
		// var_dump(json_decode($redis->getValue(128),true));
		// exit();

		require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-leaderboard.php';
	}

	public function search_leaderboard_course(){

		global $wpdb;

		$course_id = $_REQUEST['val'];
		$user = wp_get_current_user();
		$usermeta_table = $wpdb->prefix.'usermeta';

		if(in_array('subscriber',$user->roles)) {
			$user_id = $user->ID;
			$display_count = 6;
			$student_id = 0;
			$student_details = NULL;
		}else{
			$parent_id = $user->ID;

		    // $get_user_courses = ld_get_mycourses($parent_id);
			$query = "SELECT `meta_value` FROM $usermeta_table WHERE `user_id` =".$parent_id." AND `meta_key` LIKE '%learndash_group_leaders%'";
			$group = $wpdb->get_row($query, ARRAY_A);

			$student_details = learndash_get_groups_users($group["meta_value"],true);
			$student_id = array();

			foreach ($student_details as $student_detail) {
			    $student_id[] = $student_detail->ID;
			}

			$student_count = count($student_id);
			$display_count = 5 + $student_count;

		}


		$mostTimeSpent = $this->search_mostTimeSpent($user, $course_id, $display_count,$student_id,$student_details);
		$mostTopicCompleted = $this->search_mostTopicCompleted($user, $course_id, $display_count,$student_id,$student_details);
		$mostQuesAnswered = $this->search_mostQuesAnswered($user, $course_id, $display_count,$student_id,$student_details);
		$mostBadgesEarned = $this->search_mostBadgesEarned($user, $course_id, $display_count,$student_id, $student_details);

		$res["mostTimeSpent"] = array('result' => $mostTimeSpent, 'status' => true);
		$res["mostTopicCompleted"] = array('result' => $mostTopicCompleted, 'status' => true);
		$res["mostQuesAnswered"] = array('result' => $mostQuesAnswered, 'status' => true);
		$res["mostBadgesEarned"] = array('result' => $mostBadgesEarned, 'status' => true);

		echo json_encode($res);
		wp_die();
	}

	private function search_mostBadgesEarned($current_user, $course_id, $display_count,$student_id,$student_details){

		$current_user_id = $current_user->ID;

		$user_ids = array();
		$redis = new RedisCache();

		if(in_array('subscriber',$current_user->roles)){
			$get_data = json_decode($redis->getValue($current_user_id),true);
		}else{

			$get_data = json_decode($redis->getValue($student_details[0]->ID),true);
		}
		$col_badges = $get_data["leaderboard"]["mostBadgesEarned"][$course_id]["result"];

		$results = '<ul class="list-group bt-lb" id="lb-badges-earned">';

            foreach($col_badges as $get_badges){
                $user_ids[] =  $get_badges["user_id"];
                $user_info = get_userdata($get_badges["user_id"]);

                if(in_array('group_leader',$current_user->roles)){

                	$style = in_array($get_badges["user_id"], $student_id) ? "color:blue;" : "";
                	// error_log("test");
                	$results .=  '<li class="list-group-item">
                            <div class="row" style="'.$style.'">
                                <div class="col-md-2">
                                    <img src="'.get_avatar_url($get_badges["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                                <div class="col-md-9">
                                    <div>
                                        <label>Name: </label>'. ucfirst($user_info->display_name).'</br>
                                        <label>Badges Earned: </label>'. $get_badges["badges_earned"].'
                                    </div>
                                </div>
                            </div>
                        </li>';

                }else{
                    if($get_badges["user_id"] == $current_user_id){
                        // error_log("test1");
                        $results .=  '<li class="list-group-item">
                            <div class="row" style="color:blue;">
                                <div class="col-md-2">
                                    <img src="'.get_avatar_url($get_badges["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                                <div class="col-md-9">
                                    <div>
                                        <label>Name: </label>'. ucfirst($user_info->display_name).'</br>
                                        <label>Badges Earned: </label>'. $get_badges["badges_earned"].'
                                    </div>
                                </div>
                            </div>
                        </li>';
                     }else{
                        $results .= '<li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="'.get_avatar_url($get_badges["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                                <div class="col-md-9">
                                    <div>
                                        <label>Name: </label>'. ucfirst($user_info->display_name).'</br>
                                        <label>Badges Earned: </label>'. $get_badges["badges_earned"].'
                                    </div>
                                </div>
                            </div>
                        </li>';
               		}
                }
            }

            $j = count($user_ids);

            if(in_array('group_leader', $current_user->roles)){

            	foreach($student_details as $student_detail){

            	$current_user_id = $student_detail->ID;
            	// error_log($current_user_id);
	             if(!in_array($current_user_id,$user_ids)){
	                $j = $j + 1;
	                $get_json = json_decode($redis->getValue($current_user_id),true);
	                $badges_earned_user = $get_json['leaderboard']['mostBadgesEarned'][$course_id][$current_user_id];
	                $user_inf = get_userdata($current_user_id);

	                $results .= '<li class="list-group-item">
	                    <div class="row"  style="color:blue;">
	                        <div class="col-md-2">
	                            <img src="'.get_avatar_url($current_user_id).'" class="img-circle img-responsive" alt="" /></div>
	                        <div class="col-md-9">
	                            <div>
	                                <label>Name: </label>'. ucfirst($user_inf->display_name).'</br>
	                                <label>Badges Earned: </label>'. ($badges_earned_user['badges_earned']? $badges_earned_user['badges_earned'] : 0).'
	                            </div>
	                        </div>
	                    </div>
	                </li>';
	            	}
	            }
	        }else{
	        	if(!in_array($current_user_id,$user_ids)){
	                $j = $j + 1;
	                $badges_earned_user = $get_data['leaderboard']['mostBadgesEarned'][$course_id][$current_user_id];
	                $user_inf = get_userdata($current_user_id);

	                $results .= '<li class="list-group-item">
	                    <div class="row"  style="color:blue;">
	                        <div class="col-md-2">
	                            <img src="'.get_avatar_url($current_user_id).'" class="img-circle img-responsive" alt="" /></div>
	                        <div class="col-md-9">
	                            <div>
	                                <label>Name: </label>'. ucfirst($user_inf->display_name).'</br>
	                                <label>Badges Earned: </label>'. ($badges_earned_user['badges_earned']? $badges_earned_user['badges_earned'] : 0).'
	                            </div>
	                        </div>
	                    </div>
	                </li>';
	             }
	        }

             $shows = $display_count - $j;

             if($shows !== 0){
            for ($i=0; $i < $shows; $i++) {
                $results .='<li class="list-group-item">
                      <div class="row">
                          <div class="col-md-2">
                              <img src="'.get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg').'" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label> NIL </br>
                                      <label>Badges Earned: </label> NIL
                              </div>
                          </div>
                      </div>
                  </li>';
                  }
                  }
            $results .= '</ul>';

        return $results;
	}

	private function search_mostQuesAnswered($current_user, $course_id, $display_count,$student_id, $student_details){

		$current_user_id = $current_user->ID;
		$usr_ids = array();
		$redis = new RedisCache();
		if(in_array('subscriber',$current_user->roles)){
			$get_data = json_decode($redis->getValue($current_user_id),true);
		}else{

			$get_data = json_decode($redis->getValue($student_details[0]->ID),true);
		}
		$ques_ans = $get_data['leaderboard']['mostQuesAnswered'][$course_id]['result'];

		 foreach($ques_ans as $user_detail){

            	// if($user_detail['correct_ans'] != NULL && $user_detail['total_questions'] != NULL){
                $usr_ids[] =  $user_detail["user_id"];
                $get_userdata = get_userdata($user_detail["user_id"]);

                if(in_array('group_leader',$current_user->roles)){

                	$style = in_array($user_detail["user_id"], $student_id) ? "color:blue;" : "";
                	$results .= '<li class="list-group-item">
	                        <div class="row" style="'.$style.'">
	                            <div class="col-md-2">
	                                <img src="'.get_avatar_url($user_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
	                            <div class="col-md-9">
	                                <div>
	                                   <label>Name: </label>'. ucfirst($get_userdata->display_name).'</br>
	                                   <label>Answered: </label>'. ($user_detail["total_questions"] ? $user_detail["correct_ans"] : 0).' /
	                                   <label>Total Questions: </label>'. ($user_detail["total_questions"] ? $user_detail["correct_ans"] : 0).'
	                                </div>
	                            </div>
	                        </div>
	                    </li>';
	                    //error_log(ucfirst($get_userdata->display_name));
                }else{
		                if($current_user_id == $user_detail["user_id"]){

		                   $results .= '<li class="list-group-item">
		                        <div class="row" style="color:blue;">
		                            <div class="col-md-2">
		                                <img src="'.get_avatar_url($user_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
		                            <div class="col-md-9">
		                                <div>
		                                   <label>Name: </label>'. ucfirst($get_userdata->display_name).'</br>
		                                   <label>Answered: </label>'. ($user_detail["total_questions"] ? $user_detail["correct_ans"] : 0).' /
		                                   <label>Total Questions: </label>'. ($user_detail["total_questions"] ? $user_detail["total_questions"] : 0).'
		                                </div>
		                            </div>
		                        </div>
		                    </li>';
		                }else{

		                    $results .= '<li class="list-group-item">
		                        <div class="row">
		                            <div class="col-md-2">
		                                <img src="'.get_avatar_url($user_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
		                            <div class="col-md-9">
		                                <div>
		                                   <label>Name: </label>'. ucfirst($get_userdata->display_name).'</br>
		                                   <label>Answered: </label>'. ($user_detail["total_questions"] ? $user_detail["correct_ans"] : 0).' /
		                                   <label>Total Questions: </label>'. ($user_detail["total_questions"] ? $user_detail["total_questions"] : 0).'
		                                </div>
		                            </div>
		                        </div>
		                    </li>';
		                }
            	}
            // }
        }

        $n = count($usr_ids);

        if(in_array('group_leader', $current_user->roles)){

        	foreach($student_details as $student_detail){
        		$current_user_id = $student_detail->ID;

		        if(!in_array($current_user_id,$usr_ids)){

		            $n = $n + 1 ;
		            $get_json = json_decode($redis->getValue($current_user_id),true);
		            $get_user_data =  $get_json['leaderboard']['mostQuesAnswered'][$course_id][$current_user_id];
		            $user_data = get_userdata($current_user_id);

		           $results .= '<li class="list-group-item">
		                <div class="row"  style="color:blue;">
		                    <div class="col-md-2">
		                        <img src="'.get_avatar_url($current_user_id).'" class="img-circle img-responsive" alt="" /></div>
		                    <div class="col-md-9">
		                        <div>
		                            <label>Name: </label>'. ucfirst($user_data->display_name).'</br>
		                            <label>Answered: </label>'. ($get_user_data['total_questions'] ? $get_user_data['correct_ans'] : 0).' /
		                             <label>Total Questions: </label>'. ($get_user_data['total_questions'] ? $get_user_data['total_questions'] : 0).'
		                        </div>
		                    </div>
		                </div>
		            </li>';
	       		}
	       	}
	    }else{
	    	if(!in_array($current_user_id,$usr_ids)){

	            $n = $n + 1 ;
	            $get_user_data =  $get_data['leaderboard']['mostQuesAnswered'][$course_id][$current_user_id];
	            $user_data = get_userdata($current_user_id);

	           	$results .= '<li class="list-group-item">
	                <div class="row"  style="color:blue;">
	                    <div class="col-md-2">
	                        <img src="'.get_avatar_url($current_user_id).'" class="img-circle img-responsive" alt="" /></div>
	                    <div class="col-md-9">
	                        <div>
	                            <label>Name: </label>'. ucfirst($user_data->display_name).'</br>
	                            <label>Answered: </label>'. ($get_user_data['total_questions'] ? $get_user_data['correct_ans'] : 0).' /
	                             <label>Total Questions: </label>'. ($get_user_data['total_questions'] ? $get_user_data['total_questions'] : 0).'
	                        </div>
	                    </div>
	                </div>
	            </li>';
	       	}
	    }

        $displays = $display_count - $n;

        if($displays !== 0){
	        for ($i=0; $i < $displays; $i++) {
		        $results .= '<li class="list-group-item">
			          <div class="row">
			              <div class="col-md-2">
			                  <img src="'.get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg').'" class="img-circle img-responsive" alt="" /></div>
			              <div class="col-md-9">
			                  <div>
			                  	  <label>Name: </label> NIL </br>
			                      <label>Answered: </label> NIL /
                             <label>Total Questions: </label> NIL
			                  </div>
			              </div>
			          </div>
			      </li>';
             }
        }
        return $results;
	}


	public function search_mostTimeSpent($current_user, $course_id, $display_count,$student_id, $student_details){

		$current_user_id = $current_user->ID;
		$ids = array();
		$redis = new RedisCache();
		if(in_array('subscriber',$current_user->roles)){
			$get_data = json_decode($redis->getValue($current_user_id),true);

		}else{

			$get_data = json_decode($redis->getValue($student_details[0]->ID),true);
		}

		$results = '<ul class="list-group bt-lb"  id="lb-time-spent">';

                    foreach($get_data["leaderboard"]["mostTimeSpent"][$course_id]["result"] as $mostTimeSpent){
                        $ids[] =  $mostTimeSpent["user_id"];
                        $user_info = get_userdata($mostTimeSpent["user_id"]);
                        if(in_array('group_leader',$current_user->roles)){

                  		     $style = in_array($mostTimeSpent["user_id"], $student_id) ? "color:blue;" : "";

		                     $results .= '<li class="list-group-item">
		                            <div class="row" style="'.$style.'">
		                                <div class="col-md-2">
		                                    <img src="'.get_avatar_url($mostTimeSpent["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
		                                <div class="col-md-9">
		                                    <div>
		                                        <label>Name : </label>'. ucfirst($user_info->display_name).'</br>
		                                        <label>Time Spent: </label>'. learndash_seconds_to_time($mostTimeSpent["time_spent"]).'
		                                    </div>
		                                </div>
		                            </div>
		                        </li>';
		                }else{

	                        if($mostTimeSpent["user_id"] == $current_user_id){
		                        $results .= '<li class="list-group-item">
		                            <div class="row" style="color:blue;">
		                                <div class="col-md-2">
		                                    <img src="'.get_avatar_url($mostTimeSpent["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
		                                <div class="col-md-9">
		                                    <div>
		                                        <label>Name : </label>'. ucfirst($user_info->display_name).'</br>
		                                        <label>Time Spent: </label>'. learndash_seconds_to_time($mostTimeSpent["time_spent"]).'
		                                    </div>
		                                </div>
		                            </div>
		                        </li>';
	                        }else{
		                        $results .= '<li class="list-group-item">
		                            <div class="row">
		                                <div class="col-md-2">
		                                    <img src="'.get_avatar_url($mostTimeSpent["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
		                                <div class="col-md-9">
		                                    <div>
		                                        <label>Name : </label>'. ucfirst($user_info->display_name).'</br>
		                                        <label>Time Spent: </label>'. learndash_seconds_to_time($mostTimeSpent["time_spent"]).'
		                                    </div>
		                                </div>
		                            </div>
		                        </li>';
	                    	}
		                }
                    }

                    $r = count($ids);

                    if(in_array('group_leader', $current_user->roles)){

	        			foreach($student_details as $student_detail){
	        				$current_user_id = $student_detail->ID;
		                    if(!in_array($current_user_id,$ids)){
		                        $r = $r + 1 ;
		                        $get_json = json_decode($redis->getValue($current_user_id),true);
		                        $get_data = $get_json['leaderboard']['mostTimeSpent'][$course_id][$current_user_id];
		                        $user_data = get_userdata($current_user_id);
		                        $results .= '<li class="list-group-item">
		                            <div class="row"  style="color:blue;">
		                                <div class="col-md-2">
		                                    <img src="'.get_avatar_url($current_user_id).'" class="img-circle img-responsive" alt="" /></div>
		                                <div class="col-md-9">
		                                    <div>
		                                        <label>Name: </label>'. ucfirst($user_data->display_name).'</br>
		                                        <label>Time Spent: </label>'. ($get_data["time_spent"] ? learndash_seconds_to_time($get_data["time_spent"]) : 0).'
		                                    </div>
		                                </div>
		                            </div>
		                        </li>';
		                    }
	                    }
	                }else{
	                	if(!in_array($current_user_id,$ids)){
	                        $r = $r + 1 ;
	                        $get_data = $get_data['leaderboard']['mostTimeSpent'][$course_id][$current_user_id];
		                    $user_data = get_userdata($current_user_id);
		                	$results .= '<li class="list-group-item">
	                            <div class="row"  style="color:blue;">
	                                <div class="col-md-2">
	                                    <img src="'.get_avatar_url($current_user_id).'" class="img-circle img-responsive" alt="" /></div>
	                                <div class="col-md-9">
	                                    <div>
	                                        <label>Name : </label>'. ucfirst($user_data->display_name).'</br>
	                                        <label>Time Spent: </label>'. ($get_data["time_spent"] ? learndash_seconds_to_time($get_data["time_spent"]) : 0).'
	                                    </div>
	                                </div>
	                            </div>
	                        </li>';
		                }
	                }

                        $display = $display_count - $r;

                        if($display !== 0){
                        for ($i=0; $i < $display; $i++) {
                        $results.=   '<li class="list-group-item">
                                  <div class="row">
                                      <div class="col-md-2">
                                          <img src="'.get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg').'" class="img-circle img-responsive" alt="" /></div>
                                      <div class="col-md-9">
                                          <div>
                                              <label>Name : </label> NIL </br>
                                                  <label>Time Spent: </label> NIL
                                          </div>
                                      </div>
                                  </div>
                              </li>';
                              }
                              }
                     $results.=   '</ul>';

        return $results;
	}


	public function search_mostTopicCompleted($current_user, $course_id, $display_count,$student_id, $student_details){

		$current_user_id = $current_user->ID;
		$get_user_ids = array();
		$redis = new RedisCache();
		if(in_array('subscriber',$current_user->roles)){
			$get_data = json_decode($redis->getValue($current_user_id),true);
		}else{
			$get_data = json_decode($redis->getValue($student_details[0]->ID),true);
		}


		$results = '<ul class="list-group bt-lb" id="lb-topic-completed">';
		foreach($get_data['leaderboard']['mostTopicCompleted'][$course_id]['result'] as $get_detail){
                $get_user_ids[] =  $get_detail["user_id"];
                $user_info = get_userdata($get_detail["user_id"]);

                if(in_array('group_leader',$current_user->roles)){

                    $style = in_array($get_detail["user_id"], $student_id) ? "color:blue;" : "";
                    $results .=  '<li class="list-group-item">
                          <div class="row" style="'.$style.'">
                              <div class="col-md-2">
                                  <img src="'. get_avatar_url($get_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                              <div class="col-md-9">
                                  <div>
                                     <label>Name: </label>'. ucfirst($user_info->display_name).'</br>
                                     <label>Topics Completed: </label>'. $get_detail["count"].'</br>
                                  </div>
                              </div>
                          </div>
                      </li>';
                }else{

                  if($get_detail["user_id"] == $current_user_id){

                     $results .=  '<li class="list-group-item">
                          <div class="row" style="color:blue;">
                              <div class="col-md-2">
                                  <img src="'. get_avatar_url($get_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                              <div class="col-md-9">
                                  <div>
                                     <label>Name: </label>'. ucfirst($user_info->display_name).'</br>
                                     <label>Topics Completed: </label>'. $get_detail["count"].'</br>
                                  </div>
                              </div>
                          </div>
                      </li>';
                 }else{

                    $results .=   '<li class="list-group-item">
                          <div class="row">
                              <div class="col-md-2">
                                  <img src="'.get_avatar_url($get_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                              <div class="col-md-9">
                                  <div>
                                     <label>Name: </label>'. ucfirst($user_info->display_name).'</br>
                                     <label>Topics Completed: </label>'. $get_detail["count"].'</br>
                                  </div>
                              </div>
                          </div>
                      </li>';
                  }
                }
              }

            $k = count($get_user_ids);

            if(in_array('group_leader', $current_user->roles)){

    			foreach($student_details as $student_detail){
    				$current_user_id = $student_detail->ID;

    				if(!in_array($current_user_id,$get_user_ids)){
    					$get_json = json_decode($redis->getValue($current_user_id),true);
    					$get_user_data = $get_json['leaderboard']['mostTopicCompleted'][$course_id][$current_user_id];
    					$user_detail = get_userdata($current_user_id);
    			 		$k = $k + 1;
	    				$results .=  '<li class="list-group-item">
	                      <div class="row"  style="color:blue;">
	                          <div class="col-md-2">
	                              <img src="'.get_avatar_url($get_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
	                          <div class="col-md-9">
	                              <div>
	                                  <label>Name: </label>'. ucfirst($user_detail->display_name) .'</br>
	                                      <label>Topics Completed: </label>'. ($get_user_data["count"]? $get_user_data["count"] : 0).'
	                              </div>
	                          </div>
	                      </div>
	                  </li>';
	                }
    			}
    		}else{
	            if(!in_array($current_user_id,$get_user_ids)){
                  $k = $k + 1;
                  $get_user_data = $get_data['leaderboard']['mostTopicCompleted'][$course_id][$current_user_id];
                  $user_detail = get_userdata($current_user_id);
                   $results .=  '<li class="list-group-item">
                      <div class="row"  style="color:blue;">
                          <div class="col-md-2">
                              <img src="'.get_avatar_url($get_detail["user_id"]).'" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label>'. ucfirst($user_detail->display_name) .'</br>
                                      <label>Topics Completed: </label>'. ($get_user_data["count"]? $get_user_data["count"] : 0).'
                              </div>
                          </div>
                      </div>
                  </li>';
				}
			}
            $show = $display_count - $k;

            if($show !== 0){
            for ($i=0; $i < $show; $i++) {
                $results .= '<li class="list-group-item">
                      <div class="row">
                          <div class="col-md-2">
                              <img src="'.get_avatar_url('https://ckmacleod.com/wp-content/uploads/2015/05/mystery-man.jpg').'" class="img-circle img-responsive" alt="" /></div>
                          <div class="col-md-9">
                              <div>
                                  <label>Name: </label> NIL </br>
                                      <label>Topics Completed: </label> NIL
                              </div>
                          </div>
                      </div>
                  </li>';
            }
            }
            $results .= '<ul>';

            return $results;

	}

	public function search_course()
	{
		$course_id = $_REQUEST['val'];
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {
			$user_id = $user->ID;
		}else{
			$user_id =  $_REQUEST["student_id"];
		}

		$redis = new RedisCache();
                /**********************************************************************************/
                //Modified by Vignesh on March 30th 2020 - Start (Checking if data exists in Redis)
                /**********************************************************************************/
                $checkkey = $redis->isExists($user_id);
                if($checkkey == 0) {
                        //If user data does not exists in Redis
                        //Getting dummy data
                        $results = $this->defaultChartValue();
                        $forceY = array('forceY' => 0);
                } else if($checkkey == 1) {
                        //If user data exists in Redis
                        $getStudentDetails =  json_decode($redis->getValue($user_id));

			if(isset($getStudentDetails->studentActivityChart->$course_id->result)) {
                                $results = $getStudentDetails->studentActivityChart->$course_id->result;
				$maxtime = is_null(($getStudentDetails->studentActivityChart->$course_id->maxTimeSpent)) ? array('forceY' => 0) : $getStudentDetails->studentActivityChart->$course_id->maxTimeSpent;
                                $forceY = is_null($results) ? $maxtime : array('forceY' => 0);
                        } else {
                                $results = $this->defaultChartValue();
                                $forceY = array('forceY' => 0);
                        }
                }
                /**********************************************************************************/
                //Modified by Vignesh on March 30th 2020 - End (Checking if data exists in Redis)
                /**********************************************************************************/
	   	$courses = learndash_user_get_enrolled_courses($user_id,true);
		$php_vars = array(
			'datum' => array( array('key' => "Cumulative Return", 'values' => $results)),
			'forceY' =>$forceY,
			'type' => $type
		);

		echo json_encode($php_vars);
		wp_die();

	}

	public function kcg_search_course()
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
   		$getStudentDetails =  json_decode($redis->getValue($user_id), true);

   	   	$node_vars = $getStudentDetails['KCG']['graph data'][$course_id];

		if(is_null($node_vars)) {
			$getDummyDetails =  json_decode($redis->getValue(1), true);
			$node_vars = $getDummyDetails['KCG']['graph data'][$course_id];
		}

		echo json_encode($node_vars); 
		wp_die();
	}

	// public function bit_student_tp_freq_filter(){

	// 	// $user = wp_get_current_user();
	// 	$user=get_userdata(75);

	// 	if(in_array('group_leader', (array) $user->roles)){

	// 		$school_group = get_user_meta($user->ID,'_school_group',true);  
	// 		$student_details = learndash_get_groups_users($school_group,true);

	// 		$results = '<select id="student_tp_freq" class="form-control selectpicker">';
  			
 //  			foreach ($student_details as $student_detail) {
 //  				$results.=	'<option value='.$student_detail->data->ID.'>'.$student_detail->data->display_name.'</option>';
 //  			}
 //  			$results .='</select>'; 
				
	// 		return $results;

	// 	}else{
	// 		var_dump("false");
	// 	}

	// }

// 	public function tp_freq_search(){

// 		$user_id = $_REQUEST['val'];

// 		echo $user_id;
// 		wp_die();

// 		global $wpdb;

// 		$activity_table = $wpdb->prefix.'bitWise_student_activity';

// 		$query = "SELECT $activity_table.`id`,$activity_table.`course_id`,$activity_table.`user_id`,$activity_table.`post_id`,$activity_table.`time_spent`,`bit_posts`.`id`,`bit_posts`.`post_title`,`bit_posts`.`post_type` FROM $activity_table INNER JOIN `bit_posts` ON `bit_posts`.`ID` = $activity_table.`post_id` WHERE `bit_posts`.`post_type` = 'sfwd-topic' AND `$activity_table`.`user_id` = $user_id  ORDER BY $activity_table.`time_spent` DESC LIMIT 0 ,5";

// 		$get_course_id = $wpdb->get_results( $query, ARRAY_A);
// // var_dump($get_course_id);exit();
// 		echo json_encode($get_course_id);
// 		wp_die();
// 	}

	// public function display_student_time_spent_and_visited(){

		
	// 	$results = "<span>
	// 			<table id='tp_time_spent' class='table table-striped table-responsive table-responsive table-bordered bt_box_shadow'>
 //   				<tr>
 //   					<th colspan='2' style='text-align:center; background-color:#2c6ec5; font-weight:bold; color:white;'>MOST TIME SPENT</th>
 //   				</tr>
 //   				<tr>
 //   					<th style='text-align:center;'><strong>Topic</strong></th>
 //   					<th style='text-align:center;'><strong>Time</strong></th>
 //   				</tr>";
	// 			foreach($get_course_id as $data){

	// 				$results .=
	//    				"<tr>
	//    					<td>".$data['post_title']."</td>
	//    					<td>". gmdate("H:i:s", $data['time_spent'])." <span style='color:grey; font-size:10px;'>(HH:MM:SS)</span> </td>
	//    				</tr>";
	// 			}
   				
	// 			$results .= "</table>
 //   			</span>";


	// 	echo $results;
	// 	wp_die();
	// }	


	public function bit_pt_child_profile(){

		global $wpdb;
		$childrens_profile = true;
		$user = wp_get_current_user();
		$usermeta_table = $wpdb->prefix.'usermeta';

		if(in_array('group_leader', (array) $user->roles)){

			$query = "SELECT * FROM $usermeta_table WHERE `user_id` =".$user->ID." AND `meta_key` LIKE '%learndash_group_leaders%'";
			$user_details = $wpdb->get_results( $query, ARRAY_A);

			$group_id = array_map(function($data){
				return $data['meta_value'];
			}, $user_details);

			// $school_group = get_user_meta($user->ID,'_school_group',true);
			// $test = learndash_get_users_group_ids( $user->ID, $bypass_transient = false ) ;
			// $student_details = learndash_get_groups_user_ids($group_id[0],true);
			if(count($group_id) >= 1)
			{
				$student_details = learndash_get_groups_users($group_id[0],true);
				// $student_details = learndash_get_groups_users($school_group,true);
				foreach ($student_details as $student_detail) {
					$quiz_attempt[$student_detail->data->ID] = $this->bit_get_user_quiz_attempts( $student_detail->data->ID );
				}
				require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-display.php';
			}
			else
			{ ?>


				<div class="alert alert-danger" style="padding:3px;">
				<strong>*** ALERT *** You are currently logged in as Parent. Courses are only visible in the Student account.<br/>
				<span style="color:#000099;">Please purchase the subscription first.</span><br/>
				<span style="color:#000099;">After purchasing a subscription, please</span> ADD <span style="color:#000099;">your child by clicking the <a href="'.site_url('manage-users').'" style="text-decoration: underline !important;color:#a94442 !important;">Students</a> tab on the menu bar. Please make sure that you do</span> NOT <span style="color:#000099;">reuse your Parent email address for your child\'s account. These must be different email addresses to complete the Student registration process.</span>
				</strong>
				</div>
<?php
				echo "<h3 style='color:#000!important'>There is no students enrolled yet!</h3>";
			}

		}
	}



	public function bit_get_user_quiz_attempts( $user_id = 0) {
		global $wpdb;

		if ( !empty( $user_id ) ) {
			$sql_str = $wpdb->prepare( "SELECT activity_id, activity_started, activity_completed FROM ". $wpdb->prefix .'learndash_user_activity WHERE user_id=%d AND activity_type=%s ORDER BY activity_id, activity_started ASC', $user_id, 'quiz' );
			return $wpdb->get_results( $sql_str );
		}
	}

	public function bit_kcg_report_function($atts)
	{

		global $wpdb;
		$user = wp_get_current_user();

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

	   	$redis = new RedisCache();
                $checkkey = $redis->isExists($user_id);
                if($checkkey == 0) {
                        //If user data does not exists in Redis
                        //Getting dummy data
                        $getDummyDetails =  json_decode($redis->getValue(1), true);
                        $kcgChart = $getDummyDetails['KCG']['graph data'][266];
                } else if($checkkey == 1) {

			$getStudentDetails =  json_decode($redis->getValue($user_id), true);
			if(array_key_exists('KCG',$getStudentDetails))
			{
	   			$kcgChart = $getStudentDetails['KCG']['graph data'][266]; //266 is course id of the intro to scracth pgm, which is very first chart to be display
	   			if (is_null($kcgChart)) {
					$getDummyDetails =  json_decode($redis->getValue(1), true);
	   				$kcgChart = $getDummyDetails['KCG']['graph data'][266];
	   			}
			}
			else
			{
				$getDummyDetails =  json_decode($redis->getValue(1), true);
                        	$kcgChart = $getDummyDetails['KCG']['graph data'][266];
			}
		}

		wp_localize_script( 'graph_report', 'node_vars', $kcgChart );
		require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-kcg-chart.php';

	}

	public function bit_performance_curve_function($atts)
	{

		global $wpdb;
		$user = wp_get_current_user();

		if(in_array('subscriber',$user->roles)) {
			$user_id = $user->ID;
		}else{
			$user_id = $_GET["id"];
		}

		$courses = learndash_user_get_enrolled_courses($user_id,true);
		foreach($courses as $course){
			$get_datas[] = get_post($course);
		}

	   	extract( shortcode_atts(
	   		array(
	      			'width'   	=> 100,
	      			'height'  	=> 200,
	      			'user_id'	=> $user_id),
	   		$atts)
	   	);

		// $user_id = 52;
	   	$redis = new RedisCache();
		$getperformance_chart = json_decode($redis->getValue($user_id), true);

	   	$performance_chart = $getperformance_chart['SPC']['graph data'][266];

		wp_localize_script( 'graph_report', 'spc_vars', $performance_chart );
		require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-data-visualisation-public-performance-curve.php';
	}

	public function spc_search_course()
	{
		$course_id = $_REQUEST['val'];
		$user = wp_get_current_user();
		if(in_array('subscriber',$user->roles)) {
			$user_id = $user->ID;
		}else{
			$user_id =  $_REQUEST["student_id"];
		}

	   	$courses = learndash_user_get_enrolled_courses($user_id, true);
	   	// $user_id = 52;
   	   	$redis = new RedisCache();
   		$getperformance_chart = json_decode($redis->getValue($user_id), true);
   	   	$spc_vars = $getperformance_chart['SPC']['graph data'][$course_id];

		echo json_encode($spc_vars); 
		wp_die();
	}

	/**
	*Added by Vignesh R on 13th March 2020 for creating custom shortcode for transcripts in report page
	*
	*/
	public function bit_student_transcript_function($attributes) {

		if ( ! is_user_logged_in() ) {
			$html = "Login to view information";
		} else {

			$logo_url         = '';
			$colors = array('primary_ui_color' => '#114982','primary_text_color' => '#114982','accent_ui_color' => '#cbd4db','accent_text_color' => '#fff');

			$request = shortcode_atts( array(
				'logo-url'    => '',
				'user-id'     => false,
				'date-format' => 'F j, Y'
			), $attributes );

			$user = wp_get_current_user();
                	/***********************************************************************************************/
                	//Modified by Vignesh on March 30th 2020 - Start (Reports opened without userid from parent Login
                	/***********************************************************************************************/
                	if(isset($_GET["id"]) && !$_GET["id"] == '') {
                        	$user_id = $_GET["id"];
                	}
			else if(in_array('college_student',$user->roles)) {
                        	$user_id = $user->ID;
                	}
			else {
                        	echo "<script>document.location = '/student-reports/';</script>";
                        	exit();
                	}
                	/**********************************************************************************************/
                	//Modified by Vignesh on March 30th 2020 - End (Reports opened without userid from parent Login)
                	/**********************************************************************************************/
			$date_format = 'F j, Y';
			//Generating Reports based on Userid
			$html = $this->bit_generate_transcript_html( $colors, $logo_url, $user_id, $date_format );
		}

		return $html;
        }


	/**
	 * Added by Vignesh R on 13th March 2020 to Generate transcript HTML Output
	 * @param string $primary_ui_color
	 * @param string $primary_text_color
	 * @param string $accent_ui_color
	 * @param string $accent_text_color
	 * @param string $logo_url
	 * @param string $user_id
	 * @param string $date_format
	 *
	 * @return string
	 */
	private function bit_generate_transcript_html( $colors, $logo_url, $user_id, $date_format ) {

		//Get userid for shortcode attributes or else set userid of current logged in user
		if ( absint( $user_id ) ) {
			$current_user = get_userdata( absint( $user_id ) );
		} else {
			$current_user = wp_get_current_user();
		}

		$primary_ui_color         = $colors['primary_ui_color'];
                $primary_text_color         = $colors['primary_text_color'];
                $accent_ui_color         = $colors['accent_ui_color'];
                $accent_text_color         = $colors['accent_text_color'];

		$current_user = apply_filters('uo_transcript_current_user', $current_user);

		// Collect some needed LearnDash labels
		$course_label  = \LearnDash_Custom_Label::get_label( 'course' );
		$courses_label = \LearnDash_Custom_Label::get_label( 'courses' );
		$lessons_label = \LearnDash_Custom_Label::get_label( 'lessons' );
		$quiz_label = \LearnDash_Custom_Label::get_label( 'quiz' );

		// Setup data to populate Header
		$transcript_heading                     = array();
		$transcript_heading['placeholder_text'] = '&nbsp;';

		// Heading
		$transcript_heading['text_print']       = __( 'Print Transcript', 'bitwise-ai-service' );
		$transcript_heading['text_before_name'] = __( 'Transcript for ', 'bitwise-ai-service' );
		$transcript_heading['first_name']       = $current_user->user_firstname;
		$transcript_heading['last_name']        = $current_user->user_lastname;
		$transcript_heading['logo_url']         = $logo_url;

		$transcript_heading['text_before_date'] = __( 'Published: ', 'bitwise-ai-service' );
		$transcript_heading['current_date']     = current_time( $date_format );

		// Sub heading bar
		$transcript_heading['text_before_total_enrolled_courses']  = __( "Total Enrolled $courses_label: ", 'bitwise-ai-service' );
		$transcript_heading['text_before_total_completed_courses'] = __( 'Total Completed: ', 'bitwise-ai-service' );
		$transcript_heading['text_before_average_quiz_score']      = __( "Avg $quiz_label Score: ", 'bitwise-ai-service' );

		// Filter
		$transcript_heading = apply_filters( 'uo_filter_transcript_heading', $transcript_heading );

		// Default amount of courses completed
		$courses_completed = 0;

		// Set up calculation for average quiz score
		$quizzes_completed                           = 0;
		$sum_course_average_percentage_quizzes_score = 0;

		// Get registered Courses
		$courses_registered = ld_get_mycourses( $current_user->ID );

		//  Build Table Data
		$table = array();

		// Create table headings
		$table_heading['text_course']           = __( $course_label, 'bitwise-ai-service' );
		$table_heading['text_percent_Complete'] = __( '% Complete', 'bitwise-ai-service' );
		$table_heading['text_completion_date']  = __( 'Completion Date', 'bitwise-ai-service' );
		$table_heading['text_lessons_competed'] = __( "$lessons_label Completed", 'bitwise-ai-service' );
		$table_heading['text_avg_quiz_score']   = __( "Avg $quiz_label Score", 'bitwise-ai-service' );
		$table_heading['text_final_quiz_score'] = __( "Final $quiz_label Score", 'bitwise-ai-service' );

		// Filter
		$table_heading = apply_filters( 'uo_filter_table_heading', $table_heading );

		$table['headings'] = array(
			$table_heading['text_course'],
			$table_heading['text_percent_Complete'],
			$table_heading['text_completion_date'],
			$table_heading['text_lessons_competed'],
			$table_heading['text_avg_quiz_score'],
			$table_heading['text_final_quiz_score']
		);

		$table['rows'] = array();

		$usermeta        = get_user_meta( $current_user->ID, '_sfwd-course_progress', true );
		$course_progress = empty( $usermeta ) ? false : $usermeta;

		$usermeta            = get_user_meta( $current_user->ID, '_sfwd-quizzes', true );
		$users_taken_quizzes = empty( $usermeta ) ? false : $usermeta;


		if ( $courses_registered ) {

			foreach ( $courses_registered as $course_id ) {

				//// Setup Data
				// list of all quizzes associated to the a course
				$course_quiz_list = learndash_get_course_quiz_list( $course_id, $current_user->ID );
				// list of all lessons in course with add field to the quizes in the lesson(User id is a LearnDash reference and does not apply)
				$lesson_list_with_quiz_list = $this->bit_get_lesson_list_with_quiz_list( $course_id, $current_user->ID );

				//Added by Vignesh on July 17 2020 - Checking the course progress to display the course detail
                                if ( isset( $course_progress[ $course_id ]['completed'] ) && isset( $course_progress[ $course_id ]['total'] ) ) {

                                        $table['rows'][ $course_id ] = array();

					// Column Title
					$table['rows'][ $course_id ][] = array( 'title', get_the_title( $course_id ) );

					// Column Course Progress
					$completed                     = $course_progress[ $course_id ]['completed'];
					$total                         = $course_progress[ $course_id ]['total'];
					$table['rows'][ $course_id ][] = array('percent',$this->bit_get_percent_complete( $completed, $total ));

					// Column Completion Date
					$completion_date = $this->bit_get_completion_date( $current_user->ID, $course_id, $date_format );
					if ( $completion_date ) {
						//Added by Vignesh on July 15 2020
						if( $completion_date != 'Not Completed')
						{
							$courses_completed ++;
						}
						$table['rows'][ $course_id ][] = array( 'date', $completion_date );
					} else {
						$table['rows'][ $course_id ][] = array( 'placeholder', $transcript_heading['placeholder_text'] );
					}

					// Column Lessons Completed
					if ( $lesson_list_with_quiz_list && isset( $course_progress[ $course_id ]['lessons'] ) ) {

						$all_lessons       = count( $lesson_list_with_quiz_list );
						$lessons_completed = count( $course_progress[ $course_id ]['lessons'] );

						$table['rows'][ $course_id ][] = array(
							'lessons-completed',
							$lessons_completed . ' of ' . $all_lessons
						);
					} else {
						$lessons_compl = 0;
						$all_lessons = count(learndash_get_course_lessons_list( $course_id ));
						$table['rows'][ $course_id ][] = array( 'lessons-completed', $lessons_compl . ' of ' . $all_lessons );
					}

					// Column Quiz Average
					$course_quiz_average = $this->bit_get_avergae_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes );

					if ( $course_quiz_average ) {
						$table['rows'][ $course_id ][] = array( 'percent', $course_quiz_average );
						$quizzes_completed ++;
						$sum_course_average_percentage_quizzes_score += $course_quiz_average;
					} else {
						$table['rows'][ $course_id ][] = array( 'percent', 0 );
					}

					//Column Final quiz
					$final_quiz_results = $this->bit_get_final_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes );
					if ( $final_quiz_results ) {
						$table['rows'][ $course_id ][] = array( 'percent', $final_quiz_results );
					} else {
						$table['rows'][ $course_id ][] = array( 'percent', 0 );
					}

				}

			}
		}


		$transcript_heading['total_enrolled_course']   = count( $courses_registered );
		$transcript_heading['total_completed_courses'] = $courses_completed;
		if ( $quizzes_completed ) {
			$transcript_heading['average_quiz_score'] = array(
				'percent',
				absint( $sum_course_average_percentage_quizzes_score / $quizzes_completed )
			);
		} else {
			$transcript_heading['average_quiz_score'] = array( 'percent', 0 );
		}

		// Filter
		$table = apply_filters( 'uo_filter_transcript_table', $table );

		//Output the HTML Data
		ob_start();
		?>
		<div id="uo-t-print">
			<div>
				<?php echo $this->bit_create_heading( $transcript_heading, $colors ); ?>
			</div>
			<div>
				<?php echo $this->bit_create_table( $table['headings'], $table['rows'] ); ?>
			</div>
		</div>
		<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
		<div style="clear: both"></div>
		<?php
		return ob_get_clean();
	}

	/**
	*Added by Vignesh R on 13th March 2020 to Generate table heading
	*/
	private function bit_create_heading( $transcript_heading, $colors ) {
		$primary_ui_color         = $colors['primary_ui_color'];
                $primary_text_color         = $colors['primary_text_color'];
                $accent_ui_color         = $colors['accent_ui_color'];
                $accent_text_color         = $colors['accent_text_color'];
		ob_start();
		?>
		<style>
		//Including css for media print
		<?php include 'css/custom-transcript.css'; ?>
		</style>
		<div class="">
			<button id="uo-t-print-button"><?php echo $transcript_heading['text_print']; ?></button>
		</div>
		<div class="uo-t-row">
			<div class="uo-t-logo">
				<img id="uo-t-logo" src="<?php echo $transcript_heading['logo_url']; ?>"/>
			</div>
			<div class="uo-t-heading">
				<h1 style="line-height: 0px" class="uo-t-heading-main">
					<span><?php echo $transcript_heading['text_before_name']; ?> </span>
					<span class="primary-text-color"><?php echo $transcript_heading['first_name']; ?> </span>
					<span class="primary-text-color"><?php echo $transcript_heading['last_name']; ?></span>
				</h1>
				<h1 style="line-height: 0px" class="uo-t-heading-main">
					<span><?php echo $transcript_heading['text_before_date']; ?> </span>
					<span class="primary-text-color"><strong><?php echo $transcript_heading['current_date']; ?> </strong></span>
				</h1>
			</div>
		</div>
		<div class="uo-t-row primary-ui-color">
			<div class="uo-t-sub-heading">
				<h3 class="uo-t-sub-heading-section">
					<span><?php echo $transcript_heading['text_before_total_enrolled_courses']; ?></span>
					<span><?php echo $transcript_heading['total_enrolled_course']; ?></span>
				</h3>
				<h3 class="uo-t-sub-heading-section-pipe">|</h3>
				<h3 class="uo-t-sub-heading-section">
					<span><?php echo $transcript_heading['text_before_total_completed_courses']; ?></span>
					<span><?php echo $transcript_heading['total_completed_courses']; ?></span>
				</h3>
				<h3 class="uo-t-sub-heading-section-pipe">|</h3>
				<h3 class="uo-t-sub-heading-section">
					<span><?php echo $transcript_heading['text_before_average_quiz_score']; ?></span>
					<span><?php echo $transcript_heading['average_quiz_score'][1]; ?>%</span>
				</h3>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
        *Added by Vignesh R on 13th March 2020 to Generate table with enrolled course status
        */
	private function bit_create_table( $headings, $rows ) {

		// table element text
		$table_elements['text_show']        = __( 'Show', 'bitwise-ai-service' );
		$table_elements['text_all']         = __( 'ALL', 'bitwise-ai-service' );
		$table_elements['text_entries']     = __( 'entries', 'bitwise-ai-service' );
		$table_elements['text_sEmptyTable'] = __( 'No data available.', 'bitwise-ai-service' );
		$table_elements['text_sInfo']       = __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'bitwise-ai-service' );
		$table_elements['text_sInfoEmpty']  = __( 'Showing 0 to 0 of 0 entries', 'bitwise-ai-service' );
		$table_elements['text_sPrevious']   = __( 'Previous', 'bitwise-ai-service' );
		$table_elements['text_sNext']       = __( 'Next', 'bitwise-ai-service' );

		// Filter
		$table_elements = apply_filters( 'uo_filter_table_elements', $table_elements );

		$headings_html = '';
		foreach ( $headings as $heading ) {
			$headings_html .= '<th>' . $heading . '</th>';
		}

		$rows_html = '';
		foreach ( $rows as $row ) {
			$rows_html .= '<tr class="ou-t">';
			foreach ( $row as $value ) {
				$suffix = '';
				switch ( $value[0] ) {
					case 'title':
						$data_order = strtolower( $value[1] );
						break;
					case 'percent':
						$data_order = absint( $value[1] );
						$suffix     = '%';
						break;
					case 'date':
						$data_order = strtotime( $value[1] );
						break;
					case 'lessons-completed':
						$data_order = explode( ' ', $value[1] );
						$data_order = absint( $data_order[0] );
						break;
					default:
						$data_order = 0;
				}
				$rows_html .= '<td data-order="' . $data_order . '">' . $value[1] . $suffix . '</td>';
			}
			$rows_html .= '</tr>';

		}

		$page_length = apply_filters('transcript_page_length', '10' );

		$add_length = '';
		if( $page_length != 10 && $page_length != 25) {
		   $add_length = '<option value="'.$page_length.'">'.$page_length.'</option>';
        	}

		ob_start();
		?>

		<table id="uo-transcript-table" class="display responsive no-wrap" cellspacing="0" width="100%"
		       data-order='[[ 0, "desc" ]]' data-page-length='<?php echo $page_length;?>'>
			<thead>
			<tr>
				<?php echo $headings_html; ?>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<?php echo $headings_html; ?>
			</tr>
			</tfoot>
			<tbody>
			<?php echo $rows_html; ?>
			</tbody>
		</table>

		<?php
		return ob_get_clean();
	}


	/*
	 * Added by Vignesh R on 13th March 2020 to Get percentage of completed modules vs total modules
	 * @param int $completed
	 * @param int $total
	 *
	 * @return string
	 */
	private function bit_get_percent_complete( $completed, $total ) {

		$percentage = $completed / $total * 100;
		$percentage = absint( $percentage );
		//Getting course completion percentage
		if ( 0 === $percentage ) {
			return 0;
		} else {
			return $percentage;
		}

	}

	/*
	 * Added by Vignesh R on 13th March 2020 to Get course completed on date with formatting
	 * @param int $user_id
	 * @param int $course_id
	 * @param string
	 *
	 * @return string
	 */
	private function bit_get_completion_date( $user_id, $course_id, $format ) {

		$timestamp = get_user_meta( $user_id, 'course_completed_' . $course_id, true );

		//If course not completed yet
		if ( '' === $timestamp ) {
			//return false;
			return "Not Completed";
		}

		$date = gmdate( $format, $timestamp );

		return $date;

	}

	/*
	 *Added by Vignesh R on 13th March 2020 to Get list of lesson with Quiz
	 */
	private function bit_get_lesson_list_with_quiz_list( $course_id, $user_id ) {

		$lesson_list = learndash_get_course_lessons_list( $course_id );
		//Getting the list of quizzes if the lesson have a quiz
		if ( '' !== $lesson_list ) {

			foreach ( $lesson_list as $key => &$lesson ) {

				$lesson['quiz_list'] = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id );

			}

		}

		return $lesson_list;
	}

	/*
	 *Added by Vignesh R on 13th March 2020 to Get overall average quiz result
	 */
	private function bit_get_avergae_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes ) {

		$highest_score = array();
		//Checking the course having quizzes
		if ( '' !== $course_quiz_list ) {

			foreach ( $course_quiz_list as $course_quiz ) {
				$quiz_id = $course_quiz['post']->ID;
				if ( $users_taken_quizzes ) {

					foreach ( $users_taken_quizzes as $taken_quiz ) {

						if ( (int) $taken_quiz['quiz'] === $quiz_id ) {

							if ( 1 === $taken_quiz['pass'] ) {
								if ( isset( $highest_score[ $quiz_id ] ) ) {
									if ( $highest_score[ $quiz_id ] <= $taken_quiz['percentage'] ) {
										$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
									}

								} else {
									$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
								}

							}
						}
					}
				}
			}
		}
		//Checking the lessons having quizzes
		if ( '' !== $lesson_list_with_quiz_list ) {

			foreach ( $lesson_list_with_quiz_list as $lesson ) {

				if ( '' !== $lesson['quiz_list'] ) {

					foreach ( $lesson['quiz_list'] as $lesson_quiz ) {

						$quiz_id = $lesson_quiz['post']->ID;

						if ( $users_taken_quizzes ) {
							foreach ( $users_taken_quizzes as $taken_quiz ) {

								if ( (int) $taken_quiz['quiz'] === $quiz_id ) {

									if ( 1 === $taken_quiz['pass'] ) {
										if ( isset( $highest_score[ $quiz_id ] ) ) {
											if ( $highest_score[ $quiz_id ] <= $taken_quiz['percentage'] ) {
												$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
											}

										} else {
											$highest_score[ $quiz_id ] = $taken_quiz['percentage'];
										}

									}
								}
							}
						}
					}
				}
			}

		}

		//Checking if highest score is greater than 0
		if ( 0 !== count( $highest_score ) ) {
			$average = absint( array_sum( $highest_score ) / count( $highest_score ) );
		} else {
			$average = false;
		}

		//Returning the average quiz score
		return $average;

	}

	/*
	 *Added by Vignesh R on 13th March 2020 to Get final quiz result
	 */
	private function bit_get_final_quiz_result( $course_quiz_list, $lesson_list_with_quiz_list, $users_taken_quizzes ) {

		$last_quiz_id          = false;
		$final_quiz_percentage = false;

		//Checking if there are any lessons
		if ( '' !== $lesson_list_with_quiz_list ) {

			foreach ( $lesson_list_with_quiz_list as $lesson ) {
				if ( '' !== $lesson['quiz_list'] ) {
					$last_quiz    = end( $lesson['quiz_list'] );
					if($last_quiz){
						$last_quiz_id = $last_quiz['post']->ID;
					}
				}
			}

		}

		//Getting last quiz id
		if ( '' !== $course_quiz_list ) {
			$last_quiz    = end( $course_quiz_list );
			if($last_quiz){
				$last_quiz_id = $last_quiz['post']->ID;
			}
		}

		//If its last quiz id then generating final quiz percentage
		if ( $last_quiz_id ) {
			if ( $users_taken_quizzes ) {
				foreach ( $users_taken_quizzes as $taken_quiz ) {
					if ( (int) $taken_quiz['quiz'] === $last_quiz_id ) {
						if ( ! $final_quiz_percentage ) {
							$final_quiz_percentage = $taken_quiz['percentage'];
							$passed                = $taken_quiz['pass'];
						} else {
							if ( $final_quiz_percentage <= $taken_quiz['percentage'] ) {
								$final_quiz_percentage = $taken_quiz['percentage'];
								$passed                = $taken_quiz['pass'];
							}
						}
					}

				}
			}


		}
		//Returning the quiz percentage if all the quizzes completed
		return $final_quiz_percentage;

	}

}
