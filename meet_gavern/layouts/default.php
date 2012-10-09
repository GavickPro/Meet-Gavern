<?php
/**
 * @package		Joomla.Site
 * @subpackage	Templates.strapped
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		3.0
 */
 
// no direct access
defined('_JEXEC') or die;

// get important objects
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
// Add current user information
$user = JFactory::getUser();

// get the option and view value
$option = JRequest::getCmd('option');
$view = JRequest::getCmd('view');

// username-less login
if($this->API->get('usernameless_login', 0)) {
	if(
		$user->id != 0 && 
		(!isset($_COOKIE['gkusernameless']) || $_COOKIE['gkusernameless'] != md5(strtolower(trim($user->email))) . ',' . $user->name . ',' . $user->username)
	) {
		setcookie('gkusernameless', md5(strtolower(trim($user->email))) . ',' . $user->name . ',' . $user->username, time()+60*60*24*365, '/');
	}
}
// Adjusting content width
$span = 12;
$sidebar_width = $this->API->get('sidebar_width', 3);

if ($this->API->modules('sidebar')) {
	$span = 12 - $sidebar_width;
}

?>
<!DOCTYPE html>
<html lang="<?php echo $this->APITPL->language; ?>">
<head>
	<?php if($this->browser->get('browser') == 'ie8' || $this->browser->get('browser') == 'ie7' || $this->browser->get('browser') == 'ie6') : ?>
	<meta http-equiv="X-UA-Compatible" content="IE=9">
	<?php endif; ?>
	<?php if($this->API->get("chrome_frame_support", '0') == '1' && ($this->browser->get('browser') == 'ie8' || $this->browser->get('browser') == 'ie7' || $this->browser->get('browser') == 'ie6')) : ?>
	<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
	<?php endif; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<jdoc:include type="head" />
	<?php $this->layout->loadBlock('head'); ?>
	<?php $this->layout->loadBlock('cookielaw'); ?>
</head>

<body<?php if($this->browser->get("tablet") == true) echo ' data-tablet="true"'; ?><?php if($this->browser->get("mobile") == true) echo ' data-mobile="true"'; ?>>
	<?php if(count($app->getMessageQueue())) : ?>
	<div class="container-fluid gk-message">
		<jdoc:include type="message" />
	</div>
	<?php endif; ?>

	<header class="container-fluid">
		<?php $this->layout->loadBlock('logo'); ?>
		
		<div class="gk-mainmenu-toggle">
			<a href="#" data-toggle="collapse" data-target=".nav-collapse">
		  		<?php echo JText::_('TPL_GK_LANG_MAINMENU'); ?>
			</a>
		</div>
		
		<div class="nav-collapse gk-main-menu">
			<jdoc:include type="modules" name="menu" style="none" />
		</div>
	</header>

	<?php if($this->API->modules('header')) : ?>
	<section id="gk-header">
		<div class="container-fluid">
			<jdoc:include type="modules" name="header" style="none" />
		</div>
	</section>
	<?php endif; ?>

	<section id="gk-main">
		<section class="container-fluid">
			<?php if($this->API->modules('top')) : ?>
			<section id="gk-top" class="row-fluid">
				<jdoc:include type="modules" name="top" modpos="top" modnum="<?php echo $this->API->modules('top'); ?>" modamount="4" style="gk_style" />
			</section>
			<?php endif; ?>
			
			<section class="gk-content-wrap row-fluid">
				<section id="gk-content" class="span<?php echo $span;?>">
					<?php if($this->API->modules('breadcrumb')) : ?>
					<section id="gk-breadcrumb">
						<jdoc:include type="modules" name="breadcrumb" style="none" />
					</section>
					<?php endif; ?>
					
					<?php if($this->API->modules('mainbody_top')) : ?>
					<section id="gk-mainbody-top">
						<jdoc:include type="modules" name="mainbody_top" style="gk_style" />
					</section>
					<?php endif; ?>
					
					<?php if($this->layout->isFrontpage() && $this->API->modules('mainbody')) : ?>
					<section id="gk-mainbody">
						<jdoc:include type="modules" name="mainbody" style="gk_style" />
					</section>
					<?php else : ?>
					<section id="gk-component">
						<jdoc:include type="component" />
					</section>
					<?php endif; ?>
					
					<?php if($this->API->modules('mainbody_bottom')) : ?>
					<section id="gk-mainbody-bottom">
						<jdoc:include type="modules" name="mainbody_bottom" style="gk_style" />
					</section>
					<?php endif; ?>
				</section>
				
				<?php if ($this->API->modules('sidebar + search')): ?>
				<aside id="sidebar" class="span<?php echo $sidebar_width; ?>">
					<?php if ($this->API->modules('search')): ?>	
					<div class="sidebar-search">
						<jdoc:include type="modules" name="search" style="gk_style" />
					</div>
					<?php endif; ?>
					
					<div class="sidebar-nav">
						<jdoc:include type="modules" name="sidebar" style="gk_style" />
					</div>
				</aside>
				<?php endif; ?>
			</section>
			
			<?php if($this->API->modules('bottom')) : ?>
			<section id="gk-bottom">
				<jdoc:include type="modules" name="bottom" modpos="bottom" modnum="<?php echo $this->API->modules('bottom'); ?>" style="gk_style" />
			</section>
			<?php endif; ?>
		</section>
	</section>
	
	<footer class="container-fluid">
		<?php if($this->API->modules('footer')) : ?>
		<jdoc:include type="modules" name="footer" style="none" />
		<?php endif; ?>
		
		<p class="pull-right gk-toplink"><a href="#top" id="back-top">Back to top</a></p>
		
		<p class="pull-right gk-copyrights">
		<?php if($this->API->get('copyrights', '') == '') : ?>
			&copy; Meet Gavern - <a href="http://www.gavick.com" title="Free Joomla! 3.0 Template">Free Joomla! 3.0 Template</a> <?php echo date('Y');?>
		<?php else : ?>
			<?php echo $this->API->get('copyrights', ''); ?>
		<?php endif; ?> 
		</p>
		
		<?php if($this->API->get('framework_logo', 1)) : ?>
		<a href="http://www.gavick.com" id="gk-framework-logo">Gavern Framework</a>
		<?php endif; ?>
		
		<p class="gk-disclaimer">GavickPro is not affiliated with or endorsed by Open Source Matters or the Joomla! Project.<br />
		The Joomla! logo is used under a limited license granted by Open Source Matters the trademark holder in the United States and other countries.<br />Icons from <a href="http://glyphicons.com">Glyphicons Free</a>, licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
	</footer>
		
	<?php if($this->API->modules('social')) : ?>
	<div id="gk-social-icons" class="<?php echo $this->API->get('social_pos', 'left'); ?>">
		<jdoc:include type="modules" name="social" style="none" />
	</div>
	<?php endif; ?>
	
	<script type="text/javascript">
		jQuery('*[rel=tooltip]').tooltip();
		jQuery('*[rel=popover]').popover();
		jQuery('.tip-bottom').tooltip({placement: "bottom"});
	</script>
	
	<?php $this->layout->loadBlock('social'); ?>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
<?php

// Rules to remove predefined jQuery and Bootstrap and MooTools More
GKParser::$customRules['/<script src="(.*?)media\/jui\/js\/jquery.min.js" type="text\/javascript"><\/script>/mi'] = '';
GKParser::$customRules['/<script src="(.*?)media\/jui\/js\/jquery-noconflict.js" type="text\/javascript"><\/script>/mi'] = '';
GKParser::$customRules['/<script src="(.*?)media\/jui\/js\/bootstrap.min.js" type="text\/javascript"><\/script>/mi'] = '';
GKParser::$customRules['/<script src="(.*?)media\/system\/js\/mootools-more.js" type="text\/javascript"><\/script>/mi'] = '';

// EOF