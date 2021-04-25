<?php
// Way to set menu, import revolution slider, set blog page and set home page
if ( ! function_exists( 'deep_wbc_extended' ) ) :

	/**
	 *  Delete Defult mec posts.
	 */
	add_action( 'import_start', 'deep_wbc_mec_options', 10, 2 );

	function deep_wbc_mec_options() {
		$post_type = array(
			'0' => 'mec_calendars',
			'1' => 'mec-events',
		);

		foreach ( $post_type as $key => $value ) {

			$args = array(
				'post_type' => $value,
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				// The 2nd Loop
				while ( $query->have_posts() ) {

					$query->the_post();
					wp_delete_post( $query->post->ID );

				}
				wp_reset_postdata();
			}
		}
	}

	add_action( 'wbc_importer_after_content_import', 'deep_wbc_extended', 10, 4 );
	function deep_wbc_extended( $demo_active_import, $demo_directory_path, $import_sliders, $import_theme_opts ) {

		reset( $demo_active_import );
		$current_key = key( $demo_active_import );

		if ( isset( $demo_active_import[ $current_key ]['directory'] ) && ! empty( $demo_active_import[ $current_key ]['directory'] ) ) :

			/**
			 * Import Header
			 *
			 * @author  WEBNUS
			 * @since   1.0.0
			 */
			$headerFile = $demo_directory_path . 'header.json';
			if ( file_exists( $headerFile ) ) {
				$headerData = file_get_contents( $headerFile );
				$headerData = json_decode( stripslashes( $headerData ), true );
				update_option( 'whb_data_components', $headerData['whb_data_components'] );
				update_option( 'whb_data_editor_components', $headerData['whb_data_editor_components'] );
				update_option( 'whb_data_frontend_components', $headerData['whb_data_frontend_components'] );
			}

			/**
			 * Import theme options
			 *
			 * @since 1.0.0
			 */
			if ( $import_theme_opts ) {
				// Get file contents and decode
				$file = $demo_directory_path . 'theme_options.txt';
				if ( file_exists( $file ) ) {
					$data = file_get_contents( $file );
					$data = json_decode( $data, true );
					$data = maybe_unserialize( $data );
					update_option( 'deep_options', $data );
				}
			}

			/**
			 * Set HomePage
			 *
			 * @since 1.0.0
			 */
			$wbc_home_pages = array(
				// folder name
				'magazine-free'      => 'Home 1 - Magazine',
				'personal-blog-free' => 'Home 1 - Personal Blog',
				'minimal-blog-free'  => '',
				'agency2free'        => 'Home - Agency 2',
			);

			if ( array_key_exists( $demo_active_import[ $current_key ]['directory'], $wbc_home_pages ) ) :
				$home_page = get_page_by_title( $wbc_home_pages[ $demo_active_import[ $current_key ]['directory'] ] );
				if ( isset( $home_page->ID ) ) :
					update_option( 'page_on_front', $home_page->ID );
					update_option( 'show_on_front', 'page' );
				endif;
			endif;

			/**
			 * Select category source for the grid
			 *
			 * @since 1.0.0
			 */

			$the_grid_cats = array(
				'furniture' => '205|gallery_category:5',
			);

			if ( array_key_exists( $demo_active_import[ $current_key ]['directory'], $the_grid_cats ) ) :
				foreach ( $the_grid_cats as $key => $value ) {
					$data = ( explode( '|', $the_grid_cats[ $key ] ) );
					update_post_meta( $data['0'], 'the_grid_categories', $data['1'], '' );
				}
			endif;

			/**
			 * Set BlogPage
			 *
			 * @since 1.0.0
			 */
			$wbc_blog_pages = array(
				// folder name
				'magazine-free'      => 'Blog',
				'personal-blog'      => 'Blog',
				'agency2free'        => 'Blog',
				'minimal-blog-free'  => 'Blog',
			);

			/**
			 * Import MEC posts
			 *
			 * @since 1.0.0
			 */
			if ( is_plugin_active( 'modern-events-calendar-lite/mec.php' ) ) {
				if ( file_exists( $demo_directory_path . 'events.xml' ) ) {
					do_action( 'mec_import_file', $demo_directory_path . 'events.xml' );
				}
			}

			/**
			 * Import menu items icon
			 *
			 * @version 1.0.0
			*/
			if ( file_exists( $demo_directory_path . 'menu_icons.txt' ) ) {

				$file         = $demo_directory_path . 'menu_icons.txt';
				$data         = file_get_contents( $file );
				$demo_icons   = json_decode( $data, true );
				$menus        = wp_get_nav_menus();
				$menus_counts = sizeof( $menus );

				for ( $i = 0; $i < $menus_counts; $i++ ) {

					foreach ( wp_get_nav_menu_items( $menus[ $i ] ) as $menu_item ) {

						update_post_meta(
							$menu_item->ID,
							'_menu_item_icon',
							$demo_icons[ $menu_item->title ],
							''
						);

					}
				}
			}

		endif;

		/**
		 * Update Woocommerce Image Size
		 *
		 * @version 1.0.0
		*/
		if ( class_exists( 'Woocommerce' ) ) {
			update_option( 'woocommerce_thumbnail_cropping', 'uncropped' );
			update_option( 'woocommerce_thumbnail_image_width', '242' );
			update_option( 'woocommerce_single_image_width', '660' );
		}

		/**
		 * Update WP Hotel Booking Image Size
		 *
		 * @version 1.0.0
		*/
		if ( class_exists( 'WP_Hotel_Booking' ) ) {
			update_option( 'tp_hotel_booking_catalog_image_width', '470' );
			update_option( 'tp_hotel_booking_catalog_image_height', '470' );
		}

		/**
		 * Set  permaline on /%postname%/
		 *
		 * @author Webnus
		 * @version 1.0.0
		 */
		function wn_set_permalinks() {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		}
		add_action( 'init', 'wn_set_permalinks' );

		/**
		 * Set  "no" value for beadcrumb
		 *
		 * @author Webnus
		 * @version 1.0.0
		*/
		$args  = array(
			'sort_order'   => 'asc',
			'sort_column'  => 'post_title',
			'hierarchical' => 1,
			'child_of'     => 0,
			'parent'       => -1,
			'post_type'    => 'page',
			'post_status'  => 'publish',
		);
		$pages = get_pages( $args );
		foreach ( $pages as $page ) {
			update_post_meta( $page->ID, 'deep_breadcrumb_meta', 'no' );
		}

	} // end deep_wbc_extended function

endif;

/**
 * Required plugins
 *
 * @author  Webnus
 */
function deep_demo_plugins( $demo_id ) {
		$main_plugins = array(
			'elementor',
			'contact-form-7',
		);
		$plugins_array = array(
			// Agency2
			'wbc-import-1'  => array_merge( $main_plugins, array( 'wp-pagenavi' ) ),
			// Magazine
			'wbc-import-2'  => array_merge( $main_plugins, array( 'post-ratings', 'wp-cloudy', 'wp-pagenavi' ) ),			
			// personal-blog-free
			'wbc-import-3'	=> array_merge( $main_plugins, array( 'wp-pagenavi' ) ),
			// minimal-blog-free
			'wbc-import-4'	=> array_merge( $main_plugins, array( 'wp-pagenavi' ) ),
			// modern-business
			'wbc-import-5'	=> array_merge( $main_plugins, array( '' ) ),
			// conference-free
			'wbc-import-6'	=> array_merge( $main_plugins, array( '' ) ),
			// SPA Free
			'wbc-import-7'	=> array_merge( $main_plugins, array( '' ) ),
			// corporate-free
			'wbc-import-8'	=> array_merge( $main_plugins, array( '' ) ),
			// corporate2-free
			'wbc-import-9'	=> array_merge( $main_plugins, array( '' ) ),
			// events-free
			'wbc-import-10'	=> array_merge( $main_plugins, array( 'modern-events-calendar-lite' ) ),
			// church-free
			'wbc-import-11'	=> array_merge( $main_plugins, array( 'modern-events-calendar-lite' ) ),
			// real-estate-free
			'wbc-import-12'	=> array_merge( $main_plugins, array( '' ) ),
			// freelancer-free
			'wbc-import-13'	=> array_merge( $main_plugins, array( '' ) ),
			// language-school-free
			'wbc-import-14'	=> array_merge( $main_plugins, array( 'modern-events-calendar-lite' ) ),
			// business-free
			'wbc-import-15'	=> array_merge( $main_plugins, array( '' ) ),
			// lawyer-free
			'wbc-import-16'	=> array_merge( $main_plugins, array( '' ) ),
			// dentist-free
			'wbc-import-17'	=> array_merge( $main_plugins, array( '' ) ),
			// startup-free
			'wbc-import-18'	=> array_merge( $main_plugins, array( '' ) ),
			// wedding-free
			'wbc-import-19'	=> array_merge( $main_plugins, array( '' ) ),
			// insurance-free
			'wbc-import-20'	=> array_merge( $main_plugins, array( '' ) ),
			// yoga-free
			'wbc-import-21'	=> array_merge( $main_plugins, array( '' ) ),
			// mechanic-free
			'wbc-import-22'	=> array_merge( $main_plugins, array( '' ) ),
			// portfolio-free
			'wbc-import-23'	=> array_merge( $main_plugins, array( '' ) ),
			// dietitian-free
			'wbc-import-24'	=> array_merge( $main_plugins, array( '' ) ),
			// software-free
			'wbc-import-25'	=> array_merge( $main_plugins, array( '' ) ),
			// beauty-free
			'wbc-import-26'	=> array_merge( $main_plugins, array( '' ) ),
			// consulting-free
			'wbc-import-27'	=> array_merge( $main_plugins, array( '' ) ),
			// crypto-free
			'wbc-import-28'	=> array_merge( $main_plugins, array( '' ) ),
		);
	return $plugins_array[ $demo_id ];
}

/**
 * Post types
 *
 * @author  Webnus
 */
function deep_importer_post_types( $demo_id ) {
	$main_post_types = array( 'pages', 'posts', 'contact forms', );
		$post_types_array = array(
			// Agency2
			'wbc-import-1' => array_merge( $main_post_types, array() ),
			// magazine
			'wbc-import-2' => array_merge( $main_post_types, array() ),
			// personal-blog
			'wbc-import-3' => array_merge( $main_post_types, array() ),
			// minimal-blog-free
			'wbc-import-4' => array_merge( $main_post_types, array() ),
			// modern-business
			'wbc-import-5' => array_merge( $main_post_types, array() ),
			// conference-free
			'wbc-import-6' => array_merge( $main_post_types, array() ),
			// SPA Free
			'wbc-import-7' => array_merge( $main_post_types, array() ),
			// corporate-free
			'wbc-import-8' => array_merge( $main_post_types, array() ),
			// corporate2-free
			'wbc-import-9' => array_merge( $main_post_types, array() ),
			// events-free
			'wbc-import-10' => array_merge( $main_post_types, array() ),
			// church-free
			'wbc-import-11' => array_merge( $main_post_types, array() ),
			// real-estate-free
			'wbc-import-12' => array_merge( $main_post_types, array() ),
			// freelancer-free
			'wbc-import-13' => array_merge( $main_post_types, array() ),
			// language-school-free
			'wbc-import-14' => array_merge( $main_post_types, array() ),
			// business-free
			'wbc-import-15' => array_merge( $main_post_types, array() ),
			// lawyer-free
			'wbc-import-16' => array_merge( $main_post_types, array() ),
			// dentist-free
			'wbc-import-17' => array_merge( $main_post_types, array() ),
			// startup-free
			'wbc-import-18' => array_merge( $main_post_types, array() ),
			// wedding-free
			'wbc-import-19' => array_merge( $main_post_types, array() ),
			// insurance-free
			'wbc-import-20' => array_merge( $main_post_types, array() ),
			// yoga-free
			'wbc-import-21' => array_merge( $main_post_types, array() ),
			// mechanic-free
			'wbc-import-22' => array_merge( $main_post_types, array() ),
			// portfolio-free
			'wbc-import-23' => array_merge( $main_post_types, array() ),
			// dietitian-free
			'wbc-import-24' => array_merge( $main_post_types, array() ),
			// software-free
			'wbc-import-25' => array_merge( $main_post_types, array() ),
			// beauty-free
			'wbc-import-26' => array_merge( $main_post_types, array() ),
			// consulting-free
			'wbc-import-27' => array_merge( $main_post_types, array() ),
			// crypto-free
			'wbc-import-28' => array_merge( $main_post_types, array() ),
		);
	return $post_types_array[ $demo_id ];
}