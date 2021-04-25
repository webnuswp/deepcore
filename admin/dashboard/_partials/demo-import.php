<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap about-wrap wn-admin-wrap">

	<h1><?php echo esc_html__( 'Welcome to ', 'deep' ) . Deep_Admin::theme( 'name' ); ?></h1>
	<div class="about-text"><?php echo Deep_Admin::theme( 'name' ) . esc_html__( ' is now installed and ready to use! Let’s convert your imaginations to reality on the web!', 'deep' ); ?></div>
	<div class="wp-badge"><?php printf( esc_html__( 'Version %s', 'deep' ), Deep_Admin::theme( 'version' ) ); ?></div>
	<?php do_action( 'deep_before_start_dashboard' ); ?>
	<h2 class="nav-tab-wrapper wp-clearfix">
		<?php
		// Dashboard Menu
		Deep_Admin::dashboard_menu();
		?>
	</h2>

	<?php
		// system info message
		$execution_time     = ini_get( 'max_execution_time' );
		$max_execution_time = ini_get( 'max_execution_time' ) < 180 ? esc_html__( '"PHP Maximum Execution Time"', 'deep' ) : '';
		$max_input_vars     = ini_get( 'max_input_vars' ) < 1620 ? esc_html__( '"PHP Maximum Input Vars"', 'deep' ) : '';
		if ( $max_execution_time || $max_input_vars ) {
			if ( $max_execution_time && $max_input_vars ) {
				$and_str   = esc_html__( ' and ', 'deep' );
				$value_str = esc_html__( ' values of ', 'deep' );
			} else {
				$and_str   = '';
				$value_str = esc_html__( ' value of ', 'deep' );
			}
			echo '
				<div id="wni-bad-status-message">
					<p>' . esc_html__( 'The configuration', 'deep' ) . $value_str . $max_execution_time . $and_str . $max_input_vars . esc_html__( ' should be increased in your host. Otherwise, you may face these issues:', 'deep' ) . '</p>
					<ul>
						<li>' . esc_html__( 'Duplicating or repeating menu', 'deep' ) . '</li>
						<li>' . esc_html__( 'The RevSlider plugin will not install completely and the slider in the demo will not be imported', 'deep' ) . '</li>
						<li>' . esc_html__( 'For large sized demos such as "Shop", it will take a lot of time to install', 'deep' ) . '</li>
					</ul>
					<div class="btn-wrap">
						<a class="importer-button" href="' . esc_url( self_admin_url( 'admin.php?page=wn-admin-welcome' ) ) . '#wSystemStatus">' . esc_html__( 'How to fix it', 'deep' ) . '</a>
						<a class="importer-button import-risk-btn" href="#">' . esc_html__( 'Let\'s go, I take the risk', 'deep' ) . '</a>
					</div>
				</div>';
		}

		// already imported Message
		$wbc_importer         = ReduxFramework_extension_wbc_importer::get_instance();
		$wbc_demo_imports     = $wbc_importer->wbc_import_files;
		$already_imported_msg = '';
		$output               = '';
		$already_imported     = array();

		// Get imported demos
		for ( $i = 1; $i < sizeof( $wbc_demo_imports ); $i++ ) {
			if ( array_key_exists( 'imported', $wbc_demo_imports[ 'wbc-import-' . $i ] ) ) {
				$already_imported[ $i ] = $wbc_demo_imports[ 'wbc-import-' . $i ]['directory'];
			}
		}
		// Show Message
		if ( ! empty( $already_imported ) ) {
			$already_imported_msg     .= '<div id="wni-bad-status-message" class="yhalready-demo-wrap">';
				$already_imported_msg .= '<p class="yhalready-demo">' . esc_html__( 'You have imported the demo(s)', 'deep' );
				$i                     = 0;
			foreach ( $already_imported as $value ) {
				$i++;
				$separator             = ( $i < sizeof( $already_imported ) ) ? ',' : '';
				$already_imported_msg .= ' <strong>' . $value . '</strong>' . $separator . ' ';
			}
			$already_imported_msg 	.= esc_html__( 'before. Please note that importing demos and overriding them, can mess up your site.', 'deep' ) . '</p>';
			$already_imported_msg 	.= '<a class="importer-button import-risk-btn" href="#">' . esc_html__( 'Let\'s go, I take the risk', 'deep' ) . '</a>';
			$already_imported_msg	.= '</div>';
		}

		?>

		<div class="wbc_importer wn-theme-browser-wrap">
			<div class="theme-browser rendered wp-clearfix">
				<div class="themes wp-clearfix">
				<?php
					$wbc_importer     = ReduxFramework_extension_wbc_importer::get_instance();
					$tgmpa_list_table = new TGMPA_List_Table();
					$plugins          = TGM_Plugin_Activation::$instance->plugins;

				if ( ! empty( $wbc_importer->demo_data_dir ) ) {
					$demo_data_dir = $wbc_importer->demo_data_dir;
					$demo_data_url = deep_ssl() . 'deeptem.com/deep-downloads/demo-data/';
				}

					$nonce    = wp_create_nonce( 'redux_deep_options_wbc_importer' );
					$imported = false;

				if ( ! empty( $wbc_demo_imports ) ) {
					$i = '0';

					foreach ( $wbc_demo_imports as $section => $imports ) :
						$i++;
						if ( empty( $imports ) ) {
							continue;
						}

						if ( ! array_key_exists( 'imported', $imports ) ) {
							$imported       = false;
							$extra_class    = 'not-imported';
							$import_message = esc_html__( 'Import Demo', 'deep' );
						} else {
							$imported       = true;
							$extra_class    = 'active imported';
							$import_message = esc_html__( 'Demo Imported', 'deep' );
						}

						$output .= '
						<div class="wrap-importer theme ' . $extra_class . '" data-demo-id="' . esc_attr( $section ) . '"  data-nonce="' . $nonce . '">
							<div class="theme-screenshot">';

						if ( isset( $imports['image'] ) ) {
							$output .= '<img class="wbc_image" src="' . esc_attr( esc_url( $demo_data_url . $imports['directory'] . '/' . $imports['image'] ) ) . '">';
						}

						$output .= '
						</div>  <!-- end theme-screenshot -->
						<a class="more-details" href="' . $imports['preview'] . '" target="_blank">' . esc_html__( 'Preview', 'deep' ) . '</a>
						<h3 class="theme-name">' . esc_html( apply_filters( 'wbc_importer_directory_title', $imports['display'] ) ) . '</h3>
						<div class="theme-actions">';

						if ( $imported == false ) {
							$output .= '
							<div class="wbc-importer-buttons">
								<span class="spinner">' . esc_html__( 'Please Wait...', 'deep' ) . '</span>
								<span class="button button-primary importer-button import-demo-data">' . esc_html__( 'Import', 'deep' ) . '</span>
							</div>';
						} else {
							$output .= '
									<div class="wbc-importer-buttons button-secondary importer-button">' . esc_html__( 'Imported', 'deep' ) . '</div>
									<span class="spinner">' . esc_html__( 'Please Wait...', 'deep' ) . '</span>
									<div id="wbc-importer-reimport" class="wbc-importer-buttons button-primary import-demo-data importer-button">' . esc_html__( 'Re-Import', 'deep' ) . '</div>';
						}

						$output .= '
									</div> <!-- end theme-actions -->
								</div>';

						// lightbox
						$output .= '
							<div class="wn-lightbox-wrap wp-clearfix">
								<div class="wn-lightbox-contents">
									<i class="ti-close"></i>
									<div class="wn-lightbox" data-demo-id="' . esc_attr( $section ) . '">
										<h2>' . esc_html( apply_filters( 'wbc_importer_directory_title', $imports['directory'] ) ) . '</h2>
										<div class="wn-lb-content wni-settings">';

						
						$ispro   = defined( 'DEEPFREE' ) ? ' wn-gopro' : '';
						$output .= '
						<div class="wnl-row">
							<h3>' . esc_html__( 'Choose page builder', 'deep' ) . '</h3>
							<div class="wn-pagebuilder-wrap">
								<div class="wn-radio-wrap">
									<label class="wn-radio-control elementor-builder checked" for="elementor-' . esc_attr( $section ) . '">
										<input type="radio" id="elementor-' . esc_attr( $section ) . '" name="pagebuilder" value="elementor" checked>
										<span class="wn-radio-indicator checked"></span>
										' . esc_html__( 'Elementor', 'deep' ) . '
									</label>
								</div>
								<div class="wn-radio-wrap">
									<label class="wn-radio-control vc-builder' . esc_attr( $ispro ) . '" for="visualcomposer-' . esc_attr( $section ) . '">
										<input type="radio" disabled id="visualcomposer-' . esc_attr( $section ) . '" name="pagebuilder" value="visualcomposer">
										<span class="wn-radio-indicator"></span>
										' . esc_html__( 'WPBakery (VC)', 'deep' ) . '
									</label>
								</div>
							</div>
							<input id="demo" name="demo" type="hidden" value="'.apply_filters( 'wbc_importer_directory_title', $imports['directory'] ).'">
						</div>';


						
						$output .= '
						<div class="wnl-row">
							<div class="whi-install-plugins-wrap">
								<h3>' . esc_html__( 'The plugins below are required', 'deep' ) . '</h3>
								<a href="#" class="wn-admin-btn whi-install-plugins">Activate all plugins</a>
							</div>
							<div class="wn-plugins-wrap wn-plugins">';
					
						$req_plugins = array();
						$keys        = deep_demo_plugins( $section );

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
										<a class="button wn-admin-btn">' . esc_html__( 'Activated', 'deep' ) . '</a>
									</span>
								</div>';
							}
							
							$output .= '<div class="wn-plugin wp-clearfix" data-plugin-name="' . esc_html( $plugin['name'] ) . '">';
							$output .= '<h4>' . esc_html( $plugin['name'] ) . '</h4>';
							$output .= '<span class="wn-plugin-line"></span>';
							$output .= $plugin_action;
							$output .= '</div>';
						
						endforeach;

						$post_types_str   = '';
						$post_types_array = deep_importer_post_types( $section );

						foreach ( $post_types_array as $post_type ) {
							$post_types_str .= '
							<div class="wn-checkbox-wrap">
								<input type="checkbox" class="wn-checkbox-input" id="' . esc_attr( sanitize_title( $post_type ) . $i ) . '" name="importcontent" value="' . esc_attr( sanitize_title( $post_type ) ) . '">
								<label for="' . esc_attr( sanitize_title( $post_type ) . $i ) . '" class="wn-checkbox-label"></label>
								<span>' . esc_attr( ucfirst( $post_type ) ) . '</span>
							</div>';
						}

						
						$output .= '
							</div> <!-- end wn-plugins-wrap -->
						</div>';
					

						
						$output .= '
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
									<input type="checkbox" class="wn-checkbox-input" id="header' . esc_attr( $i ) . '" name="importcontent" value="header">
									<label for="header' . esc_attr( $i ) . '" class="wn-checkbox-label"></label>
									<span>' . esc_html__( 'Header', 'deep' ) . '</span>
								</div>
							</div>
						</div>';
						

						
						$output .= '
						<div class="wnl-row import-btn-row">
							<div class="wbc-progress-back">
								<div class="wbc-progress-bar button-primary" style="width: 0%;">
									<span class="wbc-progress-count">0%</span>
								</div>
							</div>
							<p id="w-importing">Before you begin, make sure all the required plugins are activated.</p>
							<a href="#" class="wn-import-demo-btn">' . esc_html__( 'Import', 'deep' ) . '</a>
						</div>';
						

										$output .= '
										</div>
										<div class="wn-suc-imp-title"><strong>' . esc_html( apply_filters( 'wbc_importer_directory_title', $imports['directory'] ) ) . '</strong></div>
										<div class="wn-lb-content wn-suc-imp-content-wrap">
											<div class="wn-suc-imp-content">
												<a href="' . esc_url( home_url( '/' ) ) . '" target="_blank" title="' . esc_html__( 'Visit Site', 'deep' ) . '">
													<img class="wbc_image" src="' . esc_attr( esc_url( $demo_data_url . $imports['directory'] . '/' . $imports['image'] ) ) . '">
												</a>
												<div class="wn-suc-imp-t100"><strong>% 100</strong>' . esc_html__( 'Demo imported successfully', 'deep' ) . '</div>
												<div class="wn-suc-imp-links">
													<a class="wn-suc-imp-links-d" href="' . esc_url( self_admin_url( 'admin.php?page=wn-admin-welcome' ) ) . '">' . esc_html__( 'Deep Dashboard', 'deep' ) . '</a>
													<a class="wn-suc-imp-links-t" href="' . esc_url( self_admin_url( 'admin.php?page=webnus_theme_options' ) ) . '">' . esc_html__( 'Theme Options', 'deep' ) . '</a>
													<a class="wn-suc-imp-links-v" href="' . esc_url( home_url( '/' ) ) . '" target="_blank">' . esc_html__( 'Visit Site', 'deep' ) . '</a>
												</div>
												<div class="wni-start">
													<span class="wni-start-message">' . esc_html__( 'Please do not refresh the page until import is complete. The time it takes to import depends on your host configuration and it may take up to 15 minutes, so please be patient.', 'deep' ) . '</span>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>';

						endforeach;
					$output .= $already_imported_msg;
					echo $output;
				} else {
					echo '<h5>' . esc_html__( 'No Demo Data Provided', 'deep' ) . '</h5>';
				}
				?>
				</div>
			</div>
		</div>
</div> <!-- end wrap -->
<?php 