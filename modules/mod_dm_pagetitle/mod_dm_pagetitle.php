<?php

// no direct access
defined('_JEXEC') or die;
$document = & JFactory::getDocument();
$title = $document->title;
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_dm_pagetitle', $params->get('layout', 'default'));
