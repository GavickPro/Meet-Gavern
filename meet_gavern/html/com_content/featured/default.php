<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.caption');
// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space

// URL for Social API
$cur_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$cur_url = preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($cur_url, ENT_QUOTES, 'UTF-8'));

// Create shortcuts to some parameters.
$doc = JFactory::getDocument();

// OpenGraph support
$template_config = new JConfig();
$uri = JURI::getInstance();
$og_title = $doc->getTitle();
$og_type = 'blog';
$og_url = $cur_url;
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$og_image = $uri->root() . $templateParams->get('logo_image','');

list($width, $height, $type, $attr) = getimagesize($og_image);
$ogfb_image = $templateParams->get('ogfb_image','');
if (!empty($ogfb_image) && ($width < 200 || $height < 200)) {
        $og_image = $uri->root() . $ogfb_image;

} 
$og_site_name = $template_config->sitename;
$og_desc = $doc->getMetaData('description');

$doc->setMetaData( 'og:title', $og_title );
$doc->setMetaData( 'og:type', $og_type );
$doc->setMetaData( 'og:url', $og_url );
$doc->setMetaData( 'og:image', $og_image );
$doc->setMetaData( 'og:site_name', $og_site_name );
$doc->setMetaData( 'og:description', $og_desc );
?>
<div class="blog-featured<?php echo $this->pageclass_sfx;?> item-page" itemscope itemtype="http://schema.org/Blog">
<?php if ( $this->params->get('show_page_heading')!=0) : ?>
<div class="page-header">
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
</div>
<?php endif; ?>

<?php $leadingcount=0; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="items-leading">
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</div>
		<div class="clearfix"></div>
		<?php
			$leadingcount++;
		?>
	<?php endforeach; ?>
</div>
<div class="clearfix"></div>
<?php endif; ?>
<?php
	$introcount=(count($this->intro_items));
	$counter=0;
?>
<?php if (!empty($this->intro_items)) : ?>
	<?php foreach ($this->intro_items as $key => &$item) : ?>

		<?php
		$key= ($key-$leadingcount)+1;
		$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
		$row = $counter / $this->columns;

		if ($rowcount==1) : ?>

		<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row ; ?> row-fluid">
		<?php endif; ?>
			<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished"' : null; ?> span<?php echo round((12/$this->columns));?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
			<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
			?>
			</div>
			<?php $counter++; ?>
			
			<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>	
			
		</div>
		<?php endif; ?>
			
	<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($this->link_items)) : ?>
	<div class="items-more">
	<?php echo $this->loadTemplate('links'); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php  endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>

</div>
