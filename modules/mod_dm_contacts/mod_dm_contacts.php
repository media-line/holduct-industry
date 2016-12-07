<?php


// no direct access
defined('_JEXEC') or die;


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_dm_contacts', $params->get('layout', 'default'));
