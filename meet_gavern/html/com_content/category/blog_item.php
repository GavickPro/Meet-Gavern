<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

$templateParams = JFactory::getApplication()->getTemplate(true)->params;

?>
<?php if ($this->item->state == 0) : ?>

<div class="system-unpublished">
	<?php endif; ?>
	
	<?php if (($params->get('show_modify_date')) or ($params->get('show_publish_date'))
		or ($params->get('show_hits')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_parent_category')) or ($params->get('show_author')) or $params->get('show_publish_date') or ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit)) : ?>
	<aside>
		<?php if ($params->get('show_publish_date')) : ?>	
		<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'Y-m-d'); ?>">
			<?php echo JHtml::_('date', $this->item->publish_up, JText::_('d')); ?>
			<span><?php echo JHtml::_('date', $this->item->publish_up, JText::_('M')); ?></span>
		</time>
		<?php endif; ?>
		
		<?php if (($params->get('show_modify_date')) or ($params->get('show_publish_date'))
			or ($params->get('show_hits')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_parent_category')) or ($params->get('show_author'))) : ?>
		
		<dl class="article-info">
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
				<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
				<?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
					<?php
						$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$menu = JFactory::getApplication()->getMenu();
						$item = $menu->getItems('link', $needle, true);
						$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
					?>
					<dt class="createdby"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '</dt><dd>' . JHtml::_('link', JRoute::_($cntlink), $author) . '</dd>'); ?>
				<?php else: ?>
					<dt class="createdby"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '</dt><dd>' . $author . '</dd>'); ?>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php if($templateParams->get('show_category_details', 0) == 1) : ?>
				<?php if ($params->get('show_modify_date')) : ?>
				<dt class="modified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', '</dt><dd>' . JHtml::_('date', $this->item->modified, JText::sprintf('DATE_FORMAT_LC3')) . '</dd>'); ?>
				<?php endif; ?>
				
				<?php if ($params->get('show_publish_date')) : ?>
				<dt class="published"><?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', '</dt><dd>' . JHtml::_('date', $this->item->publish_up, JText::sprintf('DATE_FORMAT_LC3')) . '</dd>'); ?>
				<?php endif; ?>
				
				<?php if ($params->get('show_hits')) : ?>
				<dt class="hits"><?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', '</dt><dd>' . $this->item->hits . '</dd>'); ?>
				<?php endif; ?>
				
				<?php if ($params->get('show_create_date')) : ?>
				<dt class="create"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', '</dt><dd>' . JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')) . '</dd>'); ?>
				<?php endif; ?>
				
				<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
					<?php $title = $this->escape($this->item->parent_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
						<dt class="parent-category-name"><?php echo JText::sprintf('COM_CONTENT_PARENT', '</dt><dd>' . $url . '</dd>'); ?>
					<?php else : ?>
						<dt class="parent-category-name"><?php echo JText::sprintf('COM_CONTENT_PARENT', '</dt><dd>' . $title . '</dd>'); ?>
					<?php endif; ?>
				<?php endif; ?>
				
				<?php if ($params->get('show_category')) : ?>
					<?php $title = $this->escape($this->item->category_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_category') and $this->item->catslug) : ?>
					<dt class="category-name"><?php echo JText::sprintf('COM_CONTENT_CATEGORY', '</dt><dd>' . $url . '</dd>'); ?>
					<?php else : ?>
					<dt class="category-name"><?php echo JText::sprintf('COM_CONTENT_CATEGORY', '</dt><dd>' . $title . '</dd>'); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</dl>
		<?php endif; ?>
		
		<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
		<div class="btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-cog"></i> <span class="caret"></span> </a>
			<ul class="dropdown-menu">
				<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
				<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>
	</aside>
	<?php endif; ?>
	
	<div class="gk-article">
		<?php if ($params->get('show_title')) : ?>
			<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
			<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
			<div class="img-intro-<?php echo htmlspecialchars($imgfloat); ?>"> 
				<img
					<?php if ($images->image_intro_caption):
						echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
					endif; ?>
					src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"
				/>
			</div>
			<?php endif; ?>
			
			<h2 class="article-header">
				<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>"> <?php echo $this->escape($this->item->title); ?></a>
				<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
			</h2>
		<?php endif; ?>
		
		<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
		<?php endif; ?>
		<?php echo $this->item->event->beforeDisplayContent; ?>
		<?php echo $this->item->introtext; ?>
		
		<?php if ($params->get('show_readmore') && $this->item->readmore) :
		if ($params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
			$link = new JURI($link1);
			$link->setVar('return', base64_encode($returnURL));
		endif;
	?>
		<a class="btn" href="<?php echo $link; ?>"> <i class="icon-chevron-right"></i>
		<?php if (!$params->get('access-view')) :
				echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
			elseif ($readmore = $this->item->alternative_readmore) :
				echo $readmore;
				if ($params->get('show_readmore_title', 0) != 0) :
				    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif;
			elseif ($params->get('show_readmore_title', 0) == 0) :
				echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
			else :
				echo JText::_('COM_CONTENT_READ_MORE');
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif; ?>
		</a>
		<?php endif; ?>
	</div>
	<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>
<?php echo $this->item->event->afterDisplayContent; ?> 
