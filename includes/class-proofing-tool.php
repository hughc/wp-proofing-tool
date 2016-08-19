<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://highbrow.com.au/
 * @since      1.0.0
 *
 * @package    Proofing_Tool
 * @subpackage Proofing_Tool/includes
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
 * @package    Proofing_Tool
 * @subpackage Proofing_Tool/includes
 * @author     Hugh Campbell <hc@highbrow.com.au>
 */
class Proofing_Tool {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Proofing_Tool_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'proofing-tool';
		$this->version = '1.0.0';

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
	 * - Proofing_Tool_Loader. Orchestrates the hooks of the plugin.
	 * - Proofing_Tool_i18n. Defines internationalization functionality.
	 * - Proofing_Tool_Admin. Defines all hooks for the admin area.
	 * - Proofing_Tool_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-proofing-tool-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-proofing-tool-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-proofing-tool-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-proofing-tool-public.php';

		$this->loader = new Proofing_Tool_Loader();

		$this->loader->add_action( 'init', $this, 'do_init');
		$this->loader->add_filter('piklist_post_types', $this, 'add_my_post_types');
		$this->loader->add_filter('template_include', $this, 'filter_page_template');
		$this->loader->add_filter('next_post_link', $this, 'post_link_attributes');
		$this->loader->add_filter('previous_post_link', $this, 'post_link_attributes');
	}


		function post_link_attributes($output) {
		    $code = 'class="nav-button"';
		    return str_replace('<a href=', '<a '.$code.' href=', $output);
		}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Proofing_Tool_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Proofing_Tool_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	public function do_init() {
		  if(is_admin())
		  {
		   include_once('class-piklist-checker.php');
		 
		   if (!piklist_checker::check(__FILE__))
		   {
		     return;
		   }
		  }

		// piklist custom post types
	}

	public function add_my_post_types($post_types)
	{

	  $labels = array(
	  'name' => _x( 'Proofing Notes', 'post type general name', 'your-plugin-textdomain' ),
	  'singular_name' => _x( 'Proofing Note', 'post type singular name', 'your-plugin-textdomain' ),
	  'menu_name' => _x( 'Proofing Notes', 'admin menu', 'your-plugin-textdomain' ),
	  'name_admin_bar' => _x( 'Proofing Note', 'add new on admin bar', 'your-plugin-textdomain' ),
	  'add_new' => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
	  'add_new_item' => __( 'Add New Proofing Note', 'your-plugin-textdomain' ),
	  'new_item' => __( 'New Proofing Note', 'your-plugin-textdomain' ),
	  'edit_item' => __( 'Edit Proofing Note', 'your-plugin-textdomain' ),
	  'view_item' => __( 'View Proofing Note', 'your-plugin-textdomain' ),
	  'all_items' => __( 'All Proofing Notes', 'your-plugin-textdomain' ),
	  'search_items' => __( 'Search Proofing Note', 'your-plugin-textdomain' ),
	  'parent_item_colon' => __( 'Parent Proofing Note:', 'your-plugin-textdomain' ),
	  'not_found' => __( 'No Proofing Notes found.', 'your-plugin-textdomain' ),
	  'not_found_in_trash' => __( 'No Proofing Notes found in Trash.', 'your-plugin-textdomain' )
	);

	  $post_types['proof'] = array(
	    'labels' => $labels
	    ,'public' => true
	    ,'rewrite' => array(
	      'slug' => 'proofing-notes'
	    )
	    , 'description' => 'things to fix'
	    ,'supports' => array(
	      'author'
	      ,'title'
	      ,'editor'
	      ,'excerpt'
	      ,'thumbnail'
	      ,'revisions'
	    )
	    ,'has_archive' => true
	    ,'hide_meta_box' => array(
	      'slug'
	      ,'author'
	      ,'revisions'
	      ,'commentstatus'
	    )
	  );

	  return $post_types;
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Proofing_Tool_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Proofing_Tool_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'init' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}


	public function filter_page_template( $page_template )
		{

		    if ( ( 'proof' == get_post_type() ) ) {
		        $page_template = dirname( __FILE__ ) . '/../proof-page-template.php';
		    }
		    return $page_template;
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
	 * @return    Proofing_Tool_Loader    Orchestrates the hooks of the plugin.
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
