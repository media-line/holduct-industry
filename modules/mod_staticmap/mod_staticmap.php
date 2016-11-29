<?php

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$doc = JFactory::getDocument();
$markersList = json_decode($params->get('markers_list'));
/*
$markersCount = count($markersList->marker_latitude);
$markerLatitude = implode(',',$markersList->marker_latitude);
$markerLongitude = implode(',',$markersList->marker_longitude);
$markerLink = implode(',',$markersList->marker_link);
foreach($markersList->marker_text as $marker_text){
	$markerText[] = trim($marker_text);
}
$markerText = str_replace(array("\r\n", "\r", "\n"), ' ', implode(',',$markerText)); 
*/
require JModuleHelper::getLayoutPath('mod_staticmap', $params->get('layout', 'default'));
