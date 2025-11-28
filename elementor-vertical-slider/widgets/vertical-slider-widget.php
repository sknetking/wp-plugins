<?php
if (!defined('ABSPATH')) exit;
Elementor\Plugin::instance()->frontend->enqueue_styles();
Elementor\Plugin::instance()->frontend->enqueue_scripts();
Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, true );


class EVS_Vertical_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'evs_vertical_slider'; }
    public function get_title() { return 'Vertical Slider'; }
    public function get_icon() { return 'eicon-slider-vertical'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('content', [ 'label' => 'Content' ]);

        $this->add_control('query_source', [
            'label' => 'Query Source',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'current'   => 'Current Query',
                'post_type' => 'Post Type',
            ],
            'default' => 'post_type'
        ]);

        // Build post type options (slug => Label)
        $post_type_objects = get_post_types(['public' => true], 'objects');
        $pt_options = [];
        if (!empty($post_type_objects) && is_array($post_type_objects)) {
            foreach ($post_type_objects as $slug => $obj) {
                $pt_options[$slug] = !empty($obj->labels->singular_name) ? $obj->labels->singular_name : $slug;
            }
        } else {
            $pt_options['post'] = 'Post';
        }

        $this->add_control('post_type', [
            'label' => 'Post Type',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $pt_options,
            'default' => key($pt_options)
        ]);

        // Template selector: elementor_library posts (if exists)
        $tpl_options = [ '' => '— Select Template —' ];
        if ( post_type_exists('elementor_library') ) {
            $templates = get_posts([ 'post_type' => 'elementor_library', 'posts_per_page' => -1, 'post_status' => 'publish' ]);
            if ($templates) {
                foreach ($templates as $t) {
                    $tpl_options[$t->ID] = $t->post_title;
                }
            }
        }

        $this->add_control('template_id', [
            'label' => 'Elementor Template',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $tpl_options,
            'description' => 'Template will be used as slide content. If empty, slide will show post title.'
        ]);

        $this->add_control(
			'vs_short_code',
			[
				'label' => esc_html__( 'Short Code', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'placeholder' => esc_html__( '[show_post]', 'textdomain' ),
			]
		);


        $this->end_controls_section();

        // Slider Settings Section
        $this->start_controls_section('slider_settings', [
            'label' => 'Slider Settings',
        ]);

        $this->add_control('slides_to_show', [
            'label' => 'Slides to Show',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 10,
            'step' => 1,
            'default' => 1,
            'frontend_available' => true,
        ]);

        $this->add_control('slides_to_scroll', [
            'label' => 'Slides to Scroll',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 10,
            'step' => 1,
            'default' => 1,
            'frontend_available' => true,
        ]);

        $this->add_control('autoplay', [
            'label' => 'Autoplay',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'frontend_available' => true,
        ]);

        $this->add_control('autoplay_speed', [
            'label' => 'Autoplay Speed (ms)',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1000,
            'max' => 10000,
            'step' => 500,
            'default' => 3000,
            'condition' => [
                'autoplay' => 'yes',
            ],
            'frontend_available' => true,
        ]);

        $this->add_control('speed', [
            'label' => 'Transition Speed (ms)',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 100,
            'max' => 2000,
            'step' => 100,
            'default' => 500,
            'frontend_available' => true,
        ]);

        $this->add_control('infinite', [
            'label' => 'Infinite Loop',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'frontend_available' => true,
        ]);

        $this->add_control('show_arrows', [
            'label' => 'Show Arrows',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'frontend_available' => true,
        ]);

        $this->add_control('show_dots', [
            'label' => 'Show Dots',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'frontend_available' => true,
        ]);

        $this->add_control('pause_on_hover', [
            'label' => 'Pause on Hover',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => [
                'autoplay' => 'yes',
            ],
            'frontend_available' => true,
        ]);

    // Add this in your arrows style section
        $this->add_control('prev_icon', [
            'label' => 'Previous Arrow Icon',
            'type' => \Elementor\Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default' => [
                'value' => 'fas fa-chevron-up',
                'library' => 'fa-solid',
            ]
            
        ]);

        $this->add_control('next_icon', [
            'label' => 'Next Arrow Icon',
            'type' => \Elementor\Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default' => [
                'value' => 'fas fa-chevron-down',
                'library' => 'fa-solid',
            ]
           
        ]);

        $this->end_controls_section();

        // Responsive Settings Section
        $this->start_controls_section('responsive_settings', [
            'label' => 'Responsive Settings',
        ]);

        $this->add_control('tablet_slides', [
            'label' => 'Slides on Tablet',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'step' => 1,
            'default' => 1,
            'frontend_available' => true,
        ]);

        $this->add_control('mobile_slides', [
            'label' => 'Slides on Mobile',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 3,
            'step' => 1,
            'default' => 1,
            'frontend_available' => true,
        ]);

        $this->end_controls_section();

        // Arrows Style Section
        $this->start_controls_section('arrows_style', [
            'label' => 'Arrows Style',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]);

        $this->add_control('arrows_position', [
            'label' => 'Arrows Position',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'outside' => 'Outside',
                'inside'  => 'Inside',
            ],
            'default' => 'outside',
            'frontend_available' => true,
        ]);

        // $this->add_control('arrow_size', [
        //     'label' => 'Arrow Size',
        //     'type' => \Elementor\Controls_Manager::SLIDER,
        //     'size_units' => ['px'],
        //     'range' => [
        //         'px' => [
        //             'min' => 10,
        //             'max' => 100,
        //             'step' => 1,
        //         ],
        //     ],
        //     'default' => [
        //         'unit' => 'px',
        //         'size' => 24,
        //     ],
        //     'selectors' => [
        //         '{{WRAPPER}} .evs-prev, {{WRAPPER}} .evs-next' => 'font-size: {{SIZE}}{{UNIT}};',
        //     ],
        // ]);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'arrow_typography',
				'selector' => '{{WRAPPER}} .evs-prev, {{WRAPPER}} .evs-next',
			]
		);

        $this->add_control(
			'arrow_padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .evs-prev, .evs-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->add_control('arrow_color', [
            'label' => 'Arrow Color',
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .evs-prev svg, {{WRAPPER}} .evs-next svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_bg_color', [
            'label' => 'Background Color',
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .evs-prev, {{WRAPPER}} .evs-next' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_hover_color', [
            'label' => 'Hover Color',
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .evs-prev svg:hover, {{WRAPPER}} .evs-next svg:hover' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_bg_hover_color', [
            'label' => 'Hover Background',
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .evs-prev:hover, {{WRAPPER}} .evs-next:hover' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'label' => 'Arrow Border',
                'selector' => '{{WRAPPER}} .evs-prev, {{WRAPPER}} .evs-next',
            ]
        );

        $this->add_control('arrow_border_radius', [
            'label' => 'Border Radius',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .evs-prev, {{WRAPPER}} .evs-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrow_shadow',
                'label' => 'Arrow Shadow',
                'selector' => '{{WRAPPER}} .evs-prev, {{WRAPPER}} .evs-next',
            ]
        );

        $this->end_controls_section();

        // Dots Style Section
        $this->start_controls_section('dots_style', [
            'label' => 'Dots Style',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_dots' => 'yes',
            ],
        ]);

        $this->add_control('dots_color', [
            'label' => 'Dots Color',
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('dots_active_color', [
            'label' => 'Active Dot Color',
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-dots li.slick-active button:before' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('dots_size', [
            'label' => 'Dots Size',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 5,
                    'max' => 30,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 12,
            ],
            'selectors' => [
                '{{WRAPPER}} .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
    $settings = $this->get_settings_for_display();

    // Safe defaults
    $post_type        = $settings['post_type'] ?? 'post';
    $template_id      = isset($settings['template_id']) ? (int) $settings['template_id'] : 0;
    $slides_to_show   = isset($settings['slides_to_show']) ? (int) $settings['slides_to_show'] : 1;
    $slides_to_scroll = isset($settings['slides_to_scroll']) ? (int) $settings['slides_to_scroll'] : 1;

    $autoplay         = !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
    $autoplay_speed   = isset($settings['autoplay_speed']) ? (int) $settings['autoplay_speed'] : 3000;
    $speed            = isset($settings['speed']) ? (int) $settings['speed'] : 500;
    $infinite         = !empty($settings['infinite']) && $settings['infinite'] === 'yes';
    $show_arrows      = !empty($settings['show_arrows']) && $settings['show_arrows'] === 'yes';
    $show_dots        = !empty($settings['show_dots']) && $settings['show_dots'] === 'yes';
    $pause_on_hover   = !empty($settings['pause_on_hover']) && $settings['pause_on_hover'] === 'yes';

    // Responsive
    $tablet_slides = isset($settings['tablet_slides']) ? (int)$settings['tablet_slides'] : 1;
    $mobile_slides = isset($settings['mobile_slides']) ? (int)$settings['mobile_slides'] : 1;

    // Posts
    if ($settings['query_source'] === 'current') {
        global $wp_query;
        $posts = !empty($wp_query->posts) ? $wp_query->posts : [];
    } else {
        $posts = get_posts([
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);
    }

    // Unique class
    $uid           = uniqid('evs_');
    $wrapper_class = esc_attr($uid);

    echo '<div class="evs-slider ' . $wrapper_class . '">';

    if (!empty($posts)) {
        foreach ($posts as $p) {
            echo '<div class="evs-slide">';

            // Elementor template rendering
            if ($template_id && class_exists('\Elementor\Plugin')) {

                $frontend = \Elementor\Plugin::instance()->frontend;

                if (method_exists($frontend, 'enqueue_styles')) {
                    $frontend->enqueue_styles();
                }
                if (method_exists($frontend, 'enqueue_scripts')) {
                    $frontend->enqueue_scripts();
                }

                $content = method_exists($frontend, 'get_builder_content_for_display')
                    ? $frontend->get_builder_content_for_display($template_id, true)
                    : '';

                echo $content ?: '<h3>' . esc_html($p->post_title) . '</h3>';

            } elseif (!empty($settings['vs_short_code'])) {

                echo do_shortcode($settings['vs_short_code']);

            } else {
                echo '<h3>' . esc_html($p->post_title) . '</h3>';
            }

            echo '</div>';
        }
    } else {
        echo '<div class="evs-slide"><p>' . esc_html__('No posts found.', 'evs') . '</p></div>';
    }

    echo '</div>';

    /*
    |--------------------------------------------------------------------------
    | Elementor Icons Fix (SVG + Font Icons)
    |--------------------------------------------------------------------------
    */
    $prev_icon_html = '';
    $next_icon_html = '';

    if (!empty($settings['prev_icon'])) {
        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['prev_icon'], ['aria-hidden' => 'true']);
        $prev_icon_html = ob_get_clean();
    }

    if (!empty($settings['next_icon'])) {
        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['next_icon'], ['aria-hidden' => 'true']);
        $next_icon_html = ob_get_clean();
    }

    // Breakpoints
    $tablet_breakpoint = 1024;
    $mobile_breakpoint = 768;

    if (defined('ELEMENTOR_VERSION') && isset(\Elementor\Plugin::$instance->breakpoints)) {
        $bps = \Elementor\Plugin::$instance->breakpoints->get_breakpoints();

        if (!empty($bps['tablet']) && method_exists($bps['tablet'], 'get_value')) {
            $tablet_breakpoint = (int)$bps['tablet']->get_value();
        }
        if (!empty($bps['mobile']) && method_exists($bps['mobile'], 'get_value')) {
            $mobile_breakpoint = (int)$bps['mobile']->get_value();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Slick Options
    |--------------------------------------------------------------------------
    */
    $slick_options = [
        'vertical'        => true,
        'verticalSwiping' => true,
        'slidesToShow'    => $slides_to_show,
        'slidesToScroll'  => $slides_to_scroll,
        'infinite'        => $infinite,
        'autoplay'        => $autoplay,
        'autoplaySpeed'   => $autoplay_speed,
        'speed'           => $speed,
        'arrows'          => $show_arrows,
        'dots'            => $show_dots,
        'pauseOnHover'    => $pause_on_hover,
        'prevArrow'       => '<button class="slick-prev evs-prev">' .$prev_icon_html. '</button>',
        'nextArrow'       => '<button class="slick-next evs-next">' .$next_icon_html. '</button>',
        'responsive'      => [
            [
                'breakpoint' => $tablet_breakpoint + 1,
                'settings'   => [
                    'slidesToShow'   => $tablet_slides,
                    'slidesToScroll' => min($slides_to_scroll, max(1, $tablet_slides)),
                    'vertical'       => true,
                    'verticalSwiping'=> true,
                ],
            ],
            [
                'breakpoint' => $mobile_breakpoint + 1,
                'settings'   => [
                    'slidesToShow'   => $mobile_slides,
                    'slidesToScroll' => min($slides_to_scroll, max(1, $mobile_slides)),
                    'vertical'       => true,
                    'verticalSwiping'=> true,
                ],
            ],
        ],
    ];

    ?>

    <script>
    (function($){
        var sel  = '.<?php echo esc_js($uid); ?>';
        var opts = <?php echo wp_json_encode($slick_options); ?>;

        $(document).ready(function(){
            if (typeof $.fn.slick !== 'function') return;

            var $el = $(sel);

            if ($el.hasClass('slick-initialized')) {
                $el.slick('unslick');
            }

            $el.slick(opts);
        });
    })(jQuery);
    </script>

    <style>
    .<?php echo $uid; ?> .evs-prev,
    .<?php echo $uid; ?> .evs-next {
        position: absolute;
        z-index: 5;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .<?php echo $uid; ?> .evs-prev svg,
    .<?php echo $uid; ?> .evs-next svg,
    .<?php echo $uid; ?> .evs-prev i,
    .<?php echo $uid; ?> .evs-next i {
        width: 1em;
        height: 1em;
        line-height: 1;
    }

    .<?php echo $uid; ?> .evs-prev {
        <?php echo ($settings['arrows_position'] ?? '') === 'inside' ? 'top: 10px;' : 'top: -40px;'; ?>
    }

    .<?php echo $uid; ?> .evs-next {
        <?php echo ($settings['arrows_position'] ?? '') === 'inside' ? 'bottom: 10px;' : 'bottom: -40px;'; ?>
    }

    .<?php echo $uid; ?> .slick-dots {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }
    </style>

    <?php
}


    
}