<?php
/**
* @package		ZOOlanders
* @author    	JOOlanders, SL http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders, SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * The ZOOlanders Controller Class
 */
class ZoolandersController extends AppController
{
	public function __construct($default = array()) {
		parent::__construct($default);

		// save the active comp ref
		$this->component = $this->app->component->active;
	}

	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) 
	{
		// set default task if none
		$task = $this->app->request->getWord('task', 'extensions');

		// display view
		$this->getView()->setLayout($task)->display();
	}

	/**
	 * Save form data to component params. Ajax request
	 *
	 * @return void
	 */
	public function saveOptions()
	{	
		$success = false;
		$response = array('errors' => array(), 'notices' => array());

		// get form data
		$username = $this->app->request->get('username', 'string', '');
		$password = $this->app->request->get('password', 'string', '');

		// encrypt password
		$password = $this->app->zlfw->crypt($password, 'encrypt');

		// save data
		$success = $this->app->zl->setConfig('zoolanders', compact('username', 'password'));

		// set and return results
		$response['success'] = $success;
		echo json_encode($response);
		return;
	}

	/**
	 * Get languages list
	 */
	public function getLangList()
	{
		$languages = $this->app->zl->transifex->getLangList();

		// render layout
		$html = $this->app->zlfw->renderLayout($this->app->path->path('zl:views/zoolanders/tmpl/_languages.php'), array('languages' => $languages));
		$response['html'] = $html;

		$response['success'] = true;
		$response['languages'] = $languages;

		echo json_encode($response);
		return;
	}

	/**
	 * Get resource specific language list
	 *
	 * @return json
	 */
	public function getResourceLangList()
	{	
		$title = $this->app->request->get('title', 'string', '');
		$resource = $this->app->zl->transifex->formatResName($title);
		$languages = $this->app->zl->transifex->getResourceLangList($resource);

		// render layout
		$html = $this->app->zlfw->renderLayout($this->app->path->path('zl:views/zoolanders/tmpl/_resource_lang.php'), array('languages' => $languages, 'title' => $title, 'resource' => $resource));
		$response['html'] = $html;

		$response['success'] = true;
		echo json_encode($response);
		return;
	}

	/**
	 * Download language pack installer
	 */
	public function downloadLangInstaller()
	{
		$resources = $this->app->request->get('resources', 'array', array());
		$language = $this->app->request->get('language', 'string', '');

		// if empty, get all resources
		if (empty($resources)) foreach ($this->app->zl->extensions->getExtensions() as $ext) {
			$resources[] = $this->app->zl->transifex->formatResName($ext->name);

		// make sure resources are well formated
		} else foreach ($resources as &$resource) {
			$resource = $this->app->zl->transifex->formatResName($resource);
		}

		// download
		$file = $this->app->zl->transifex->downloadLangInstaller($resources, $language);

		if(JFile::exists($file) && is_readable($file)) {
			$response['success'] = true;
			$response['filepath'] = $file;
		}

		echo json_encode($response);
		return;
	}

	/**
	 * Install language package
	 */
	public function installLangPack()
	{
		$resources = $this->app->request->get('resources', 'array', array());
		$language = $this->app->request->get('language', 'string', '');

		// if empty, get all resources
		if (empty($resources)) foreach ($this->app->zl->extensions->getExtensions() as $ext) {
			$resources[] = $this->app->zl->transifex->formatResName($ext->name);

		// make sure resources are well formated
		} else foreach ($resources as &$resource) {
			$resource = $this->app->zl->transifex->formatResName($resource);
		}

		// download
		$file = $this->app->zl->transifex->downloadLangInstaller($resources, $language);

		if(JFile::exists($file) && is_readable($file)) {
			$response = $this->app->zl->extensions->install($file);
		}

		echo json_encode($response);
		return;
	}

	/**
	 * Download and zip the extensions. Ajax request
	 *
	 * @return json
	 */
	public function downloadAndZip()
	{	
		$extensions = $this->app->request->get('extensions', 'array', array());
		$response = $this->app->zl->extensions->downloadAndZip($extensions);

		echo json_encode($response);
		return;
	}

	/**
	 * Output a file to the browser. Ajax request
	 */
	public function outputFile()
	{
		$file = $this->app->request->get('file', 'string', array());
		
		// check file
		if (!is_readable($file) && !JFile::exists($file)) {
			$response['errors'][] = 'File does not exist or inaccessible';
			$response['success'] = false;

			echo json_encode($response);
			jexit();
		}

		$this->app->filesystem->output($file);
		JFile::delete($file);
	}

	/**
	 * Download and install extension. Ajax request
	 *
	 * @return json
	 */
	public function downloadAndInstall()
	{	
		$extensions = $this->app->request->get('extensions', 'array', array());
		$response = $this->app->zl->extensions->downloadAndInstall($extensions);

		echo json_encode($response);
		return;
	}

	/**
	 * Uninstall extension. Ajax request
	 *
	 * @return json
	 */
	public function uninstall()
	{
		$name = $this->app->request->get('name', 'string', '');

		$response = $this->app->zl->extensions->uninstall($name);

		echo json_encode($response);
		return;
	}

	/**
	 * Toggle Extension state. Ajax request
	 *
	 * @return json
	 */
	public function toggleExtState()
	{	
		// init vars
		$success = false;
		$response = array('errors' => array(), 'notices' => array());
		$name = $this->app->request->get('name', 'string', '');
		$db = JFactory::getDBO();
		
		// get extension
		$ext = $this->app->zl->extensions->getExtension($name);

		// if installed
		if($ext && $ext->installed) {
			$new_state = $ext->installed->enabled == 1 ? 0 : 1;
			$success = $db->setQuery("UPDATE `#__extensions` SET `enabled` = {$new_state} WHERE `extension_id` = '{$ext->installed->extension_id}'")->execute();
			$response['new_state'] = $new_state;
			$response['message'] = JText::sprintf($new_state ? 'PLG_ZLFRAMEWORK_EXT_ENABLED' : 'PLG_ZLFRAMEWORK_EXT_DISABLED', $ext->title);
		} else {
			$response['errors'][] = JText::_('PLG_ZLFRAMEWORK_EXT_NOT_INSTALLED');
		}

		// set and return results
		$response['success'] = $success;
		echo json_encode($response);
		return;
	}
}