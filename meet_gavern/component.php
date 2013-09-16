<?php
/**
* @copyright Copyright (C) 2008 JoomlaPraise. All rights reserved.
*/

// no direct access
defined('_JEXEC') or die;

// getting document object
$doc = JFactory::getDocument();

// Check for the print page
$print = JRequest::getCmd('print');
// Check for the mail page
$mailto = JRequest::getCmd('option') == 'com_mailto';

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
	<?php if($mailto == true) : ?>     
	<?php $this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/bootstrap/output/bootstrap.css'); ?>
	<?php endif; ?>
	
	<jdoc:include type="head" />
	
	<?php if($mailto == true) : ?>     
	<?php $this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/mail.css'); ?>
	<?php endif; ?>
	
	<?php if($print == 1) : ?>     
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/print.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/print.css" type="text/css" media="print" />
	<?php endif; ?>
</head>
<body class="contentpane">
	<?php 
		if($print == 1) : 
			$params = JFactory::getApplication()->getTemplate(true)->params;
			$logo_text = $params->get('logo_text', '') != '' ? $params->get('logo_text', '') : $params->getPageName();
			$logo_slogan = $params->get('logo_slogan', '');
	?>    
	<div id="gk-print-top">
		<img src="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/images/logo_print.png" alt="<?php echo $logo_text . ' - ' . $logo_slogan; ?>" />
	</div>
	<?php endif; ?>
	
	<jdoc:include type="message" />
	<jdoc:include type="component" />
	
	<?php 
	
		if($print == 1) : 
		
		function GKParserEmbed() {
			$body = JResponse::getBody();
			$body = preg_replace('/<gavern:fblogin(.*?)gavern:fblogin>/mis', '', $body);
			$body = preg_replace('/<gavern:social><fb:like(.*?)fb:like><\/gavern:social>/mi', '', $body);
			$body = preg_replace('/<gavern:social><g:plusone(.*?)g:plusone><\/gavern:social>/mi', '', $body);
			$body = preg_replace('/<gavern:social><a href="http:\/\/twitter.com\/share"(.*?)\/a><\/gavern:social>/mi', '', $body);
			$body = preg_replace('/<gavern:social><a href="http:\/\/pinterest.com\/pin\/create\/button\/(.*?)\/a><\/gavern:social>/mi', '', $body);
			$body = preg_replace('/<gavern:social>/mi', '', $body);
			$body = preg_replace('/<\/gavern:social>/mi', '', $body);
			$body = preg_replace('/<gavern:socialAPI>/mi', '', $body);
			$body = preg_replace('/<\/gavern:socialAPI>/mi', '', $body);
			
			JResponse::setBody($body);
		}
		
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->register('onAfterRender', 'GKParserEmbed');
		
	?>    
	<div id="gk-print-bottom">
		<?php if($params->get('copyrights', '') == '') : ?>
			&copy; Meet Gavern - <a href="http://www.gavick.com" title="Free Joomla! 3.0 Template">Free Joomla! 3.0 Template</a> <?php echo date('Y');?>
		<?php else : ?>
			<?php echo $params->get('copyrights', ''); ?>
		<?php endif; ?> 
	</div>
	<?php endif; ?>
</body>
</html>
