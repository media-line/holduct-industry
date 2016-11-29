<?php

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$textBefore = $params->get('text_before');
$textAfter = $params->get('text_after');
$phoneList = json_decode($params->get('phone_list'));
$phoneCount = count($phoneList->country_code);

require JModuleHelper::getLayoutPath('mod_md_phones', $params->get('layout', 'default'));
