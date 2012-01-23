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
/* Number: / -? [0-9]+ ( \.[0-9]+ )?/ */
protected $match_Number_typestack = array('Number');
function match_Number ($stack = array()) {
	$matchrule = "Number"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/ -? [0-9]+ ( \.[0-9]+ )?/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}

function Number__finalise ( &$self ) {
		// Parse positive/negative integers and floats, and convert them to PHP float values
		$self['text'] = floatval($self['text']);
	}

/* DoubleQuotedString: '"' / (\\"|[^"])* / '"' */
protected $match_DoubleQuotedString_typestack = array('DoubleQuotedString');
function match_DoubleQuotedString ($stack = array()) {
	$matchrule = "DoubleQuotedString"; $result = $this->construct($matchrule, $matchrule, null);
	$_4 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '"') {
			$this->pos += 1;
			$result["text"] .= '"';
		}
		else { $_4 = FALSE; break; }
		if (( $subres = $this->rx( '/ (\\\\"|[^"])* /' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_4 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '"') {
			$this->pos += 1;
			$result["text"] .= '"';
		}
		else { $_4 = FALSE; break; }
		$_4 = TRUE; break;
	}
	while(0);
	if( $_4 === TRUE ) { return $this->finalise($result); }
	if( $_4 === FALSE) { return FALSE; }
}

function DoubleQuotedString__finalise ( &$self ) {
		// Parse double-quoted strings - automatically removing the surrounding quotes and de-escape the text.
		$self['text'] = str_replace('\"', '"', substr($self['text'], 1, -1));
	}

/* SingleQuotedString: "\'" / (\\'|[^'])* / "\'" */
protected $match_SingleQuotedString_typestack = array('SingleQuotedString');
function match_SingleQuotedString ($stack = array()) {
	$matchrule = "SingleQuotedString"; $result = $this->construct($matchrule, $matchrule, null);
	$_9 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '\'') {
			$this->pos += 1;
			$result["text"] .= '\'';
		}
		else { $_9 = FALSE; break; }
		if (( $subres = $this->rx( '/ (\\\\\'|[^\'])* /' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_9 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '\'') {
			$this->pos += 1;
			$result["text"] .= '\'';
		}
		else { $_9 = FALSE; break; }
		$_9 = TRUE; break;
	}
	while(0);
	if( $_9 === TRUE ) { return $this->finalise($result); }
	if( $_9 === FALSE) { return FALSE; }
}

function SingleQuotedString__finalise ( &$self ) {
		// Parse single-quoted strings - automatically removing the surrounding quotes and de-escape the text.
		$self['text'] = str_replace("\'", "'", substr($self['text'], 1, -1));
	}

/* Identifier: /[a-zA-Z0-9]+/ */
protected $match_Identifier_typestack = array('Identifier');
function match_Identifier ($stack = array()) {
	$matchrule = "Identifier"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/[a-zA-Z0-9]+/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* ObjectName: Identifier */
protected $match_ObjectName_typestack = array('ObjectName');
function match_ObjectName ($stack = array()) {
	$matchrule = "ObjectName"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'Identifier'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres );
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* MethodIdentifier: Identifier */
protected $match_MethodIdentifier_typestack = array('MethodIdentifier');
function match_MethodIdentifier ($stack = array()) {
	$matchrule = "MethodIdentifier"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'Identifier'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres );
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* SimpleType: val:Number | val:SingleQuotedString | val:DoubleQuotedString */
protected $match_SimpleType_typestack = array('SimpleType');
function match_SimpleType ($stack = array()) {
	$matchrule = "SimpleType"; $result = $this->construct($matchrule, $matchrule, null);
	$_21 = NULL;
	do {
		$res_14 = $result;
		$pos_14 = $this->pos;
		$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "val" );
			$_21 = TRUE; break;
		}
		$result = $res_14;
		$this->pos = $pos_14;
		$_19 = NULL;
		do {
			$res_16 = $result;
			$pos_16 = $this->pos;
			$matcher = 'match_'.'SingleQuotedString'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "val" );
				$_19 = TRUE; break;
			}
			$result = $res_16;
			$this->pos = $pos_16;
			$matcher = 'match_'.'DoubleQuotedString'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "val" );
				$_19 = TRUE; break;
			}
			$result = $res_16;
			$this->pos = $pos_16;
			$_19 = FALSE; break;
		}
		while(0);
		if( $_19 === TRUE ) { $_21 = TRUE; break; }
		$result = $res_14;
		$this->pos = $pos_14;
		$_21 = FALSE; break;
	}
	while(0);
	if( $_21 === TRUE ) { return $this->finalise($result); }
	if( $_21 === FALSE) { return FALSE; }
}

function SimpleType__finalise ( &$self ) {
		// Number single quoted string or double quoted string. This method acts
		// as a shorthand for directly inserting the simple types
		// in a rule so it will not directly appear in the resulting array.
		$self = $self['val'];
	}



/**
 * Compound functions
 */
/* CallChain: :ObjectName ( '.' Methods:MethodCall )* */
protected $match_CallChain_typestack = array('CallChain');
function match_CallChain ($stack = array()) {
	$matchrule = "CallChain"; $result = $this->construct($matchrule, $matchrule, null);
	$_28 = NULL;
	do {
		$matcher = 'match_'.'ObjectName'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "ObjectName" );
		}
		else { $_28 = FALSE; break; }
		while (true) {
			$res_27 = $result;
			$pos_27 = $this->pos;
			$_26 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == '.') {
					$this->pos += 1;
					$result["text"] .= '.';
				}
				else { $_26 = FALSE; break; }
				$matcher = 'match_'.'MethodCall'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "Methods" );
				}
				else { $_26 = FALSE; break; }
				$_26 = TRUE; break;
			}
			while(0);
			if( $_26 === FALSE) {
				$result = $res_27;
				$this->pos = $pos_27;
				unset( $res_27 );
				unset( $pos_27 );
				break;
			}
		}
		$_28 = TRUE; break;
	}
	while(0);
	if( $_28 === TRUE ) { return $this->finalise($result); }
	if( $_28 === FALSE) { return FALSE; }
}

function CallChain__finalise ( &$self ) {
		// Main entry point.
		// Handle the case that there is just one method.
		if (isset($self['Methods']) && !isset($self['Methods'][0])) {
			$self['Methods'] = array($self['Methods']);
		}
	}

/* MethodCall: :MethodIdentifier / \s* / '('  / \s* / ( :MethodArguments )? / \s* / ')' */
protected $match_MethodCall_typestack = array('MethodCall');
function match_MethodCall ($stack = array()) {
	$matchrule = "MethodCall"; $result = $this->construct($matchrule, $matchrule, null);
	$_39 = NULL;
	do {
		$matcher = 'match_'.'MethodIdentifier'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "MethodIdentifier" );
		}
		else { $_39 = FALSE; break; }
		if (( $subres = $this->rx( '/ \s* /' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_39 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_39 = FALSE; break; }
		if (( $subres = $this->rx( '/ \s* /' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_39 = FALSE; break; }
		$res_36 = $result;
		$pos_36 = $this->pos;
		$_35 = NULL;
		do {
			$matcher = 'match_'.'MethodArguments'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "MethodArguments" );
			}
			else { $_35 = FALSE; break; }
			$_35 = TRUE; break;
		}
		while(0);
		if( $_35 === FALSE) {
			$result = $res_36;
			$this->pos = $pos_36;
			unset( $res_36 );
			unset( $pos_36 );
		}
		if (( $subres = $this->rx( '/ \s* /' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_39 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_39 = FALSE; break; }
		$_39 = TRUE; break;
	}
	while(0);
	if( $_39 === TRUE ) { return $this->finalise($result); }
	if( $_39 === FALSE) { return FALSE; }
}


/* MethodArguments: :MethodArgument ( / \s* , \s* / :MethodArgument )* */
protected $match_MethodArguments_typestack = array('MethodArguments');
function match_MethodArguments ($stack = array()) {
	$matchrule = "MethodArguments"; $result = $this->construct($matchrule, $matchrule, null);
	$_46 = NULL;
	do {
		$matcher = 'match_'.'MethodArgument'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "MethodArgument" );
		}
		else { $_46 = FALSE; break; }
		while (true) {
			$res_45 = $result;
			$pos_45 = $this->pos;
			$_44 = NULL;
			do {
				if (( $subres = $this->rx( '/ \s* , \s* /' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_44 = FALSE; break; }
				$matcher = 'match_'.'MethodArgument'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "MethodArgument" );
				}
				else { $_44 = FALSE; break; }
				$_44 = TRUE; break;
			}
			while(0);
			if( $_44 === FALSE) {
				$result = $res_45;
				$this->pos = $pos_45;
				unset( $res_45 );
				unset( $pos_45 );
				break;
			}
		}
		$_46 = TRUE; break;
	}
	while(0);
	if( $_46 === TRUE ) { return $this->finalise($result); }
	if( $_46 === FALSE) { return FALSE; }
}

function MethodArguments__finalise ( &$self ) {
		// Handle the case that there is just one method argument.
		if (isset($self['MethodArgument']) && !isset($self['MethodArgument'][0])) {
			$self['MethodArgument'] = array($self['MethodArgument']);
		}
	}

/* MethodArgument: val:SimpleType */
protected $match_MethodArgument_typestack = array('MethodArgument');
function match_MethodArgument ($stack = array()) {
	$matchrule = "MethodArgument"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'SimpleType'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres, "val" );
		return $this->finalise($result);
	}
	else { return FALSE; }
}

function MethodArgument__finalise ( &$self ) {
		// The method argument is currently just a shortcut to a simple type.
		$self = $self['val'];
	}



}


?>
