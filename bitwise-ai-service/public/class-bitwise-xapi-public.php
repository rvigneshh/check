<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       support@bitwiseacademy.com
 * @since      1.0.0
 *
 * @package    Bitwise_Xapi
 * @subpackage Bitwise_Xapi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bitwise_Xapi
 * @subpackage Bitwise_Xapi/public
 * @author     Bitwise <support@bitwiseacademy.com>
 */
class Bitwise_Xapi_Public {

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
	
	public function enqueue_xapidata_scripts() {
		
		if ( is_user_logged_in() ) {

                	wp_enqueue_script('xapidata-script', plugin_dir_url( __FILE__ ) . 'js/xapidata.js');
			wp_localize_script( 
    				'xapidata-script', 
				'xapiAjax',
    				array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) 
  			);
		}
        }
	
	public function enqueue_xapilogout_scripts() {
                wp_enqueue_script('xapilogout-script', plugin_dir_url( __FILE__ ) . 'js/xapilogout.js');
        }

	public function enqueue_xapilib_scripts() {
		if ( get_post_type( get_the_ID() ) == 'sfwd-topic' ) {
                	wp_enqueue_script('xapilib-script', plugin_dir_url( __FILE__ ) . 'js/bitwisexapilib.js');
		}
        }

	public function enqueue_indexeddb_scripts()
	{
		wp_enqueue_script('browser-detector', plugin_dir_url( __FILE__ ) . 'js/lib/browserdetect.js');
		wp_enqueue_script('indexeddb-script', plugin_dir_url( __FILE__ ) . 'js/lib/node_modules/localforage/dist/localforage.js');
		wp_add_inline_script('indexeddb-script', 'localforage.setDriver([localforage.INDEXEDDB,localforage.WEBSQL,localforage.LOCALSTORAGE])');
		wp_enqueue_script('indexeddb-script-items', plugin_dir_url( __FILE__ ) . 'js/lib/node_modules/localforage-getitems/dist/localforage-getitems.js');	
	}

	public function bit_xapi_stmt()
	{
		$host = get_option('bitwise_rabbitMQ_settings_host');
                $port = get_option('bitwise_rabbitMQ_settings_port');
                $username = get_option('bitwise_rabbitMQ_settings_username');
                $password = get_option('bitwise_rabbitMQ_settings_password');

                $connection = new RabbitMQ( $host, $port, $username, $password );
                $channel = $connection->Channel();
                $channel = $connection->QueueDeclration('dev_nss_xapi');

		/*Modified By Vignesh R for LRS*/
                $msg_data = json_decode(stripslashes($_POST['stmt']),true);
                $msg = json_encode($msg_data);
                $connection->BasicPublish($msg, '', 'dev_nss_xapi');
                $response = "success";
                echo json_encode($response);
                wp_die();
	}


}
