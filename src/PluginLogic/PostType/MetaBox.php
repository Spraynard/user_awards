<?php

namespace WPAward\PluginLogic\PostType;

class MetaBox {
	private $wp_metabox_data;
	private $input;
	private $output;

	function __construct( array $wp_metabox_data, $input, $output ) {
		$this->wp_metabox_data = $wp_metabox_data;
		$this->input = $input;
		$this->output = $output;
	}

	public function add_metabox() {
		$metabox_params = [
			$this->wp_metabox_data['id'],
			$this->wp_metabox_data['title'],
			[$this, 'getOutput'],
			$this->wp_metabox_data['page'],
			$this->wp_metabox_data['context'],
			$this->wp_metabox_data['priority'],
			$this->wp_metabox_data['callback_args']
		];
		call_user_func_array('add_meta_box', $metabox_params);
	}

	public function getInput() {
		$this->input->output();
	}

	public function getOutput() {
		$this->output->output();
	}
} ?>