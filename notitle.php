<?php

namespace MediaWiki\Extension\RationalWiki;

$wgExtensionCredits['parserhook'][] = array(
	'name' => 'No title',
	'author' => '[http://www.mediawiki.org/wiki/User:Nx Nx]',
	'description' => 'Adds a magic word to hide the title heading.'
);

$wgHooks['ParserBeforeTidy'][] = 'MediaWiki\\Extension\\RationalWiki\\NoTitle::checkForMagicWord';
$wgHooks['GetDoubleUnderscoreIDs'] = 'MediaWiki\\Extension\\RationalWiki\\NoTitle::onGetDoubleUnderscoreIDs';
$wgExtensionMessagesFiles['RationalWikiMagic'] = __DIR__ . '/notitle.i18n.php';

class NoTitle
{

	public static function onGetDoubleUnderscoreIDs( &$mDoubleUnderscoreIDs ) {
		$mDoubleUnderscoreIDs[] = 'notitle';
		return true;
	}

	/**
	 * @param \Parser $parser
	 * @param string &$text
	 * @return bool
	 */
	public static function checkForMagicWord($parser, &$text) {
		if ( isset( $parser->mDoubleUnderscores['notitle'] ) ) {
			$parser->mOutput->addHeadItem('<style type="text/css">/*<![CDATA[*/ .firstHeading, .subtitle, #siteSub, #contentSub, .pagetitle { display:none; } /*]]>*/</style>');
		}
		return true;
	}
}
