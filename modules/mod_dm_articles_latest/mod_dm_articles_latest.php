<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the latest functions only once
JLoader::register('ModArticlesLatestHelper', __DIR__ . '/helper.php');

$list            = ModArticlesLatestHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

$application = JFactory::getApplication();
$menu = $application->getMenu();
$menuItem = $menu->getItem($params->get('all_news_link', ''));

require JModuleHelper::getLayoutPath('mod_dm_articles_latest', $params->get('layout', 'default'));
