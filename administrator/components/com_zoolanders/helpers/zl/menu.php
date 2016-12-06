<?php
/**
* @package		ZOOlanders
* @author    	JOOlanders, SL http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders, SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register
App::getInstance('zoo')->loader->register('AppTree', 'classes:tree.php');
App::getInstance('zoo')->loader->register('AppMenu', 'classes:menu.php');
App::getInstance('zoo')->loader->register('AppMenuItem', 'classes:menu.php');

/**
 * Menu helper class
 */
class zlHelperMenu extends AppHelper {

	/**
	 * The menus
	 * @var array
	 */
	protected static $_menus = array();

	/**
	 * The active site menu item
	 * @var array
	 */
	protected $_active;

	/**
	 * Gets the AppMenu instance
	 *
	 * @param string $name Menu name
	 * @return AppMenu
	 * @since 2.0
	 */
	public function get($name) {

		if (isset(self::$_menus[$name])) {
			return self::$_menus[$name];
		}

		self::$_menus[$name] = $this->app->object->create('AppMenu', array($name));

		return self::$_menus[$name];
	}

	/**
	 * Gets the active site menu
	 */
	public function getActive() {
		if ($this->_active === null) {
			if ($menu = $this->app->system->application->getMenu('site') and $menu instanceof JMenu) {
				$this->_active = $menu->getActive();
			}
		}
		return $this->_active;
	}
}

/**
 * ZL Class to represent a Menu Item
 */
class ZlMenuItem extends AppMenuItem {

	/**
	 * Render the single menu item
	 *
	 * @return string The html for this menu item
	 */
	public function render() {
		$hidemainmenu = $this->app->request->getVar('hidemainmenu');
		$html = array('<li '.JArrayHelper::toString($this->_attributes).'>');
		$icon = $this->getAttribute('icon') ? '<i class="uk-icon-'.$this->getAttribute('icon').'">&#160;</i>' : '';
		$has_children = count($this->getChildren());

		if (!$hidemainmenu) {
			$html[] = '<a href="'.JRoute::_($this->_link).'">'.$icon.$this->getName().($has_children ? '&#160;<i class="uk-icon-caret-down">&#160;</i>' : '').'</a>';
		} else {
			$html[] = '<span>'.$this->getName().'</span>';
		}

		if ($has_children && !$hidemainmenu) {
			$html[] = '<div class="uk-dropdown uk-dropdown-navbar"><ul>';
			foreach ($this->getChildren() as $child) {
				$html[] = $child->render();
			}
			$html[] = '</ul></div>';
		}

		$html[] = '</li>';

		return implode("\n", $html);
	}
}

/**
 * ZL Class to represent a Menu Divider
 */
class ZlMenuDivider extends AppMenuItem {

	/**
	 * Render the single menu item
	 *
	 * @return string The html for this menu item
	 */
	public function render() {
		return '<li class="uk-nav-divider"></li>';
	}
}