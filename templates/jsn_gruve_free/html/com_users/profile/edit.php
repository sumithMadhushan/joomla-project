<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;

// Load template framework
if (!defined('JSN_PATH_TPLFRAMEWORK')) {
	require_once JPATH_ROOT . '/plugins/system/jsntplframework/jsntplframework.defines.php';
	require_once JPATH_ROOT . '/plugins/system/jsntplframework/libraries/joomlashine/loader.php';
}

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();
$jsnUtils   = JSNTplUtils::getInstance();

//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load( 'plg_user_profile', JPATH_ADMINISTRATOR );
?>
<div class="com-user profile-edit <?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<?php if ($jsnUtils->isJoomla3()): ?>
		<div class="page-header">
	<?php endif; ?>	
			<h2 class="componentheading"><?php echo $this->escape($this->params->get('page_heading')); ?></h2>
	<?php if ($jsnUtils->isJoomla3()): ?>
		</div>
	<script type="text/javascript">
		Joomla.twoFactorMethodChange = function(e)
		{
			var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();
	
			jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
				if (el.id != selectedPane)
				{
					jQuery('#' + el.id).hide(0);
				}
				else
				{
					jQuery('#' + el.id).show(0);
				}
			});
		}
	</script>
	<?php endif; ?>
<?php endif; ?>

<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate <?php if ($jsnUtils->isJoomla3()){echo 'form-horizontal';} ?>"  enctype="multipart/form-data">
<?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($group);?>
	<?php if (count($fields)):?>
	<fieldset>
		<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
		<legend><?php echo JText::_($fieldset->label); ?></legend>
		<?php endif;?>
		<?php if ($jsnUtils->isJoomla3()):
		JHtml::_('formbehavior.chosen', 'select');
		?>
		<?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<div class="control-group">
					<div class="controls">
						<?php echo $field->input;?>
					</div>
				</div>
			<?php else:?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $field->label; ?>
						<?php if (!$field->required && $field->type != 'Spacer') : ?>
						<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
						<?php endif; ?>
					</div>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
			<?php endif;?>
		<?php endforeach;?>
		<?php if (count($this->twofactormethods) > 1): ?>
		<fieldset>
			<legend><?php echo JText::_('COM_USERS_PROFILE_TWO_FACTOR_AUTH') ?></legend>

			<div class="control-group">
				<div class="control-label">
					<label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
						   title="<strong><?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL') ?></strong><br/><?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_DESC') ?>">
						<?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL'); ?>
					</label>
				</div>
				<div class="controls">
					<?php echo JHtml::_('select.genericlist', $this->twofactormethods, 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false) ?>
				</div>
			</div>
			<div id="com_users_twofactor_forms_container">
				<?php foreach($this->twofactorform as $form): ?>
				<?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
				<div id="com_users_twofactor_<?php echo $form['method'] ?>" style="<?php echo $style; ?>">
					<?php echo $form['form'] ?>
				</div>
				<?php endforeach; ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS') ?>
			</legend>
			<div class="alert alert-info">
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS_DESC') ?>
			</div>
			<?php if (empty($this->otpConfig->otep)): ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS_WAIT_DESC') ?>
			</div>
			<?php else: ?>
			<?php foreach ($this->otpConfig->otep as $otep): ?>
			<span class="span3">
				<?php echo substr($otep, 0, 4) ?>-<?php echo substr($otep, 4, 4) ?>-<?php echo substr($otep, 8, 4) ?>-<?php echo substr($otep, 12, 4) ?>
			</span>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<?php endif; ?>
		</fieldset>
	<?php endif; ?>
	<?php else : 
	JHtml::_('behavior.tooltip');
	?>
		<dl>
		<?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>
				<dt>
					<?php echo $field->label; ?>
					<?php if (!$field->required && $field->type!='Spacer' && $field->name!='jform[username]'): ?>
						<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
					<?php endif; ?>
				</dt>
				<dd><?php echo $field->input; ?></dd>
			<?php endif;?>
		<?php endforeach;?>
		</dl>
	<?php endif;?>
	</fieldset>
	<?php endif;?>
<?php endforeach;?>

		<div <?php if ($jsnUtils->isJoomla3()){ echo 'class="form-actions"';} ?>>
			<button type="submit" class="validate <?php if ($jsnUtils->isJoomla3()){ echo 'btn btn-primary';} ?>"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
			<a <?php if ($jsnUtils->isJoomla3()){ echo 'class="btn"';} ?> href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="profile.save" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
