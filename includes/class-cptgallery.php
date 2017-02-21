<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    cptgallery
 * @subpackage cptgallery/includes
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
 * @package    cptgallery
 * @subpackage cptgallery/includes
 * @author     Andrew Shultz <shultz.andrey@gmail.com>
 */
class CPTGallery {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      cptgallery_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $cptgallery    The string used to uniquely identify this plugin.
	 */
	protected $cptgallery;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * The unique identifier of this plugin taxonomy.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $cptgallery_taxonomy    The string used to uniquely identify this plugin taxonomy.
	 */
	 
	protected $cptgallery_taxonomy;
	
	/**
	 * Settings for gallery widget.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string		$cptgallery_settings		Settings for gallery widget.
	 */
	 
	protected $cptgallery_settings;

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

		$this->cptgallery = 'cptgallery';
		$this->cptgallery_taxonomy = 'cptgallery_taxonomy';
		$this->textdomain = 'cpt-gallery';
		$this->version = '1.0.0';
		$this->cptgallery_settings = array(
			'default' => 4,
			'variants' => array(
				'2' => __( '2 images', $this->textdomain ),
				'3' => __( '3 images', $this->textdomain ),
				'4' => __( '4 images', $this->textdomain ),
				'5' => __( '5 images', $this->textdomain ),
				'6' => __( '6 images', $this->textdomain )
			)
		);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_custom_post_type();
		$this->register_shortcode('cptgallery');

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - cptgallery_Loader. Orchestrates the hooks of the plugin.
	 * - cptgallery_i18n. Defines internationalization functionality.
	 * - cptgallery_Admin. Defines all hooks for the admin area.
	 * - cptgallery_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cptgallery-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cptgallery-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cptgallery-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cptgallery-public.php';
		
		/**
		 * The class responsible for creation of custom post type and taxonomy.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cptgallery-custom-post.php';
		
		/**
		 * The class responsible for creation of shortcodes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cptgallery-shortcode.php';

		$this->loader = new CPTGallery_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the cptgallery_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new CPTGallery_i18n($this->get_textdomain());

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

		$plugin_admin = new CPTGallery_Admin( $this->get_cptgallery(), $this->cptgallery_taxonomy, $this->get_textdomain(), $this->cptgallery_settings, $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( "manage_edit-{$this->cptgallery_taxonomy}_columns", $plugin_admin, 'add_shortcode_column_to_admin_grid', 10, 2 );
		$this->loader->add_action( "manage_{$this->cptgallery_taxonomy}_custom_column", $plugin_admin, 'add_shortcode_value_to_admin_grid', 10, 3 );
		$this->loader->add_action( "{$this->cptgallery_taxonomy}_add_form_fields", $plugin_admin, 'add_shortcode_field_to_add_form' );
		$this->loader->add_action( "{$this->cptgallery_taxonomy}_edit_form_fields", $plugin_admin, 'add_shortcode_field_to_edit_form' );
		$this->loader->add_action( "edited_{$this->cptgallery_taxonomy}", $plugin_admin, 'shortcode_save_controller' );  
		$this->loader->add_action( "create_{$this->cptgallery_taxonomy}", $plugin_admin, 'shortcode_save_controller' );

	}
	
	/**
	 * Register custom post type and taxonomy.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_custom_post_type() {

		$custom_post = new CPTGallery_Custom_Post( $this->get_cptgallery(), $this->cptgallery_taxonomy, $this->get_textdomain(), $this->get_version() );

		$this->loader->add_action( 'init', $custom_post, 'register_custom_post_type' );
		$this->loader->add_action( 'init', $custom_post, 'register_custom_post_type_taxonomy' );

	}
	
	/**
	 * Register shortcode.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_shortcode($shortcode) {

		$shortcode_class = new CPTGallery_Shortcode( $this->get_cptgallery(), $this->cptgallery_taxonomy, $this->get_textdomain() );

		add_shortcode( $shortcode, array( $shortcode_class, 'add_shortcode' ) );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new CPTGallery_Public( $this->get_cptgallery(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	public function get_cptgallery() {
		return $this->cptgallery;
	}

	/**
	 * The name of the plugin taxonomy used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin taxonomy.
	 */
	public function get_cptgallery_taxonomy() {
		return $this->cptgallery_taxonomy;
	}

	/**
	 * The plugin textdomain used to translation
	 *
	 * @since     1.0.0
	 * @return    string    The plugin textdomain.
	 */
	public function get_textdomain() {
		return $this->textdomain;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    cptgallery_Loader    Orchestrates the hooks of the plugin.
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
