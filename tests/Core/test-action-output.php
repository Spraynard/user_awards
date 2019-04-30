<?php
/**
 * Class Test_Wp_Award_Action_Output
 *
 * @package  Wp_awards
 */

use PHPUnit\Framework\TestCase;
use WPAward\Actions\Output\HTMLOptionOutput;

class Test__Action_Output extends TestCase {
	public function testHtmlOptionNullFormats() {

		$list = [
			'foo',
			'bar'
		];

		$accept = '<option value="0">Select...</option><option value="foo">foo</option><option value="bar">bar</option>';

		$class = new HTMLOptionOutput($list);
		$this->assertTrue($class->output() === $accept, $class->output());
	}

	public function testHtmlOptionArraysFormats() {

		$list = [
			[
				'id' => 1,
				'text' => "Foo"
			],
			[
				'id' => 2,
				'text' => "Bar"
			]
		];

		$accept = '<option value="0">Select...</option><option value="1">Foo</option><option value="2">Bar</option>';

		$class = new HTMLOptionOutput($list, 'id', 'text');
		$this->assertTrue($class->output() === $accept, $class->output());
	}

	public function testHtmlOptionObjectFormats() {
		$std_1 = new stdClass();
		$std_2 = new stdClass();

		$std_1->id = 1;
		$std_1->text = "Foo";
		$std_2->id = 2;
		$std_2->text = "Bar";

		$list = [
			$std_1,
			$std_2
		];

		$accept = '<option value="0">Select...</option><option value="1">Foo</option><option value="2">Bar</option>';

		$class = new HTMLOptionOutput($list, 'id', 'text');
		$this->assertTrue($class->output() === $accept, $class->output());
	}
}
?>