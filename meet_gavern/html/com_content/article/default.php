<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

// Create shortcuts to some parameters.
$params		= $this->item->params;
$images     = json_decode($this->item->images);
$urls       = json_decode($this->item->urls);
$canEdit	= $this->item->params->get('access-edit');
$user		= JFactory::getUser();

JHtml::_('behavior.caption');

// URL for Social API
$cur_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$cur_url = preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($cur_url, ENT_QUOTES, 'UTF-8'));

// OpenGraph support
$template_config = new JConfig();
$uri = JURI::getInstance();
$article_attribs = json_decode($this->item->attribs, true);
$pin_image = '';
$og_title = $this->escape($this->item->title);
$og_type = 'article';
$og_url = $cur_url;
if (isset($images->image_fulltext) and !empty($images->image_fulltext)) {     $og_image = $uri->root() . htmlspecialchars($images->image_fulltext);
     $pin_image = $uri->root() . htmlspecialchars($images->image_fulltext);
} else {
     $og_image = '';
     preg_match('/src="([^"]*)"/', $this->item->text, $matches);
     
     if(isset($matches[0])) {
     	$pin_image = $uri->root() . substr($matches[0], 5,-1);
     }
}

$og_site_name = $template_config->sitename;
$og_desc = '';


if(isset($article_attribs['og:title'])) {
     $og_title = ($article_attribs['og:title'] == '') ? $this->escape($this->item->title) : $this->escape($article_attribs['og:title']);
     $og_type = $this->escape($article_attribs['og:type']);
     $og_url = $cur_url;
     $og_image = ($article_attribs['og:image'] == '') ? $og_image : $uri->root() . $article_attribs['og:image'];
     $og_site_name = ($article_attribs['og:site_name'] == '') ? $template_config->sitename : $this->escape($article_attribs['og:site_name']);
     $og_desc = $this->escape($article_attribs['og:description']);
}

$doc = JFactory::getDocument();
$doc->setMetaData( 'og:title', $og_title );
$doc->setMetaData( 'og:type', $og_type );
$doc->setMetaData( 'og:url', $og_url );
$doc->setMetaData( 'og:image', $og_image );
$doc->setMetaData( 'og:site_name', $og_site_name );
$doc->setMetaData( 'og:description', $og_desc );


?>

<div class="item-page<?php echo $this->pageclass_sfx?> gk-item-page" itemscope itemtype="http://schema.org/Article">
<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
	<?php
if (!empty($this->item->pagination) AND $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
{
 echo $this->item->pagination;
}
 ?>
	
	<?php if (($params->get('show_modify_date')) or ($params->get('show_publish_date'))
		or ($params->get('show_hits')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_parent_category')) or ($params->get('show_author')) or $params->get('show_publish_date') or ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon'))) : ?>
	<aside>
		<?php if ($params->get('show_publish_date')) : ?>	
		<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'Y-m-d'); ?>" itemprop="datePublished">
			<?php echo JHtml::_('date', $this->item->publish_up, JText::_('d')); ?>
			<span><?php echo JHtml::_('date', $this->item->publish_up, JText::_('M')); ?></span>
		</time>
		<?php endif; ?>
		
		<?php if (($params->get('show_modify_date')) or ($params->get('show_publish_date'))
			or ($params->get('show_hits')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_parent_category')) or ($params->get('show_author')) or ($params->get('show_tags', 1))) : ?>
		
		<dl class="article-info">
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
				<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
				<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
				<?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
					<?php
						$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$menu = JFactory::getApplication()->getMenu();
						$item = $menu->getItems('link', $needle, true);
						$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
					?>
					<dt class="createdby"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '</dt><dd>' . JHtml::_('link', JRoute::_($cntlink), $author, array('itemprop' => 'url')) . '</dd>'); ?>
				<?php else: ?>
					<dt class="createdby"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '</dt><dd>' . $author . '</dd>'); ?>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php if ($params->get('show_modify_date')) : ?>
			<dt class="modified" itemprop="dateModified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', '</dt><dd>' . JHtml::_('date', $this->item->modified, JText::sprintf('DATE_FORMAT_LC3')) . '</dd>'); ?>
			<?php endif; ?>
			
			<?php if ($params->get('show_publish_date')) : ?>
			<dt class="published"><?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', '</dt><dd>' . JHtml::_('date', $this->item->publish_up, JText::sprintf('DATE_FORMAT_LC3')) . '</dd>'); ?>
			<?php endif; ?>
			
			<?php if ($params->get('show_hits')) : ?>
			<dt class="hits">
			<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $this->item->hits; ?>" />
			<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', '</dt><dd>' . $this->item->hits . '</dd>'); ?>
			<?php endif; ?>
			
			<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
				<dt class="category-name"><?php echo JText::sprintf('TPL_GK_LANG_TAGGED_UNDER', '</dt>'); ?>
				<dd>	
				<?php foreach ($this->item->tags->itemTags as $tag) : ?>
					<a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($tag->tag_id . ':' . $tag->alias)) ?>"><?php echo $tag->title; ?></a>
				<?php endforeach; ?>
				</dd>
			<?php endif; ?>
			
			<?php if ($params->get('show_create_date')) : ?>
			<dt class="create"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', '</dt><dd itemprop="dateCreated">' . JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')) . '</dd>'); ?>
			<?php endif; ?>
			
			<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
				<?php $title = $this->escape($this->item->parent_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'" itemprop="genre">'.$title.'</a>';?>
				<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
					<dt class="parent-category-name"><?php echo JText::sprintf('COM_CONTENT_PARENT', '</dt><dd>' . $url . '</dd>'); ?>
				<?php else : ?>
					<dt class="parent-category-name"><?php echo JText::sprintf('COM_CONTENT_PARENT', '</dt><dd itemprop="genre">' . $title . '</dd>'); ?>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php if ($params->get('show_category')) : ?>
				<?php $title = $this->escape($this->item->category_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
				<?php if ($params->get('link_category') and $this->item->catslug) : ?>
				<dt class="category-name"><?php echo JText::sprintf('COM_CONTENT_CATEGORY', '</dt><dd>' . $url . '</dd>'); ?>
				<?php else : ?>
				<dt class="category-name"><?php echo JText::sprintf('COM_CONTENT_CATEGORY', '</dt><dd itemprop="genre">' . $title . '</dd>'); ?>
				<?php endif; ?>
			<?php endif; ?>
		
		</dl>
		<?php endif; ?>
		
		<?php if ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
		<div class="btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-cog"></i> <span class="caret"></span> </a>
			<ul class="dropdown-menu">
				<?php if (!$this->print) : ?>
				<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email',  $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
				<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php else : ?>
				<li> <?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?> </li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>
	</aside>
	<?php endif; ?>
	
	<div class="gk-article">
	<?php if ($params->get('show_title') or (isset($images->image_fulltext) and !empty($images->image_fulltext))) : ?>
		<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
		<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
		<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
		<?php if ($images->image_fulltext_caption):
			echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
		endif; ?>
		src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" itemprop="image"/> </div>
		<?php endif; ?>
		
		<h1 class="article-header"  itemprop="name">
			<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
				<a href="<?php echo $this->item->readmore_link; ?>" itemprop="url"> <?php echo $this->escape($this->item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
		</h1>
	<?php endif; ?>


	<?php 
		if (isset ($this->item->toc)) :
			echo $this->item->toc;
		endif; 
	?>

	<?php  if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>
	
	<?php if ($params->get('access-view')):?>
	
	<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
	echo $this->item->pagination;
 endif;
?>
	<span itemprop="articleBody">
		<?php echo $this->item->text; ?>
	</span>
	
	<?php 
	//ToDo: move it to the proper place when config save will be available.
	if (isset($urls) AND ((!empty($urls->urls_position) AND ($urls->urls_position=='0')) OR  ($params->get('urls_position')=='0' AND empty($urls->urls_position) ))
		OR (empty($urls->urls_position) AND (!$params->get('urls_position')))): ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	
	<?php if (isset($urls) AND ((!empty($urls->urls_position)  AND ($urls->urls_position=='1')) OR ( $params->get('urls_position')=='1') )): ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	
	<?php if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND!$this->item->paginationrelative): ?>
		<?php echo $this->item->pagination; ?>
	<?php endif; ?>
	
	<?php //optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true and  $user->get('guest') ) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JURI($link1);?>
	<p class="readmore"> <a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs);  ?>
		<?php
		if ($attribs->alternative_readmore == null) :
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
		</a> </p>
	<?php endif; ?>
	<?php endif; ?>
	<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND $this->item->paginationrelative):
	echo $this->item->pagination;
?>
	<?php endif; ?>
	</div>
	
	<gavern:social><div id="gkSocialAPI"></gavern:social>
			<gavern:social><fb:like href="<?php echo $cur_url; ?>" GK_FB_LIKE_SETTINGS></fb:like></gavern:social>
		    <gavern:social><g:plusone GK_GOOGLE_PLUS_SETTINGS callback="<?php echo $cur_url; ?>"></g:plusone></gavern:social>
	        <gavern:social><g:plus action="share" GK_GOOGLE_PLUS_SHARE_SETTINGS href="<?php echo $cur_url; ?>"></g:plus></gavern:social>
		    <gavern:social><a href="http://twitter.com/share" class="twitter-share-button" data-text="<?php echo $this->item->title; ?>" data-url="<?php $cur_url; ?>"  gk_tweet_btn_settings>Tweet</a></gavern:social>
			<gavern:social><a href="http://pinterest.com/pin/create/button/?url=<?php echo $cur_url; ?>&amp;media=<?php echo $pin_image; ?>&amp;description=<?php echo str_replace(" ", "%20", $this->item->title); ?>" class="pin-it-button" count-layout="GK_PINTEREST_SETTINGS"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="<?php echo JText::_('TPL_GK_LANG_PINIT_TITLE'); ?>" /></a></gavern:social>
	
	
	<gavern:social></div></gavern:social>
	
	<?php echo $this->item->event->afterDisplayContent; ?> 
</div>
