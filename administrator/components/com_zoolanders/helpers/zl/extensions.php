<?php
/**
 * @package		ZOOlanders
 * @author		ZOOlanders http://www.zoolanders.com
 * @copyright	Copyright (C) JOOlanders, SL
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');

/**
 * Class zlHelperExtensions
 */
class zlHelperExtensions extends AppHelper {

	/**
	 * Cache object
	 */
	protected $_cache;

	/**
	 * Extensions array
	 */
	protected $_extensions;

	/**
	 * Retrieve specific Extension
	 *
	 * @param string $name The extension name formated as "type_name"
	 * @param bool $force Avoid using the cached data
	 *
	 * @return object The extension
	 */
	public function getExtension($name, $force = false)
	{
		// populate the list
		$this->getExtensions($force);

		// clean name
		$name = strtolower(str_replace(array(' ', '-'), '', $name));

		// return the required extension
		if (isset($this->_extensions[$name])) {
			return $this->_extensions[$name];
		} else {
			return false;
		}
	}

	/**
	 * Retrieve all Extensions
	 *
	 * @param bool $force Avoid using the cached data
	 *
	 * @return array The extensions
	 */
	public function getExtensions($force = false)
	{
		if(empty($this->_extensions) || $force)
		{
			// init vars
			$this->_extensions = array();
			$available = $this->getAvailable();
			$installed = $this->getInstalled();
			$deprecated = array('ZL Extensions Manager', 'Audio', 'ZOOtienda');

			// iterate
			foreach ($available as $key => &$ext)
			{
				// title fix for check purpose
				if($ext->title == 'ZL Extensions Manager') {
					$title = 'zlmanager';
				} else {
					$title = $ext->title;
				}

				// get name
				$ext->name = strtolower(str_replace(' ', '', $title));

				// get install url
				$url = (array)$ext->url;
				$ext->url = array_shift($url);

				// check if installed
				if(array_key_exists($ext->name, $installed)){
					$ext->installed = $installed[$ext->name];
				} else {
					$ext->installed = false;
				}

				// shortcut versions
				$ext->version = str_replace(' ', '', preg_replace('/[b|B][e|E][t|T][a|A]/', 'B', $ext->version));
				if($ext->installed){
					$ext->installed->version = str_replace(' ', '', preg_replace('/[b|B][e|E][t|T][a|A]/', 'B', $ext->installed->version));
				}

				// deprecated extensions
				if(in_array($ext->title, $deprecated)) {
					$ext->deprecated = true;
				}

				// set state, if installed
				if($ext->installed)
				{
					// outdated
					if($ext->installed->version != $ext->version) {

						// any version lower than 3.0 is considered deprecated
						if (version_compare($ext->installed->version, '3.0') < 0) {
							$ext->state = 'deprecated';
						} else {
							$ext->state = 'outdated';
						}

					// updated, no action required
					} else {
						$ext->state = 'uptodate';
					}

				// if uninstalled
				} else {
					$ext->state = 'uninstalled';
				}

				// set link
				$ext->link = 'https://www.zoolanders.com'.$ext->link;

				// add to list
				$this->_extensions[$ext->name] = $ext;
			}
		}

		return $this->_extensions;
	}

	/**
	 * Retrieve the available extensions from zl.com
	 *
	 * @return array List of extensions
	 */
	public function getAvailable()
	{
		// retrieve caching
		$key = md5($this->_getCredentialsParams());
		$cache = $this->_getCache($key);
		if (!$cache || !$json = $cache->get('json')) {

			$url = 'https://www.zoolanders.com/index.php?option=com_zoo&controller=zooextensions&task=getExtList&format=raw';
			$url .= $this->_getCredentialsParams();

			$file = JPATH_SITE . '/tmp/zlextensions.json';
			$this->download($url, $file);

			$json = JFile::read($file);

			JFile::delete($file);

			if ($cache && !empty($json)) {
				$cache->set('json', $json)->save();
			}
		}

		// check if result returned
		if(empty($json)) return array();

		// decode result
		$extensions = json_decode($json);

		// remove spaces
		foreach($extensions as $key => &$ext) {
			foreach($ext as &$data) {
				$data = trim($data);
			}

			// filter out ext that have not necessary data
			if(!strlen($ext->type)) {
				unset($extensions[$key]);
				continue;
			}

			// lowercase the type
			$ext->type = strtolower($ext->type);
		}

		return $extensions;
	}

	/**
	 * Retrieve the installed extensions
	 *
	 * @return array Array of objects
	 */
	public function getInstalled()
	{
		// init vars
		$installed = array();
		$db = JFactory::getDBO();

		// get available ext list
		$available = $this->getAvailable();
		foreach($available as &$ext)
		{
			// quick naming fix
			if(trim($ext->title) == 'ZL Extensions Manager'){
				$ext->title = 'zlmanager';
			}

			$ext = $db->Quote(trim($ext->title));
		}

		// check if available retrieved
		if (empty($available)) return array();
	
		/* PLUGINS/MODULES */
		$query = "SELECT * FROM `#__extensions`"
				." WHERE `name` IN (".implode(',', $available).")"
				." AND (type = 'plugin' OR type = 'module' OR type = 'component')";

		// update objects with manifest data
		foreach($db->setQuery($query)->loadObjectList('name') as $name => $item)
		{
			if (strlen($item->manifest_cache)) {
				$data = json_decode($item->manifest_cache);
				if ($data) foreach($data as $key => $value) {

					// ignore the type field
					if ($key == 'type') continue;

					$item->$key = trim($value);
				}
			}

			$name = strtolower(str_replace(' ', '', $name));

			// save item
			$installed[$name] = $item;
			$installed[$name]->type = 'extension';
		}

		/* ELEMENTS */
		foreach ($this->app->path->dirs('plugins:system/zoo_zlelements/zoo_zlelements/elements') as $type)
		{
			if (is_file($this->app->path->path("elements:$type/$type.php"))) {
				if ($element = $this->app->element->create($type)) {
					if ($element->getMetaData('hidden') != 'true') {
						$name = strtolower(str_replace(' ', '', $element->getMetaData('name')));
						$installed[$name] = $element->getMetaData();
						$installed[$name]->type = 'element';
					}
				}
			}
		}

		// Texts workaround
		if(isset($installed['textpro']) && isset($installed['textareapro'])) {
			$installed['texts'] = $installed['textareapro'];
			$installed['texts']->title = 'Texts';
		}
		unset($installed['textpro']);
		unset($installed['textareapro']);

		/* APPS */
		foreach($this->app->zoo->getApplicationGroups() as $app)
		{
			$author = strtolower($app->getMetaData('author'));
			if($author == 'zoolanders' || $author == 'joolanders'){
				$name = strtolower(str_replace(' ', '', $app->getMetaData('name')));
				$installed[$name] = $app->getMetaData();
				$installed[$name]->type = 'app';
			}
		}

		return $installed;
	}

	/**
	 * Uninstall an extension
	 *
	 * @param string|array $name The extension/s name formated as "type_name"
	 *
	 * @return mixed Result string on success, false on failure
	 */
	public function uninstall($name)
	{
		// init vars
		$ext = $this->getExtension($name);
		$success = false;
		$response = array('errors' => array(), 'notices' => array());

		// extension check
		if($ext && $ext->installed)
		{
			if($ext->type == 'extension')
			{
				require_once JPATH_ADMINISTRATOR . '/components/com_installer/models/manage.php';
				$installer = JModelLegacy::getInstance('Manage', 'InstallerModel');
				
				if($installer->remove((array)$ext->installed->extension_id)) {
					$success = true;
				}

				// force true for ZL Manager due to a bug on its uninstall process
				if($ext->title == 'ZL Extensions Manager') {
					$success = true;
				}

			} else 

			if($ext->type == 'element')
			{
				// init vars
				$paths = array();
				$element = strtolower(str_replace(' ', '', $ext->title));
				$els_path = $this->app->path->path('root:plugins/system/zoo_zlelements/zoo_zlelements/elements');
				
				// workaround for Texts elements
				if ($ext->title == 'Texts') {
					$paths[] = $els_path.'/textareapro';
					$paths[] = $els_path.'/textpro';

				} else {
					$paths[] = $els_path.'/'.$element;
				}

				foreach ($paths as $path) {
					// delete the element specific folder
					if(JFolder::exists($path) && JFolder::exists($path) && JFolder::delete($path)) {
						$success = true;
					} else {
						$response['errors'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_INS_ERR_ELEMENT_FOLDER', $ext->title);
					}
				}
			}

			if($ext->type == 'app')
			{
				$app_group = strtolower($ext->title);
				
				// check if there are App instances created, if so, abort
				$db = JFactory::getDBO();
				$query = "SELECT * FROM `#__zoo_application`"
						." WHERE `application_group` = ".$db->quote($app_group);
				$result = $db->setQuery($query)->loadObjectList();

				if(!empty($result)) {
					$response['notices'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_REMANING_APPS', $ext->title);
				} else {

					// create application object
					$application = $this->app->object->create('Application');
					$application->setGroup($app_group);

					try {
						// uninstall app
						$this->app->install->uninstallApplication($application);
						$success = true;

					} catch (InstallHelperException $e) {

						// raise notice on exception
						$response['errors'][] = JText::sprintf('Error uninstalling application group (%s).', $e);
					}
				}
			}

			// if succesfully uninstalled
			if($success) {
				$response['ext'] = array('title' => $ext->title, 'version' => (string)$ext->version);

				// if deprecated extension, send on response
				if (isset($ext->deprecated)) $response['ext']['deprecated'] = true;

				// set message
				$response['message'] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_UNINSTALLED', $ext->title);

			} else {

				// get error messages
				foreach (JFactory::getApplication()->getMessageQueue() as $msg) {
					$response['errors'][] = $msg['message'];
				}
			}

		} else {
			$response['errors'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_NOT_RECOGNIZED', $name);
		}

		$response['success'] = $success;
		return $response;
	}

	/**
	 * Download extension to desktop
	 *
	 * @return void
	 */
	public function downloadAndZip($extensions)
	{
		// init vars
		$success = false;
		$response = array('errors' => array(), 'notices' => array());
		$files = array();

		// make sure is array
		settype($extensions, 'array');

		// install
		foreach ($extensions as $key => &$ext) {
			$ext = $this->getExtension($ext['name']);

			// check if ext recognized
			if(!$ext) {
				$response['errors'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_NOT_RECOGNIZED', $name);
				$response['success'] = $success;
				return $response;
			}

			// download
			if(strlen($ext->url))
			{
				// set url
				$url = 'https://www.zoolanders.com' . $ext->url . $this->_getCredentialsParams();
				
				// override url params to get download trough zooextension plugin
				$url .= '&task=download&controller=zooextensions';

				// set path
				$file = JPath::clean(JPATH_SITE . '/tmp/'.str_replace(' ', '_', $ext->title).'_v'.str_replace(' ', '_', $ext->version).'.zip');

				// download
				$success = $this->download($url, $file);

				// check the downloaded file
				if(!$success || filesize($file) < 0) {
					$response['errors'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_DOWNLOAD_FAILED', $ext->title);
					$response['success'] = $success;
					return $response;	
				}

				$files[] = $file;
			}
		}

		// save file path reference
		if (count($files) > 1) {

			// zip the downloaded files
			$filepath = $this->app->path->path('tmp:').'/ZOOlanders_'.date('m.d.Y').'.zip';
			$zip = $this->app->archive->open($filepath, 'zip');
			$zip->create($files, PCLZIP_OPT_REMOVE_ALL_PATH);

			if (is_readable($filepath) && JFile::exists($filepath)) {

				// save file path reference
				$response['filepath'] = $filepath;

				// delete temporal files
				foreach ($files as $file) {
					if (JFile::exists($file)) {
						// JFile::delete($file);
					}
				}
			}

		} else {
			$response['filepath'] = array_shift($files);
		}

		$response['success'] = $success;
		return $response;
	}

	/**
	 * Download and install an Add-on
	 *
	 * @return void
	 */
	public function downloadAndInstall($extensions)
	{
		// init vars
		$response = array('errors' => array(), 'notices' => array());
		$failed_response = array();

		// make sure is array
		settype($extensions, 'array');

		// install
		foreach ($extensions as $key => &$ext) {
			$ext['response'] = $this->_downloadAndInstall($ext['name']);

			if(!$ext['response']['success']) {
				$failed_response = array_merge_recursive($failed_response, $ext['response']);
			}
		}

		// if multiple
		if (count($extensions) > 1) {

			if (!count($failed_response)) {
				$response = array('success' => true, 'message' => JText::_('PLG_ZLFRAMEWORK_EXT_ALL_INSTALLED'));

			} else {
				$response = $failed_response;
				$response['success'] = false;
			}

		// if singular
		} else {

			$ext = array_shift($extensions);
			if ($ext['response']['success']) {
				$installed = $ext['response']['ext'];
				$ext['response']['message'] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_INSTALLED', $installed['title'], $installed['version']);
			}

			// set response
			$response = $ext['response'];
		}

		return $response;
	}
	
	protected function _downloadAndInstall($extension)
	{
		// init vars
		$ext = is_string($extension) ? $this->getExtension($extension) : $extension;
		$success = false;
		$response = array('errors' => array(), 'notices' => array());

		// check if ext recognized
		if(!$ext) {
			$response['errors'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_NOT_RECOGNIZED', $name);
			$response['success'] = $success;
			return $response;
		}

		// download
		if(strlen($ext->url))
		{
			// set url
			$url = 'https://www.zoolanders.com' . $ext->url . $this->_getCredentialsParams();
			
			// override url params to get download trough zooextension plugin
			$url .= '&task=download&controller=zooextensions';

			// set path
			$file = JPath::clean(JPATH_SITE . '/tmp/zl_'.$ext->name.'.zip');

			// download
			$success = $this->download($url, $file);
		}

		// check the downloaded file
		if(!$success || filesize($file) < 0) {
			$response['errors'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_DOWNLOAD_FAILED', $ext->title);
			$response['success'] = $success;
			return $response;	
		}

		// install
		jimport('joomla.installer.installer');
	
		// load lang files
		$lang = JFactory::getLanguage();
		$lang->load(strtolower('com_installer'), JPATH_ADMINISTRATOR, null, false, false) || $lang->load(strtolower('com_installer'), JPATH_ADMINISTRATOR, $lang->getDefault(), false, false);
	
		jimport('joomla.installer.helper');
		jimport('joomla.filesystem.archive');

		// install
		$install = $this->install($file);
		$success = $success && $install['success'];
		$response = array_merge_recursive($response, $install);

		// check installation
		if($success) {

			// force ext retrievement to get it's new status
			$ext = $this->getExtension($ext->name, true);

			if ($ext && $ext->installed)
			{
				// set ext info
				$response['ext'] = array(
					'title' => $ext->title, 
					'version' => (string)$ext->installed->version,
					'enabled' => isset($ext->installed->enabled) ? $ext->installed->enabled : true,
					'type' => $ext->installed->type
				);

				if (!$response['ext']['enabled']) {
					$response['notices'][] = JText::sprintf('PLG_ZLFRAMEWORK_EXT_INS_WRN_DISABLED', $ext->title);
				}

			} else {
				$success = false;
				$response['errors'][] = JText::_('PLG_ZLFRAMEWORK_EXT_INSTALL_FAILED');
			}
		}


		// set and return results
		$response['success'] = $success;
		return $response;
	}

	/**
	 * Downloads from a URL and saves the result as a local file
	 *
	 * @param string $url
	 * @param path $target
	 *
	 * @return bool True on success
	 */
	public function download($url, $target)
	{
		// Import Joomla! libraries
		jimport('joomla.filesystem.file');

		/** @var bool Did we try to force permissions? */
		$hackPermissions = false;

		// Make sure the target does not exist
		if(JFile::exists($target)) {
			if(!@unlink($target)) {
				JFile::delete($target);
			}
		}

		// Try to open the output file for writing
		$fp = @fopen($target, 'wb');
		if($fp === false) {
			// The file can not be opened for writing. Let's try a hack.
			$empty = '';
			if( JFile::write($target, $empty) ) {
				if( $this->_chmod($target, 511) ) {
					$fp = @fopen($target, 'wb');
					$hackPermissions = true;
				}
			}
		}
		
		$result = false;
		if($fp !== false)
		{
			// First try to download directly to file if $fp !== false
			$adapters = $this->_getAdapters();
			$result = false;
			while(!empty($adapters) && ($result === false)) {
				// Run the current download method
				$method = '_get' . strtoupper( array_shift($adapters) );
				$result = $this->$method($url, $fp);
				
				// Check if we have a download
				if($result === true) {
					// The download is complete, close the file pointer
					@fclose($fp);
					// If the filesize is not at least 1 byte, we consider it failed.
					clearstatcache();
					$filesize = @filesize($target);
					if($filesize <= 0) {
						$result = false;
						$fp = @fopen($target, 'wb');
					}
				}
			}
			
			// If we have no download, close the file pointer
			if($result === false) {
				@fclose($fp);
			}
		}

		if($result === false)
		{
			// Delete the target file if it exists
			if(file_exists($target)) {
				if( !@unlink($target) ) {
					JFile::delete($target);
				}
			}
			// Download and write using JFile::write();
			$result = $this->downloadAndReturn($url);
			$download = $result['data'];
			$result = JFile::write($target, $download);
		}

		return $result;
	}

	/**
	 * Downloads from a URL and returns the result as a string
	 *
	 * @param string $url
	 *
	 * @return mixed Result string on success, false on failure
	 */
	public function downloadAndReturn($url)
	{
		$adapters = $this->_getAdapters();
		$result = false;
		
		while(!empty($adapters) && ($result === false)) {
			// Run the current download method
			$method = '_get' . strtoupper( array_shift($adapters) );
			$result = $this->$method($url, null);
		}
		
		return $result;
	}

	/**
	 * Installs the downloaded package
	 *
	 * @param string $file Path to the download package
	 *
	 * @return mixed Result string on success, false on failure
	 */
	public function install($file)
	{
		// init vars
		$success = false;
		$response = array('errors' => array(), 'notices' => array());

		// Unpack the package
		$dir = JPATH_SITE . '/tmp/' . uniqid('zoolanders_');
		$success = JArchive::extract($file, $dir);

		if(!$success) {
			$response['success'] = false;
			$response['errors'][] = 'File extraction failed';
			return $response;
		}

		// Get an installer instance
		$installer = JInstaller::getInstance();
		
		// get manifest
		$xmls = JFolder::files($dir, '.xml$', 1, true);
		if(count($xmls))
		{
			// It's an app maybe?
			$app_xml = $dir  . '/application.xml';
			if(JFile::exists($app_xml))
			{
				// Get group for folder name
				$xml = simplexml_load_file($app_xml);
				if(!$xml || $xml->getName() != 'application'){
					$success = false;
				} else {
					$group = (string) $xml->group;
					$dest = JPATH_SITE .  '/media/zoo/applications/' . $group . '/';
					$dir = rtrim($dir, "\\/") . '/';
					
					// If this is an update - do not overwrite config/positions/metadata
					if (JFolder::exists($dest)) {
						
						// Get the ZOO App instance
						$zoo = App::getInstance('zoo');
						
						$files = $zoo->zoo->app->filesystem->readDirectoryFiles($dir.'types/', '', '/\.config$/', false);
						foreach ($files as $file) {
							if (JFile::exists($dest.'types/'.$file)) {
								JFile::delete($dir.'types/'.$file);
							}
						}

						$files = $zoo->zoo->app->filesystem->readDirectoryFiles($dir, '', '/(positions\.(config|xml)|metadata\.xml)$/', true);
						foreach ($files as $file) {
							if (JFile::exists($dest.$file)) {
								JFile::delete($dir.$file);
							}
						}
					}
					
					JFolder::copy($dir, $dest, '', true );	
					$success = true;
				}
			} 
			else 
			{
				// Install the package
				if (!$installer->install($dir)) {
					foreach (JFactory::getApplication()->getMessageQueue() as $msg) {
						$response['errors'][] = $msg['message'];
					}
					
					$success = false;
				} else {
					$success = true;
				}
			}
 
		// No xml, probably pack of packages
		} else {
			
			$zips = JFolder::files($dir, '.zip', 1, true);
			if(count($zips))
			{
				foreach($zips as $zip)
				{
					$install = $this->install($zip);
					$success = $install['success'];

					// if some install fail, abort and report
					if (!$success) {
						foreach (JFactory::getApplication()->getMessageQueue() as $msg) {
							$response['errors'][] = $msg['message'];
						}
						break;
					}
				}
			}
		}
		
		// cleanup temporal files
		JInstallerHelper::cleanupInstall($file, $dir);

		// set and return results
		$response['success'] = $success;
		return $response;
	}

	/**
	 * Does the server support PHP's cURL extension?
	 *
	 * @return bool True if it is supported
	 */
	private function _hasCURL()
	{
		static $result = null;

		if(is_null($result)) {
			$result = function_exists('curl_init');
		}

		return $result;
	}
	
	/**
	 * Downloads the contents of a URL and writes them to disk (if $fp is not null)
	 * or returns them as a string (if $fp is null)
	 *
	 * @param string $url The URL to download from
	 * @param resource $fp The file pointer to download to. Omit to return the contents.
	 *
	 * @return bool|string False on failure, true on success ($fp not null) or the URL contents (if $fp is null)
	 */
	private function &_getCURL($url, $fp = null, $nofollow = false)
	{
		$result = false;
		
		$ch = curl_init($url);

		if( !@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1) && !$nofollow ) {
			// Safe Mode is enabled. We have to fetch the headers and
			// parse any redirections present in there.
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);

			// Get the headers
			$data = curl_exec($ch);
			curl_close($ch);
			
			// Init
			$newURL = $url;
			
			// Parse the headers
			$lines = explode("\n", $data);
			foreach($lines as $line) {
				if(substr($line, 0, 9) == "Location:") {
					$newURL = trim(substr($line,9));
				}
			}

			// Download from the new URL
			if($url != $newURL) {
				return $this->_getCURL($newURL, $fp);
			} else {
				return $this->_getCURL($newURL, $fp, true);
			}
		} else {
			@curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
		}
		
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 
		// Pretend we are IE7, so that webservers play nice with us
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
		
		if(is_resource($fp)) {
			curl_setopt($ch, CURLOPT_FILE, $fp);
		}

		// Get the data
		$result = curl_exec($ch);

		// Close the request
		curl_close($ch);

		return $result;
	}

	/**
	 * Does the server support URL fopen() wrappers?
	 *
	 * @return  boolean
	 */
	private function _hasFOPEN()
	{
		static $result = null;

		if (is_null($result))
		{
			// If we are not allowed to use ini_get, we assume that URL fopen is disabled
			if (!function_exists('ini_get')) {
				$result = false;
			} else {
				$result = ini_get('allow_url_fopen');
			}
		}
		return $result;
	}

	/**
	 * Downloads the contents of a URL and writes them to disk (if $fp is not null)
	 * or returns them as a string (if $fp is null) using fopen() URL wrappers
	 *
	 * @param   string    $url  The URL to download from
	 * @param   resource  $fp   The file pointer to download to. Omit to return the contents.
	 *
	 * @return  boolean|string  False on failure, true on success ($fp not null) or the URL contents (if $fp is null)
	 */
	private function &_getFOPEN($url, $fp = null)
	{
		$result = false;

		// Track errors
		if (function_exists('ini_set'))
		{
			$track_errors = ini_set('track_errors', true);
		}

		// Open the URL for reading
		if (function_exists('stream_context_create'))
		{
			// PHP 5+ way (best)
			$httpopts	 = array(
				'user_agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)',
				'timeout'	 => 10.0,
			);
			$context	 = stream_context_create(array('http' => $httpopts));
			$ih			 = @fopen($url, 'r', false, $context);
		}
		else
		{
			// PHP 4 way (actually, it's just a fallback as we can't run this code in PHP4)
			if (function_exists('ini_set'))
			{
				ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
			}
			$ih = @fopen($url, 'r');
		}

		// If fopen() fails, abort
		if (!is_resource($ih))
		{
			return $result;
		}

		// Try to download
		$bytes	 = 0;
		$result	 = true;
		$return	 = '';
		while (!feof($ih) && $result)
		{
			$contents = fread($ih, 4096);
			if ($contents === false)
			{
				@fclose($ih);
				$result = false;
				return $result;
			}
			else
			{
				$bytes += strlen($contents);
				if (is_resource($fp))
				{
					$result = @fwrite($fp, $contents);
				}
				else
				{
					$return .= $contents;
					unset($contents);
				}
			}
		}

		@fclose($ih);

		if (is_resource($fp))
		{
			return $result;
		}
		elseif ($result === true)
		{
			return $return;
		}
		else
		{
			return $result;
		}

		return $result;
	}

	/**
	 * Detect and return available download methods
	 *
	 * @return  array
	 */
	private function _getAdapters()
	{
		// Detect available adapters
		$adapters	 = array();
		if ($this->_hasCURL())
			$adapters[]	 = 'curl';
		if ($this->_hasFOPEN())
			$adapters[]	 = 'fopen';

		return $adapters;
	}

	/**
	 * Change the permissions of a file, optionally using FTP
	 *
	 * @param string $file Absolute path to file
	 * @param int $mode Permissions, e.g. 0755
	 */
	private function _chmod($path, $mode)
	{
		if(is_string($mode))
		{
			$mode = octdec($mode);
			if( ($mode < 0600) || ($mode > 0777) ) $mode = 0755;
		}

		// Initialize variables
		jimport('joomla.client.helper');
		$ftpOptions = JClientHelper::getCredentials('ftp');

		// Check to make sure the path valid and clean
		$path = JPath::clean($path);

		if ($ftpOptions['enabled'] == 1) {
			// Connect the FTP client
			JLoader::import('joomla.client.ftp');
			if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$ftp = JClientFTP::getInstance(
						$ftpOptions['host'], $ftpOptions['port'], array(), $ftpOptions['user'], $ftpOptions['pass']
				);
			}
			else
			{
				if (version_compare(JVERSION, '3.0', 'ge'))
				{
					$ftp = JClientFTP::getInstance(
							$ftpOptions['host'], $ftpOptions['port'], array(), $ftpOptions['user'], $ftpOptions['pass']
					);
				}
				else
				{
					$ftp = JFTP::getInstance(
							$ftpOptions['host'], $ftpOptions['port'], array(), $ftpOptions['user'], $ftpOptions['pass']
					);
				}
			}
		}

		if(@chmod($path, $mode))
		{
			$ret = true;
		} elseif ($ftpOptions['enabled'] == 1) {
			// Translate path and delete
			JLoader::import('joomla.client.ftp');
			$path	 = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $path), '/');
			// FTP connector throws an error
			$ret = $ftp->chmod($path, $mode);
		} else {
			return false;
		}
	}

	/**
	 * Get the cache object
	 *
	 * @param string $key
	 * @return object Cache object
	 */
	protected function _getCache($key)
	{
		if (empty($this->_cache)) {
			$this->_cache = $this->app->cache->create($this->app->path->path('cache:') . '/com_zoolanders_' . $key, true, 3600, 'apc');
			if (!$this->_cache || !$this->_cache->check()) {
				$this->_cache = null;
			}
		}

		return $this->_cache;
	}

	/**
	 * Get credentials params
	 *
	 * @return string The url encoded credentials
	 */
	protected function _getCredentialsParams()
	{
		// get credentials
		$data = $this->app->zl->getConfig('zoolanders');
		$user = $data->get('username', '');
		$pass = $data->get('password', false);
		$pass = $pass ? $this->app->zlfw->crypt($pass, 'decrypt') : '';
		
		// return encoded
		return '&username='.urlencode($user).'&password='.urlencode($pass);
	}
}