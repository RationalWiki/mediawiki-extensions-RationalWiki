<?php
 
$wgExtensionCredits['parserhook'][] = array(
	'name' => 'No title',
	'author' => '[http://www.mediawiki.org/wiki/User:Nx Nx]',
	'description' => 'Adds a magic word to hide the title heading.'
);
 
 
//$wgHooks['MagicWordwgVariableIDs'][] = 'NoTitle::addMagicWordId';
$wgHooks['ParserBeforeTidy'][] = 'NoTitle::checkForMagicWord';
$wgHooks['GetDoubleUnderscoreIDs'][] = 'NoTitle::addMagicWordId';
$wgExtensionMessagesFiles['RationalWikiMagic'] = __DIR__ . '/notitle.i18n.magic.php';

class NoTitle
{
  static function addMagicWordId(&$mDoubleUnderscoreIDs) {
    $mDoubleUnderscoreIDs[] = 'notitle';
    return true;
  }
 
  static function checkForMagicWord(&$parser, &$text) {
    if ( isset( $parser->mDoubleUnderscores['notitle'] ) ) {
      $parser->mOutput->addHeadItem('<style type="text/css">/*<![CDATA[*/ .firstHeading, .subtitle, #siteSub, #contentSub, .pagetitle { display:none; } /*]]>*/</style>');
    }
    return true;
  }
  
}
