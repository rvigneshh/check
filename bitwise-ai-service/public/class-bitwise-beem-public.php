<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       support@bitwiseacademy.com
 * @since      1.0.0
 *
 * @package    Bitwise_Beem
 * @subpackage Bitwise_Beem/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bitwise_Beem
 * @subpackage Bitwise_Beem/public
 * @author     Bitwise <support@bitwiseacademy.com>
 */
class Bitwise_Beem_Public {

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
		 * defined in Bitwise_Beem_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bitwise_Beem_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( is_user_logged_in() ) {

			$user = wp_get_current_user();
                        $chatbot_enable = get_option( 'bitwise_beem_settings_enable' );

			if ( in_array( 'subscriber', (array) $user->roles ) && $chatbot_enable == 1) { 

				wp_enqueue_style( $this->plugin_name.'-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), $this->version, 'all');

				wp_enqueue_style( $this->plugin_name.'-material-icons', 	'https://fonts.googleapis.com/icon?family=Material+Icons', array(), $this->version, 'all');
				
				wp_enqueue_style( $this->plugin_name.'-bot-css', plugin_dir_url( __FILE__ ) . 'css/bitwise-beem-public.css', array($this->plugin_name.'-material-icons'), $this->version, 'all' );
			}
		}
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
		 * defined in Bitwise_Beem_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bitwise_Beem_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		

		if ( is_user_logged_in() ) {

			$user = wp_get_current_user();
                        $chatbot_enable = get_option( 'bitwise_beem_settings_enable' );

			if ( in_array( 'subscriber', (array) $user->roles ) && $chatbot_enable == 1) { 

				wp_enqueue_script( $this->plugin_name.'-config', plugin_dir_url( __FILE__ ) . 'js/bot-config.js', array( 'jquery' ), $this->version, false );

				$datatoBePassed = array(
										'debug ' => FALSE,
										//'printinconsole ' => FALSE,
										'host' => get_option('bitwise_beem_settings_host'),
										'userName' => $user->display_name,
										'userUniqueID' => $user->user_login,
										'userImage' => get_avatar_url($user->ID),
										'userId' => $user->ID
										);
				
				wp_localize_script( $this->plugin_name.'-config', 'bot_vars', $datatoBePassed );
				
				wp_enqueue_script( $this->plugin_name.'-bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( $this->plugin_name.'-config' ), $this->version, true );

				wp_enqueue_script( $this->plugin_name.'-script', plugin_dir_url( __FILE__ ) . 'js/bot-script.js', array( $this->plugin_name.'-bootstrap' ), $this->version, true );

				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/beem.js', array( $this->plugin_name.'-script' ), $this->version, true );
			}
		}
	}

	/**
	 * [insertChatWindow - Has the chatArea where user can do chatting with bot.]
	 * 
	 * @since  1.0.2
	 */
	public function insertChatWindow() {
		
		if ( is_user_logged_in() ) {

			$user = wp_get_current_user();
                        $chatbot_enable = get_option( 'bitwise_beem_settings_enable' );

			if ( in_array( 'subscriber', (array) $user->roles ) && $chatbot_enable == 1) { ?>
				<script>
				  function popup(mylink, windowname) {
				    console.log("popup called!");
				    if (!window.focus) return true;
				    var href;
				    // var left = (screen.width / 2) - (350 / 2);
				    // var top = (screen.height / 2) - (600 / 2); 
				    var left = 0;
				    var top = 0;

				    if (typeof (mylink) == 'string') href = mylink;
				    else href = mylink.href;
				    window.open(href, windowname,
				      'width=350,height=600,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no,directories=no,resizable=no,top=' +
				      top + ',left=' + left);
				    return false;
				  }
				</script>
				<div id="body" style="background-color:black">
        <div id="chat-circle" class="botIcon zindex">
            <div id="chat-overlay"></div>
        </div>
        <div class="dimen chat-box zindex">
            <div class="chat-box-header dimenHeader">
                <span class="chat-title">
                    <figure class="avatar-left">
                        <img id="botImage">
                    </figure>
                    <span class="font-family-bot" id="chat-title-name"></span>
                    <span class="chat-box-toggle">
                        <i class="material-icons">remove</i>
                    </span>
                    <span class="chat-speech-toggle"></span>
                </span>
            </div>
            <div class="chat-box-body">
                <div class="chat-box-overlay"></div>
                <div class="chat-logs dimenLogs" id="chat-logs">
                    <div id="think" class="">
                        <div class="loading">
                            <span id="botName"></span> is thinking</div>
                    </div>
                </div>

                <div class="chat-input">
                    <form>
                        <input type="text" id="chat-input" autocomplete="off" placeholder="Type your question...">
                        <button type="submit" class="chat-submit" id="chat-submit"></button>
                    </form>
                </div>
            </div>
            <div id="sound"></div>
        </div>
    </div>
	    	<?php 
	    	}
	    }
	}

}
