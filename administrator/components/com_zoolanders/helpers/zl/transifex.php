<?php
/**
 * @package		ZOOlanders
 * @author		ZOOlanders http://www.zoolanders.com
 * @copyright	Copyright (C) JOOlanders, SL
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Class zlHelperTransifex
 */
class zlHelperTransifex extends AppHelper {

	private static $apiUrl = 'https://www.zoolanders.com/?option=com_zoo&controller=transifex&format=raw&task=';

	/*
	   Function: Constructor
	*/
	public function __construct($app) {

		// call parent constructor
		parent::__construct($app);

		// set cache
		$this->cache = $this->app->cache->create($this->app->path->path('cache:') . '/zoolanders/transifex/data', true, '3600', 'apc');
	}

	/*
		Function: getLangList
	*/
	public function getLangList() 
	{
		$this->_initCheck();

		// check if cached
		if (!$data = $this->cache->get('languages')) {

			// get resource translation stats
			$data = $this->getData('getLangList');

			// save to cache
			$this->cache->set('languages', $data)->save();
		}

		return $data;
	}

	/*
		Function: getResourceLangList
	*/
	public function getResourceLangList($resource) 
	{
		$this->_initCheck();

		// check if cached
		if (!$data = $this->cache->get('resource-' . $resource)) {

			// get resource translation stats
			$data = $this->getData('getResourceLangList&resource='.$resource);

			// save to cache
			$this->cache->set('resource-' . $resource, $data)->save();
		}

		return $data;
	}

	/*
		Function: downloadLangInstaller
	*/
	public function downloadLangInstaller($resources, $language) 
	{
		$this->_initCheck();

		// set default file name
		$filename = $language.'.'.(count($resources) > 1 ? 'language_pack' : $resources[0]).'.zip';

		// set url
		$url = self::$apiUrl . 'getLangInstaller&resources='.implode(',', $resources).'&language='.$language;

		// attempt to override file name with one from header response
		$headers = get_headers($url, 1);
		if (isset($headers['Content-Disposition'])) {
			if (preg_match('/name="(?P<filename>.+?)"/', $headers['Content-Disposition'], $matches)) {
				$filename = $matches['filename'];
			}
		}

		// set file destination
		$file = JPath::clean(JPATH_SITE . '/tmp/'.$filename);

		// download
		$result = $this->app->zl->extensions->download($url, $file);

		return $file;
	}

	/**
	 * Gets data from transifex
	 *
	 * @param   string  $path  - the url
	 *
	 * @return array
	 */
	public function getData($task, $report=true)
	{
		$url = self::$apiUrl . $task;
		$result = $this->app->zl->extensions->downloadAndReturn($url);

		// if failed and reporing enabled
		if (!$result)
		{
			$response['errors'][] = 'Response error for "' . $task . '" request.';
			$response['success'] = false;

			echo json_encode($response);
			jexit();
		}

		return json_decode($result, true);
	}

	/*
		Function: getJoomlaLanguageCode
	*/
	public function getJoomlaLanguageCode($language)
	{
		if (stripos($language, "_") !== false){
			return str_ireplace("_", "-", $language);
		}

		if ($language == 'en') {
			return 'en-GB';
		}

		return $language . '-' . strtoupper($language);
	}

	/**
	 * Format the resource as expected by Transifex
	 *
	 * @return bool
	 */
	public function formatResName($resource) {
		return str_replace(array(' ', '-'), '', strtolower($resource));
	}

	/**
	 * Checks the basics
	 *
	 * @return bool
	 */
	private function _initCheck()
	{
		// $this->_checkSession();
		$this->_checkCache();
	}

	/**
	 * Checks the cache
	 *
	 * @return bool
	 */
	private function _checkCache()
	{
		if (!$this->cache && !$this->cache->check())
		{
			$response['success'] = false;
			$response['errors'][] = 'Cache inaccessible';

			echo json_encode($response);
			jexit();
		}

		return true;
	}
}