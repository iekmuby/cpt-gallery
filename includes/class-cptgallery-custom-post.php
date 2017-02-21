<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    cptgallery
 * @subpackage cptgallery/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    cptgallery
 * @subpackage cptgallery/includes
 * @author     Andrey Shultz <shultz.andrey@gmail.com>
 */
class CPTGallery_Custom_Post {

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Custom post type slug.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $post_name    Custom post type slug.
	 */
	protected $plugin_name;

	/**
	 * Custom post taxonomy name.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $taxonomy_name    Custom post taxonomy name.
	 */
	protected $taxonomy_name;

	/**
	 * Custom post text domain.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $textdomain    Custom post text domain.
	 */
	protected $textdomain;

	/**
	 * Initialize variables.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $taxonomy_name, $textdomain ) {

		$this->plugin_name = $plugin_name;
		$this->taxonomy_name = $taxonomy_name;
		$this->textdomain = $textdomain;

	}

	/**
	 * Register custom post type for gallery item
	 * 
	 * @since	1.0.0
	 */
	public function register_custom_post_type() {
		register_post_type( $this->plugin_name,
			array(
				'labels'		=> array(
					'name'					=> __( 'CPT gallery', $this->textdomain ),
					'add_new'				=> __( 'Add New Picture', $this->textdomain ),
					'add_new_item'			=> __( 'Add New Picture', $this->textdomain ),
					'edit'					=> __( 'Edit Picture', $this->textdomain ),
					'edit_item'				=> __( 'Edit Picture', $this->textdomain ),
					'new_item'				=> __( 'New Picture', $this->textdomain ),
					'view'					=> __( 'View Picture', $this->textdomain ),
					'view_item'				=> __( 'View Picture', $this->textdomain ),
					'search_items'			=> __( 'Search Pictures', $this->textdomain ),
					'not_found'				=> __( 'No Pictures found', $this->textdomain ),
					'not_found_in_trash'	=> __( 'No Pictures found in Trash', $this->textdomain ),
					'parent'				=> __( 'Parent Picture', $this->textdomain ),
				),
				'public'		=> true,
				'menu_position'	=> 29,
				'supports'		=>
					array(
						'title',
						'editor',
						'thumbnail'					
					),
				'taxonomies'	=> array(
					$this->taxonomy_name,
				),
				'menu_icon'		=> 'dashicons-format-gallery',
				'has_archive'	=> false
			)
		);
	}
	
	/**
	 * Register taxonomy for custom post type
	 * 
	 * @since	1.0.0
	 */
	public function register_custom_post_type_taxonomy() {
		if (!taxonomy_exists($this->taxonomy_name)) {
			register_taxonomy(
				$this->taxonomy_name,
				array($this->plugin_name),
				array (
					'hierarchical'			=> true,
					'label'					=> __( 'Galleries', $this->textdomain ),
					'singular_label'		=> __( 'Galleries', $this->textdomain ),
					'query_var'				=> $this->taxonomy_name,
					'show_in_quick_edit'	=> true,
					'show_admin_column'		=> true,
					'capabilities'			=> array (
						'manage_terms',
						'edit_terms',
						'delete_terms',
						'assign_terms'
					)
				)
			);
		}
	}

}
