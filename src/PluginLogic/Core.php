<?php
/**
 * Namespace: PluginLogic
 * Name: Core
 * Description: Contains our logic for providing WordPress specific logic for our plugin.
 * This includes entities such as Post Types, Meta Boxes, and the required functionality for each of these entities.
 */
namespace WPAward\PluginLogic;

class Core {
	private $post_type_name;

	function __construct( $post_type_name ) {
		$this->post_type_name = $post_type_name;
		add_action('init', [$this, 'PostType']);
		add_action('add_meta_boxes_' . $this->post_type_name, [$this, 'PostTypeMetaBoxes']);
	}

	/**
	 * Register post type with wordpress
	 */
	public function PostType() {
		$args = [
			'labels' => [ 'name' => 'Awards', 'singular_name' => 'Award' ],
			'show_ui' => true
		];

		register_post_type($this->post_type_name, $args);
	}

	public function PostTypeMetaBoxes() {
		$this->_addGrammarMeta();
		$this->_addAutoGiveAwardMeta();
	}

	private function _addGrammarMeta() {
		add_meta_box(
			$this->post_type_name . "_grammar", // CSS ID Attribute
			'Award Trigger', // Title
			[$this, '_grammarMetaHTML'], // Callback
			$this->post_type_name, // Page
			'advanced', // Context
			'default', // priority
			null // Callback Args
		);
	}

	function _grammarMetaHTML( $post, $box ) {
		$grammar_string = get_post_meta( $post->ID, 'WPAward_Grammar', true);
		echo <<<HTML
		<label for="WPAward_Grammar">Write out a grammar string to act as a trigger for a user obtaining this award</label><br>
		<input type="text" name="WPAward_Grammar" id="WPAward_Grammar" value="{$grammar_string}"/>
HTML;
	}

	private function _addAutoGiveAwardMeta() {
		add_meta_box(
			$this->post_type_name . "_auto_give", // CSS ID Attribute
			'Auto Give Award', // Title
			[$this, '_autoGiveAwardHTML'], // Callback
			$this->post_type_name, // Page
			'side', // Context
			'default', // priority
			null // Callback Args
		);
	}

	function _autoGiveAwardHTML( $post, $box ) {
		$auto_give_award_value = get_post_meta( $post->ID, 'WPAward_Auto_Give', true);
		echo <<<HTML
		<input type="checkbox" name="WPAward_Auto_Give" id="WPAward_Auto_Give"/>
		<label for="WPAward_Auto_Give">Checking this box will automatically give award to user when they trigger the award</label>
HTML;
	}

}
?>