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
class CPTGallery_Shortcode {

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
	 * Register new shortcode
	 * 
	 * @since	1.0.0
	 */
	public function add_shortcode( $atts ) {
		if (isset($atts['id'])) {
			$args = array(
				'post_type'		=> $this->plugin_name,
				'post_status'	=> 'publish',
				'tax_query'		=> array(
					array(
						'taxonomy'	=> $this->taxonomy_name,
						'field'		=> 'id',
						'terms'		=> $atts['id']
					)
				)
			);
			
			$loop = new WP_Query( $args );

			$pictures = array();

			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$pictures[] = array(
						'id'		=> get_the_ID(),
						'title'		=> get_the_title(),
						'caption'	=> get_the_content(),
						'thumb'		=> get_the_post_thumbnail_url(get_the_ID(), 'medium'),
						'large'		=> get_the_post_thumbnail_url(get_the_ID(), 'large')
					);
				}
			}
			
			if ($cpt_cols = get_option( "cpt_cols_{$atts['id']}" )) {
				$cptGalleryImgPerRow = (int)$cpt_cols['cpt_cols'];
			} else {
				$cptGalleryImgPerRow = $cptGalleryImagesPerRow['default'];
			}

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/cpt-widget.php';
			
		}
	}

}
