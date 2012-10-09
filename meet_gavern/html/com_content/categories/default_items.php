<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
<ul <?php echo $class; ?>>
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php
	if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
	if (!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li>
	<?php $class = ''; ?>
		<span class="item-title"><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));?>">
			<?php echo $this->escape($item->title); ?></a>
			<?php if ($this->params->get('show_cat_num_articles_cat') == 1) :?>
				<span class="badge badge-info tip" rel="tooltip" title="<?php echo JText::_('COM_CONTENT_NUM_ITEMS'); ?>">
					<?php echo $item->numitems; ?> 
				</span>
			<?php endif; ?>
			<?php if (count($item->getChildren()) > 0) : ?>
				<a href="#category-<?php echo $item->id;?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right"><i class="icon-plus"></i></a>
			<?php endif;?>
		</span>
		<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
		<?php if ($item->description) : ?>
		<div class="category-desc">
			<?php echo JHtml::_('content.prepare', $item->description, '', 'com_content.categories'); ?>
		</div>
		<?php endif; ?>
        <?php endif; ?>

		<?php if (count($item->getChildren()) > 0) :?>
		<div class="collapse fade" id="category-<?php echo $item->id;?>">
			<?php
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevelcat--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->maxLevelcat++;
			?>
		</div>
		<?php endif; ?>
	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
