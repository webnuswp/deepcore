<?php
	// The Grid Skins
	add_filter('tg_add_item_skin', function($deep_thegrid_skins) {
		$PATH = plugin_dir_path( __FILE__ );
		//Grid Skins
		$deep_thegrid_skins['freelancer-1'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'freelancer-1',
			'name'		=> 'Freelancer 1',
			'php'		=> $PATH . 'grid/freelancer-1/freelancer-1.php',
			'css'		=> $PATH . 'grid/freelancer-1/freelancer-1.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['freelancer-2'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'freelancer-2',
			'name'		=> 'Freelancer 2',
			'php'		=> $PATH . 'grid/freelancer-2/freelancer-2.php',
			'css'		=> $PATH . 'grid/freelancer-2/freelancer-2.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-carousel-1'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-carousel-1',
			'name'		=> 'Portfolio Carousel 1',
			'php'		=> $PATH . 'grid/portfolio-carousel-1/portfolio-carousel-1.php',
			'css'		=> $PATH . 'grid/portfolio-carousel-1/portfolio-carousel-1.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-carousel-2'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-carousel-2',
			'name'		=> 'Portfolio Carousel-2',
			'php'		=> $PATH . 'grid/portfolio-carousel-2/portfolio-carousel-2.php',
			'css'		=> $PATH . 'grid/portfolio-carousel-2/portfolio-carousel-2.css',
			'col'		=> 1,
			'row'		=> 1 
		);		
		$deep_thegrid_skins['portfolio-hover-1'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-1',
			'name'		=> 'Portfolio Hover 1',
			'php'		=> $PATH . 'grid/portfolio-hover-1/portfolio-hover-1.php',
			'css'		=> $PATH . 'grid/portfolio-hover-1/portfolio-hover-1.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-2'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-2',
			'name'		=> 'Portfolio Hover 2',
			'php'		=> $PATH . 'grid/portfolio-hover-2/portfolio-hover-2.php',
			'css'		=> $PATH . 'grid/portfolio-hover-2/portfolio-hover-2.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-3'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-3',
			'name'		=> 'Portfolio Hover 3',
			'php'		=> $PATH . 'grid/portfolio-hover-3/portfolio-hover-3.php',
			'css'		=> $PATH . 'grid/portfolio-hover-3/portfolio-hover-3.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-4'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-4',
			'name'		=> 'Portfolio Hover 4',
			'php'		=> $PATH . 'grid/portfolio-hover-4/portfolio-hover-4.php',
			'css'		=> $PATH . 'grid/portfolio-hover-4/portfolio-hover-4.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-5'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-5',
			'name'		=> 'Portfolio Hover 5',
			'php'		=> $PATH . 'grid/portfolio-hover-5/portfolio-hover-5.php',
			'css'		=> $PATH . 'grid/portfolio-hover-5/portfolio-hover-5.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-6'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-6',
			'name'		=> 'Portfolio Hover 6',
			'php'		=> $PATH . 'grid/portfolio-hover-6/portfolio-hover-6.php',
			'css'		=> $PATH . 'grid/portfolio-hover-6/portfolio-hover-6.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-7'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-7',
			'name'		=> 'Portfolio Hover 7',
			'php'		=> $PATH . 'grid/portfolio-hover-7/portfolio-hover-7.php',
			'css'		=> $PATH . 'grid/portfolio-hover-7/portfolio-hover-7.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['portfolio-hover-8'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'portfolio-hover-8',
			'name'		=> 'Portfolio Hover 8',
			'php'		=> $PATH . 'grid/portfolio-hover-8/portfolio-hover-8.php',
			'css'		=> $PATH . 'grid/portfolio-hover-8/portfolio-hover-8.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['crypto'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'crypto',
			'name'		=> 'Crypto',
			'php'		=> $PATH . 'grid/crypto/crypto.php',
			'css'		=> $PATH . 'grid/crypto/crypto.css',
			'col'		=> 1,
			'row'		=> 1 
		);

		// Masonry Skins
		$deep_thegrid_skins['deepshop'] = array(
			'type'		=> 'masonry',
			'filter'	=> 'Deep',
			'slug'		=> 'deepshop',
			'name'		=> 'Deep Shop',
			'php'		=> $PATH . 'masonry/deepshop/deepshop.php',
			'css'		=> $PATH . 'masonry/deepshop/deepshop.css',
			'col'		=> 1,
			'row'		=> 1 
		);
		$deep_thegrid_skins['photography-home'] = array(
			'type'		=> 'masonry',
			'filter'	=> 'Deep',
			'slug'		=> 'photography-home',
			'name'		=> 'Photography Home',
			'php'		=> $PATH . 'masonry/photography-home/photography-home.php',
			'css'		=> $PATH . 'masonry/photography-home/photography-home.css',
			'col'		=> 1,
			'row'		=> 1 
		);

		// Single Portfolio 14
		$deep_thegrid_skins['single-portfolio-14'] = array(
			'type'		=> 'grid',
			'filter'	=> 'Deep',
			'slug'		=> 'single-portfolio-14',
			'name'		=> 'single-portfolio-14',
			'php'		=> $PATH . 'grid/single-portfolio-14/single-portfolio-14.php',
			'css'		=> $PATH . 'grid/single-portfolio-14/single-portfolio-14.css',
			'col'		=> 1,
			'row'		=> 1 
		);


	//Return Skins
	return $deep_thegrid_skins;
	});
?>