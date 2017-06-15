<?php

// Extension credits that show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'Bible Generator',
        'author' => '[http://rationalwiki.com/wiki/User:Tmtoulouse Trent Toulouse]',
        'url' => 'http://rationalwiki.com/wiki/RationalWiki:Annotated_Bible',
        'description' => 'Allows for easy quoting of bible verses'
);
 
/*
 * @todo Document 
 */
$wgExtensionFunctions[] = 'wfBible';
 
/**
 * @todo Document
 */
function wfBible() {
    global $wgParser;
 
    $wgParser->setHook('bible', 'renderBible');
}
 
/**
 * @todo Document
 */

function renderBible($input, $argv, $parser) {
	global $wgUser;
	$title_text = "Project:Annotated Bible/".$argv['book'];
	$title    = Title::newFromText($title_text);
	$revision = Revision::newFromTitle( $title );
	if ( !$revision ) {
		$wikitext = '';
	} elseif ( is_callable( array( $revision, 'getContent' ) ) ) {
		$wikitext = $revision->getContent()->getWikitextForTransclusion();
	} else {
		$wikitext = $revision->getText();
	}
	if(!$argv['verse2']) {
		$argv['verse2']=$argv['verse1'];
	}
	$argv['verse2']=$argv['verse2']+1;

	$params = array(
        	"book", "chapter", "verse1", "verse2"
	);
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
			Html::element( 'a',
				$attr = array( 'href' => $title->getFullURL() . "#".$argv['book']."_".$argv['chapter'].":".$argv['verse1'] ),
				$argv['book']." ".$argv['chapter'].":".$argv['verse1']
			) .
			"<br>";
	} else {
		$output2 =
			Html::element( 'a',
				array( 'href' => $title->getFullURL() . "#".$argv['book']."_".$argv['chapter'].":".$argv['verse1'] ),
				$argv['book']." ".$argv['chapter'].":".$argv['verse1']."-".$verse2
			) .
			"<br>";
	}
#	preg_match("/(Genesis.*?)Genesis+/i",$wikitext,$match);
	preg_match($pattern,$wikitext,$match);
	$output = $match[1];
#	$output = str_ireplace($argv['book'], "<br>".$argv['book'], $output);
	$pattern="/<\/td>.*?<tr><td valign=top>/";
	$replace="";
	$output=preg_replace($pattern,$replace,$output);
	$pattern="/".$argv['book'].".*?<br>/";
	$replace="";
	$output=preg_replace($pattern,$replace,$output);

	$output3=$output2. $parser->recursiveTagParse( $output );
	return $output3;
}

