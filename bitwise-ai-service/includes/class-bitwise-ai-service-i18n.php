<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.bitwise.academy
 * @since      1.0.0
 *
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bitwise_Ai_Service
 * @subpackage Bitwise_Ai_Service/includes
 * @author     MadhanKarthik Ramasamy <madhan.k@bitwiseacademy.com>
 */
class Bitwise_Ai_Service_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bitwise-ai-service',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
