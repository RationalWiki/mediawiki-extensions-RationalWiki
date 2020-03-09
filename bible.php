<?php

namespace MediaWiki\Extension\RationalWiki;

use MediaWiki\MediaWikiServices;

// Extension credits that show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'name' => 'Bible Generator',
	'author' => '[http://rationalwiki.com/wiki/User:Tmtoulouse Trent Toulouse]',
	'url' => 'http://rationalwiki.com/wiki/RationalWiki:Annotated_Bible',
	'description' => 'Allows for easy quoting of bible verses'
);

$wgHooks['ParserFirstCallInit'][] = function ( \Parser $parser ) {
	$parser->setHook('bible', 'renderBible');
};

/**
 * @param string $input
 * @param string[] $argv
 * @param \Parser $parser
 * @return string
 */
function renderBible($input, $argv, $parser) {
	$missingParams = array_diff( [ 'book', 'chapter', 'verse1' ], array_keys( $argv ) );
	if ( $missingParams ) {
		return '<span class="error">' .
			'Missing parameter(s) in &lt;bible&gt; tag: ' .
			implode( ', ', $missingParams ) .
			'</span>';
	}
	$title_text = "Project:Annotated Bible/".$argv['book'];
	$title    = \Title::newFromText($title_text);
	$revisionLookup = MediaWikiServices::getInstance()->getRevisionLookup();
	$revision = $revisionLookup->getRevisionByTitle( $title );
	$content = $revision->getContent( 'main' );
	if ( $content instanceof \WikitextContent ) {
		$wikitext = $content->getText();
	} else {
		$wikitext = '';
	}
	if(!isset($argv['verse2'])) {
		$argv['verse2']=$argv['verse1'];
	}
	$argv['verse2']=$argv['verse2']+1;

	$pattern = "/(";
	$pattern .= preg_quote( $argv['book'], '/' );
	$pattern .= " ";
	$pattern .= preg_quote( $argv['chapter'], '/' );
	$pattern .= ":";
	$pattern .= preg_quote( $argv['verse1'] . "<br>", '/' );
	$pattern .= ".*?)";
	$pattern .= preg_quote( $argv['book']." ", '/' );
	$pattern .= preg_quote( $argv['chapter'].":", '/' );
	$pattern .= preg_quote( $argv['verse2'] . "<br>", '/' );
	$pattern .= "/i";

	$wikitext = preg_replace("/==+/","<br>",$wikitext);
	$wikitext = str_ireplace("__notoc__","",$wikitext);
	$wikitext = str_ireplace("\n","",$wikitext);

	$verse2=$argv['verse2']-1;

	if($verse2 == $argv['verse1']){
		$output2 =
			\Html::element( 'a',
				$attr = array( 'href' => $title->getFullURL() . "#".$argv['book']."_".$argv['chapter'].":".$argv['verse1'] ),
				$argv['book']." ".$argv['chapter'].":".$argv['verse1']
			) .
			"<br>";
	} else {
		$output2 =
			\Html::element( 'a',
				array( 'href' => $title->getFullURL() . "#".$argv['book']."_".$argv['chapter'].":".$argv['verse1'] ),
				$argv['book']." ".$argv['chapter'].":".$argv['verse1']."-".$verse2
			) .
			"<br>";
	}
	preg_match($pattern,$wikitext,$match);
	$output = $match[1];
	$pattern="/<\/td>.*?<tr><td valign=top>/";
	$replace="";
	$output=preg_replace($pattern,$replace,$output);
	$pattern="/".$argv['book'].".*?<br>/";
	$replace="";
	$output=preg_replace($pattern,$replace,$output);

	$output3=$output2. $parser->recursiveTagParse( $output );
	return $output3;
}

