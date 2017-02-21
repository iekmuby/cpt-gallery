<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    cptgallery
 * @subpackage cptgallery/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    cptgallery
 * @subpackage cptgallery/includes
 * @author     Andrey Shultz <shultz.andrey@gmail.com>
 */
class CPTGallery_i18n {

	/**
	 * Plugin textdomain.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $text_domain    Plugin textdomain.
	 */
	private $text_domain;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param	string	$textdomain	Plugin textdomain.
	 */
	public function __construct($textdomain) {
		$this->text_domain = $textdomain;
	}
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->text_domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
