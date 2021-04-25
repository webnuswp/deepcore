<?php
/**
 * Deep Theme.
 *
 * The Demo Importer
 *
 * @since   4.2.8
 * @author  Webnus
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Deep_Demo_Importer {

	/**
	 * Instance of this class.
	 *
	 * @since   4.2.8
	 * @access  public
	 * @var     Deep_Demo_Importer
	 */
	public static $instance;

	private $demo;

	private $path;

	private $webnus_dir;

	private $demo_dir;

	private $pages;

	private $posts;

	private $theme_opts;

	private $widgets;

	private $attachments;

	private $contact_forms;

	private $header;
	
	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   4.2.8
	 * @return  object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Define the core functionality of the theme.
	 *
	 * Load the dependencies.
	 *
	 * @since   4.2.8
	 */
	public function __construct() {		                
		add_action( 'admin_enqueue_scripts', [$this, 'scripts'] );
		add_action( 'wp_ajax_importing_demo_content', [$this, 'importing_demo_content'] );
		add_action( 'wp_ajax_nopriv_importing_demo_content', [$this, 'importing_demo_content'] );	
		add_action( 'wp_ajax_reset_progress', [$this, 'reset_progress'] );
		add_action( 'wp_ajax_nopriv_reset_progress', [$this, 'reset_progress'] );
		add_action( 'init', [$this, 'deep_events_demo'] );
		add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
		add_action( 'pt-ocdi/time_for_one_ajax_call', [$this, 'single_ajax_call_time'] );
	}

	/**
	 * Register Scripts.
	 *
	 * Load the dependencies.
	 *
	 * @since   4.2.8
	 */
	public function scripts(){
		wp_enqueue_script( 'one-demo-importer', DEEP_ASSETS_URL . 'js/backend/one-importer.js', array( 'jquery' ) );
		
		wp_localize_script(
		'one-demo-importer',
		'OneImporter', 
			array(
				'ajax_url'   =>  admin_url( 'admin-ajax.php' ), 
				'nonce'      => wp_create_nonce( 'one_demo_importer' ),
			)
		);

		// Progress Data
		$req_data = wp_upload_dir()['baseurl'] . '/webnus/demo-data/result.txt';				
		wp_localize_script(	
			'one-demo-importer',				
			'ProgressData', 
			array(
				'ajax_url' => $req_data, 						
			)
		);
	}

	/**
	 * Importing Content.
	 *
	 * @since   4.2.8
	 */
	public function importing_demo_content() {
		
		$nonce = $_POST['nonce'];		
		if ( ! wp_verify_nonce( $nonce, 'one_demo_importer' ) )
			die ( 'Wrong nonce!');

		if( ! empty( $_POST )){

			if( isset( $_POST['demo'] ) ) {
                $this->demo = sanitize_text_field( $_POST['demo'] );
			}
								
			if ( isset( $_POST['pagebuilder'] ) ) {
                $this->pagebuilder = sanitize_text_field( $_POST['pagebuilder'] );
            }
								
			if ( isset( $_POST['pages'] ) && $_POST['pages'] == 'yes') {
                $this->pages = 'pages';
            }
								
			if ( isset( $_POST['posts'] ) && $_POST['posts'] == 'yes' ) {
                $this->posts = 'posts';
            }
								
			if ( isset( $_POST['contact_forms'] ) && $_POST['contact_forms'] == 'yes') {
                $this->contact_forms = 'contact_forms';
            }										
			
			if ( isset( $_POST['import_attachments'] ) && $_POST['import_attachments'] == 'yes') {
                $this->attachments = 'attachments';
            }
								
			if ( isset( $_POST['import_widgets'] ) && $_POST['import_widgets'] == 'yes') {
                $this->widgets = 'widgets';
            }
								
			if ( isset( $_POST['import_theme_opts'] ) && $_POST['import_theme_opts'] == 'yes') {
                $this->theme_opts = 'theme_opts';
			}	
			
			if ( isset( $_POST['import_header'] ) && $_POST['import_header'] == 'yes' ) {
                $this->header = 'header';
            }
            	   
		}
		
		$this->demo_setup();	
		$this->import_content();		
		wp_die();
		
	}
	
	/**
	 * Demo Setup.
	 *
	 * @since   4.2.8
	 */
	public function demo_setup() {

		$this->webnus_dir = wp_upload_dir()['basedir'] . '/webnus/';
		$this->demo_dir = $this->webnus_dir . 'demo-data/';
		$this->path = $this->demo_dir . $this->demo;

		if ( wp_mkdir_p( $this->demo_dir. $this->demo ) ) {
			wp_mkdir_p( $this->demo_dir . $this->demo );
		}

		// Upload files in demo folder
		$value = '';
		
		if ( wn_check_url( deep_ssl() . 'deeptem.com/deep-downloads/demo-data/' . $this->demo . '/' . $this->demo . '.zip') ) {
			$value = deep_ssl() . 'deeptem.com/deep-downloads/demo-data/' . $this->demo . '/' . $this->demo . '.zip';
		} else {
			$value = 'http://webnus.biz/deep-downloads/demo-data/' . $this->demo . '/' . $this->demo . '.zip';
		}
		
		//$response = $http->request( $value );
		$get_file = wp_remote_get( 
			$value, 
			array(
				'timeout'     => 120,
				'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36'
			)
		);
		
		$upload = wp_upload_bits( basename( $value ), '', wp_remote_retrieve_body( $get_file ) );
		if( !empty( $upload['error'] ) ) {
			return false;
		}
		
		// unzip demo files
		if ( class_exists('ZipArchive', false) == false ) {

			require_once ( 'zip-extention/zip.php' );
			$zip = new Zip();
			$zip->unzip_file( $upload['file'], $this->demo_dir . $this->demo . '/' );

		} else {

			$zip = new ZipArchive;
			$success_unzip = '';
			if ( $zip->open( $upload['file'] ) === TRUE ) {
				$zip->extractTo( $this->demo_dir . $this->demo . '/' );
				$zip->deleteAfterUnzip = true;
				$zip->close();
				$success_unzip = 'success';
			} else {
				$success_unzip = 'faild';
			}

		}
	}

	/**
	 * Import Content.
	 *
	 * @since   4.2.8
	 */
	public function import_content() {

		if ( ! class_exists( 'OCDI_Plugin' ) ) {
			require_once DEEP_CORE_DIR . 'importer/one-click-demo-import/one-click-demo-import.php';
		}

		// Progress Content
		$result = $this->demo_dir.'/result.txt';
		$this->result_file = $result;
		
		$im_content = array (
			"pages"         => $this->pages,
			"posts"         => $this->posts,
			"attachments"   => $this->attachments,
			"theme_opts"    => $this->theme_opts,
			"header"        => $this->header,
			"widgets"       => $this->widgets,
			"contact_forms" => $this->contact_forms,			
		);
		
		$cn_logger = new OCDI\Logger();
		$importing_demo = new OCDI\Importer( [
			'update_attachment_guids' => true,
			'fetch_attachments' => true
		], $cn_logger );
		
		foreach ( $im_content as $key => $item ) {
			switch ($item) {								
				case 'pages':
				
					$importing_demo->import_content($this->path.'/pages-ele.xml');
					$importing_demo->import_content($this->path.'/mega-menus-ele.xml');
					$importing_demo->import_content($this->path.'/footer-ele.xml');
					$importing_demo->import_content($this->path.'/templates.xml');					
					$importing_demo->import_content($this->path.'/menu-ele.xml');

					file_put_contents($result, '10');
								
					break;

				case 'posts':
				
					$importing_demo->import_content($this->path.'/posts-ele.xml');

					file_put_contents($result, '20');
										
					break;

				case 'attachments':									
													
					$importing_demo->import_content($this->path.'/media-ele.xml');	
					
					file_put_contents($result, '30');
									
					break;

				case 'theme_opts':
					/**
					 * Theme Options
					 */		
					if ( file_exists( $this->path.'/theme_options.txt' ) ) {
						$file_contents = file_get_contents( $this->path.'/theme_options.txt' );
						$options = json_decode($file_contents, true);
						$redux = ReduxFrameworkInstances::get_instance('deep_options');
						$redux->set_options($options);
					}
				
					file_put_contents($result, '50');
											
					break;

				case 'header':
					/**
					 * Header
					 */			
					$headerFile = $this->path.'/header.json';
					if ( file_exists( $headerFile ) ) {
						$headerData = file_get_contents( $headerFile );
						$headerData = json_decode( stripslashes( $headerData ), true );
						update_option( 'whb_data_components', $headerData['whb_data_components'] );
						update_option( 'whb_data_editor_components', $headerData['whb_data_editor_components'] );
						update_option( 'whb_data_frontend_components', $headerData['whb_data_frontend_components'] );
					}
										
					file_put_contents($result, '60');

					break;

				case 'widgets':
					/**
					 * Widgets
					 */
					if ( file_exists( $this->path . '/widgets.json' ) ) {
						OCDI\WidgetImporter::import($this->path . '/widgets.json');
					}	
					
					file_put_contents($result, '70');

					break;				

				case 'contact_forms':

					$importing_demo->import_content($this->path.'/contact-forms.xml');		

					file_put_contents($result, '80');

					break;
								
					
				default:
					
					break;
			}
		}

		
		/**
		 * MEC Shortcodes
		 *
		 * @since 4.2.8
		 */		
		if ( is_plugin_active( 'modern-events-calendar-lite/modern-events-calendar-lite.php' ) || is_plugin_active( 'modern-events-calendar/mec.php' ) ) {

			if ( file_exists( $this->path.'/mec-shortcodes.xml' ) ) {
				$importing_demo->import_content($this->path.'/mec-shortcodes.xml');
			}
			
			$this->deep_events_demo();

		}
	

		$this->front_posts_page();

		file_put_contents($result, '100');
	}

	/**
	 * Assign front page and posts page.
	 *
	 * @since   4.2.8
	 */
	public function front_posts_page() {

		switch ( $this->demo ) {
			case 'magazine-free':
				$front_page = get_page_by_title( 'Home 1 - Magazine' );
				$blog_page  = get_page_by_title( 'Blog' );
				break;
			
			case 'personal-blog-free':
				$front_page = get_page_by_title( 'Home 1 - Personal Blog' );
				$blog_page  = get_page_by_title( 'Blog' );
				break;
			
			case 'agency2free':
				$front_page = get_page_by_title( 'Home - Agency 2' );
				$blog_page  = get_page_by_title( 'Blog' );
				break;	

			case 'modern-business':
			case 'conference-free':
			case 'spa-free':
			case 'corporate-free':
			case 'corporate2-free':
			case 'events-free':
			case 'church-free':
			case 'real-estate-free':
			case 'freelancer-free':
			case 'language-school-free':
			case 'business-free':
			case 'lawyer-free':
			case 'dentist-free':
			case 'startup-free':
			case 'wedding-free':
			case 'insurance-free':
			case 'yoga-free':
			case 'mechanic-free':
			case 'portfolio-free':
			case 'dietitian-free':
			case 'software-free':
			case 'beauty-free':
			case 'consulting-free':
			case 'crypto-free':
				$front_page = get_page_by_title( 'Home' );
				$blog_page  = get_page_by_title( '' );
				break; 

			default:
				
				break;
		}						
		
		update_option( 'show_on_front', 'page' );
		
		if ( $front_page ) {
			update_option( 'page_on_front', $front_page->ID );
		}

		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}
			
	}

	/**
	 * Reset progress bar
	 *
	 * @since 4.2.10
	 */	
	public function reset_progress() {
							
		$result_file = wp_upload_dir()['basedir'] . '/webnus/demo-data/result.txt';
	
		if ( file_exists( $result_file ) ) {
			file_put_contents($result_file, '0');
		}

		wp_die();
						
	}

	/**
	 * Events
	 *
	 * @since 4.2.8
	 */	
	public function deep_events_demo() {
			
		if ( is_plugin_active( 'modern-events-calendar-lite/modern-events-calendar-lite.php' ) || is_plugin_active( 'modern-events-calendar/mec.php' ) ) {							

			if ( file_exists( $this->path.'/events.xml' ) ) {							
				do_action( 'mec_import_file', $this->path.'/events.xml' );																			
			}

		}
				
	}

	/**
	 * Single Ajax Call Time
	 *
	 * @since 4.2.10
	 */	
	public function single_ajax_call_time() {
		return 99999;
	}
}
// Run
Deep_Demo_Importer::get_instance();