<?php
/**
 * Importer
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.3
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// don't load directly
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( !class_exists( 'ReduxFramework_extension_wbc_importer' ) ) {
    class ReduxFramework_extension_wbc_importer {

        public static $instance;

        static $version = "1.0.3";

        protected $parent;

        private $filesystem = array();

        public $extension_url;
        public $extension_dir;
        public $demo_data_dir;

        public $wbc_import_files = array();

        public $active_import_id;
        public $active_import;
        public $pages;
        public $posts;
        public $contact_forms;
        public $portfolios;
        public $fetch_attachments;
        public $import_sliders;
        public $import_theme_opts;
        public $import_widgets;
        public $page_builder;
        private $webnus_dir;
        private $demo_dir;

        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct( $parent ) {
            
            $this->parent = $parent;

            if ( !is_admin() ) return;

            // Hides importer section if anything but true returned. Way to abort :)
            if ( true !== apply_filters( 'wbc_importer_abort', true ) ) {
                return;
            }

            $this->webnus_dir = wp_upload_dir()['basedir'] . '/webnus/';
            $this->demo_dir = $this->webnus_dir . 'demo-data/';


            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
                $this->demo_data_dir = apply_filters( "wbc_importer_dir_path", $this->demo_dir );
            }

            // Delete saved options of imported demos, for dev/testing purpose
            // delete_option('wbc_imported_demos');

            $this->getImports();

            $this->field_name = 'wbc_importer';

            self::$instance = $this;

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
                    'overload_field_path'
                ) );

            // call ajax actions
            add_action( 'wp_ajax_redux_wbc_importer', array( $this, 'ajax_importer' ) );
            add_action( 'wp_ajax_webnus_create_demo_dir', array( $this, 'create_demo_dir' ) );

            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/wbc_importer_files', array( $this, 'addImportFiles' ) );

            // Adds Importer section to panel
            // $this->add_importer_section();

            include $this->extension_dir.'inc/class-wbc-importer-progress.php';
            $wbc_progress = new Wbc_Importer_Progress( $this->parent );

            include_once 'webnus-wbc-configs.php';
        }

        /**
         * Get the demo folders/files
         * Provided fallback where some host require FTP info
         *
         * @return array list of files for demos
         */
        public function demoFiles() {
            if ( defined( 'DEEPFREE' ) ) {
                $fix_options = array(
                    'content-ele.xml' => array(
                        'name' => 'content-ele.xml',
                        'type' => 'f',
                    ),
                    'header.json' => array(
                        'name' => "header.json",
                        'type' => "f"
                    ),
                    'screen-image.jpg' => array(
                        'name' => "screen-image.jpg",
                        'type' => "f"
                    ),
                    'theme_options.txt' => array(
                        'name' => "theme_options.txt",
                        'type' => "f"
                    ),
                    'widgets.json' => array(
                        'name' => "widgets.json",
                        'type' => "f"
                    ),
                );
                $dir_array = array(
                    'agency2'       => array(
                        'name'          => 'agency2free',
                        'type'          => 'd',
                        'display_name'  => 'Modern Agency',
                        'preview_url'   => 'http://deeptem.com/agency2free/',
                        'import_name'   => 'wbc-import-1',
                        'files'         => $fix_options,
                    ),
                    'magazine'      => array(
                        'name'          => 'magazine-free',
                        'type'          => 'd',
                        'display_name'  => 'Magazine',
                        'preview_url'   => 'http://deeptem.com/magazine/elementor/',
                        'import_name'   => 'wbc-import-2',
                        'files'         => $fix_options,
                    ),
                    'personal-blog' => array(
                        'name'          => 'personal-blog-free',
                        'type'          => 'd',
                        'display_name'  => 'Personal Blog',
                        'preview_url'   => 'http://deeptem.com/personal-blog/elementor/',
                        'import_name'   => 'wbc-import-3',
                        'files'         => $fix_options,
                    ),
                    'minimal-blog-free' => array(
                        'name'          => 'minimal-blog-free',
                        'type'          => 'd',
                        'display_name'  => 'Minimal Blog',
                        'preview_url'   => 'http://deeptem.com/minimal-blog-free/',
                        'import_name'   => 'wbc-import-4',
                        'files'         => $fix_options,
                    ),
                    'modern-business' => array(
                        'name'          => 'modern-business',
                        'type'          => 'd',
                        'display_name'  => 'Modern Business',
                        'preview_url'   => 'https://deeptem.com/modern-business/',
                        'import_name'   => 'wbc-import-5',
                        'files'         => $fix_options,
                    ),
                    'conference-free' => array(
                        'name'          => 'conference-free',
                        'type'          => 'd',
                        'display_name'  => 'Conference',
                        'preview_url'   => 'https://deeptem.com/conference-free/',
                        'import_name'   => 'wbc-import-6',
                        'files'         => $fix_options,
                    ),
                    'spa-free' => array(
                        'name'          => 'spa-free',
                        'type'          => 'd',
                        'display_name'  => 'SPA',
                        'preview_url'   => 'https://deeptem.com/spa-free/',
                        'import_name'   => 'wbc-import-7',
                        'files'         => $fix_options,
                    ),
                    'corporate-free' => array(
                        'name'          => 'corporate-free',
                        'type'          => 'd',
                        'display_name'  => 'Corporate',
                        'preview_url'   => 'https://deeptem.com/corporate-free/',
                        'import_name'   => 'wbc-import-8',
                        'files'         => $fix_options,
                    ),
                    'corporate2-free' => array(
                        'name'          => 'corporate2-free',
                        'type'          => 'd',
                        'display_name'  => 'Corporate2',
                        'preview_url'   => 'https://deeptem.com/corporate2-free/',
                        'import_name'   => 'wbc-import-9',
                        'files'         => $fix_options,
                    ),
                    'events-free' => array(
                        'name'          => 'events-free',
                        'type'          => 'd',
                        'display_name'  => 'Events',
                        'preview_url'   => 'https://deeptem.com/events-free/',
                        'import_name'   => 'wbc-import-10',
                        'files'         => $fix_options,
                    ),
                    'church-free' => array(
                        'name'          => 'church-free',
                        'type'          => 'd',
                        'display_name'  => 'Church',
                        'preview_url'   => 'https://deeptem.com/church-free/',
                        'import_name'   => 'wbc-import-11',
                        'files'         => $fix_options,
                    ),
                    'real-estate-free' => array(
                        'name'          => 'real-estate-free',
                        'type'          => 'd',
                        'display_name'  => 'Real Estate',
                        'preview_url'   => 'https://deeptem.com/real-estate-free/',
                        'import_name'   => 'wbc-import-12',
                        'files'         => $fix_options,
                    ),
                    'freelancer-free' => array(
                        'name'          => 'freelancer-free',
                        'type'          => 'd',
                        'display_name'  => 'Freelancer',
                        'preview_url'   => 'https://deeptem.com/freelancer-free/',
                        'import_name'   => 'wbc-import-13',
                        'files'         => $fix_options,
                    ),
                    'language-school-free' => array(
                        'name'          => 'language-school-free',
                        'type'          => 'd',
                        'display_name'  => 'Language School',
                        'preview_url'   => 'https://deeptem.com/language-school-free/',
                        'import_name'   => 'wbc-import-14',
                        'files'         => $fix_options,
                    ),
                    'business-free' => array(
                        'name'          => 'business-free',
                        'type'          => 'd',
                        'display_name'  => 'Business',
                        'preview_url'   => 'https://deeptem.com/business-free/',
                        'import_name'   => 'wbc-import-15',
                        'files'         => $fix_options,
                    ),
                    'lawyer-free' => array(
                        'name'          => 'lawyer-free',
                        'type'          => 'd',
                        'display_name'  => 'Lawyer',
                        'preview_url'   => 'https://deeptem.com/lawyer-free/',
                        'import_name'   => 'wbc-import-16',
                        'files'         => $fix_options,
                    ),
                    'dentist-free' => array(
                        'name'          => 'dentist-free',
                        'type'          => 'd',
                        'display_name'  => 'Dentist',
                        'preview_url'   => 'https://deeptem.com/dentist-free/',
                        'import_name'   => 'wbc-import-17',
                        'files'         => $fix_options,
                    ),
                    'startup-free' => array(
                        'name'          => 'startup-free',
                        'type'          => 'd',
                        'display_name'  => 'Startup',
                        'preview_url'   => 'https://deeptem.com/startup-free/',
                        'import_name'   => 'wbc-import-18',
                        'files'         => $fix_options,
                    ),
                    'wedding-free' => array(
                        'name'          => 'wedding-free',
                        'type'          => 'd',
                        'display_name'  => 'Wedding',
                        'preview_url'   => 'https://deeptem.com/wedding-free/',
                        'import_name'   => 'wbc-import-19',
                        'files'         => $fix_options,
                    ),
                    'insurance-free' => array(
                        'name'          => 'insurance-free',
                        'type'          => 'd',
                        'display_name'  => 'Insurance',
                        'preview_url'   => 'https://deeptem.com/insurance-free/',
                        'import_name'   => 'wbc-import-20',
                        'files'         => $fix_options,
                    ),
                    'yoga-free' => array(
                        'name'          => 'yoga-free',
                        'type'          => 'd',
                        'display_name'  => 'Yoga',
                        'preview_url'   => 'https://deeptem.com/yoga-free/',
                        'import_name'   => 'wbc-import-21',
                        'files'         => $fix_options,
                    ),
                    'mechanic-free' => array(
                        'name'          => 'mechanic-free',
                        'type'          => 'd',
                        'display_name'  => 'Mechanic',
                        'preview_url'   => 'https://deeptem.com/mechanic-free/',
                        'import_name'   => 'wbc-import-22',
                        'files'         => $fix_options,
                    ),
                    'portfolio-free' => array(
                        'name'          => 'portfolio-free',
                        'type'          => 'd',
                        'display_name'  => 'Portfolio',
                        'preview_url'   => 'https://deeptem.com/portfolio-free/',
                        'import_name'   => 'wbc-import-23',
                        'files'         => $fix_options,
                    ),
                    'dietitian-free' => array(
                        'name'          => 'dietitian-free',
                        'type'          => 'd',
                        'display_name'  => 'Dietitian',
                        'preview_url'   => 'https://deeptem.com/dietitian-free/',
                        'import_name'   => 'wbc-import-24',
                        'files'         => $fix_options,
                    ),
                    'software-free' => array(
                        'name'          => 'software-free',
                        'type'          => 'd',
                        'display_name'  => 'Software',
                        'preview_url'   => 'https://deeptem.com/software-free/',
                        'import_name'   => 'wbc-import-25',
                        'files'         => $fix_options,
                    ),
                    'beauty-free' => array(
                        'name'          => 'beauty-free',
                        'type'          => 'd',
                        'display_name'  => 'Beauty',
                        'preview_url'   => 'https://deeptem.com/beauty-free/',
                        'import_name'   => 'wbc-import-26',
                        'files'         => $fix_options,
                    ),
                    'consulting-free' => array(
                        'name'          => 'consulting-free',
                        'type'          => 'd',
                        'display_name'  => 'Consulting',
                        'preview_url'   => 'https://deeptem.com/consulting-free/',
                        'import_name'   => 'wbc-import-27',
                        'files'         => $fix_options,
                    ),
                    'crypto-free' => array(
                        'name'          => 'crypto-free',
                        'type'          => 'd',
                        'display_name'  => 'Crypto',
                        'preview_url'   => 'https://deeptem.com/crypto-free/',
                        'import_name'   => 'wbc-import-28',
                        'files'         => $fix_options,
                    ),
                );
            } 
            return $dir_array;
        }

        public function getImports() {

            if ( !empty( $this->wbc_import_files ) ) {
                return $this->wbc_import_files;
            }

            $imports = $this->demoFiles();

            $imported = get_option( 'wbc_imported_demos' );

            if ( !empty( $imports ) && is_array( $imports ) ) {
                $x = 1;
                foreach ( $imports as $import ) {

                    if ( !isset( $import['files'] ) || empty( $import['files'] ) ) {
                        continue;
                    }

                    if ( $import['type'] == "d" && !empty( $import['name'] ) ) {
                        $this->wbc_import_files['wbc-import-'.$x] = isset( $this->wbc_import_files['wbc-import-'.$x] ) ? $this->wbc_import_files['wbc-import-'.$x] : array();
                        $this->wbc_import_files['wbc-import-'.$x]['directory'] = $import['name'];

                        if ( !empty( $imported ) && is_array( $imported ) ) {
                            if ( array_key_exists( 'wbc-import-'.$x, $imported ) ) {
                                $this->wbc_import_files['wbc-import-'.$x]['imported'] = 'imported';
                            }
                        }

                        foreach ( $import['files'] as $file ) {

                            switch ( $file['name'] ) {
                                case 'content.xml':
                                    $this->wbc_import_files['wbc-import-'.$x]['content_file'] = $file['name'];
                                    break;

                                case 'theme-options.txt':
                                case 'theme-options.json':
                                    $this->wbc_import_files['wbc-import-'.$x]['theme_options'] = $file['name'];
                                    break;

                                case 'widgets.json':
                                case 'widgets.txt':
                                    $this->wbc_import_files['wbc-import-'.$x]['widgets'] = $file['name'];
                                    break;

                                case 'screen-image.png':
                                case 'screen-image.jpg':
                                case 'screen-image.gif':
                                    $this->wbc_import_files['wbc-import-'.$x]['image'] = $file['name'];
                                    break;

                                case 'header.json':
                                    $this->wbc_import_files['wbc-import-'.$x]['header'] = $file['name'];
                                    break;

                                case 'go-pricing.txt':
                                    $this->wbc_import_files['wbc-import-'.$x]['header'] = $file['name'];
                                    break;
                            }

                        }

                         $this->wbc_import_files['wbc-import-'.$x]['preview'] = $import['preview_url'];
                         $this->wbc_import_files['wbc-import-'.$x]['display'] = $import['display_name'];

                    }

                    $x++;
                }

            }

        }

        public function addImportFiles( $wbc_import_files ) {

            if ( !is_array( $wbc_import_files ) || empty( $wbc_import_files ) ) {
                $wbc_import_files = array();
            }

            $wbc_import_files = wp_parse_args( $wbc_import_files, $this->wbc_import_files );

            return $wbc_import_files;
        }

        public function create_demo_dir() {

            // Add zip format to upload_filetypes
            if ( is_multisite() ) {

                // Get site option upload_filetypes
                $upload_filetypes   = explode( ' ', get_site_option( 'upload_filetypes' ) );
                $array_size         = sizeof($upload_filetypes);
                
                // add zip format to upload_filetypes
                if ( ! in_array( 'zip', $upload_filetypes ) ) {
                    
                    $upload_filetypes[$array_size] = 'zip';

                    // change format to string with one space
                    $upload_filetypes = implode( ' ', $upload_filetypes );

                    // update upload_filetypes
                    update_site_option( 'upload_filetypes', $upload_filetypes );
                
                }

            }

            $this->active_import_id     = $_REQUEST['demo_import_id'];

            // Get target importer directory
            $get_all_demoes = $this->demoFiles();
            $target_demo = '';
            foreach ( $get_all_demoes as $demo ) {
                if ( $demo['import_name'] == $this->active_import_id ) {
                    $target_demo = $demo['name'];
                }
            }

            // Create demo-data folder
            if ( wp_mkdir_p( $this->demo_dir. $target_demo ) ) {
                wp_mkdir_p( $this->demo_dir . $target_demo );
            }

            $value = '';
            
            if ( wn_check_url( deep_ssl() . 'deeptem.com/deep-downloads/demo-data/' . $target_demo . '/' . $target_demo . '.zip') ) {
                $value = deep_ssl() . 'deeptem.com/deep-downloads/demo-data/' . $target_demo . '/' . $target_demo . '.zip';
            } else {
                $value = 'http://webnus.biz/deep-downloads/demo-data/' . $target_demo . '/' . $target_demo . '.zip';
            }
            
            //$response = $http->request( $value );
            $get_file = wp_remote_get( $value, array( 'timeout' => 120, 'httpversion' => '1.1', ) );
            
            $upload = wp_upload_bits( basename( $value ), '', wp_remote_retrieve_body( $get_file ) );
            if( !empty( $upload['error'] ) ) {
                return false;
            }
            
            // unzip demo files
            if ( class_exists('ZipArchive', false) == false ) {

                require_once ( 'zip-extention/zip.php' );
                $zip = new Zip();
                $zip->unzip_file( $upload['file'], $this->demo_dir . $target_demo . '/' );

            } else {

                $zip = new ZipArchive;
                $success_unzip = '';
                if ( $zip->open( $upload['file'] ) === TRUE ) {
                    $zip->extractTo( $this->demo_dir . $target_demo . '/' );
                    $zip->deleteAfterUnzip = true;
                    $zip->close();
                    $success_unzip = 'success';
                } else {
                    $success_unzip = 'faild';
                }

            }
            

        }

        public function ajax_importer() {

            if ( is_plugin_active('wordpress-importer/wordpress-importer.php') ) {
                deactivate_plugins( '/wordpress-importer/wordpress-importer.php' );
            }

            wp_delete_post( 1, false );

            if ( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], "redux_{$this->parent->args['opt_name']}_wbc_importer" ) ) {
                die( 0 );
            }

            if ( isset( $_REQUEST['type'] ) && $_REQUEST['type'] == "import-demo-content" && array_key_exists( $_REQUEST['demo_import_id'], $this->wbc_import_files ) ) {                
                $reimporting = false;

                if ( isset( $_REQUEST['wbc_import'] ) && $_REQUEST['wbc_import'] == 're-importing' ) {
                    $reimporting = true;
                }

                $this->active_import_id     = $_REQUEST['demo_import_id'];
                $this->pages                = ( $_REQUEST['pages'] == 'yes' ) ? true : false;
                $this->posts                = ( $_REQUEST['posts'] == 'yes' ) ? true : false;
                $this->contact_forms        = ( $_REQUEST['contact_forms'] == 'yes' ) ? true : false;
                $this->portfolios           = ( $_REQUEST['portfolios'] == 'yes' ) ? true : false;
                $this->fetch_attachments    = ( $_REQUEST['fetch_attachments'] == 'yes' ) ? true : false;
                $this->import_sliders       = ( $_REQUEST['import_sliders'] == 'yes' ) ? true : false;
                $this->import_theme_opts    = ( $_REQUEST['import_theme_opts'] == 'yes' ) ? true : false;
                $this->import_widgets       = ( $_REQUEST['import_widgets'] == 'yes' ) ? true : false;
                $this->page_builder         = $_REQUEST['page_builder'];

                if ( $this->page_builder == 'kc' ) {
                    $this->wbc_import_files[$this->active_import_id]['content_file'] = 'content-kc.xml';
                }

                if ( $this->page_builder == 'elementor' ) {
                    $this->wbc_import_files[$this->active_import_id]['content_file'] = 'content-ele.xml';
                }
                $this->active_import = array( $this->active_import_id => $this->wbc_import_files[$this->active_import_id] );

                if ( !isset( $import_parts['imported'] ) || true === $reimporting ) {
                    include $this->extension_dir.'inc/init-installer.php';
                    $installer = new Radium_Theme_Demo_Data_Importer( $this, $this->parent );
                } else {
                    echo esc_html__( "Demo Already Imported", 'framework' );
                }

                die();
            }
                
            die();
        }

        public static function get_instance() {
            return self::$instance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }

        function add_importer_section() {
            // Checks to see if section was set in config of redux.
            for ( $n = 0; $n <= count( $this->parent->sections ); $n++ ) {
                if ( isset( $this->parent->sections[$n]['id'] ) && $this->parent->sections[$n]['id'] == 'wbc_importer_section' ) {
                    return;
                }
            }

            $wbc_importer_label = trim( esc_html( apply_filters( 'wbc_importer_label', __( 'Demo Importer', 'framework' ) ) ) );

            $wbc_importer_label = ( !empty( $wbc_importer_label ) ) ? $wbc_importer_label : __( 'Demo Importer', 'framework' );

            $this->parent->sections[] = array(
                'id'     => 'wbc_importer_section',
                'title'  => $wbc_importer_label,
                'desc'   => '<p class="description">'. apply_filters( 'wbc_importer_description', esc_html__( 'Works best to import on a new install of WordPress', 'framework' ) ).'</p>',
                'icon'   => 'el-icon-website',
                'fields' => array(
                    array(
                        'id'   => 'wbc_demo_importer',
                        'type' => 'wbc_importer'
                    )
                )
            );
        }

    } // class
} // if
