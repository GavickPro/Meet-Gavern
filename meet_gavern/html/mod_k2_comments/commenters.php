<?php
/**
 * @version		$Id: commenters.php 1251 2011-10-19 17:50:13Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2TopCommentersBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">
	<?php if(count($commenters)): ?>
	<ul>
		<?php foreach ($commenters as $key=>$commenter): ?>
		<li class="<?php echo ($key%2) ? "odd" : "even"; if(count($commenters)==$key+1) echo ' lastItem'; ?>">

			<?php if($commenter->userImage): ?>
			<a class="k2Avatar tcAvatar" rel="author" href="<?php echo $commenter->link; ?>">
				<img src="<?php echo $commenter->userImage; ?>" alt="<?php echo JFilterOutput::cleanText($commenter->userName); ?>" style="width:<?php echo $tcAvatarWidth; ?>px;height:auto;" />
			</a>
			<?php endif; ?>

			<div class="k2CommenterItem">
				<?php if($params->get('commenterLink')): ?>
				<a class="tcLink" rel="author" href="<?php echo $commenter->link; ?>">
				<?php endif; ?>
	
				<span class="tcUsername"><?php echo $commenter->userName; ?></span>
	
				<?php if($params->get('commenterCommentsCounter')): ?>
				<span class="tcCommentsCounter">(<?php echo $commenter->counter; ?>)</span>
				<?php endif; ?>
	
				<?php if($params->get('commenterLink')): ?>
				</a>
				<?php endif; ?>
	
				<?php if($params->get('commenterLatestComment')): ?>
				<a class="tcLatestComment" href="<?php echo $commenter->latestCommentLink; ?>">
					<?php echo $commenter->latestCommentText; ?>
				</a>
				<span class="tcLatestCommentDate"><?php echo JText::_('K2_POSTED_ON'); ?> <?php echo JHTML::_('date', $commenter->latestCommentDate, JText::_('K2_DATE_FORMAT_LC2')); ?></span>
				<?php endif; ?>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>
