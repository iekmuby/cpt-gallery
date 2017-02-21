<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class CPTGallery_Admin {

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
	 * Plugin textdomain.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $textdomain    Plugin textdomain.
	 */
	private $textdomain;

	/**
	 * Settings for gallery widget.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string		$cptgallery_settings		Settings for gallery widget.
	 */
	 
	protected $settings;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $taxonomy_name, $textdomain, $settings, $version ) {

		$this->plugin_name = $plugin_name;
		$this->taxonomy_name = $taxonomy_name;
		$this->textdomain = $textdomain;
		$this->settings = $settings;
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
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

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
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add shortcode column to admin grid
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function add_shortcode_column_to_admin_grid( $column_name, $post_ID ) {
		$new_columns = array(
			'cb'		=> '<input type="checkbox" />',
			'name'		=> __( 'Name' ),
			'slug'		=> __( 'Slug' ),
			'posts'		=> __( 'Posts' ),
			'shortcode'	=> __( 'Shortcode', $this->textdomain )
		);

		return $new_columns;
	}
	
	/**
	 * Add shortcode value to admin grid
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function add_shortcode_value_to_admin_grid( $value, $name, $id ) {
		return 'shortcode' === $name ? '[cptgallery id=' . $id . ']' : $value;
	}
	
	//Add selector for number of images per row
	public function add_shortcode_field_to_add_form() {
		?>
		<div class="form-field">
			<label for="term_meta[cpt_cols]"><?php _e( 'Number of images per row', $this->textdomain ); ?></label>
			<select name="term_meta[cpt_cols]" id="term_meta[cpt_cols]">
				<?php foreach ($this->settings['variants'] as $k => $v): ?>
					<?php $selected = ($k == $this->settings['default']) ? ' selected' : ''; ?>
					<?php printf('<option value="%s"%s>%s</option>', $k, $selected, __( $v, $this->textdomain )); ?>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php _e( 'Choose a number of images in row', $this->textdomain ); ?></p>
		</div>
	<?php
	}

	public function add_shortcode_field_to_edit_form($term) {
		$t_id = $term->term_id;
	 
		$term_meta = get_option( "cpt_cols_{$t_id}" ); ?>
		<tr class="form-field">
			<th scope="row" valign="top">	
				<label for="term_meta[cpt_cols]"><?php _e( 'Number of images in row', $this->textdomain ); ?></label>
			</th>
			<td>
				<select name="term_meta[cpt_cols]" id="term_meta[cpt_cols]">
					<?php foreach ($this->settings['variants'] as $k => $v): ?>
						<?php $selected = ($k == $term_meta['cpt_cols']) ? ' selected' : ''; ?>
						<?php printf('<option value="%s"%s>%s</option>', $k, $selected, __( $v, $this->textdomain )); ?>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php _e( 'Enter a value for this field', $this->textdomain ); ?></p>
			</td>
		</tr>
	<?php
	}

	public function shortcode_save_controller( $term_id ) {
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "cpt_cols_{$t_id}" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}

			update_option( "cpt_cols_{$t_id}", $term_meta );
		}
	}

}
