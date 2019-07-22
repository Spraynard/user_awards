<?php

use PHPUnit\Framework\TestCase;

class Test_Grammar extends TestCase {

	private $typeString = "CURRENT_USER_META UPDATED";
	private $grammar;

	public function setUp() {
		parent::setUp();
		$this->grammar = new UserAwards\Grammar\Core();
	}

	// Perfectly formed string
	public function testAcceptableParsing() {
		$acceptGrammarString = $this->typeString . " WHERE hours GT 2";
		$this->grammar->parse($acceptGrammarString);

		$this->assertEquals($this->grammar->entity, "current_user_meta");
		$this->assertEquals($this->grammar->trigger_type, "updated");
		$this->assertEquals($this->grammar->trigger->input_string, "hours gt 2");

		// Testing the trigger
		$this->assertEquals($this->grammar->trigger->descriptor, "hours");
		$this->assertEquals($this->grammar->trigger->operator, "gt");
		$this->assertEquals($this->grammar->trigger->control, 2);
	}

	// No Where clause in our string should cause an exception
	public function testUnacceptableParsingNoWhereClause() {

		$this->expectException(InvalidArgumentException::class);
		$unacceptGrammarString = $this->typeString . " hours GT 2";
		$this->grammar->parse($unacceptGrammarString);
	}


	// Trigger control can only use strings when the trigger control operator is "eq"
	public function testUnacceptableParsingTriggerControlStringOperatorNotEq() {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Trigger control must be a numeric if you're not testing equality");
		$unacceptGrammarString = $this->typeString . " WHERE hours GT hello";
		$this->grammar->parse($unacceptGrammarString);
	}

	public function testAcceptableParsingTriggerControlCanBeZero() {
		$grammarString = $this->typeString . " WHERE hours eq 0";
		$this->grammar->parse($grammarString);

		$this->assertEquals($this->grammar->trigger->control, 0);
	}

	public function testAcceptableParsingTriggerControlStringOperator() {
		$acceptGrammarString = $this->typeString . " WHERE hours eq hello";
		$this->grammar->parse($acceptGrammarString);
		$this->assertEquals($this->grammar->trigger->descriptor, "hours");
		$this->assertEquals($this->grammar->trigger->operator, "eq");
		$this->assertEquals($this->grammar->trigger->control, "hello");
	}

	public function testAssignedTriggerTypeForGoodBehavior() {
		$acceptGrammarString = "CURRENT_USER_META ASSIGNED" . " WHERE hours GT 10";

		$this->assertTrue($this->grammar->parse($acceptGrammarString));
	}
}
?>