<?php

use PHPUnit\Framework\TestCase;

class Award_GrammarTest extends TestCase {

	private $typeString = "CURRENT_USER_META UPDATED";

	// Perfectly formed string
	public function testAcceptableParsing() {
		$acceptGrammarString = $this->typeString . " WHERE key=hours GT 2";
		$grammar = new WPAward\AwardGrammar($acceptGrammarString);

		$this->assertEquals($grammar->entity, "current_user_meta");
		$this->assertEquals($grammar->trigger_type, "updated");
		$this->assertEquals($grammar->trigger->input_string, "key=hours gt 2");

		// Testing the trigger
		$this->assertEquals($grammar->trigger->descriptor->input_string, "key=hours");
		$this->assertEquals($grammar->trigger->descriptor->key, "key");
		$this->assertEquals($grammar->trigger->descriptor->value, "hours");
		$this->assertEquals($grammar->trigger->operator, "gt");
		$this->assertEquals($grammar->trigger->control, 2);
	}

	// No Where clause in our string should cause an exception
	public function testUnacceptableParsingNoWhereClause() {

		$this->expectException(InvalidArgumentException::class);
		$unacceptGrammarString = $this->typeString . " key=hours GT 2";
		$grammar = new WPAward\AwardGrammar($unacceptGrammarString);
	}


	// Trigger control can only use strings when the trigger control operator is "eq"
	public function testUnacceptableParsingTriggerControlStringOperatorNotEq() {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Trigger control must be a numeric if you're not testing equality");
		$unacceptGrammarString = $this->typeString . " WHERE key=hours GT hello";
		$grammar = new WPAward\AwardGrammar($unacceptGrammarString);
	}

	public function testAcceptableParsingTriggerControlStringOperator() {
		$acceptGrammarString = $this->typeString . " WHERE key=hours eq hello";
		$grammar = new WPAward\AwardGrammar($acceptGrammarString);

		$this->assertEquals($grammar->trigger->descriptor->input_string, "key=hours");
		$this->assertEquals($grammar->trigger->descriptor->key, "key");
		$this->assertEquals($grammar->trigger->descriptor->value, "hours");
		$this->assertEquals($grammar->trigger->operator, "eq");
		$this->assertEquals($grammar->trigger->control, "hello");
	}
}
?>