<?php

/**
 * Foo bar
 */
require_once 'php-peg/Parser.php' ;

class MethodChainingSyntaxParser extends Packrat {

/**
 * Simple Identifiers
 *
 *
 *
 */
/*!* BasicTypes

Number: / -? [0-9]+ ( \.[0-9]+ )?/
	function __finalise( &$self ) {
		// Parse positive/negative integers and floats, and convert them to PHP float values
		$self['text'] = floatval($self['text']);
	}
DoubleQuotedString: '"' / (\\"|[^"])* / '"'
	function __finalise( &$self ) {
		// Parse double-quoted strings - automatically removing the surrounding quotes and de-escape the text.
		$self['text'] = str_replace('\"', '"', substr($self['text'], 1, -1));
	}
SingleQuotedString: "\'" / (\\'|[^'])* / "\'"
	function __finalise( &$self ) {
		// Parse single-quoted strings - automatically removing the surrounding quotes and de-escape the text.
		$self['text'] = str_replace("\'", "'", substr($self['text'], 1, -1));
	}

Identifier: /[a-zA-Z0-9]+/

ObjectName: Identifier
MethodIdentifier: Identifier

SimpleType: val:Number | val:SingleQuotedString | val:DoubleQuotedString
	function __finalise( &$self ) {
		// Number single quoted string or double quoted string. This method acts
		// as a shorthand for directly inserting the simple types
		// in a rule so it will not directly appear in the resulting array.
		$self = $self['val'];
	}
*/

/**
 * Compound functions
 */
/*!* Compound

CallChain: :ObjectName ( '.' Methods:MethodCall )*
	function __finalise( &$self ) {
		// Main entry point.
		// Handle the case that there is just one method.
		if (isset($self['Methods']) && !isset($self['Methods'][0])) {
			$self['Methods'] = array($self['Methods']);
		}
	}

MethodCall: :MethodIdentifier / \s* / '('  / \s* / ( :MethodArguments )? / \s* / ')'

MethodArguments: :MethodArgument ( / \s* , \s* / :MethodArgument )*
	function __finalise( &$self ) {
		// Handle the case that there is just one method argument.
		if (isset($self['MethodArgument']) && !isset($self['MethodArgument'][0])) {
			$self['MethodArgument'] = array($self['MethodArgument']);
		}
	}

MethodArgument: val:SimpleType
	function __finalise( &$self ) {
		// The method argument is currently just a shortcut to a simple type.
		$self = $self['val'];
	}
*/

}


?>
