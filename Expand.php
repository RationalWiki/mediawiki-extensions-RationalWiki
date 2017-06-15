<?php

$wgExtensionCredits['parserhook'][] = array(
    'name'           => 'Expand',
    'description'    => 'Preprocesses input as template call, to allow recursive substitution with {{subst:expand:Template}}',
);

//Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
	$wgHooks['ParserFirstCallInit'][] = 'wfExpand';
} else {
	$wgExtensionFunctions[] = 'wfExpand';
}

$wgHooks['LanguageGetMagic'][] = 'efExpand_Magic';

function efExpand_Magic( &$magicWords, $langCode) {
	$magicWords['expand'] = array( 0, 'expand' );
	return true;
}

function wfExpand() {
	global $wgParser;
	$wgParser->setFunctionHook( 'expand', 'efExpand_Render', SFH_NO_HASH);
	return true;
}

function efExpand_Render( &$parser ) {
	//$output = $parser->replaceVariables( "{{{$text}}}" );
	$args = func_get_args();
	array_shift($args);
	$text = implode('|',$args);
	//$output = $parser->preprocess( "{{{$text}}}", $parser->mTitle, $parser->mOptions );
	$mOt = $parser->mOutputType;
	$parser->setOutputType( OT_PREPROCESS );
	$output = $parser->replaceVariables( "{{{$text}}}" );
	$parser->setOutputType( $mOt );
	return array($output, 'noparse' => true );
}

