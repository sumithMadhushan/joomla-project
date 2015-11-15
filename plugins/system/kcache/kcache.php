<?php
/**
 * @version 	1.0 build 1
 * @package 	kareebu Cache
 * @copyright 	(c) 2012 www.kareebu.com
 * @license		GNU/GPL, http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemkCache extends JPlugin
{
	var $_cache 	 = null;
	var $_exclusions = array();
	var $_excluded	 = false;

	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param	array	$config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		//Set the language in the class
		$options = array(
			'defaultgroup'	=> 'page',
			'browsercache'	=> $this->params->get('browsercache', false),
			'caching'		=> false,
		);

		$this->_cache = JCache::getInstance('page', $options);
		$exclusions = $this->params->get('exclusions', '');
		$exclusions = str_replace(array("\r\n", "\r"), "\n", $exclusions);
		$exclusions = explode("\n", $exclusions);
		foreach ($exclusions as $exclusion)
		{
			$query 		= parse_url($exclusion, PHP_URL_QUERY);
			$exclusion 	= array();
			$values 	= explode('&', $query);
			foreach ($values as $value)
				$exclusion[] = @explode('=', $value, 2);
			
			$this->_exclusions[] = $exclusion;
		}
	}

	function onAfterDispatch()
	{
		global $_PROFILER;
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();

		if ($app->isAdmin() || JDEBUG) {
			return;
		}

		if (count($app->getMessageQueue())) {
			return;
		}
		
		foreach ($this->_exclusions as $exclusion)
		{
			$count 		= count($exclusion);
			$our_count 	= 0;
			foreach ($exclusion as $param)
			{
				$key = isset($param[0]) ? $param[0] : '';
				$val = isset($param[1]) ? $param[1] : '';
				
				if ($key && JRequest::getVar($key, null, 'default', 'none', JREQUEST_ALLOWRAW) == $val)
					$our_count++;
			}
			if ($count == $our_count) // match
			{
				$this->_excluded = true;
				return;
			}
		}
		
		if ($user->get('guest') && $_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->_cache->setCaching(true);
		}
		
		$data = $this->_cache->get();

		if ($data !== false)
		{
			JResponse::setBody($data);

			echo JResponse::toString($app->getCfg('gzip'));

			if (JDEBUG)
			{
				$_PROFILER->mark('afterCache');
				echo implode('', $_PROFILER->getBuffer());
			}

			$app->close();
		}
	}
	
	function onAfterRender()
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin() || JDEBUG) {
			return;
		}

		if (count($app->getMessageQueue())) {
			return;
		}

		$user = JFactory::getUser();
		if ($user->get('guest') && !$this->_excluded) {
			//We need to check again here, because auto-login plugins have not been fired before the first aid check
			$this->_cache->store();
		}
	}
}