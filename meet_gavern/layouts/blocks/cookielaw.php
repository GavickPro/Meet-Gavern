<?php

// No direct access.
defined('_JEXEC') or die;

?>
 <?php if($this->API->get('cookie_consent', '0') == '1') : ?>
 	<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
      <!-- cookie conset latest version or not -->
      <?php if($this->API->get('cookie_latest_version', '0') == '0') : ?>
           <?php if($this->API->get('cookiecss', '1') == '1') : ?><link rel="stylesheet" type="text/css" href="https://s3-eu-west-1.amazonaws.com/assets.cookieconsent.silktide.com/1.0.7/style.min.css"/><?php endif; ?>
           <script type="text/javascript" src="https://s3-eu-west-1.amazonaws.com/assets.cookieconsent.silktide.com/1.0.7/plugin.min.js"></script>
      <?php else : ?>
           <?php if($this->API->get('cookiecss', '1') == '1') : ?><link rel="stylesheet" type="text/css" href="https://s3-eu-west-1.amazonaws.com/assets.cookieconsent.silktide.com/current/style.min.css"/><?php endif; ?>
           <script type="text/javascript" src="https://s3-eu-west-1.amazonaws.com/assets.cookieconsent.silktide.com/current/plugin.min.js"></script>
      <?php endif; ?>
    
      <script type="text/javascript">
      // <![CDATA[
      cc.initialise({
           cookies: {
                social: {},
                analytics: {}
           },
           settings: {
                bannerPosition: <?php echo '"'.$this->API->get('banner_position', 'bottom').'"'; ?>,
                consenttype: <?php echo '"'.$this->API->get('consenttype', 'explicit').'"'; ?>,
                onlyshowbanneronce: false,
                style: <?php echo '"'.$this->API->get('cookie_style', 'light').'"'; ?>,
                refreshOnConsent: <?php if($this->API->get('refreshOnConsent', '0') == '1') : ?>true<?php else : ?>false<?php endif; ?>,
                useSSL: <?php if($this->API->get('cookie_use_ssl', '0') == '1') : ?>true<?php else : ?>false<?php endif; ?>,
                tagPosition: <?php echo '"'.$this->API->get('banner_tag_placement', 'bottom-right').'"'; ?>
           },
           strings: {
                socialDefaultTitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_SOCIALDEFAULTTITLE'); ?>',
                socialDefaultDescription: '<?php echo JText::_('TPL_GK_LANG_COOKIE_SOCIALDEFAULTDESCRIPTION'); ?>',
                analyticsDefaultTitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ANALYTICSDEFAULTTITLE'); ?>',
                analyticsDefaultDescription: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ANALYTICSDEFAULTDESCRIPTION'); ?>',
                advertisingDefaultTitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ADVERTISINGDEFAULTTITLE'); ?>',
                advertisingDefaultDescription: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ADVERTISINGDEFAULTDESCRIPTION'); ?>',
                defaultTitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_DEFAULTTITLE'); ?>',
                defaultDescription: '<?php echo JText::_('TPL_GK_LANG_COOKIE_DEFAULTDESCRIPTION'); ?>',
                learnMore: '<?php echo JText::_('TPL_GK_LANG_COOKIE_LEARNMORE'); ?>',
                closeWindow: '<?php echo JText::_('TPL_GK_LANG_COOKIE_CLOSEWINDOW'); ?>',
                notificationTitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_NOTIFICATIONTITLE'); ?>',
                notificationTitleImplicit: '<?php echo JText::_('TPL_GK_LANG_COOKIE_NOTIFICATIONTITLEIMPLICIT'); ?>',
                customCookie: '<?php echo JText::_('TPL_GK_LANG_COOKIE_CUSTOMCOOKIE'); ?>',
                seeDetails: '<?php echo JText::_('TPL_GK_LANG_COOKIE_SEEDETAILS'); ?>',
                seeDetailsImplicit: '<?php echo JText::_('TPL_GK_LANG_COOKIE_SEEDETAILSIMPLICIT'); ?>',
                hideDetails: '<?php echo JText::_('TPL_GK_LANG_COOKIE_HIDEDETAILS'); ?>',
                allowCookies: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ALLOWCOOKIES'); ?>',
                allowCookiesImplicit: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ALLOWCOOKIESIMPLICIT'); ?>',
                allowForAllSites: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ALLOWFORALLSITES'); ?>',
                savePreference: '<?php echo JText::_('TPL_GK_LANG_COOKIE_SAVEPREFERENCE'); ?>',
                saveForAllSites: '<?php echo JText::_('TPL_GK_LANG_COOKIE_SAVEFORALLSITES'); ?>',
                privacySettings: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PRIVACYSETTINGS'); ?>',
                privacySettingsDialogTitleA: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PRIVACYSETTINGSDIALOGTITLEA'); ?>',
                privacySettingsDialogTitleB: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PRIVACYSETTINGSDIALOGTITLEB'); ?>',
                privacySettingsDialogSubtitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PRIVACYSETTINGSDIALOGSUBTITLE'); ?>',
                changeForAllSitesLink: '<?php echo JText::_('TPL_GK_LANG_COOKIE_CHANGEFORALLSITESLINK'); ?>',
                preferenceUseGlobal: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCEUSEGLOBAL'); ?>',
                preferenceConsent: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCECONSENT'); ?>',
                preferenceDecline: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCEDECLINE'); ?>',
                notUsingCookies: '<?php echo JText::_('TPL_GK_LANG_COOKIE_NOTUSINGCOOKIES'); ?>.',
                allSitesSettingsDialogTitleA: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ALLSITESSETTINGSDIALOGTITLEA'); ?>',
                allSitesSettingsDialogTitleB: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ALLSITESSETTINGSDIALOGTITLEB'); ?>',
                allSitesSettingsDialogSubtitle: '<?php echo JText::_('TPL_GK_LANG_COOKIE_ALLSITESSETTINGSDIALOGSUBTITLE'); ?>',
                backToSiteSettings: '<?php echo JText::_('TPL_GK_LANG_COOKIE_BACKTOSITESETTINGS'); ?>',
                preferenceAsk: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCEASK'); ?>',
                preferenceAlways: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCEALWAYS'); ?>',
                preferenceNever: '<?php echo JText::_('TPL_GK_LANG_COOKIE_PREFERENCENEVER'); ?>'
 }
      });
      // ]]>
      </script>
      <!-- End Cookie Consent plugin -->
 <?php endif; ?>