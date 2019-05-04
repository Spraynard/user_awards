<?php

use PHPUnit\Framework\TestCase;

class Test_WP_Awards_Grammar extends TestCase {

	private $typeString = "CURRENT_USER_META UPDATED";
	private $grammar;

	public function setUp() {
		parent::setUp();
		$this->grammar = new WPAward\Grammar\Core();
	}

	// Perfectly formed string
	public function testAcceptableParsing() {
		$acceptGrammarString = $this->typeString . " WHERE key=hours GT 2";
		$this->grammar->parse($acceptGrammarString);

		$this->assertEquals($this->grammar->entity, "current_user_meta");
		$this->assertEquals($this->grammar->trigger_type, "updated");
		$this->assertEquals($this->grammar->trigger->input_string, "key=hours gt 2");

		// Testing the trigger
		$this->assertEquals($this->grammar->trigger->descriptor->input_string, "key=hours");
		$this->assertEquals($this->grammar->trigger->descriptor->key, "key");
		$this->assertEquals($this->grammar->trigger->descriptor->value, "hours");
		$this->assertEquals($this->grammar->trigger->operator, "gt");
		$this->assertEquals($this->grammar->trigger->control, 2);
	}

	// No Where clause in our string should cause an exception
	public function testUnacceptableParsingNoWhereClause() {

		$this->expectException(InvalidArgumentException::class);
		$unacceptGrammarString = $this->typeString . " key=hours GT 2";
		$this->grammar->parse($unacceptGrammarString);
	}


	// Trigger control can only use strings when the trigger control operator is "eq"
	public function testUnacceptableParsingTriggerControlStringOperatorNotEq() {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Trigger control must be a numeric if you're not testing equality");
		$unacceptGrammarString = $this->typeString . " WHERE key=hours GT hello";
		$this->grammar->parse($unacceptGrammarString);
	}

	public function testAcceptableParsingTriggerControlStringOperator() {
		$acceptGrammarString = $this->typeString . " WHERE key=hours eq hello";
		$this->grammar->parse($acceptGrammarString);

		$this->assertEquals($this->grammar->trigger->descriptor->input_string, "key=hours");
		$this->assertEquals($this->grammar->trigger->descriptor->key, "key");
		$this->assertEquals($this->grammar->trigger->descriptor->value, "hours");
		$this->assertEquals($this->grammar->trigger->operator, "eq");
		$this->assertEquals($this->grammar->trigger->control, "hello");
	}

	public function testAssignedTriggerTypeForGoodBehavior() {
		$acceptGrammarString = "CURRENT_USER META ASSIGNED" . " WHERE key=hours gt 10";

		$this->assertTrue($this->grammar->parse($acceptGrammarString));
	}
}
?>