<?php
class Audio_player extends \Elementor\Widget_Base {

	public function get_name() {
		return 'audio_player';
	}

	public function get_title() {
		return esc_html__( 'Audio Player', 'elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-play';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'Player', 'Audio' ];
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Settings', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title',
			[
				'label' => esc_html__( 'Title', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'textdomain' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'thumbnail-image',
			[
				'label' => esc_html__( 'Choose Image', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'audio-url',
			[
				'label' => esc_html__( 'Audio Link', 'audio-player' ),
				'type' => \Elementor\Controls_Manager::URL,
				'default' => [
					'url' => 'https://github.com/rafaelreis-hotmart/Audio-Sample-files/raw/master/sample.mp3',
					// 'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);
		

		
		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),'default' => [
					[
						'list_title' => esc_html__( 'Audio #1', 'textdomain' ),
						'thumbnail-image' => esc_html__( 'https://c.saavncdn.com/987/BIBA-English-2019-20190201201359-500x500.jpg', 'textdomain' ),
						'audio-url'=>esc_html__('https://github.com/rafaelreis-hotmart/Audio-Sample-files/raw/master/sample.mp3'),
					],
				],
				'title_field' => '{{{ list_title }}}',
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

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .player-wraper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {
	$settings = $this->get_settings_for_display();
   $trackList = []; //empty array for hold all info 

if ( $settings['list'] ) {
    foreach ( $settings['list'] as $item ) { //list array 
        $track = [
            "src" => $item['audio-url']['url'],
            "image" => $item['thumbnail-image']['url'],
			'track_title'=>  $item['list_title']
        ];
        array_push($trackList, $track); //push on every loop in array with two value 
    }
}

$jsonTrackList = json_encode($trackList, JSON_UNESCAPED_SLASHES);
//here i get the json array with the help of - json_encode 		

		?>
<style>
	#audio-player, .player-wraper {
			display: flex;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			margin:20px;
			padding: 20px;
			border-radius: 10px;
			background-color: #eee;
		}
.player-wraper {
border: 2px solid black;
  	box-shadow: 5px 5px 10px #888;
}
#audio-progress-fill {
    height: 20px;
    background-color: #007a0f;
    width: 250px;
}
		/* Style for audio player image */
		#audio-image {
			max-width: 300px;
			height:250px;
			margin-bottom:5px;
			border-radius: 10px;
			box-shadow: 3px 3px 5px #888;
		}
/* we need to fix image size  */
		/* Style for audio player controls */
		#audio-controls {
			display: flex;
			align-items: center;
			justify-content: center;
			margin-top: -30px;
		}

		/* Style for audio player seek bar */
/* 		#audio-seek {
			display: flex;
			align-items: center;
			justify-content: center;
			margin-top: 20px;
		} */

		/* Style for audio player progress bar */
		#audio-progress {
			width: 200px;
			height: 10px;
			background-color:green;
     cursor:pointer;
		}

		/* Style for audio player progress */
		#audio-progress-fill {
			height: 100%;
			background-color:blue;
		}

		/* Style for audio player buttons */
		#audio-controls button {
			font-size: 16px;
			font-weight: bold;
			color: white;
			border: none;
			border-radius: 50%;
			width: 40px;
			height: 40px;
			margin: 5px;
			cursor: pointer;
			box-shadow: 3px 3px 5px #888;
		}
</style>
		<section class='player-wraper'>
		<div id="audio-player">
		  <img id="audio-image" src="audio-image.jpg" alt="Audio Image">
			<h5 id='track_title'></h5>
		  <audio id="audio" src="audio.mp3"></audio>
		  <input type="range" min="1" max="" value="50" class="slider" id="audio-progress-fill">
	  </div>
  
	  <div id="audio-controls">
		<button id="previous-button">&#x23EE;</button>
		<button id="play-pause-button">&#x23EF;</button>
		<button id="next-button">&#x23ED;</button>
	  <i class="fas fa-volume-up"></i>  <input type="range" id="volume-control" min="0" max="1" step="0.1" value="1">
	  </div>
</section>
<script>
	var audio = document.getElementById("audio");
		var playPauseButton = document.getElementById("play-pause-button");
		var previousButton = document.getElementById("previous-button");
		var nextButton = document.getElementById("next-button");
		var progressBar = document.getElementById("audio-progress-fill");
				  
		// Set initial values
		var isPlaying = false;
		var currentTrack = 0;
		var trackList = <?php echo $jsonTrackList; ?>;
    console.log(trackList);
//     Now it's ready to play
	playPauseButton.onclick = function() {
		if (isPlaying) {
			pauseAudio();
		} else {
			playAudio();
		}
	};

	// Previous button click event
	previousButton.onclick = function() {
		currentTrack--;
		if (currentTrack < 0) {
			currentTrack = trackList.length - 1;
		}
		loadTrack(currentTrack);
		playAudio();
	};

	// Next button click event
	nextButton.onclick = function() {
		currentTrack++;
		if (currentTrack >= trackList.length) {
			currentTrack = 0;
		}
		loadTrack(currentTrack);
		playAudio();
	};

	// Load track function
	function loadTrack(index) {
		audio.src = trackList[index].src;
		document.getElementById("audio-image").src = trackList[index].image;
		document.getElementById("track_title").innerHTML = trackList[index].track_title;
	}

	// Play audio function
	function playAudio() {
		audio.play();
		isPlaying = true;
		playPauseButton.innerHTML = "&#x23F8;";
	}

	// Pause audio function
	function pauseAudio() {
		audio.pause();
		isPlaying = false;
		playPauseButton.innerHTML = "&#x23EF;";
	}

	// Update progress bar function
	audio.addEventListener("timeupdate", function() {
		var progress = audio.currentTime;
		progressBar.value =progress;
    progressBar.max=audio.duration;
  });

progressBar.oninput = function(event) {
     audio.currentTime =this.value;
}

var volumeControl = document.getElementById("volume-control");

// Volume control change event
volumeControl.oninput = function() {
audio.volume = volumeControl.value;
}

	// Load first track and play
	loadTrack(currentTrack);
	playAudio();

</script>	
		<?php
	}
	
}