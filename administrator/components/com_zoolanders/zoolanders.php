<?php
/**
* @package		ZOOlanders
* @author    	JOOlanders, SL http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders, SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// make sure ZOO is installed and enabled
if (!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')
		|| !JComponentHelper::getComponent('com_zoo', true)->enabled) {

	echo 'Make sure ZOO is installed and enabled';
	return;
}

// make sure ZLFW is installed and enabled
if (!JFile::exists(JPATH_ROOT.'/plugins/system/zlframework/zlframework.xml')
		|| !JPluginHelper::getPlugin('system', 'zlframework')) {
	
	echo 'Make sure ZL Framework is installed and enabled';
	return;
}

// load zoo config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

// init vars
$zoo = App::getInstance('zoo');
$path = dirname(__FILE__);
$cache_path = JPATH_ROOT.'/cache/com_zoolanders';
$media_path = JPATH_ROOT.'/media/zoolanders';
$controller = $zoo->request->getWord('controller');
$task		= $zoo->request->getWord('task');

// register paths
$zoo->path->register($path, 'zl');
$zoo->path->register($path.'/controllers', 'controllers');
$zoo->path->register($path.'/partials', 'partials');
$zoo->path->register($path.'/helpers', 'helpers');

// load assets
$zoo->zlfw->zlux->loadMainAssets(true);
$zoo->document->addStylesheet('media:com_zoolanders/css/admin.min.css');
$zoo->document->addScript('media:com_zoolanders/js/admin.min.js');
$zoo->document->addScript('zlfw:vendor/zlux/js/addons/notify.min.js');

// build menu
$menu = $zoo->zl->menu->get('nav');

// extensions menu
$menu->addChild($zoo->object->create('AppMenuItem', array('extensions', 'Extensions', $zoo->link(array('option' => 'com_zoolanders', 'controller' => 'zoolanders', 'task' => 'extensions')))));

// trigger event for adding config tab menu items
$zoo->event->dispatcher->notify($zoo->event->create(null, 'zoolanders:menuitems', array('tab' => &$menu)));

try {

	// delegate dispatch
	if(!$zoo->zlfw->request->isAjax()) {
		echo '<div class="zx">';
			$zoo->dispatch('zoolanders');
		echo '</div>';
	} else {
		$zoo->dispatch('zoolanders');	
	}

} catch (AppException $e) {
	$zoo->error->raiseError(500, $e);
}