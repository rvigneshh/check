<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.bitwise.academy
 * @since      1.0.0
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/admin
 * @author     MadhanKarthik Ramasamy <madhan.k@bitwiseacademy.com>
 */
class Bitwise_Ai_Service_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style( $this->plugin_name.'--bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bitwise-ai-service-admin.css', array(), $this->version, 'all' );


		//wp_enqueue_style( $this->plugin_name.'-dataTables-bootstrap','//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name.'-jquery1', plugin_dir_url( __FILE__ ) . 'js/bitwise-ai-service-admin.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name.'-jquery-dataTables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array(  $this->plugin_name.'-jquery1'  ), $this->version, true );

		wp_enqueue_script( $this->plugin_name.'-dataTables-bootstrap', plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap.min.js',array( $this->plugin_name.'-jquery-dataTables' ), $this->version, true );
		wp_enqueue_script($this->plugin_name.'script',plugin_dir_url(__FILE__).'js/script.js', array($this->plugin_name.'-dataTables-bootstrap'),$this->version,true);
		wp_localize_script($this->plugin_name.'script','php_vars',array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ),true);
	}

	
	public function bitWise_admin_page() 
	{
		$icon_url = esc_url( plugins_url( 'images/bitwise.png', __FILE__ ) );
  		add_menu_page( __( 'bitWise AI Service', 'bitwise-ai-service' ), __( 'bitWise AI Service', 'bitwise-ai-service'), 'manage_options', $this->plugin_name, array($this, 'plugin_menu_page'), $icon_url, 2);
	}

	/**
	 * [plugin_menu_page - Function used to get the result of the Ml services details
	 */
	public function plugin_menu_page() 
	{
		/*
		global $wpdb;

		// Get the corn_concept_mastery table
		$mastery_table = $wpdb->prefix . 'corn_concept_mastery';

		// Get the student_retention_score table
		$retention_table = $wpdb->prefix . 'student_retention_score';

		//Posts Table
		$posts_table = $wpdb->prefix. 'posts';

		//Posts Table
		$users_table = $wpdb->prefix. 'users';

		$mastery_query = "SELECT `id`,`user_id`,
							(SELECT display_name FROM $users_table WHERE $users_table.id = $mastery_table.user_id) as user,
							(SELECT post_title FROM $posts_table WHERE $posts_table.id = $mastery_table.course_id) as course,
							(SELECT post_title FROM $posts_table WHERE $posts_table.id = $mastery_table.post_id) as topic,
							`time_spent`,
							`cron_status`
							FROM $mastery_table";
		$mastery_results = $wpdb->get_results( $mastery_query, ARRAY_A );

		$retention_query = "SELECT `id`, `user_id`, `retention_score`, `retention_category`, `status`, `course_id`, `created_on`,
							(SELECT display_name FROM $users_table WHERE $users_table.id = user_id) as user,
							(SELECT post_title FROM $posts_table WHERE $posts_table.id = course_id) as course
							FROM $retention_table";
		$retention_results = $wpdb->get_results( $retention_query, ARRAY_A );*/

	    require_once plugin_dir_path( __FILE__ ) . 'partials/bitwise-ai-service-admin-display.php';
	}

	/**
	 * [getSessionTiming Function is used to get the timespent by the user for the particular course]
	 * @param  [int] $course_ID      [To get the course ID]
	 * @param  [int] $post_ID        [To get the Post ID]
	 * @param  [int] $timer_interval [Time spent by the user]
	 */
	public function getSessionTiming($course_ID, $post_ID, $timer_interval)
	{
		global $wpdb;

		$roles = wp_get_current_user()->roles;
		$id    = get_current_user_id();


		// Get the corn_concept_mastery table
		$mastery_table = $wpdb->prefix . 'corn_concept_mastery';

		//If the user is subscriber perform the following actions in it.
		if(in_array('subscriber',$roles)) {

			$select_query = $wpdb->prepare("SELECT * FROM $mastery_table WHERE `user_id`=%d AND`course_id`=%d AND `post_id`=%d", $id, $course_ID, $post_ID);

			$result = $wpdb->query($select_query);

			if($result) {
				$select_query = $wpdb->prepare("UPDATE $mastery_table SET `time_spent`=`time_spent`+%d, `created_on`=now() WHERE `user_id`=%d AND `course_id`=%d AND `post_id`=%d", $timer_interval, $id, $course_ID, $post_ID);

				$resut = $wpdb->query($select_query);
			} else {
				$select_query = $wpdb->prepare("INSERT INTO $mastery_table(`user_id`,`course_id`,`post_id`,`time_spent`, `created_on`) VALUES(%d, %d, %d, %d, now())",$id, $course_ID, $post_ID, $timer_interval);

				$resut = $wpdb->query($select_query);
			}
		}
	}

	/**
	 * [bitwise_settings_page Holds the all settings information]
	 * @ Since 1.0.0
	 */
	public function bitwise_settings_page()
	{

		//Add settings page for Redis.
		add_settings_section( 'bitwise_redis_settings_section', __( 'Redis Cache Settings', 'bitwise-ai-service' ), array($this, 'bitwise_redis_settings_section'), 'bitwise_redis_settings' );

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_redis_settings_fields_host', __( 'Host*', 'bitwise-ai-service' ), array($this, 'bitwise_redis_settings_host'), 'bitwise_redis_settings',
		 'bitwise_redis_settings_section');

		
		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_redis_settings_fields_password', __( 'Password*', 'bitwise-ai-service' ), array($this, 'bitwise_redis_settings_password'), 'bitwise_redis_settings',
		 'bitwise_redis_settings_section');

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_redis_settings_fields_port', __( 'port*', 'bitwise-ai-service' ), array($this, 'bitwise_redis_settings_port'), 'bitwise_redis_settings',
		 'bitwise_redis_settings_section');

		//----------RabbitMQ--------- 
		//Add settings page for RabbitMQ.
		add_settings_section( 'bitwise_rabbitMQ_settings_section', __( 'RabbitMQ Settings', 'bitwise-ai-service' ), array($this, 'bitwise_rabbitMQ_settings_section'), 'bitwise_rabbitMQ_settings' );

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_rabbitMQ_settings_fields_host', __( 'Host*', 'bitwise-ai-service' ), array($this, 'bitwise_rabbitMQ_settings_host'), 'bitwise_rabbitMQ_settings',
		 'bitwise_rabbitMQ_settings_section');

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_rabbitMQ_settings_fields_port', __( 'port*', 'bitwise-ai-service' ), array($this, 'bitwise_rabbitMQ_settings_port'), 'bitwise_rabbitMQ_settings',
		 'bitwise_rabbitMQ_settings_section');

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_rabbitMQ_settings_fields_username', __( 'Username*', 'bitwise-ai-service' ), array($this, 'bitwise_rabbitMQ_settings_username'), 'bitwise_rabbitMQ_settings',
		 'bitwise_rabbitMQ_settings_section');

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_rabbitMQ_settings_fields_password', __( 'Password*', 'bitwise-ai-service' ), array($this, 'bitwise_rabbitMQ_settings_password'), 'bitwise_rabbitMQ_settings',
		 'bitwise_rabbitMQ_settings_section');



		//----------BeemChat Bot--------- 
		//Add settings page for BeemChat.
		add_settings_section( 'bitwise_beem_settings_section', __( 'Beem ChatBot Settings', 'bitwise-ai-service' ), array($this, 'bitwise_beem_settings_section'),
			'bitwise_beem_settings' );

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password
		add_settings_field( 'bitwise_beem_settings_fields_host', __( 'Host*', 'bitwise-ai-service' ), array($this, 'bitwise_beem_settings_host'), 'bitwise_beem_settings',
		 'bitwise_beem_settings_section');

                add_settings_field( 'bitwise_beem_settings_fields_enable', __( 'Enable', 'bitwise-ai-service' ), array($this, 'bitwise_beem_settings_enable'), 'bitwise_beem_settings',
                 'bitwise_beem_settings_section');

		add_settings_field( 'bitwise_beem_settings_fields_Debug', __( 'Debug', 'bitwise-ai-service' ), array($this, 'bitwise_beem_settings_Debug'), 'bitwise_beem_settings',
		 'bitwise_beem_settings_section');
		//add_settings_field( 'bitwise_beem_settings_fields_printconsole', __( 'PrintinConsole*', 'bitwise-ai-service' ), array($this, 'bitwise_beem_settings_printconsole'), 'bitwise_beem_settings',
		 //'bitwise_beem_settings_section');


		//-------------Grade Prerequisties-----------
		//Add settings page for Grade Prerequisties.
		add_settings_section( 'bitwise_grade_prerequisties_settings_section', __(' Grade Prerequisties', 'bitwise-ai-service' ), array($this, 'bitwise_grade_prerequisties_settings_section'),
			'bitwise_grade_prerequisties_settings' );

		// register a new field in the "bitwise_settings_section" section, inside the "bitwise_settings" page for password

		add_settings_field( 'bitwise_grade_prerequisties_settings_fields_beginner', __( 'Beginner *', 'bitwise-ai-service' ), array($this, 'bitwise_grade_prerequisties_settings_beginner'), 'bitwise_grade_prerequisties_settings',
		 'bitwise_grade_prerequisties_settings_section');
		add_settings_field( 'bitwise_grade_prerequisties_settings_fields_intermediate', __( 'Intermediate *', 'bitwise-ai-service' ), array($this, 'bitwise_grade_prerequisties_settings_intermediate'), 'bitwise_grade_prerequisties_settings',
		 'bitwise_grade_prerequisties_settings_section');
		add_settings_field( 'bitwise_grade_prerequisties_settings_fields_advanced', __( 'Advanced *', 'bitwise-ai-service' ), array($this, 'bitwise_grade_prerequisties_settings_advanced'), 'bitwise_grade_prerequisties_settings',
		 'bitwise_grade_prerequisties_settings_section');

		//Register the new settings for Redis.
		register_setting( 'bitwise_redis_settings', 'bitwise_redis_settings_host');
		register_setting( 'bitwise_redis_settings', 'bitwise_redis_settings_password');
		register_setting( 'bitwise_redis_settings', 'bitwise_redis_settings_port');

		//Register the new settings for RabbitMQ.
		register_setting( 'bitwise_rabbitMQ_settings', 'bitwise_rabbitMQ_settings_host');
		register_setting( 'bitwise_rabbitMQ_settings', 'bitwise_rabbitMQ_settings_username');
		register_setting( 'bitwise_rabbitMQ_settings', 'bitwise_rabbitMQ_settings_password');
		register_setting( 'bitwise_rabbitMQ_settings', 'bitwise_rabbitMQ_settings_port');

		//Register the new settings for Beem ChatBot.
		register_setting( 'bitwise_beem_settings', 'bitwise_beem_settings_host');
                register_setting( 'bitwise_beem_settings', 'bitwise_beem_settings_enable');
		register_setting( 'bitwise_beem_settings', 'bitwise_beem_settings_Debug');
		//register_setting( 'bitwise_beem_settings', 'bitwise_beem_settings_printconsole');


		//Register the new settings for RabbitMQ.
		register_setting( 'bitwise_grade_prerequisties_settings', 'bitwise_grade_prerequisties_settings_beginner');
		register_setting( 'bitwise_grade_prerequisties_settings', 'bitwise_grade_prerequisties_settings_intermediate');
		register_setting( 'bitwise_grade_prerequisties_settings', 'bitwise_grade_prerequisties_settings_advanced');
	}

	// To show the Redis Host input fields in the Redis section
	public function bitwise_redis_settings_host( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$host = get_option('bitwise_redis_settings_host');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_redis_settings_host" value="<?= isset($host) ? esc_attr($host) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}

	// To show the Redis Database input fields in the Redis section
	public function bitwise_redis_settings_port( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$databases = get_option('bitwise_redis_settings_port');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_redis_settings_port" value="<?= isset($databases) ? esc_attr($databases) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}

	// To show the Redis Password input fields in the Redis section
	public function bitwise_redis_settings_password( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$password = get_option('bitwise_redis_settings_password');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_redis_settings_password" value="<?= isset($password) ? esc_attr($password) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}

	// To show the RabbitMQ section section
	public function bitwise_rabbitMQ_settings_section( $args ) {
	 ?>
	 <p><?php esc_html_e( 'Enter the RabbitMQ credentials', 'bitwise-ai-service' ); ?></p>
	 
	 <?php
	}

	// To show the Redis Password input fields in the CRON section
	public function bitwise_rabbitMQ_settings_username( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$username = get_option('bitwise_rabbitMQ_settings_username');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_rabbitMQ_settings_username" value="<?= isset($username) ? esc_attr($username) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}

	// To show the Redis Password input fields in the CRON section
	public function bitwise_rabbitMQ_settings_password( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$password = get_option('bitwise_rabbitMQ_settings_password');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_rabbitMQ_settings_password" value="<?= isset($password) ? esc_attr($password) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}

	// To show the Redis Host input fields in the CRON section
	public function bitwise_rabbitMQ_settings_host( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$host = get_option('bitwise_rabbitMQ_settings_host');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_rabbitMQ_settings_host" value="<?= isset($host) ? esc_attr($host) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}
 
	// To show the Redis Database input fields in the CRON section
	public function bitwise_rabbitMQ_settings_port( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$port = get_option('bitwise_rabbitMQ_settings_port');
	 	// output the field
	 	?>
	 	<input type="text" name="bitwise_rabbitMQ_settings_port" value="<?= isset($port) ? esc_attr($port) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<?php
	}

	// To show the RabbitMQ section section
	public function bitwise_beem_settings_section( $args ) {
	 ?>
	 <p><?php esc_html_e( 'Enter the Beem Settings credentials', 'bitwise-ai-service' ); ?></p>
	 
	 <?php
	}

	// To show the Redis Host input fields in the Redis section
	public function bitwise_beem_settings_host( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$host = get_option('bitwise_beem_settings_host');
	 	// $host=get_option('')

	 	// output the field
	 	?>
	 		 	
	 	<input id="host_name" type="text" name="bitwise_beem_settings_host" value="<?= isset($host) ? esc_attr($host) : ''; ?>" data-rule-required="true" data-msg-required="This field is required">
	 	<br><br>
	 	<button class="button1 button-primary" id="settings_button">Validate</button>
	 	<span id="host_result"> </span>

	 <!--	 <label for="Print">PrintinConsole</label><input type="checkbox" name="Print" id="Print" value="PrintinConsole"><br><br> -->
	 <!--	<label for="Debug">Debug</label><input type="checkbox" name="vehicle" id="Debug" value="Debug"> -->
	 	<?php
	}
	public function bitwise_beem_settings_Debug( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$Debug = get_option('bitwise_beem_settings_Debug');
	 	// $Debug=get_option('')

	 	// output the field
	 	?>
	 	<input type="checkbox" name="bitwise_beem_settings_Debug" value="1" <?php checked(1,get_option('bitwise_beem_settings_Debug'),true); ?> />
   

	 	<!--	<label for="Debug">Debug</label><input type="checkbox" name="vehicle" id="Debug" value="Debug">	 	-->


	 <!--	 <label for="Print">PrintinConsole</label><input type="checkbox" name="Print" id="Print" value="PrintinConsole"><br><br> -->
	 <!--	<label for="Debug">Debug</label><input type="checkbox" name="vehicle" id="Debug" value="Debug"> -->
	 	<?php
	}

/*public function bitwise_beem_settings_printconsole( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	$printconsole = get_option('bitwise_beem_settings_printconsole');
	 	// $printconsole=get_option('')

	 	// output the field
	 	?>
	 	<input type="checkbox" name="bitwise_beem_settings_printconsole" value="<?= isset($printconsole) ? esc_attr($printconsole) : '';?>" checked data-rule-required="true" data-msg-required="This field is required">
	 	<?php
      }
*/public function bitwise_grade_prerequisties_settings_section( $args ) {
	 ?>
	 <p><?php esc_html_e( 'Enter the Grade Prerequisties Settings credentials', 'bitwise-ai-service' ); ?></p>
	 
	 <?php
	}
	

public function bitwise_grade_prerequisties_settings_beginner( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	// var_dump(print_r('hello world',true));exit;
	    $beginner = get_option('bitwise_grade_prerequisties_settings_beginner');
	 	?>
	 	<select name="bitwise_grade_prerequisties_settings_beginner">
          <option value="1" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "1"); ?>>Grade1</option>
          <option value="2" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "2"); ?>>Grade2</option>
          <option value="3" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "3"); ?>>Grade3</option>
          <option value="4" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "4"); ?>>Grade4</option>
          <option value="5" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "5"); ?>>Grade5</option>
          <option value="6" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "6"); ?>>Grade6</option>
          <option value="7" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "7"); ?>>Grade7</option>
          <option value="8" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "8"); ?>>Grade8</option>
          <option value="9" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "9"); ?>>Grade9</option>
          <option value="10" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "10"); ?>>Grade10</option>
          <option value="11" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "11"); ?>>Grade11</option>
          <option value="12" <?php selected(get_option('bitwise_grade_prerequisties_settings_beginner'), "12"); ?>>Grade12</option>

        </select>
	 
	 	<?php
	
	}

public function bitwise_grade_prerequisties_settings_intermediate( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	// var_dump(print_r('hello world',true));exit;
		$intermediate = get_option('bitwise_grade_prerequisties_settings_intermediate');
	 	?>
	 	<select name="bitwise_grade_prerequisties_settings_intermediate">
          <option value="1" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "1"); ?>>Grade1</option>
          <option value="2" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "2"); ?>>Grade2</option>
          <option value="3" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "3"); ?>>Grade3</option>
          <option value="4" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "4"); ?>>Grade4</option>
          <option value="5" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "5"); ?>>Grade5</option>
          <option value="6" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "6"); ?>>Grade6</option>
          <option value="7" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "7"); ?>>Grade7</option>
          <option value="8" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "8"); ?>>Grade8</option>
          <option value="9" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "9"); ?>>Grade9</option>
          <option value="10" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "10"); ?>>Grade10</option>
          <option value="11" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "11"); ?>>Grade11</option>
          <option value="12" <?php selected(get_option('bitwise_grade_prerequisties_settings_intermediate'), "12"); ?>>Grade12</option>
	 	<?php
	 		 

	}
	public function bitwise_grade_prerequisties_settings_advanced( $args ) {
	 	// get the value of the setting we've registered with register_setting()
	 	// var_dump(print_r('hello world',true));exit;
       $advanced = get_option('bitwise_grade_prerequisties_settings_advanced');	 
	 	?>
	 	<select name="bitwise_grade_prerequisties_settings_advanced">
          <option value="1" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "1"); ?>>Grade1</option>
          <option value="2" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "2"); ?>>Grade2</option>
          <option value="3" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "3"); ?>>Grade3</option>
          <option value="4" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "4"); ?>>Grade4</option>
          <option value="5" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "5"); ?>>Grade5</option>
          <option value="6" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "6"); ?>>Grade6</option>
          <option value="7" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "7"); ?>>Grade7</option>
          <option value="8" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "8"); ?>>Grade8</option>
          <option value="9" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "9"); ?>>Grade9</option>
          <option value="10" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "10"); ?>>Grade10</option>
          <option value="11" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "11"); ?>>Grade11</option>
          <option value="12" <?php selected(get_option('bitwise_grade_prerequisties_settings_advanced'), "12"); ?>>Grade12</option>
 
	 	<?php
	 	
	}






      public function retention_score_ajax_request(){
      	global $wpdb;

      	$retention_table = $wpdb->prefix . 'student_retention_score';

      	$limit = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;
	    $offset = isset($_REQUEST['start']) ? $_REQUEST['start']  : 0;

	    $ret_cnt = "SELECT id FROM $retention_table";
	    $wpdb->get_results( $ret_cnt, ARRAY_A);
	    $ret_cnt_values = $wpdb->num_rows;

	    $ret = "SELECT * FROM $retention_table LIMIT $offset, $limit";
	    $ret_values =$wpdb->get_results( $ret, ARRAY_A);

	    $retList =array();

	    if( count($ret_values ) > 0 ) {
	    		foreach ($ret_values as $ret_value) {
	    			$retList[] = array(
	    				            'id' =>  $ret_value['id'],
	    				            'user_id' =>  $ret_value['user_id'],
	    				            'course_id' => $ret_value['course_id'] ,
	    				            'retention_score' =>  $ret_value['retention_score'],
	    				             'retention_category' =>  $ret_value['retention_category'],
	    				              'status' =>  $ret_value['status'],
	    				              'created_on' => $ret_value['created_on']
	    				        );
	    		}

	    			}

	    	$resp = array(
	    		          'recordsTotal' => $ret_cnt_values,
	    		          'recordsFiltered' => $ret_cnt_values,
	    		          'data' => $retList,
	                     );

	    	echo json_encode($resp);exit;
      }


public function sesion_report_ajax_request(){
      	global $wpdb;

      	$mastery_table = $wpdb->prefix . 'corn_concept_mastery';

      	$limit = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;
	    $offset = isset($_REQUEST['start']) ? $_REQUEST['start']  : 0;

	    $ret_cnt = "SELECT id FROM $mastery_table";
	    $wpdb->get_results( $ret_cnt, ARRAY_A);
	    $ret_cnt_values = $wpdb->num_rows;

	    $ret = "SELECT * FROM $mastery_table LIMIT $offset, $limit";
	    $ret_values =$wpdb->get_results( $ret, ARRAY_A);

	    $sesList =array();

	    if( count($ret_values ) > 0 ) {
	    		foreach ($ret_values as $ret_value) {
	    			$sesList[] = array(
	    				            'user_id' =>  $ret_value['user_id'],
	    				            'course_id' => $ret_value['course_id'] ,
	    				            'post_id' =>  $ret_value['post_id'],
	    				            'time_spent' =>  $ret_value['time_spent'],
	    				             'cron_status' =>  $ret_value['cron_status'],
	    				             'cron_updated_on' =>  $ret_value['cron_updated_on'],
	    				              'created_on' =>  $ret_value['created_on'],

	    				        );
	    		}

	    			}

	    	$resp = array(
	    		          'recordsTotal' => $rey_cnt_values,
	    		          'recordsFiltered' => $ret_cnt_values,
	    		          'data' => $sesList,
	                     );

	    	echo json_encode($resp);exit; 
      }

       public function bitwise_beem_settings_enable( $args ) {
                // get the value of the setting we've registered with register_setting()
                $Debug = get_option('bitwise_beem_settings_enable');

                // output the field
                ?>
                <input type="checkbox" name="bitwise_beem_settings_enable" value="1" <?php checked(1,get_option('bitwise_beem_settings_enable'),true); ?> />   
                <?php
        }

}


