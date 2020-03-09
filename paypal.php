<?php

namespace MediaWiki\Extension\RationalWiki;

$wgExtensionCredits['parserhook'][] = array(
        'name' => 'PayPal',
        'author' => 'Trent Toulouse',
        'description' => 'Renders Donate buttons'
);

$wgHooks['ParserFirstCallInit'][] = 'MediaWiki\\Extension\\RationalWiki\\wfPaypal';

/**
 * @param \Parser $parser
 */
function wfPaypal( $parser ) {
    $parser->setHook('Paypal', 'MediaWiki\\Extension\\RationalWiki\\renderPaypal');
}

function renderPaypal($input, $argv) {

$current=1570;

$percent=($current/2000)*100;

if(!strcmp($argv['widget'],"1"))
{
$string = "
<object><param name='allowScriptAccess' value='always' />
<param name='allowNetworking' value='all' />
<param name='movie' value='https://giving.paypallabs.com/flash/badge.swf' />
<param name='quality' value='high' />
<param name='bgcolor' value='#FFFFFF' />
<param name='wmode' value='transparent' />
<param name='FlashVars' value='Id=e6da2fc093d3012db68a000d60d4c902'/>
<embed src='https://giving.paypallabs.com/flash/badge.swf' FlashVars='Id=e6da2fc093d3012db68a000d60d4c902' quality='high' bgcolor='#FFFFFF' wmode='transparent' width='205' height='350' Id='badgee6da2fc093d3012db68a000d60d4c902' align='middle' allowScriptAccess='always' allowNetworking='all' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>
</object>";
}

if(!strcmp($argv['widget'],"2"))
{
$string = '
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="95H2BS7AGCMLJ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>';
}

if(!strcmp($argv['widget'],"3"))
{
$string = "
<a target='_blank' href='http://www.fundraiserinsight.org'><img alt='simple fundraisers' src='http://www.fundraiserinsight.org/libs/thermometer.php?current=$current&max=2000&curr=36&t_id=0&skin=medium_hor' border='0'></a><br>
We are currently at $current dollars or about $percent percent towards our goal! Please help us reach our goal and donate today. 
";
}
    return $string;


}

