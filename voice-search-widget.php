<?php
class Voice_search_widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'Voice_search_widget';
	}

	public function get_title() {
		return esc_html__( 'Voice_search', 'elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'Voice_search', 'search','voice' ];
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'microphone_icon',
			[
				'label' => esc_html__( 'Icon', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-microphone',
					'library' => 'fa-solid',
				]
        	]
		);


		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

       		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {
		$settings = $this->get_settings_for_display();


		?>
<form role="search" method="get" id="searchform" action="<?php echo site_url();?>/">
    <input type="text" id="s" name="s" placeholder="Search..." />
    <button type="button"
        id="voiceSearchBtn"><?php \Elementor\Icons_Manager::render_icon( $settings['microphone_icon'], [ 'aria-hidden' => 'true' ] ); ?></button>
    <input type="submit" id="searchsubmit" value="Search" />
    <span id="listeningText" style="display: none;">Listening...</span>
    <button type="button" id="stopListeningBtn" style="display: none;">Stop</button>
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('s');
    const searchsubmit = document.getElementById('searchsubmit');

    const voiceSearchBtn = document.getElementById('voiceSearchBtn');
    const listeningText = document.getElementById('listeningText');
    const stopListeningBtn = document.getElementById('stopListeningBtn');

    document.getElementById('searchform').addEventListener('submit', function(event) {
        var searchInput = document.getElementById('s').value
    .trim(); // Get the value of the input and remove spaces
        if (searchInput === '') {
            event.preventDefault(); // Prevent the form from submitting
            alert('Please enter a search term.'); // Optional: alert message
        }
    });


    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        voiceSearchBtn.addEventListener('click', function() {
            recognition.start();
            listeningText.style.display = 'inline';
            stopListeningBtn.style.display = 'inline';
            voiceSearchBtn.style.display = 'none';
            searchsubmit.style.display = 'none';
        });

        stopListeningBtn.addEventListener('click', function() {
            recognition.stop();
            listeningText.style.display = 'none';
            searchsubmit.style.display = 'block';
            stopListeningBtn.style.display = 'none';
            voiceSearchBtn.style.display = 'inline';
        });

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            searchInput.value = transcript;
            listeningText.style.display = 'none';
            searchsubmit.style.display = 'block';
            stopListeningBtn.style.display = 'none';
            voiceSearchBtn.style.display = 'inline';
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error', event);
            listeningText.style.display = 'none';
            stopListeningBtn.style.display = 'none';
            voiceSearchBtn.style.display = 'inline';
        };

        recognition.onend = function() {
            listeningText.style.display = 'none';
            searchsubmit.style.display = 'block';
            stopListeningBtn.style.display = 'none';
            voiceSearchBtn.style.display = 'inline';
        };
    } else {
        alert('Your browser does not support speech recognition.');
        voiceSearchBtn.disabled = true;
    }
});
</script>
<style>
#listeningText {
    position: absolute;
    font-weight: bold;
    color: red;
    right: 80px;
    top: 11px;
}

#stopListeningBtn {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    margin-left: 10px;
}

button#voiceSearchBtn:hover {
    background-color: #0000;
}

button#voiceSearchBtn:hover svg {
    fill: green;
}

input#s {
    padding-right: 30px;
    font-size: 18px;
}

#voiceSearchBtn svg {
    height:auto;
    width: 26px;
}

#voiceSearchBtn {
    border: none;
    border-radius: 30px;
    position: absolute;
    right:80px;
    top: -5px;
}

form#searchform {
    display: flex;
    column-gap: 5px;
}
</style>

<?php
	}

}