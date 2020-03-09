<?php

namespace MediaWiki\Extension\RationalWiki;

$wgExtensionCredits['parserfunction'][] = array(
	'name' => 'Style title',
	'author' => '[http://www.mediawiki.org/wiki/User:Nx Nx]',
	'description' => 'Allows styling the page title (.firstHeading)'
);

$wgHooks['ParserFirstCallInit'][] = 'MediaWiki\\Extension\\RationalWiki\\efTitlestyle_Setup';
$wgHooks['LanguageGetMagic'][] = 'MediaWiki\\Extension\\RationalWiki\\efTitlestyle_Magic';

/**
 * @param \Parser $parser
 * @return bool
 */
function efTitlestyle_Setup( $parser ) {
	$parser->setFunctionHook( 'titlestyle',
		'MediaWiki\\Extension\\RationalWiki\\efTitlestyle_Render',
		SFH_NO_HASH);
	return true;
}

function efTitlestyle_Magic( &$magicWords, $langCode ) {
	$magicWords['titlestyle'] = array( 0, 'titlestyle' );
	return true;
}

/**
 * @param \Parser $parser
 * @param string $text
 * @return string
 */
function efTitlestyle_Render( $parser, $text = '' ) {
	$css = \Sanitizer::checkCss($text);
	if ( strcspn( $css, '{}' ) !== strlen( $text ) ) {
		return '';
	}
	$parser->mOutput->addHeadItem('<style>.firstHeading, .pagetitle { ' . htmlspecialchars( $css ) . ' }</style>');
	return '';
}
