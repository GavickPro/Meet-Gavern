<?php

/**
 * @package		K2
 * @author		GavickPro http://gavick.com
 */

// no direct access
defined('_JEXEC') or die;

?>

<section id="k2Container" class="latestView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
	<?php if($this->params->get('show_page_title')): ?>
	<header>
		<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
  	</header>
  	<?php endif; ?>
  
  	<?php foreach($this->blocks as $key=>$block): ?>
  	<div class="itemsContainer" style="width:<?php echo number_format(99.0/$this->params->get('latestItemsCols'), 1); ?>%;">
    	<?php if($this->params->get('latestItemsCols') > 1) : ?>
  		<div class="itemsContainerWrap itemsContainerWrap<?php echo $this->params->get('latestItemsCols'); ?>">  
    	<?php endif; ?>
    
    	<?php if($this->source=='categories'): $category=$block; ?>
	    	<?php if($this->params->get('categoryFeed') || $this->params->get('categoryImage') || $this->params->get('categoryTitle') || $this->params->get('categoryDescription')): ?>
	    	<div class="itemsCategory">
	      		<?php if ($this->params->get('categoryImage') && !empty($category->image)): ?>
	      		<img src="<?php echo $category->image; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($category->name); ?>" style="width:<?php echo $this->params->get('catImageWidth'); ?>px;height:auto;" />
	      		<?php endif; ?>
	      		
	      		<?php if ($this->params->get('categoryTitle')): ?>
	      		<h2><a href="<?php echo $category->link; ?>"><?php echo $category->name; ?></a></h2>
	      		<?php endif; ?>
	      
	      		<?php if ($this->params->get('categoryDescription') && isset($category->description)): ?>
	      		<p><?php echo $category->description; ?></p>
	      		<?php endif; ?>
	      		
	      		<?php echo $category->event->K2CategoryDisplay; ?>
	    	</div>
	    
	    	<?php if($this->params->get('categoryFeed')): ?>
	    	<a class="k2FeedIcon" href="<?php echo $category->feed; ?>"><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></a>
	    	<?php endif; ?>
    	<?php endif; ?>
	    <?php else: $user=$block; ?>
		    <?php if ($this->params->get('userFeed') || $this->params->get('userImage') || $this->params->get('userName') || $this->params->get('userDescription') || $this->params->get('userURL') || $this->params->get('userEmail')): ?>
		    <div class="itemAuthorBlock">
		        <?php if ($this->params->get('userImage') && !empty($user->avatar)): ?>
		        <div class="gkAvatar">
		        	<img src="<?php echo $user->avatar; ?>" alt="<?php echo $user->name; ?>" style="width:<?php echo $this->params->get('userImageWidth'); ?>px;height:auto;" />
		        </div>
		        <?php endif; ?>
		        
		        <div class="itemAuthorDetails">
			        <?php if ($this->params->get('userName')): ?>
			        <h3><a rel="author" href="<?php echo $user->link; ?>"><?php echo $user->name; ?></a></h3>
			        <?php endif; ?>
			        
			        <?php if ($this->params->get('userDescription') && isset($user->profile->description)): ?>
			        	<?php echo $user->profile->description; ?>
			        <?php endif; ?>
			        
	
					<?php if ($this->params->get('userURL') && isset($user->profile->url)): ?>
					<span class="itemAuthorURL">
						<?php echo JText::_('K2_WEBSITE_URL'); ?>: <a rel="me" href="<?php echo $user->profile->url; ?>" target="_blank"><?php echo $user->profile->url; ?></a>
					</span>
					<?php endif; ?>
					
					<?php if ($this->params->get('userEmail')): ?>
						<span class="itemAuthorEmail"> <?php echo JText::_('K2_EMAIL'); ?>: <?php echo JHTML::_('Email.cloak', $user->email); ?> </span>
					<?php endif; ?>
		        </div>
		        <?php echo $user->event->K2UserDisplay; ?>
		    </div>
		    
		    <?php if($this->params->get('userFeed')): ?>
		    <a class="k2FeedIcon" href="<?php echo $user->feed; ?>"><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></a>
		    <?php endif; ?>
	    <?php endif; ?>
	    <?php endif; ?>
    
	    <div class="itemList">
	      <?php if($this->params->get('latestItemsDisplayEffect')=="first"): ?>
	      	<?php foreach ($block->items as $itemCounter=>$item): K2HelperUtilities::setDefaultImage($item, 'latest', $this->params); ?>
	      		<?php if($itemCounter==0): ?>
	      			<?php $this->item=$item; echo $this->loadTemplate('item'); ?>
	      		<?php else: ?>
	      		<h2 class="itemTitleList">
	        		<?php if ($item->params->get('latestItemTitleLinked')): ?>
	        			<a href="<?php echo $item->link; ?>"> <?php echo $item->title; ?> </a>
	        		<?php else: ?>
	        			<?php echo $item->title; ?>
	        		<?php endif; ?>
	      		</h2>
	      		<?php endif; ?>
	      	<?php endforeach; ?>
	      <?php else: ?>
	      	<?php foreach ($block->items as $item): K2HelperUtilities::setDefaultImage($item, 'latest', $this->params); ?>
	      		<?php $this->item=$item; echo $this->loadTemplate('item'); ?>
	      	<?php endforeach; ?>
	      <?php endif; ?>
	    </div>

    <?php if($this->params->get('latestItemsCols') > 1) : ?>
    </div>  
    <?php endif; ?>
  </div>
  
  <?php if(($key+1)%($this->params->get('latestItemsCols'))==0): ?>
  <div class="clr"></div>
  <?php endif; ?>
  <?php endforeach; ?>
</section>