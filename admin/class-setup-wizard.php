<?php
/**
 * Deep Theme.
 * Through six steps, new users can install their desired template.
 *
 * @since   1.0.0
 * @author  Webnus
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WN_Setup_Wizard' ) ) :
	class WN_Setup_Wizard {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     WN_Setup_Wizard
		 */
		private static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @return  object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class Constructor.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function __construct() {
			if ( ! is_admin() || empty( $_GET['page'] ) || $_GET['page'] !== 'webnus-setup-wizard' ) {
				return;
			}

			add_action( 'admin_menu', array( $this, 'admin_menus' ) );
			add_action( 'admin_init', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_init', array( $this, 'setup_wizard' ) );
		}

		/**
		 * Add admin menus.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function admin_menus() {
			add_dashboard_page( '', '', 'manage_options', 'webnus-setup-wizard', '' );
		}

		/**
		 * Register Stylesheets and JavaScripts.
		 *
		 * @since   1.0.0
		 * @access public
		 */
		public function admin_enqueue_scripts() {
			// styles
			wp_enqueue_style( 'deep-iconfonts', DEEP_ASSETS_URL . 'css/frontend/base/07-iconfonts.css' );
			wp_enqueue_style( 'deep-setup-wizard', DEEP_ASSETS_URL . 'css/backend/setup-wizard.css' );

			// scripts
			wp_register_script( 'jquery', includes_url() . '/js/jquery/jquery.min.js' );
			if ( isset( $_GET['step'] ) && $_GET['step'] == '2' ) {
				wp_enqueue_script( 'deep-admin-plugins', DEEP_ASSETS_URL . 'js/backend/wn-plugins.js', array( 'jquery' ), false, true );
				// wp_enqueue_script( 'redux-field-wbc-importer-js', DEEP_ASSETS_URL . 'js/backend/field_wbc_importer.js', array( 'jquery' ), false, true );
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
			wp_enqueue_script( 'deep-setup-wizard', DEEP_ASSETS_URL . 'js/backend/setup-wizard.js', array( 'jquery' ), false, true );
			wp_localize_script(
				'deep-setup-wizard',
				'ajaxObject',
				array(
					'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
					'colornonce' => wp_create_nonce( 'colorCategoriesNonce' ),
				)
			);
		}

		/**
		 * Fetch purchase code.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		private function purchase_code_step() {
			$purchase_code       = get_option( 'deep_purchase', '' );
			$purchase_type       = get_option( 'deep_purchase_type', 'one' );
			$purchase_validation = get_option( 'deep_purchase_validation', '' );
			$purchase_form_class = $purchase_validation ? 'class="' . esc_attr( $purchase_validation ) . '"' : '';
			?>
			<form <?php echo $purchase_form_class; ?> id="wnThemeActivate" method="post" action="#">
				<div id="wnLicenseType">
					<div class="wn-radio-wrap">
						<label class="wn-radio-control <?php echo $purchase_type == 'one' ? 'checked' : ''; ?>" for="oneLicense">
							<input type="radio" id="oneLicense" name="typeOfLicense" value="one" checked="<?php echo $purchase_type == 'one' ? 'checked' : ''; ?>">
							<span class="wn-radio-indicator <?php echo $purchase_type == 'one' ? 'checked' : ''; ?>"></span>
							<?php esc_html_e( '1 License', 'deep' ); ?>
						</label>
					</div>
					<div class="wn-radio-wrap">
						<label class="wn-radio-control <?php echo $purchase_type == 'five' ? 'checked' : ''; ?>" for="fiveLicense">
							<input type="radio" id="fiveLicense" name="typeOfLicense" value="five" checked="<?php echo $purchase_type == 'five' ? 'checked' : ''; ?>">
							<span class="wn-radio-indicator <?php echo $purchase_type == 'five' ? 'checked' : ''; ?>"></span>
							<?php esc_html_e( '5 License', 'deep' ); ?>
						</label>
					</div>
					<div class="wn-radio-wrap">
						<label class="wn-radio-control <?php echo $purchase_type == 'ten' ? 'checked' : ''; ?>" for="tenLicense">
							<input type="radio" id="tenLicense" name="typeOfLicense" value="ten" checked="<?php echo $purchase_type == 'ten' ? 'checked' : ''; ?>">
							<span class="wn-radio-indicator <?php echo $purchase_type == 'ten' ? 'checked' : ''; ?>"></span>
							<?php esc_html_e( '10 License', 'deep' ); ?>
						</label>
					</div>
					<div class="wn-radio-wrap">
						<label class="wn-radio-control <?php echo $purchase_type == 'yearly' ? 'checked' : ''; ?>" for="yearlyLicense">
							<input type="radio" id="yearlyLicense" name="typeOfLicense" value="yearly" checked="<?php echo $purchase_type == 'yearly' ? 'checked' : ''; ?>">
							<span class="wn-radio-indicator <?php echo $purchase_type == 'yearly' ? 'checked' : ''; ?>"></span>
							<?php esc_html_e( 'Yearly Access', 'deep' ); ?>
						</label>
					</div>
					<div class="wn-radio-wrap">
						<label class="wn-radio-control <?php echo $purchase_type == 'lifetime' ? 'checked' : ''; ?>" for="lifetimeLicense">
							<input type="radio" id="lifetimeLicense" name="typeOfLicense" value="lifetime" checked="<?php echo $purchase_type == 'lifetime' ? 'checked' : ''; ?>">
							<span class="wn-radio-indicator <?php echo $purchase_type == 'lifetime' ? 'checked' : ''; ?>"></span>
							<?php esc_html_e( 'Lifetime Access', 'deep' ); ?>
						</label>
					</div>
				</div>
				<div id="wnGetLicense">
					<input placeholder="<?php echo esc_html__( 'Put your purchase code here', 'deep' ); ?>" id="wnPurchaseCode" name="deep_purchase" type="text" value="<?php echo $purchase_code; ?>">
					<input type="submit" class="wsw-btn">
					<i class="wn-fa wn-fa-check" aria-hidden="true"></i>
					<i class="wn-fa wn-fa-times" aria-hidden="true"></i>
					<div id="wnFailedMesaage"></div>
				</div>
			</form>

			<div class="wsw-btn-step">
				<a href="#" class="wsw-btn" data-step="2" data-step-type="next"><?php esc_html_e( 'Next Step', 'deep' ); ?></a>
				<a id="wswReturnDashboard" href="<?php echo admin_url( 'admin.php?page=wn-admin-welcome' ); ?>"><?php esc_html_e( 'Return to the Dashboard', 'deep' ); ?></a>
			</div>
			<?php
		}

		/**
		 * Select desired template.
		 *
		 * @since   1.0.0
		 * @access  private
		 */
		private function select_template_step() {

			if ( class_exists( 'ReduxFramework_extension_wbc_importer' ) ) :
				$wbc_importer     = ReduxFramework_extension_wbc_importer::get_instance();
				$tgmpa_list_table = new TGMPA_List_Table();
				$plugins          = TGM_Plugin_Activation::$instance->plugins;
				$wbc_demo_imports = $wbc_importer->wbc_import_files;
				$nonce            = wp_create_nonce( 'redux_deep_options_wbc_importer' );
				$output           = '';

				if ( ! empty( $wbc_importer->demo_data_dir ) ) {
					$demo_data_dir = $wbc_importer->demo_data_dir;
					$demo_data_url = deep_ssl() . 'deeptem.com/deep-downloads/demo-data/';
				}

				if ( ! empty( $wbc_demo_imports ) ) {
					$output .= '<div class="wsw-select-importer">';
					$i       = 0;
					foreach ( $wbc_demo_imports as $demo_id => $demo_data ) :
						$i++;
						if ( empty( $demo_data ) ) {
							continue;
						}
						if ( array_key_exists( 'imported', $demo_data ) ) {
							$imported_class = ' wsw-imported';
							$button         = '<a class="wsw-importer-preview" href="#">' . esc_html__( 'Imported', 'deep' ) . '</a>';
						} else {
							$imported_class = '';
							$button         = '<a class="wsw-importer-preview" href="' . $demo_data['preview'] . '" target="_blank">' . esc_html__( 'Preview', 'deep' ) . '</a>';
						}

						$output .= '
						<div class="wsw-importer-demo wrap-importer' . esc_attr( $imported_class ) . '" data-demo-id="' . esc_attr( $demo_id ) . '"  data-nonce="' . $nonce . '">
							<div class="wsw-importer-screenshot">';
						if ( isset( $demo_data['image'] ) ) {
							$output .= '<img class="wsw-importer-image" src="' . esc_attr( esc_url( $demo_data_url . $demo_data['directory'] . '/' . $demo_data['image'] ) ) . '">';
						}
								$output .= '
							</div>  <!-- end theme-screenshot -->
							<h3 class="wsw-theme-name"><input type="checkbox" name="theme-name" value="' . esc_attr( $demo_id ) . '">' . esc_html( $demo_data['display'] ) . '</h3>
							' . $button . '
						</div>';

						// importer settings
						$output .= $this->fetch_importer_settings( $demo_id, $demo_data, $plugins, $tgmpa_list_table, $i, $demo_data_url );
					endforeach;
					$output .= '</div>';
					echo $output;
				} else {
					echo '<h5>' . esc_html__( 'No Demo Data Provided', 'deep' ) . '</h5>';
				}
			endif;
			?>
			<div class="wsw-btn-step">
				<a href="#" class="wsw-btn" data-step="1" data-step-type="back"><?php esc_html_e( 'Back Step', 'deep' ); ?></a>
				<a href="#" class="wsw-btn" data-step="3" data-step-type="next"><?php esc_html_e( 'Next Step', 'deep' ); ?></a>
				<a id="wswReturnDashboard" href="<?php echo admin_url( 'admin.php?page=wn-admin-welcome' ); ?>"><?php esc_html_e( 'Return to the Dashboard', 'deep' ); ?></a>
			</div>
			<?php
		}

		/**
		 * Importer settings.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function fetch_importer_settings( $demo_id, $demo_data, $plugins, $tgmpa_list_table, $i, $demo_data_url ) {
			$output = '
			<div class="wn-lightbox-wrap wp-clearfix" data-demo-id="' . esc_attr( $demo_id ) . '">
				<div class="wn-lightbox-contents">
					<div class="wn-lightbox" data-demo-id="' . esc_attr( $demo_id ) . '">
						<div class="wn-lb-content wni-settings">
							' . $this->choose_page_builder( $demo_id ) . '
							' . $this->install_plugins( $demo_id, $plugins, $tgmpa_list_table ) . '
							' . $this->import_template( $demo_id, $i ) . '
						</div>
						' . $this->ready( $demo_data, $demo_data_url ) . '
					</div>
				</div>
			</div>';

			return $output;
		}

		/**
		 * Choose page builder.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function choose_page_builder( $demo_id ) {
			$theme = wp_get_theme()->name;
			if ( $theme == 'Deep' ) {
				return '
				<div class="wnl-row">
					<h3>' . esc_html__( 'Choose page builder', 'deep' ) . '</h3>
					<div class="wn-pagebuilder-wrap">
						<div class="wn-radio-wrap">
							<label class="wn-radio-control elementor-builder checked" for="elementor-' . esc_attr( $demo_id ) . '">
								<input type="radio" id="elementor-' . esc_attr( $demo_id ) . '" name="pagebuilder" value="elementor">
								<span class="wn-radio-indicator checked"></span>
								' . esc_html__( 'Elementor', 'deep' ) . '
							</label>
						</div>
						<div class="wn-radio-wrap">
							<label class="wn-radio-control kc-builder" for="kingcomposer-' . esc_attr( $demo_id ) . '">
								<input type="radio" id="kingcomposer-' . esc_attr( $demo_id ) . '" name="pagebuilder" value="kingcomposer">
								<span class="wn-radio-indicator"></span>
								' . esc_html__( 'King Composer', 'deep' ) . '
							</label>
						</div>
						<div class="wn-radio-wrap">
							<label class="wn-radio-control vc-builder" for="visualcomposer-' . esc_attr( $demo_id ) . '">
								<input type="radio" id="visualcomposer-' . esc_attr( $demo_id ) . '" name="pagebuilder" value="visualcomposer" checked>
								<span class="wn-radio-indicator"></span>
								' . esc_html__( 'WPBakery Page Builder', 'deep' ) . '
							</label>
						</div>
					</div>
				</div>';
			} else {
				return '
				<div class="wnl-row">
					<h3>' . esc_html__( 'Choose page builder', 'deep' ) . '</h3>
					<div class="wn-pagebuilder-wrap">
						<div class="wn-radio-wrap">
							<label class="wn-radio-control elementor-builder checked" for="elementor-' . esc_attr( $demo_id ) . '">
								<input type="radio" id="elementor-' . esc_attr( $demo_id ) . '" name="pagebuilder" value="elementor" checked>
								<span class="wn-radio-indicator checked"></span>
								' . esc_html__( 'Elementor', 'deep' ) . '
							</label>
						</div>
						<div class="wn-radio-wrap wn-gopro">
							<label class="wn-radio-control vc-builder" for="visualcomposer-' . esc_attr( $demo_id ) . '">
								<input type="radio" disabled id="visualcomposer-' . esc_attr( $demo_id ) . '" name="pagebuilder" value="visualcomposer">
								<span class="wn-radio-indicator"></span>
								' . esc_html__( 'WPBakery Page Builder', 'deep' ) . '
							</label>
						</div>
					</div>
				</div>';
			}
		}

		/**
		 * Install required plugins.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function install_plugins( $demo_id, $plugins, $tgmpa_list_table ) {
			$output              = '
			<div class="wnl-row">
				<div class="whi-install-plugins-wrap">
					<h3>' . esc_html__( 'The plugins below are required', 'deep' ) . '</h3>
					<a href="#" class="wn-admin-btn wsw-btn whi-install-plugins">Activate all plugins</a>
				</div>
				<div class="wn-plugins-wrap wn-plugins">';
					$req_plugins = array();
					$keys        = deep_demo_plugins( $demo_id );

			foreach ( $keys as $key ) :
				if ( array_key_exists( $key, $plugins ) ) {
					$req_plugins[ $key ] = $plugins[ $key ];
				}
					endforeach;

			foreach ( $req_plugins as $plugin ) :
				$plugin['type']             = isset( $plugin['type'] ) ? $plugin['type'] : 'recommended';
				$plugin['sanitized_plugin'] = $plugin['name'];

				$plugin_action = $tgmpa_list_table->actions_plugin( $plugin );

				if ( is_plugin_active( $plugin['file_path'] ) ) {
					$plugin_action = '
							<div class="row-actions visible active">
								<span class="activate">
									<a class="button wn-admin-btn wsw-btn">' . esc_html__( 'Activated', 'deep' ) . '</a>
								</span>
							</div>';
				}

				$output .= '
						<div class="wn-plugin wp-clearfix" data-plugin-name="' . esc_html( $plugin['name'] ) . '">
							<h4>' . esc_html( $plugin['name'] ) . '</h4>
							<span class="wn-plugin-line"></span>
							' . $plugin_action . '
						</div>';
					endforeach;

				$output .= '
				</div> <!-- end wn-plugins-wrap -->
			</div>';

			return $output;
		}

		/**
		 * Import desired template.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function import_template( $demo_id, $i ) {
			$post_types_str   = '';
			$post_types_array = deep_importer_post_types( $demo_id );

			foreach ( $post_types_array as $post_type ) :
				$post_types_str .= '
				<div class="wn-checkbox-wrap">
					<input type="checkbox" class="wn-checkbox-input" id="' . esc_attr( sanitize_title( $post_type ) . $i ) . '" name="importcontent" value="' . esc_attr( sanitize_title( $post_type ) ) . '">
					<label for="' . esc_attr( sanitize_title( $post_type ) . $i ) . '" class="wn-checkbox-label"></label>
					<span>' . esc_attr( ucfirst( $post_type ) ) . '</span>
				</div>';
			endforeach;

			$output = '
			<div class="wnl-row">
				<!-- <h3>' . esc_html__( 'Import content', 'deep' ) . ' <span>' . esc_html__( '(menus only import by selecting "All")', 'deep' ) . '</span></h3> -->
				<h3>' . esc_html__( 'Import content', 'deep' ) . '</h3>
				<div class="wn-import-content-wrap wp-clearfix">
					<div class="wn-checkbox-wrap wn-all-contents">
						<input type="checkbox" class="wn-checkbox-input" id="all' . esc_attr( $i ) . '" name="importcontent" value="all">
						<label for="all' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
						<span>' . esc_html__( 'All', 'deep' ) . '</span>
					</div>
					' . $post_types_str . '
					<div class="wn-checkbox-wrap">
						<input type="checkbox" class="wn-checkbox-input" id="images' . esc_attr( $i ) . '" name="importcontent" value="images">
						<label for="images' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
						<span>' . esc_html__( 'Images', 'deep' ) . '</span>
					</div>
					<div class="wn-checkbox-wrap">
						<input type="checkbox" class="wn-checkbox-input" id="widgets' . esc_attr( $i ) . '" name="importcontent" value="widgets">
						<label for="widgets' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
						<span>' . esc_html__( 'Widgets', 'deep' ) . '</span>
					</div>
					<div class="wn-checkbox-wrap">
						<input type="checkbox" class="wn-checkbox-input" id="themeoptions' . esc_attr( $i ) . '" name="importcontent" value="themeoptions">
						<label for="themeoptions' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
						<span>' . esc_html__( 'Theme options', 'deep' ) . '</span>
					</div>
					<div class="wn-checkbox-wrap">
						<input type="checkbox" class="wn-checkbox-input" id="sliders' . esc_attr( $i ) . '" name="importcontent" value="sliders">
						<label for="sliders' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
						<span>' . esc_html__( 'Sliders', 'deep' ) . '</span>
					</div>
					<div class="wn-checkbox-wrap">
						<input type="checkbox" class="wn-checkbox-input" id="header' . esc_attr( $i ) . '" name="importcontent" value="header">
						<label for="header' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
						<span>' . esc_html__( 'Header', 'deep' ) . '</span>
					</div>
				</div>
			</div>';

			$output .= '
			<div class="wnl-row">
				<a href="#" class="wn-import-demo-btn">' . esc_html__( 'Import', 'deep' ) . '</a>
			</div>';

			return $output;
		}

		/**
		 * Ready step.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function ready( $demo_data, $demo_data_url ) {
			return '
			<div class="wn-suc-imp-title"><strong>' . esc_html( $demo_data['directory'] ) . '</strong></div>
			<div class="wn-lb-content wn-suc-imp-content-wrap">
				<div class="wn-suc-imp-content">
					<a href="' . esc_url( home_url( '/' ) ) . '" target="_blank" title="' . esc_html__( 'Visit Site', 'deep' ) . '">
						<img class="wbc_image" src="' . esc_attr( esc_url( $demo_data_url . $demo_data['directory'] . '/' . $demo_data['image'] ) ) . '">
					</a>
					<div class="wn-suc-imp-t100"><strong>% 100</strong>' . esc_html__( 'Demo imported successfully', 'deep' ) . '</div>
					<div class="wn-suc-imp-links">
						<a class="wn-suc-imp-links-d" href="' . esc_url( self_admin_url( 'admin.php?page=wn-admin-welcome' ) ) . '">' . esc_html__( 'Deep Dashboard', 'deep' ) . '</a>
						<a class="wn-suc-imp-links-t" href="' . esc_url( self_admin_url( 'admin.php?page=webnus_theme_options' ) ) . '">' . esc_html__( 'Theme Options', 'deep' ) . '</a>
						<a class="wn-suc-imp-links-l" href="' . esc_url( self_admin_url( 'admin.php?page=wn-admin-video-tutorial' ) ) . '">' . esc_html__( 'Tutorials', 'deep' ) . '</a>
						<a class="wn-suc-imp-links-v" href="' . esc_url( home_url( '/' ) ) . '" target="_blank">' . esc_html__( 'Visit Site', 'deep' ) . '</a>
					</div>
					<div class="wni-start">
						<div class="wbc-progress-back">
							<div class="wbc-progress-bar button-primary" style="width: 0%;">
								<span class="wbc-progress-count">0%</span>
							</div>
						</div>
						<span class="wni-start-message">' . esc_html__( 'Please do not refresh the page until import is complete. The time it takes to import depends on your host configuration and it may take up to 15 minutes, so please be patient.', 'deep' ) . '</span>
					</div>
				</div>
			</div>';
		}

		/**
		 * Show the setup wizard.
		 *
		 * @access  public
		 * @param   null
		 * @return  void
		 */
		public function setup_wizard() {
			ob_start();
			?>
			<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title><?php esc_html_e( 'Setup Wizard', 'woocommerce' ); ?></title>
				<script  >
					var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
				</script>
				<?php do_action( 'admin_print_styles' ); ?>
				<?php do_action( 'admin_head' ); ?>
			</head>
			<body>
				<?php
				$step         = isset( $_GET['step'] ) ? (int) $_GET['step'] : 1;
				$active_class = 'class="active"';
				?>
				<div id="wnSetupWizard" class="wn-setup-wizard wn-sw-step<?php echo $step; ?>">
					<div class="wsw-logo">
						<img src="<?php echo esc_url( DEEP_ASSETS_URL . 'images/deep-wizard-logo.png' ); ?>" alt="<?php esc_html_e( 'Logo', 'deep' ); ?>">
					</div>
						<ul class="wsw-menu wsw-clearfix">
							<?php
							if ( ! defined( 'DEEPFREE' ) ) {
								?>
							<li class="active"><?php esc_html_e( 'Purchase Code', 'deep' ); ?></li>
							<li <?php echo $step > 1 ? $active_class : ''; ?>><?php esc_html_e( 'Select Demo', 'deep' ); ?></li>
							<li <?php echo $step > 2 ? $active_class : ''; ?>><?php esc_html_e( 'Choose Page Builder', 'deep' ); ?></li>
							<li <?php echo $step > 3 ? $active_class : ''; ?>><?php esc_html_e( 'Install Plugins', 'deep' ); ?></li>
							<li <?php echo $step > 4 ? $active_class : ''; ?>><?php esc_html_e( 'Import', 'deep' ); ?></li>
							<li <?php echo $step > 5 ? $active_class : ''; ?>><?php esc_html_e( 'Ready!', 'deep' ); ?></li>
							<?php } else { ?>
							<li <?php echo $step >= 1 ? $active_class : ''; ?>><?php esc_html_e( 'Select Demo', 'deep' ); ?></li>
							<li <?php echo $step > 2 ? $active_class : ''; ?>><?php esc_html_e( 'Choose Page Builder', 'deep' ); ?></li>
							<li <?php echo $step > 3 ? $active_class : ''; ?>><?php esc_html_e( 'Install Plugins', 'deep' ); ?></li>
							<li <?php echo $step > 4 ? $active_class : ''; ?>><?php esc_html_e( 'Import', 'deep' ); ?></li>
							<li <?php echo $step > 5 ? $active_class : ''; ?>><?php esc_html_e( 'Ready!', 'deep' ); ?></li>
							<?php } ?>
						</ul>

					<div class="wsw-contents wsw-clearfix">
						<?php
						if ( ! defined( 'DEEPFREE' ) ) {
							switch ( $step ) {
								case '1':
									$this->purchase_code_step();
									break;
								case '2':
									$this->select_template_step();
									break;
							}
						} else {
							switch ( $step ) {
								case '1':
								case '2':
									$this->select_template_step();
									break;
							}
						}
						?>
					</div>

					<?php
					// enqueue jQuery scripts
					wp_print_scripts( 'deep-admin-plugins' );
					// wp_print_scripts( 'redux-field-wbc-importer-js' );
					wp_print_scripts( 'one-demo-importer' );
					wp_print_scripts( 'deep-setup-wizard' );
					?>
				</div>
			</body>
			</html>
			<?php
			exit;
		}

	}
	// Run
	WN_Setup_Wizard::get_instance();
endif;
