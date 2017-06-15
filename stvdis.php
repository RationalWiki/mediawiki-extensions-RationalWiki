<?php
 
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'stvdisp',
        'author' => 'Trent Toulouse',
        'description' => 'Renders Election Reults'
);
 
$wgExtensionFunctions[] = 'wfstvdisp';
 
function wfstvdisp() {
    global $wgParser;
 
    $wgParser->setHook('stvdisp', 'renderstvdisp');
}
 
function renderstvdisp($input, $argv) {

$current=1570;

$percent=($current/2000)*100;

if(!strcmp($argv['widget'],"1"))
{
$string = "";
}
    return $string;


}

