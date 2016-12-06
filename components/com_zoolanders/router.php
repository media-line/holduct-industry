<?php
/**
* @package		ZOOlanders
* @author    	JOOlanders, SL http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders, SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

function ZoolandersBuildRoute(&$query) {

	$app = App::getInstance('zoo');

	// init vars
	$segments = array();

	// iterate over routers and execute its routing
	foreach ($app->zl->route->getRouters() as $router) {
		$router->buildRoute($query, $segments);
	}

	return $segments;
}

function ZoolandersParseRoute($segments) {

	$app = App::getInstance('zoo');

	// init vars
	$vars  = array();
	$count = count($segments);

	// fix segments (see JRouter::_decodeSegments)
	foreach (array_keys($segments) as $key) {
		$segments[$key] = str_replace(':', '-', $segments[$key]);
	}

	// iterate over routers and execute its routing
	foreach ($app->zl->route->getRouters() as $router) {
		$router->parseRoute($segments, $vars);
	}

	return $vars;
}