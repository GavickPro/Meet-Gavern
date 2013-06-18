<?php

/**
 * @package		K2
 * @author		GavickPro http://gavick.com
 */

// no direct access
defined('_JEXEC') or die;

?>

<section id="k2Container" class="genericView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
		<?php if($this->params->get('show_page_title') || JRequest::getCmd('task')=='search' || JRequest::getCmd('task')=='date'): ?>
		<header>
			<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
		</header>
		<?php endif; ?>
		
		<?php if(JRequest::getCmd('task')=='search' && $this->params->get('googleSearch')): ?>
		<!-- Google Search container -->
		<div id="<?php echo $this->params->get('googleSearchContainer'); ?>"></div>
		<?php endif; ?>
		
		<?php if(count($this->items)): ?>
		<div class="itemList">
				<?php 
				$gkItemCounter = 0;
				foreach($this->items as $item): 
				$gkItemCounter++;
				?>
				<article class="itemView<?php if($gkItemCounter % 2 == 1) : ?> itemViewClear<?php endif; ?>">
				
					<?php if($this->params->get('genericItemImage') && !empty($item->imageGeneric)): ?>
					<div class="itemImageBlock"> <a class="itemImage" href="<?php echo $item->link; ?>" title="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>"> <img src="<?php echo $item->imageGeneric; ?>" alt="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>" style="width:<?php echo $this->params->get('itemImageGeneric'); ?>px; height:auto;" /> </a> </div>
					<?php endif; ?>
					
					<header>
						<?php if($this->params->get('genericItemCategory') || $this->params->get('genericItemDateCreated')): ?>
						<ul>
							<?php if($this->params->get('genericItemCategory')): ?>
							<li class="itemCategory"> <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span> <a href="<?php echo $item->category->link; ?>"><?php echo $item->category->name; ?></a> </li>
							<?php endif; ?>
							
							<?php if($this->params->get('genericItemDateCreated')): ?>
							<li class="itemDate">
								<time datetime="<?php echo JHtml::_('date', $item->created, DATE_W3C); ?>">
									<?php echo JHTML::_('date', $item->created , JText::_('F j, Y')); ?> 
								</time>
							</li>
							<?php endif; ?>
						</ul>
						<?php endif; ?>
						
						<?php if($this->params->get('genericItemTitle')): ?>
						<h2>
							<?php if ($this->params->get('genericItemTitleLinked')): ?>
							<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
							<?php else: ?>
							<?php echo $item->title; ?>
							<?php endif; ?>
						</h2>
						<?php endif; ?>
					</header>
				
					<div class="itemBody">
							<?php if($this->params->get('genericItemIntroText')): ?>
							<div class="itemIntroText"> <?php echo $item->introtext; ?> </div>
							<?php endif; ?>
					</div>
					<?php if($this->params->get('genericItemExtraFields') && count($item->extra_fields)): ?>
					<div class="itemExtraFields">
						<h4><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h4>
						<ul>
							<?php foreach ($item->extra_fields as $key=>$extraField): ?>
							<?php if($extraField->value != ''): ?>
							<li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
								<?php if($extraField->type == 'header'): ?>
								<h4 class="genericItemExtraFieldsHeader"><?php echo $extraField->name; ?></h4>
								<?php else: ?>
								<span class="genericItemExtraFieldsLabel"><?php echo $extraField->name; ?></span>
								<span class="genericItemExtraFieldsValue"><?php echo $extraField->value; ?></span>
								<?php endif; ?>
							</li>
							<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					<?php if ($this->params->get('genericItemReadMore')): ?>
					<a class="itemReadMore button" href="<?php echo $item->link; ?>"> <?php echo JText::_('K2_READ_MORE'); ?> </a>
					<?php endif; ?>
				</article>
				<?php endforeach; ?>
		</div>
		<?php if(count($this->items) && $this->params->get('genericFeedIcon',1)): ?>
		<a class="k2FeedIcon" href="<?php echo $this->feed; ?>"><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></a>
		<?php endif; ?>
		<?php if($this->pagination->getPagesLinks()): ?>
		<?php echo str_replace('</ul>', '<li class="counter">'.$this->pagination->getPagesCounter().'</li></ul>', $this->pagination->getPagesLinks()); ?>
		<?php endif; ?>
		<?php endif; ?>
</section>
