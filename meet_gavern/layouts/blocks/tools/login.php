<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// getting user ID
$user = JFactory::getUser();
$userID = $user->get('id');

?>
<?php if($this->API->modules('login') && !GK_COM_USERS) : ?>


<div id="loginModal" class="modal hide fade">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    <h3><?php echo JText::_(($userID == 0) ? 'TPL_GK_LANG_LOGIN' : 'TPL_GK_LANG_LOGOUT'); ?>
		    	<?php
		    		$usersConfig = JComponentHelper::getParams('com_users');
		    		if ($usersConfig->get('allowUserRegistration') && $userID == 0) : 
		    	?>
		    	<small><?php echo JText::_('TPL_GK_LANG_OR'); ?><a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"> <?php echo JText::_('TPL_GK_LANG_REGISTER'); ?></a></small>
		    	<?php endif; ?>
		    </h3>
		 </div>
		<div class="modal-body">
			
			<div class="overflow">
				<?php if($userID > 0) : ?>
				<jdoc:include type="modules" name="usermenu" style="gk_style>" />
				<?php endif; ?>
				<?php if($userID > 0) : ?>

					<?php endif; ?>
					<jdoc:include type="modules" name="login" style="none" />
					<?php if($userID > 0) : ?>

				<?php endif; ?>
			</div>
		</div>
		<?php if($userID == 0) : ?>
		<div class="modal-footer">
			<?php
				$usersConfig = JComponentHelper::getParams('com_users');
				if ($usersConfig->get('allowUserRegistration')) : ?>
				<ul class="unstyled">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
						  <?php echo JText::_('TPL_GK_LANG_FORGOT_YOUR_USERNAME'); ?></a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('TPL_GK_LANG_FORGOT_YOUR_PASSWORD'); ?></a>
					</li>
	
				</ul>
			<?php endif; ?>
		  </div>
		  <?php endif; ?>
</div>
<?php endif; ?>
