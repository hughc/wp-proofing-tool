<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://highbrow.com.au/
 * @since      1.0.0
 *
 * @package    Proofing_Tool
 * @subpackage Proofing_Tool/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Proofing_Tool
 * @subpackage Proofing_Tool/public
 * @author     Hugh Campbell <hc@highbrow.com.au>
 */
class Proofing_Tool_Public {

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


	private $allowedRoles;

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
		$this->allowedRoles = array('administrator', 'editor', 'author');

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
		 * defined in Proofing_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Proofing_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/proofing-tool-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-openSans',  '//fonts.googleapis.com/css?family=Open+Sans:400,700', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'feedback', plugin_dir_url( __FILE__ ) . 'css/feedback.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function init() {
		add_action('wp_footer', array( $this,'footer_print_scripts'));
		wp_register_script( $this->plugin_name . '-feedback', plugin_dir_url( __FILE__ ) . 'js/feedback.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . '-html2canvas', plugin_dir_url( __FILE__ ) . 'js/html2canvas.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . 'html2canvas.svg', plugin_dir_url( __FILE__ ) . 'js/html2canvas.svg.js', array( 'jquery' ), $this->version, false );

		add_action( 'wp_ajax_send_proof', array($this, 'handle_feedback'));
		add_action( 'wp_ajax_nopriv_send_proof', array($this, 'handle_feedback'));

	}



	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Proofing_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Proofing_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/proofing-tool-public.js', array( 'jquery' ), $this->version, false );

		// After https://codex.wordpress.org/AJAX_in_Plugins
			// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( $this->plugin_name . '-feedback', 'feedbackParams',
	            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));

	}


	public function footer_print_scripts() {
		
		$user = wp_get_current_user();
		$okRoles = array_intersect($this->allowedRoles, (array) $user->roles );
		if (count($okRoles) == 0) return;

		wp_print_scripts($this->plugin_name . '-feedback');
		wp_print_scripts($this->plugin_name . '-html2canvas');
		wp_print_scripts($this->plugin_name . '-html2canvas.svg');
		?>
		<script type="text/javascript">
        jQuery.feedback({
            ajaxURL: feedbackParams.ajax_url,
            html2canvasURL: '<?php echo plugin_dir_url( __FILE__ )?>js/html2canvas.js',
            extraPostParams: {action: 'send_proof'},
            initButtonText: "Proofing Tool"
        });
        console.log('feedback URL:', feedbackParams.ajax_url);
    </script>
		<?php
		
	}

	public function handle_feedback() {
		//die;
		$user = wp_get_current_user();
		$okRoles = array_intersect($this->allowedRoles, (array) $user->roles );
		if (count($okRoles) > 0) {
			// The nonce was valid and the user has the capabilities, it is safe to continue.

			// These files need to be included as dependencies when on the front end.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			
			// Let WordPress handle the upload.
			// Remember, 'my_image_upload' is the name of our file input in our form above.
			//echo 'got to here';
			$rawData = $_POST['feedback'];
			$submittedVars = json_decode(stripslashes  ($rawData), true);
			//print_r($submittedVars);
			$imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $submittedVars['img']));

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();
			
			$target = 'proofs';
			wp_mkdir_p( $wp_upload_dir['basedir'] . '/' . $target );
			
			$time = time();

			$clean_file_name = mb_ereg_replace("([^\w\d\-_~,;\[\]\(\).])", '', $submittedVars['title']);
			// Remove any runs of periods (thanks falstro!)
			$clean_file_name = mb_ereg_replace("([\.]{2,})", '', $clean_file_name);
			print_r($clean_file_name);

			$filename = $wp_upload_dir['basedir'] . "/{$target}/{$clean_file_name}-{$time}.png";
			file_put_contents($filename, $imageData);

			
			$post_id = wp_insert_post(array (
				'post_type' => 'proof',
				'post_title' => $submittedVars['title'],
				'post_content' => $submittedVars['note'],
				'post_status' => 'publish',
			));

			// Prepare an array of post data for the attachment.
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/proofs/' . basename($filename), 
				'post_mime_type' => 'image/png',
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($filename)),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			update_post_meta($post_id, 'url',  $submittedVars['url']);
			update_post_meta($post_id, 'browser',  $submittedVars['browser']);

			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

			set_post_thumbnail( $post_id, $attach_id );

			if ( is_wp_error( $attach_id ) ) {
				// There was an error uploading the image.
			} else {
				// The image was uploaded successfully!
			}

			return 1;

		} else {

			// The security check failed, maybe show the user an error.
		}
	}



}
