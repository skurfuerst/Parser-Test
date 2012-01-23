<?php

require_once(__DIR__ . '/../MethodChainingSyntaxParser.php');
require_once(__DIR__ . '/../php-peg/tests/ParserTestBase.php');

class MethodChainingSyntaxParserTest extends ParserTestBase {

	/**
	 * Identifier
	 * @test
	 */
	public function identifierIsMatched() {
		$parser = new ParserTestWrapper($this, 'MethodChainingSyntaxParser');
		$parser->assertMatches('Identifier', 'foo');
		$parser->assertMatches('Identifier', 'foo23S');
		$parser->assertDoesntMatch('Identifier', 'foo23S-');
	}

	/**
	 * @test
	 */
	public function numberIsMatched($type = 'Number') {
		$parser = new ParserTestWrapper($this, 'MethodChainingSyntaxParser');
		$parser->assertMatches($type, '3');
		$parser->assertMatches($type, '0');
		$parser->assertMatches($type, '42');
		$parser->assertMatches($type, '-4');
		$parser->assertMatches($type, '-4.5');
		$parser->assertMatches($type, '-4121.543');
		$parser->assertMatches($type, '4121.543');

		$res = $parser->match($type, '4121.543');
		$this->assertSame(4121.543, $res['text']);

		$parser->assertDoesntMatch($type, '4121.543a');
		$parser->assertDoesntMatch($type, '4121.5a43');
		$parser->assertDoesntMatch($type, 'a4121.543');
		$parser->assertDoesntMatch($type, 'asdf');
		$parser->assertDoesntMatch($type, '6.');
	}

	/**
	 * @test
	 */
	public function doubleQuotedStringIsMatched($type = 'DoubleQuotedString') {
		$parser = new ParserTestWrapper($this, 'MethodChainingSyntaxParser');
		$parser->assertMatches($type, '"Foo"');
		$parser->assertMatches($type, '"F \"oo"');

		$res = $parser->match($type, '"Fo\"o"');
		$this->assertSame('Fo"o', $res['text']);

		$parser->assertDoesntMatch($type, 'as"Foo"');
		$parser->assertDoesntMatch($type, '"F "oo"');
		$parser->assertDoesntMatch($type, '"Foo');
		$parser->assertDoesntMatch($type, '"Foo"as');
		$parser->assertDoesntMatch($type, 'as"Foo"');
		$parser->assertDoesntMatch($type, 'Foo', 'asdf');
		$parser->assertDoesntMatch($type, 'Foo"');
	}

	/**
	 * @test
	 */
	public function singleQuotedStringIsMatched($type = 'SingleQuotedString') {
		$parser = new ParserTestWrapper($this, 'MethodChainingSyntaxParser');
		$parser->assertMatches($type, "'Foo'");
		$parser->assertMatches($type, "'F \'oo'");

		$res = $parser->match($type, "'Fo\'oo'");
		$this->assertSame("Fo'oo", $res['text']);

		$parser->assertDoesntMatch($type, "as'Foo'");
		$parser->assertDoesntMatch($type, "'F 'oo'");
		$parser->assertDoesntMatch($type, "'Foo");
		$parser->assertDoesntMatch($type, "'Foo'as");
		$parser->assertDoesntMatch($type, "as'Foo'");
		$parser->assertDoesntMatch($type, "Foo");
		$parser->assertDoesntMatch($type, "Foo'");
	}

	/**
	 * @test
	 */
	public function simpleTypeIsMatched() {
		$this->numberIsMatched('SimpleType');
		$this->singleQuotedStringIsMatched('SimpleType');
		$this->doubleQuotedStringIsMatched('SimpleType');
	}

	/**
	 * @test
	 */
	public function methodCallIsMatched($type = 'MethodCall') {
		$parser = new ParserTestWrapper($this, 'MethodChainingSyntaxParser');
		$parser->assertMatches($type, 'myMethod()');
		$parser->assertMatches($type, 'myMethod(  )');
		$parser->assertMatches($type, 'myMethod  (  )');
		$parser->assertMatches($type, 'myMethod("Foo")');
		$parser->assertMatches($type, 'myMethod		(   "Foo"   )');
		$parser->assertMatches($type, 'myMethod(10)');
		$parser->assertMatches($type, 'myMethod(10,20)');
		$parser->assertMatches($type, 'myMethod(10  ,	20)');
		$parser->assertMatches($type, 'myMethod("Foo", \'Bar\')');
		$parser->assertMatches($type, 'myMethod( "Foo", \'Bar\' )');
		$parser->assertDoesntMatch($type, 'myMethod');

		$result = $parser->match($type, 'myMethod("F\'\"oo", \'Bar\', -12.5)');
		$this->assertSame('myMethod', $result['MethodIdentifier']['text']);
		$this->assertSame('F\'"oo', $result['MethodArguments']['MethodArgument'][0]['text']);
		$this->assertSame('Bar', $result['MethodArguments']['MethodArgument'][1]['text']);
		$this->assertSame(-12.5, $result['MethodArguments']['MethodArgument'][2]['text']);

		$result = $parser->match($type, 'myMethod("F\'\"oo")');
		$this->assertSame('myMethod', $result['MethodIdentifier']['text']);
		$this->assertSame('F\'"oo', $result['MethodArguments']['MethodArgument'][0]['text']);
	}

	/**
	 * @test
	 */
	public function callChainIsMatched($type = 'CallChain') {
		$parser = new ParserTestWrapper($this, 'MethodChainingSyntaxParser');
		$parser->assertMatches($type, 'foo');
		$parser->assertMatches($type, 'foo.myMethod()');
		$parser->assertMatches($type, 'foo.myMethod("asdg")');
		$parser->assertMatches($type, 'foo.myMethod("asdg").myOtherMethod("YEAH")');

		$result = $parser->match($type, 'foo.myMethod("asdg").myOtherMethod("YEAH")');
		$this->assertSame('foo', $result['ObjectName']['text']);
		$this->assertSame('myMethod', $result['Methods'][0]['MethodIdentifier']['text']);
		$this->assertSame('asdg', $result['Methods'][0]['MethodArguments']['MethodArgument'][0]['text']);
		$this->assertSame('myOtherMethod', $result['Methods'][1]['MethodIdentifier']['text']);
		$this->assertSame('YEAH', $result['Methods'][1]['MethodArguments']['MethodArgument'][0]['text']);

		$result = $parser->match($type, 'foo.myMethod("asdg")');
		$this->assertSame('foo', $result['ObjectName']['text']);
		$this->assertSame('myMethod', $result['Methods'][0]['MethodIdentifier']['text']);
		$this->assertSame('asdg', $result['Methods'][0]['MethodArguments']['MethodArgument'][0]['text']);
	}
}
?>