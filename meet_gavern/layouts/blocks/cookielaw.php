<?php

// No direct access.
defined('_JEXEC') or die;

?>
 <?php if($this->API->get('cookie_consent', '0') == '1') : ?>
 	<!-- Begin Cookie Consent plugin by Silktide -->
     <script>
         window.cookieconsent_options = {
             message: '<?php echo JText::_('TPL_GK_LANG_COOKIE_NOTIFICATIONTITLEIMPLICIT'); ?>',
             dismiss: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCECONSENT'); ?>',
             learnMore: '<?php echo JText::_('TPL_GK_LANG_COOKIE_LEARNMORE'); ?>',
             link: <?php echo '"'.$this->API->get('cookie_url').'"'; ?>,
             theme: <?php echo '"'.$this->API->get('cookie_theme').'"'; ?>,
         };
     </script>

     <!-- Minified Cookie Consent served from CDN -->
     <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
      <!-- End Cookie Consent plugin -->
 <?php endif; ?>