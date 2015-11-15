<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

// Load template framework
if (!defined('JSN_PATH_TPLFRAMEWORK')) {
	require_once JPATH_ROOT . '/plugins/system/jsntplframework/jsntplframework.defines.php';
	require_once JPATH_ROOT . '/plugins/system/jsntplframework/libraries/joomlashine/loader.php';
}

$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();
$jsnUtils   = JSNTplUtils::getInstance();
?>
<?php if (!$jsnUtils->isJoomla3()): ?>
<div class="com-user <?php echo $this->params->get('pageclass_sfx') ?>">
	<div class="default-login">
	<?php
		if ($this->user->get('guest')):
			// The user is not logged in.
			echo $this->loadTemplate('login');
		else:
			// The user is already logged in.
			echo $this->loadTemplate('logout');
		endif;
	?>
	</div>
</div>
<?php
else:
$cookieLogin = $this->user->get('cookieLogin');
if ($this->user->get('guest') || !empty($cookieLogin)){
	// The user is not logged in or needs to provide a password.
	echo $this->loadTemplate('login');
}else{
	// The user is already logged in.
	echo $this->loadTemplate('logout');
}
endif;?>