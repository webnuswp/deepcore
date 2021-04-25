<?php
/**
 * Plugin Name: Addons for Elementor
 * Plugin URI: https://www.livemeshthemes.com/elementor-addons
 * Description: A collection of premium quality addons or widgets for use in Elementor page builder. Elementor must be installed and activated.
 * Author: Livemesh
 * Author URI: https://www.livemeshthemes.com/elementor-addons
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 1.6
 */

namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class Webnus_Element_Widgets_Piecharts extends \Elementor\Widget_Base{

    public function get_name() {
        return 'wn_piecharts';
    }

    public function get_title() {
        return __('Webnus Piecharts', 'deep');
    }

    public function get_icon() {
        return 'eicon-counter-circle';
    }

    public function get_categories() {
        return array('webnus');
    }

    public function get_script_depends() {
        return [
            'wn-pie-cart',
            'wn-jquery-stats'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_piecharts',
            [
                'label' => __('Piecharts', 'deep'),
            ]
        );

        $this->add_control(
            'per_line',
            [
                'label' => __('Piecharts per row', 'deep'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 2,
            ]
        );
        $this->add_control(
            'chart_size',
            [
                'label' 		=> __( 'Size', 'deep' ), //heading
                'type' 			=> \Elementor\Controls_Manager::TEXT, //type
                'placeholder'	=> esc_html__( 'Example: 327', 'deep'),
                'admin_label' 	=> true,
                'default' => 375,
            ]
        );


        $this->add_control(
            'piecharts',
            [
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'stats_title' => __('Web Design', 'deep'),
                        'percentage_value' => 87,
                    ],
                    [
                        'stats_title' => __('SEO Services', 'deep'),
                        'percentage_value' => 76,
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'stats_title',
                        'label' => __('Stats Title', 'deep'),
                        'type' => Controls_Manager::TEXT,
                        'description' => __('The title for the piechart', 'deep'),
                        'dynamic' => [
                            'active' => true,
                        ],
                    ],
                    [
                        'name' => 'percentage_value',
                        'label' => __('Percentage Value', 'deep'),
                        'type' => Controls_Manager::NUMBER,
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                        'default' => 30,
                        'description' => __('The percentage value for the stats.', 'deep'),
                    ],

                ],
                'title_field' => '{{{ stats_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_styling',
            [
                'label' => __('Piechart Styling', 'deep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_color',
            [
                'label' => __('Bar color', 'deep'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f94213',
            ]
        );


        $this->add_control(
            'track_color',
            [
                'label' => __('Track color', 'deep'),
                'type' => Controls_Manager::COLOR,
                'default' => '#dddddd',
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_stats_title',
            [
                'label' => __('Stats Title', 'deep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stats_title_color',
            [
                'label' => __('Color', 'deep'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wn-piechart .wn-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stats_title_typography',
                'selector' => '{{WRAPPER}} .wn-piechart .wn-label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_stats_percentage',
            [
                'label' => __('Stats Percentage', 'deep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stats_percentage_color',
            [
                'label' => __('Color', 'deep'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wn-piechart .wn-percentage span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stats_percentage_typography',
                'selector' => '{{WRAPPER}} .wn-piechart .wn-percentage span',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_stats_percentage_symbol',
            [
                'label' => __('Stats Percentage Symbol', 'deep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stats_percentage_symbol_color',
            [
                'label' => __('Color', 'deep'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wn-piechart .wn-percentage sup' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stats_percentage_symbol_typography',
                'selector' => '{{WRAPPER}} .wn-piechart .wn-percentage sup',
            ]
        );

        $this->end_controls_section();
        
        // Custom css tab
		$this->start_controls_section(
			'custom_css_section',
			[
				'label' => __( 'Custom CSS', 'deep' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'custom_css',
			[
				'label' => __( 'Custom CSS', 'deep' ),
				'type' => \Elementor\Controls_Manager::CODE,
				'language' => 'css',
				'rows' => 20,
				'show_label' => true,
			]
		);

		$this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        ?>

        <?php
        if ( $settings['per_line'] == '1' ) {

            $column_style = '';

        } elseif ( $settings['per_line'] == '2' ) {

            $column_style = 'col-md-6';

        } elseif ( $settings['per_line'] == '3' ) {

            $column_style = 'col-md-4';

        } elseif ( $settings['per_line'] == '4' ) {

            $column_style = 'col-md-3';

        }
        ?>

        <?php

        $bar_color      = ' data-bar-color="' . esc_attr($settings['bar_color']) . '"';
        $track_color    = ' data-track-color="' . esc_attr($settings['track_color']) . '"';
        $chart_size     = ' data-size ="' . esc_attr($settings['chart_size']) . '"';

        $custom_css = $settings['custom_css'];

		if ( $custom_css != '' ) {
			echo '<style>'. $custom_css .'</style>';
		}
        
        ?>

        <div class="wn-piecharts wn-grid-container">

            <?php foreach ($settings['piecharts'] as $piechart): ?>

                <div class="wn-piechart <?php echo $column_style; ?>">

                    <div class="wn-percentage" <?php echo $bar_color . $track_color . $chart_size; ?>
                         data-percent="<?php echo round($piechart['percentage_value']); ?>">

                        <span><?php echo round($piechart['percentage_value']); ?><sup>%</sup></span>

                    </div>

                    <h4 class="wn-label"><?php echo esc_html($piechart['stats_title']); ?></h4>

                </div>

                <?php

            endforeach;

            ?>

        </div>

        <div class="wn-clear"></div>

        <?php
    }

    protected function content_template() {
    }

}   