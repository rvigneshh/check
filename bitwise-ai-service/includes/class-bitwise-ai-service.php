<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.bitwise.academy
 * @since      1.0.0
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/includes
 * @author     MadhanKarthik Ramasamy <madhan.k@bitwiseacademy.com>
 */
class Bitwise_Ai_Service {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bitwise_Ai_Service_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BITWISE_AI_SERVICE_VERSION' ) ) {
			$this->version = BITWISE_AI_SERVICE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bitwise-ai-service';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bitwise_Ai_Service_Loader. Orchestrates the hooks of the plugin.
	 * - Bitwise_Ai_Service_i18n. Defines internationalization functionality.
	 * - Bitwise_Ai_Service_Admin. Defines all hooks for the admin area.
	 * - Bitwise_Ai_Service_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bitwise-ai-service-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bitwise-ai-service-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bitwise-ai-service-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bitwise-ai-service-public.php';


		 /**
          * The class responsible for defining all actions that occur in the public-facing
          * side of the site.
          */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bitwise-xapi-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bitwise-beem-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bitwise-data-visualisation-public.php';
                /**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bitwise-iqdata-visualisation-public.php';
                /**
                 * The class responsible for defining all actions that occur in the public-facing
                 * side of the site.
                 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bitwise-grade-prerequisite-public.php';

		$this->loader = new Bitwise_Ai_Service_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bitwise_Ai_Service_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bitwise_Ai_Service_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bitwise_Ai_Service_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//Admin menu 
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'bitWise_admin_page' );

		$this->loader->add_action( 'wp_ajax_retention_score_ajax_request', $plugin_admin,'retention_score_ajax_request' );
		$this->loader->add_action( 'wp_ajax_sesion_report_ajax_request', $plugin_admin,'sesion_report_ajax_request' );

		//Settings tab
		$this->loader->add_action( 'admin_init', $plugin_admin, 'bitwise_settings_page');

		//Load the Ml serivces functionality
		$this->loader->add_action( 'uo_course_timer_add_timer', $plugin_admin, 'getSessionTiming', 10, 3);//To track the session timings.

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bitwise_Ai_Service_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_login', $plugin_public, 'wp_login_process', 10, 2);
		$this->loader->add_filter( 'clear_auth_cookie', $plugin_public, 'wp_logout_process', 9);

		//$this->loader->add_action( 'wp_login', $plugin_public, 'wp_login_function', 10, 2); //Direct the students based on the retention score.
        	//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public, 'sendAssessmentScore', 9);

        	$plugin_public_xapi = new Bitwise_Xapi_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_xapi, 'enqueue_xapi_stmt_scripts' );
        	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_xapi, 'enqueue_xapilib_scripts', 1 );
        	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_xapi, 'enqueue_xapidata_scripts', 10, 2 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_xapi, 'enqueue_indexeddb_scripts', 8, 2 );
        	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_xapi, 'enqueue_xapilogout_scripts', 10, 2 );

	    	//$this->loader->add_filter('heartbeat_received', $plugin_public_xapi, 'bit_heartbeat_process', 10, 2);
		$this->loader->add_action('wp_ajax_bit_xapi_stmt', $plugin_public_xapi, 'bit_xapi_stmt');
                $this->loader->add_action('wp_ajax_nopriv_bit_xapi_stmt', $plugin_public_xapi, 'bit_xapi_stmt');
		//$this->loader->add_filter('clear_auth_cookie', $plugin_public_xapi, 'bit_xapi_logout', 9);

		$plugin_public_bot = new Bitwise_Beem_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_bot, 'enqueue_styles',23 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_bot, 'enqueue_scripts',23);
		$this->loader->add_action( 'wp_footer', $plugin_public_bot, 'insertChatWindow', 12);

		$plugin_public_visualisation = new Bitwise_Data_Visualisation_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_visualisation, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_visualisation, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public_visualisation, 'transcript_enqueue_scripts' );

		$this->loader->add_action( 'uo_course_timer_add_timer', $plugin_public_visualisation, 'trackStudentActivity', 10, 3);//To track the session timings for students.

		//$this->loader->add_filter( 'wp_login', $plugin_public_visualisation,  'count_user_login', 9, 2);
	    	$this->loader->add_filter( 'manage_users_custom_column', $plugin_public_visualisation, 'fill_stats_columns', 10, 3);
        	$this->loader->add_filter( 'wp_head', $plugin_public_visualisation, 'viewCount');

	    	//storing redis cache
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setStudentSummary');
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setMostTimeSpentByUser');
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setVisitedCountByUser');
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setStudentActivity');

		//leaderboard
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setMostQuesAnswered');
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setMostTopicCompleted');
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setMostBadgesEarned');
		//$this->loader->add_filter( 'clear_auth_cookie', $plugin_public_visualisation, 'setMostTimeSpent');

		// hook for badges
		$this->loader->add_action( 'learndash_lesson_completed', $plugin_public_visualisation, 'bit_learndash_lesson_completed',10,1);

		// hook for quizzes
		$this->loader->add_action( 'learndash_quiz_completed', $plugin_public_visualisation, 'bit_ld_quiz_completed',10, 2);

		//This is customized action and it is residing the lms-quiz-embedder/public/class-lms-quiz-embededer-public.php:264 
		$this->loader->add_action( 'bit_get_edcite_details', $plugin_public_visualisation, 'bit_get_edcite_quiz',10, 1);

		//Shortcodes
		$this->loader->add_shortcode( 'myreports', $plugin_public_visualisation, 'student_reports' );
		$this->loader->add_shortcode( 'mycourses', $plugin_public_visualisation, 'student_courses' );
		$this->loader->add_shortcode( 'bit_student_activity', $plugin_public_visualisation, 'student_activity' );
		$this->loader->add_shortcode( 'bit_student_act_time_spent', $plugin_public_visualisation, 'bit_tp_time_spent' );
		$this->loader->add_shortcode( 'bit_student_act_visited', $plugin_public_visualisation, 'bit_tp_visited' );
		$this->loader->add_shortcode( 'bit_student_act_individual_user', $plugin_public_visualisation, 'bit_individual_user' );
		$this->loader->add_shortcode( 'bit_student_act_user_visited', $plugin_public_visualisation, 'bit_user_visited' );
		$this->loader->add_shortcode( 'bit_student_transcript', $plugin_public_visualisation, 'bit_student_transcript_function' );

		// child profile
		$this->loader->add_shortcode( 'bit_pt_child_profile', $plugin_public_visualisation, 'bit_pt_child_profile' );

		// shortcodes for leader board
		$this->loader->add_shortcode( 'bit_showLeaderboard', $plugin_public_visualisation, 'showLeaderboard' );

		//For KCG reports
		$this->loader->add_shortcode( 'bit_kcg_report', $plugin_public_visualisation, 'bit_kcg_report_function' );
		$this->loader->add_shortcode( 'bit_performance_curve', $plugin_public_visualisation, 'bit_performance_curve_function' );

		//Ajax
		$this->loader->add_action("wp_ajax_search_course", $plugin_public_visualisation, "search_course");
		$this->loader->add_action("wp_ajax_search_leaderboard_course", $plugin_public_visualisation, "search_leaderboard_course");
		$this->loader->add_action("wp_ajax_kcg_search_course", $plugin_public_visualisation, "kcg_search_course");

		$plugin_public_prerequities = new Bitwise_Grade_Prerequisite_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( "learndash_prerequities_bypass", $plugin_public_prerequities, "grade_by_pass",10,4);

		//Added By Vignesh R on Aug 07 2020
		$plugin_public_iqvisualisation = new Bitwise_Iq_Data_Visualisation_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( 'bit_iq_kcg_report', $plugin_public_iqvisualisation, 'bit_iq_kcg_report_function' );

		$this->loader->add_action("wp_ajax_iq_kcg_search_course", $plugin_public_iqvisualisation, "iq_kcg_search_course");
		$this->loader->add_action("wp_ajax_nopriv_iq_kcg_search_course", $plugin_public_iqvisualisation, "iq_kcg_search_course");

                $this->loader->add_action("wp_ajax_bit_iq_mail", $plugin_public_iqvisualisation, "bit_iq_mail");
                $this->loader->add_action("wp_ajax_nopriv_bit_iq_mail", $plugin_public_iqvisualisation, "bit_iq_mail");

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bitwise_Ai_Service_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
