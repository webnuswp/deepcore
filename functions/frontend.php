<?php
/**
 * Deep Theme.
 *
 * Deep frontend handler class is responsible for initializing Deep in the frontend.
 *
 * @since   1.0.0
 * @author  Webnus
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WN_Frontend' ) ) {
    class WN_Frontend {

		/**
		 * Instance of this class.
         *
		 * @since	1.0.0
		 * @access	private
		 * @var		WN_Frontend
		 */
		private static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since	1.0.0
		 * @return	object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
            }

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {
			if ( is_admin() ) {
				return;
			}

            add_action( 'wp_head', [ $this, 'force_css' ], 0 );
            add_action( 'wp_footer', [ $this, 'enqueue_preloader' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 99 );			
		}

		/**
         * force_css scripts.
         *
         * @since	1.0.0
         */
		public function force_css(){
			echo '<style>body.wn-preloader #wrap{opacity:0;overflow:hidden;}body.wn-start-rendering{opacity:1;overflow:unset;transition:all .3s ease;}</style>';
		}

        /**
         * Register scripts.
         *
         * @since	1.0.0
         */
        public function register_scripts() {
			$deep_options	= deep_options();
			$api_code       = ( isset( $deep_options['deep_google_map_api'] ) && $deep_options['deep_google_map_api'] ) ? 'key=' . $deep_options['deep_google_map_api'] : '';
			$init_query     = ( $api_code ) ? '?' : '';

			// Google Maps api
			wp_register_script( 'deep-googlemap-api', 'https://maps.googleapis.com/maps/api/js' . $init_query . $api_code, array(), false, false );
			// Google Maps
			wp_register_script( 'deep-googlemap', DEEP_ASSETS_URL . 'js/frontend/googlemap.js', array(), null, true );
			// Newsticker
			wp_register_script( 'deep-news-ticker', DEEP_ASSETS_URL . 'js/libraries/jquery.ticker.js', array(), false, false );
			// preloader
			wp_register_script( 'deep-preloader-script', DEEP_ASSETS_URL . 'js/libraries/preloader.js', array(), false, true );
			// Newsticker
			wp_register_script( 'slick', DEEP_ASSETS_URL . 'js/libraries/slick.js', array('jquery'), false, false );
			// tilt.js
			wp_register_script( 'deep-tilt-lib', DEEP_ASSETS_URL . 'js/libraries/tilt.js', array('jquery'), false, true );
			wp_register_script( 'deep-tilt', DEEP_ASSETS_URL . 'js/frontend/tiltvc.js', array('jquery'), false, true );

			$dynamic_dir = DEEP_ASSETS_DIR . 'css/frontend/dynamic-style';
			if ( ! file_exists( $dynamic_dir ) ) {
				mkdir( $dynamic_dir, 0777 );
			}
		}

        /**
         * Enqueue scripts.
         *
         * @since	1.0.0
         */
        public function enqueue_scripts() {
			$deep_options = deep_options();
			$one_page_class = rwmb_meta( 'deep_onepage_menu_meta' );
			$deep_options['deep_custom_scrollbar'] = isset( $deep_options['deep_custom_scrollbar'] ) ? $deep_options['deep_custom_scrollbar'] : '0';
			
			// Page Preloader
			$enable_preloader = isset( $deep_options['enable_preloader'] ) && $deep_options['enable_preloader'] == '1' ? $deep_options['enable_preloader'] : false;
			if ( $enable_preloader ) {
				wp_enqueue_script( 'deep-preloader-script' );
			}

			// Comment Reply JS
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			// Load jQuery-ui-core
			if ( $one_page_class == '1' ) {
				wp_enqueue_script( 'jquery-ui-core' );
			}

			if ( $deep_options['deep_blog_template_layout'] == '2' ) {
				wp_enqueue_script( 'jquery-masonry' );
			}

			if ( $deep_options['deep_custom_scrollbar'] == '1' ) {
				wp_enqueue_script( 'deep-nicescroll-script', DEEP_ASSETS_URL . 'js/libraries/jquery.nicescroll.js', array( 'jquery' ), null, true );
			}

			// Webnus JS
			wp_enqueue_script( 'deep-jquery-plugins', DEEP_ASSETS_URL . 'js/frontend/jquery.plugins.js', array( 'jquery' ), null, true);
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'deep_custom_script', DEEP_ASSETS_URL . 'js/frontend/webnus-custom.js', array( 'jquery' ), null, true );
			wp_localize_script( 'deep_custom_script' ,  'deep_localize' ,  array(
				'deep_ajax'		=>	admin_url( 'admin-ajax.php' ),
			));
		}

        /**
         * Enqueue all the frontend styles.
         *
         * @since	1.0.0
         */
        public function enqueue_styles() {
			$deep_options = deep_options();
			$deep_minifier = isset( $deep_options['deep_minifier'] ) ? $deep_options['deep_minifier'] : '';
			if ( $deep_minifier == '1' ) {
				$main_min_style_uri = DEEP_URL . '/assets/dist/css/frontend/base/wn-master.css';
				wp_register_style( 'deep-main-min-style', $main_min_style_uri, 'deep-preloader' );
				wp_enqueue_style( 'wn-iconfonts', DEEP_ASSETS_URL . 'css/frontend/base/07-iconfonts.css' );
				wp_enqueue_style( 'deep-main-min-style' );
			} else {
				if ( is_plugin_active( 'kingcomposer/kingcomposer.php ') ) {
					wp_enqueue_style( 'wn-kingcomposer', DEEP_ASSETS_URL . 'css/frontend/base/01-kingcomposer.css' );
				}
				if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
					wp_enqueue_style( 'wn-visualcomposer', DEEP_ASSETS_URL . 'css/frontend/base/02-visualcomposer.css' );
				}
				wp_enqueue_style( 'wn-base', DEEP_ASSETS_URL . 'css/frontend/base/03-base.css' );
				wp_enqueue_style( 'wn-scaffolding', DEEP_ASSETS_URL . 'css/frontend/base/04-scaffolding.css' );
				wp_enqueue_style( 'wn-blox', DEEP_ASSETS_URL . 'css/frontend/base/05-blox.css' );
				wp_enqueue_style( 'wn-plugins', DEEP_ASSETS_URL . 'css/frontend/base/06-plugins.css' );
				wp_enqueue_style( 'wn-iconfonts', DEEP_ASSETS_URL . 'css/frontend/base/07-iconfonts.css' );
				wp_enqueue_style( 'wn-elements', DEEP_ASSETS_URL . 'css/frontend/base/09-elements.css' );
				wp_enqueue_style( 'wn-widgets', DEEP_ASSETS_URL . 'css/frontend/base/10-widgets.css' );
				wp_enqueue_style( 'wn-main-style', DEEP_ASSETS_URL . 'css/frontend/base/11-main-style.css' );
				wp_enqueue_style( 'wn-bp', DEEP_ASSETS_URL . 'css/frontend/base/12-bp.css' );
			}

			// Header Dynamic Style
			wp_enqueue_style( 'header-dyn', DEEP_ASSETS_URL . 'css/frontend/dynamic-style/header.dyn.css', false, wp_rand( 1,100 ) );

			if ( deep_is_blog() ) {
				wp_enqueue_style( 'wn-deep-blog', DEEP_ASSETS_URL . 'css/frontend/base/08-blog.css' );
			}

			if( is_single() && defined( 'JETPACK__VERSION' ) ) {
				wp_enqueue_style( 'wn-deep-jetpack-integration', DEEP_ASSETS_URL . 'css/frontend/jetpack/jetpack.css' );
			}
			if( is_single() && defined( 'W_CAUSES_VERSION' ) && 'cause' === get_post_type() ) {
				wp_enqueue_style( 'wn-deep-single-cause', DEEP_ASSETS_URL . 'css/frontend/single-cause/single-cause.css' );
			}
			if( is_single() && defined( 'W_RECIPES_VERSION' ) && 'recipe' === get_post_type() ) {
				wp_enqueue_style( 'wn-deep-single-recipe', DEEP_ASSETS_URL . 'css/frontend/single-recipe/single-recipe.css' );
			}
			if( is_single() && defined( 'W_PORTFOLIO_VERSION' ) && 'portfolio' === get_post_type() ) {
				wp_enqueue_style( 'wn-deep-single-portfolio', DEEP_ASSETS_URL . 'css/frontend/single-portfolio/single-portfolio.css' );
			}

			
			// Page Preloader
			wp_register_style( 'deep-preloader-style',  DEEP_ASSETS_URL . 'css/libraries/preloader.css' );
			$enable_preloader = isset( $deep_options['enable_preloader'] ) && $deep_options['enable_preloader'] == '1' ? $deep_options['enable_preloader'] : false;
			if ( $enable_preloader ) {
				wp_enqueue_style( 'deep-preloader-style' );
			}

			// Elementor
			if ( did_action( 'elementor/loaded' ) ) {
				wp_enqueue_style( 'wn-elementor-elements', DEEP_ASSETS_URL . 'css/frontend/elementor/elementor-elements.css' );
			}

			// google font
			$deep_options['rm_cs_font'] = isset( $deep_options['rm_cs_font'] ) ? $deep_options['rm_cs_font'] : '';
			if ( $deep_options['rm_cs_font'] == '1' ) {
				wp_enqueue_style( 'deep-google-fonts', deep_google_fonts_url(), array(), null );
			} else {
				wp_dequeue_style( 'redux-google-fonts-deep_options' );
			}

			// typekit font
			$w_adobe_typekit = ltrim ( isset( $deep_options['deep_typekit_id'] ) ? $deep_options['deep_typekit_id'] : '' );
			$deep_options['deep_adobe_typekit'] = isset( $deep_options['deep_adobe_typekit'] ) ? $deep_options['deep_adobe_typekit'] : '0';
			if ( isset( $w_adobe_typekit ) && !empty( $w_adobe_typekit ) && $deep_options['deep_adobe_typekit'] == '1' ) {
				wp_enqueue_script( 'wn-typekit', 'https://use.typekit.net/'.esc_attr( $w_adobe_typekit ).'.js', array(), '1.0' );
				wp_add_inline_script( 'wn-typekit', 'try{Typekit.load({ async: true });}catch(e){}' );
			}

			if ( is_plugin_active( 'whmcs-bridge/bridge.php' ) ) {
				wp_enqueue_style( 'deep-whmcs', DEEP_ASSETS_URL . 'css/frontend/whmcs/whmcs.css' );
			}

			if ( defined( 'DEEP_HANDLE' ) ) {
				wp_dequeue_style( 'deeptheme-style' );
			}

			// Fast Contact Form
			$deep_options['deep_fast_contact_form'] = isset( $deep_options['deep_fast_contact_form'] ) ? $deep_options['deep_fast_contact_form'] : '';
			if ( $deep_options['deep_fast_contact_form'] == '1' ) {
				wp_enqueue_style( 'deep-fast-contact-form', DEEP_ASSETS_URL . 'css/frontend/fast-contact-form/fast-contact-form.css' );
			}
        }

        /**
         * Enqueue preloader.
         *
         * @since	1.0.0
         */
        public function enqueue_preloader() {
			$deep_options = deep_options();
			$enable_preloader = isset( $deep_options['enable_preloader'] ) && $deep_options['enable_preloader'] == '1' ? $deep_options['enable_preloader'] : false;
			if ( $enable_preloader ) {
				$preloader_logo		= isset( $deep_options['preloader_logo'] ) && !empty($deep_options['preloader_logo']['url']) ? $deep_options['preloader_logo']['url'] : '';
				$preloader_bg_color = isset( $deep_options['preloader_bg_color'] ) ? $deep_options['preloader_bg_color'] : '';
				$preloader_spinkit	= isset( $deep_options['preloader_spinkit'] ) ? $deep_options['preloader_spinkit'] : '7';
				$preloader_bg_timeout = isset( $deep_options['preloader_bg_timeout'] ) ? $deep_options['preloader_bg_timeout'] : '';

				switch ($preloader_spinkit) {
					case '1':
						$loadingHtml = '<div class="sk-spinner sk-spinner-rotating-plane"></div>';
						break;
					case '2':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-double-bounce">
							<div class="sk-double-bounce1"></div>
							<div class="sk-double-bounce2"></div>
						</div>';
						break;
					case '3':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-wave">
							<div class="sk-rect1"></div>
							<div class="sk-rect2"></div>
							<div class="sk-rect3"></div>
							<div class="sk-rect4"></div>
							<div class="sk-rect5"></div>
						</div>';
						break;
					case '4':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-wandering-cubes">
							<div class="sk-cube1"></div>
							<div class="sk-cube2"></div>
						</div>';
						break;
					case '5':
						$loadingHtml = '<div class="sk-spinner sk-spinner-pulse"></div>';
						break;
					case '6':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-chasing-dots">
							<div class="sk-dot1"></div>
							<div class="sk-dot2"></div>
						</div>';
						break;
					case '7':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-three-bounce">
							<div class="sk-bounce1"></div>
							<div class="sk-bounce2"></div>
							<div class="sk-bounce3"></div>
						</div>';
						break;
					case '8':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-circle">
							<div class="sk-circle1 sk-circle"></div>
							<div class="sk-circle2 sk-circle"></div>
							<div class="sk-circle3 sk-circle"></div>
							<div class="sk-circle4 sk-circle"></div>
							<div class="sk-circle5 sk-circle"></div>
							<div class="sk-circle6 sk-circle"></div>
							<div class="sk-circle7 sk-circle"></div>
							<div class="sk-circle8 sk-circle"></div>
							<div class="sk-circle9 sk-circle"></div>
							<div class="sk-circle10 sk-circle"></div>
							<div class="sk-circle11 sk-circle"></div>
							<div class="sk-circle12 sk-circle"></div>
						</div>';
						break;
					case '9':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-cube-grid">
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
							<div class="sk-cube"></div>
						</div>';
						break;
					case '10':
						$loadingHtml = '
						<div class="sk-spinner sk-spinner-fading-circle">
							<div class="sk-circle1 sk-circle"></div>
							<div class="sk-circle2 sk-circle"></div>
							<div class="sk-circle3 sk-circle"></div>
							<div class="sk-circle4 sk-circle"></div>
							<div class="sk-circle5 sk-circle"></div>
							<div class="sk-circle6 sk-circle"></div>
							<div class="sk-circle7 sk-circle"></div>
							<div class="sk-circle8 sk-circle"></div>
							<div class="sk-circle9 sk-circle"></div>
							<div class="sk-circle10 sk-circle"></div>
							<div class="sk-circle11 sk-circle"></div>
							<div class="sk-circle12 sk-circle"></div>
						</div>';
						break;
					case '11':
						$loadingHtml = '
							<div class="wn-sd-snt-icon">
								<div class="spinner">
									<div class="right-side"><div class="bar"></div></div>
									<div class="left-side"><div class="bar"></div></div>
								</div>
								<div class="spinner color-2">
									<div class="right-side"><div class="bar"></div></div>
									<div class="left-side"><div class="bar"></div></div>
								</div>
							</div>';
						break;
					case '12':
						$loadingHtml = '
							<div class="slor-loader">
								<div class="loading">
									<div class="bounceball"></div>
									<div class="text">' . __( 'NOW LOADING', 'deep' ) . '</div>
								</div>
							</div>';
						break;
					case '13':
						$loadingHtml = '
							<div class="jetloader">
								<span>
									<span></span>
									<span></span>
									<span></span>
									<span></span>
								</span>
								<div class="base">
									<span></span>
									<div class="face"></div>
								</div>
							</div>
							<div class="longfazers">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</div>
							<p class="loading-content">' . __( 'Redirecting', 'deep' ) . '</p>';
						break;
				}
				$preloader_image = !empty( $preloader_logo ) ? '<img alt="preloader-logo" class="pg-loading-logo" src="' . esc_url( $preloader_logo ) . '">' : '';
				$out = '
					<div class="pg-loading-screen" style="background: ' . esc_attr( $preloader_bg_color ) . ';" data-timeout="' . esc_attr( $preloader_bg_timeout ) . '">
						<div class="pg-loading-inner">
							<div class="pg-loading-center-outer">
								<div class="pg-loading-center-middle">
									<div class="pg-loading-logo-header">
										' . $preloader_image . '
									</div>
									<div class="pg-loading-html">
										' . $loadingHtml . '
									</div>
								</div>
							</div>
						</div>
					</div>';
				echo $out;


			}
		}

	}
	// Run
	WN_Frontend::get_instance();
}
