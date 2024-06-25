<?php
class Age_Calculator_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'age-calculator-widget';
    }

    public function get_title() {
        return 'Age Calculator';
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Setting', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Title', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Age Calculator', 'textdomain' ),
				'placeholder' => esc_html__( 'Type your title here', 'textdomain' ),
			]
		);
        $this->add_control(
			'title_align',
			[
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',    
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .ac-heading' => 'text-align: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'input_options',
			[
				'label' => esc_html__( 'Input Typographic', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} #ageForm input',
			]
		);

        $this->add_control(
			'label1',
			[
				'label' => esc_html__( 'label 1', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Select your DOB: ', 'textdomain' ),
				'placeholder' => esc_html__( 'dd/mm/yyyy', 'textdomain' ),
			]
		);

        $this->add_control(
			'label2',
			[
				'label' => esc_html__( 'Label 2', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Select the current date:', 'textdomain' ),
				'placeholder' => esc_html__( 'dd/mm/yyyy', 'textdomain' ),
			]
		);
        $this->add_control(
			'label_align',
			[
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',    
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} form#ageForm' => 'text-align: {{VALUE}};',
				],
			]
		);
        
        
        $this->add_control(
			'button_options',
			[
				'label' => esc_html__( 'Button Options', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

        $this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Calculate Age', 'textdomain' ),
				'placeholder' => esc_html__( 'dd/mm/yyyy', 'textdomain' ),
			]
		);
        $this->add_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',    
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .btn-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' =>5,
					'right' => 10,
					'bottom' =>5,
					'left' => 10,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .cal_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cal_button' => 'color: {{VALUE}}',
				],
			]
		);
       

        $this->add_control(
			'btn_bgcolor',
			[
				'label' => esc_html__( 'Background color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cal_button' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();


      $this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Style', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'box-margin',
			[
				'label' => esc_html__( 'Margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'em',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .add-age-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'box-padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 2,
					'right' => 1,
					'bottom' => 2,
					'left' => 1,
					'unit' => 'em',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .add-age-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
       

        $this->add_control(
			'more_options',
			[
				'label' => esc_html__( 'Title Settings', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .add-age-section',
			]
		);
        // Controls for your widget (inputs, settings, etc.)
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .ac-heading',
			]
		);
        $this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ac-heading' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'form-settings',
			[
				'label' => esc_html__( 'Form Settings', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'label-margin',
			[
				'label' => esc_html__( 'Margin label', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'em',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .ac-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ac-label' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'form_typography',
				'selector' => '{{WRAPPER}} .ac-label',
			]
		);

        $this->add_control(
			'result',
			[
				'label' => esc_html__( 'Output Style', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'ouput_margin',
			[
				'label' => esc_html__( 'Margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'em',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .result' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
			'res_color',
			[
				'label' => esc_html__( 'Label Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .result' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'result_typography',
				'selector' => '{{WRAPPER}} .result',
			]
		);


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        ?>
       <section class="add-age-section">
            <h2 class="ac-heading"><?php echo $settings['widget_title'];?></h2>
            <form id="ageForm"> 
                <label for="birthDate" class="form-label ac-label"><?php echo $settings['label1'];?></label>
                <input type="date" id="birthDate" class="form-control flatpickr-input" required>
                <label for="currentDate" class="form-label ac-label"><?php echo $settings['label2'];?></label>
                <input type="date" id="currentDate" class="form-control"  required> <br/>
                <div class="btn-wrap">
                    <button type="submit" class="btn btn-primary cal_button"> <?php echo $settings['button_text'];?>  </button>
                </div>
            </form>
            <br>
            <div class="result" id="result"> <?php if(isset($_GET['action'])){ ?> You are : 18 Years, 5 Months, 3 Days old. <?php  } ?></div>
    </section>

        <?php 

   }

    
}

// Register the widget


