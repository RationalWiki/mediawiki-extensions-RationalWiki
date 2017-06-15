<?php
 
$wgExtensionCredits['parserfunction'][] = array(
	'name' => 'Style title',
	'author' => '[http://www.mediawiki.org/wiki/User:Nx Nx]',
	'description' => 'Allows styling the page title (.firstHeading)'
);
 
$wgHooks['ParserFirstCallInit'][] = 'efTitlestyle_Setup';
$wgHooks['LanguageGetMagic'][] = 'efTitlestyle_Magic';
 
function efTitlestyle_Setup( $parser ) {
	$parser->setFunctionHook( 'titlestyle', 'efTitlestyle_Render', SFH_NO_HASH);
	return true;
}
 
function efTitlestyle_Magic( &$magicWords, $langCode ) {
        $magicWords['titlestyle'] = array( 0, 'titlestyle' );
        return true;
}
 
function efTitlestyle_Render( $parser, $text = '' ) {
        //$parser->mOutput->setProperty('titlestyle', $parser->mOutput->getProperty('titlestyle') . htmlspecialchars(Sanitizer::checkCss($text)) );
        $parser->mOutput->addHeadItem('<style type="text/css">/*<![CDATA[*/ .firstHeading, .pagetitle { ' . htmlspecialchars(Sanitizer::checkCss($text)) . ' } /*]]>*/</style>');
        return '';
}