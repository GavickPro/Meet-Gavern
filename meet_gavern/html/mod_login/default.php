<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="userdata">
		<?php if($templateParams->get('usernameless_login', 0) && isset($_COOKIE['gkusernameless'])) : ?>
			<?php $userData = explode(',', $_COOKIE['gkusernameless']); ?>
			<div id="gkuserless" data-username="<?php echo $userData[2]; ?>">
				<img src="http://www.gravatar.com/avatar/<?php echo $userData[0]; ?>?s=64" alt="<?php echo $userData[1]; ?>" />
				<h3>Login as:</h3>
				<p><strong><?php echo $userData[1]; ?></strong> (<?php echo $userData[2]; ?>)</p>
				<a href="#not" id="gkwronguserless">Not <strong><?php echo $userData[1]; ?></strong>? Click to input your username &raquo;</a>
			</div>
		<?php endif; ?>
		<div id="form-login-username" class="control-group">
			<div class="controls">
				
					<label for="modlgn-username" id="username" class="element-invisible"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
					<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="1" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
			
			</div>
		</div>
		<div id="form-login-password" class="control-group">
			<div class="controls">
				
					<label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="2" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
			</div>
		</div>
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="control-group checkbox">
			<label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label> <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
		</div>
		<?php endif; ?>
		<div id="form-login-submit" class="control-group">
			<div class="controls">
				<button type="submit" tabindex="3" name="Submit" class="btn"><i class="icon-lock"></i><?php echo JText::_('JLOGIN') ?></button>
				<gavern:fblogin><span id="fb-auth" class="btn btn-primary"><i class="icon-facebook"></i><?php echo JText::_('TPL_GK_LANG_FB_LOGIN_TEXT'); ?></span><gavern:fblogin>
			</div>
		</div>
		
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>