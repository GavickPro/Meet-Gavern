<?php

// This is the code which will be placed in the head section

// No direct access.
defined('_JEXEC') or die;
//
$doc = JFactory::getDocument();
//
$this->addTemplateFavicon();
// check the color version
$template_style = $this->getTemplateStyle('style');
// Add Stylesheets
$this->API->addCSS($this->API->URLtemplate().'/bootstrap/output/bootstrap.css');
$this->API->addCSS($this->API->URLtemplate().'/bootstrap/output/bootstrap-responsive.css');
$this->API->addCSS($this->API->URLtemplate().'/css/template.css');
$this->API->addCSS($this->API->URLtemplate() . '/css/font-awesome.css');
// generate the max-width rules
$this->API->addCSSRule('.container-fluid { max-width: '.$this->API->get('max_page_width', '1200').'px!important; }');
// generate the submenu width
$this->API->addCSSRule('.gk-main-menu ul ul { width: '.$this->API->get('submenu_width', '200').'px; } .gk-main-menu ul ul ul { margin-left: '.(0.9 * $this->API->get('submenu_width', '200')).'px; }');

// typography CSS

// CSS override on two methods
if($this->API->get("css_override", '0')) {
	$this->API->addCSS($this->API->URLtemplate() . '/css/override.css');
}

$this->API->addCSSRule($this->API->get('css_custom', ''));

// include fonts
$font_iter = 1;

while($this->API->get('font_name_group'.$font_iter, 'gkFontNull') !== 'gkFontNull') {
	$font_data = explode(';', $this->API->get('font_name_group'.$font_iter, ''));
	if(isset($font_data) && count($font_data) >= 2) {
		$font_type = $font_data[0];
		$font_name = $font_data[1];
		if($this->API->get('font_rules_group'.$font_iter, '') != ''){
			if($font_type == 'standard') {
				$this->API->addCSSRule($this->API->get('font_rules_group'.$font_iter, '') . ' { font-family: ' . $font_name . '; }'."\n");
			} elseif($font_type == 'google') {
				$font_link = $font_data[2];
				$font_family = $font_data[3];
				
				$this->API->addCSS($font_link);						
				$this->API->addCSSRule($this->API->get('font_rules_group'.$font_iter, '') . ' { font-family: \''.$font_family.'\', Arial, sans-serif; }'."\n");
			} elseif($font_type == 'squirrel') {
				$this->API->addCSS($this->API->URLtemplate() . '/fonts/' . $font_name . '/stylesheet.css');	
				$this->API->addCSSRule($this->API->get('font_rules_group'.$font_iter, '') . ' { font-family: ' . $font_name . ', Arial, sans-serif; }'."\n");
			} elseif($font_type == 'edge') {
	            $font_link = $font_data[2];
	            $font_family = $font_data[3];
	            
	            $this->API->addJS($font_link);
	            $this->API->addCSSRule($this->API->get('font_rules_group'.$font_iter, '') . ' { font-family: ' . $font_family . ', sans-serif; }'."\n");
	        }
		}
	}
	
	$font_iter++;
}

// load prefixer
if($this->API->get("css_prefixer", '0')) {
	$this->API->addJS($this->API->URLtemplate() . '/js/prefixfree.js');
}

$this->API->addJSFragment('$GK_MENU = [];' . "\n" . '$GK_MENU[\'animation\'] = "' . $this->API->get('menu_animation', 'width_height_opacity') . "\";\n" . '$GK_MENU[\'animation_speed\'] = "' . $this->API->get('menu_speed', 'fast') . "\";\n");
$this->API->addJSFragment( "\n".'$GK_TMPL_URL = "' . $this->API->URLtemplate() . '";'."\n" );
$this->API->addJSFragment( "\n".'$GK_URL = "' . $this->API->URLbase() . '";'."\n" );
// include JavaScript
$this->API->addJS($this->API->URLtemplate().'/js/bootstrap.js');
$this->API->addJS($this->API->URLtemplate().'/js/page.js');
$this->API->addJS($this->API->URLtemplate().'/js/gk.menu.js');
// load CSS compresssion
if($this->API->get('css_compression', '0') == 1 || $this->API->get('css_cache', '0') == 1) {
	$this->cache->registerCache();
}
// load JS compression
if($this->API->get('js_compression', '0') == 1 ) {
	$this->cache->registerJSCompression();
}
	
?>

<!--[if IE 9]>
<link rel="stylesheet" href="<?php echo $this->API->URLtemplate(); ?>/css/ie/ie9.css" type="text/css" />
<![endif]-->

<!--[if IE 8]>
<link rel="stylesheet" href="<?php echo $this->API->URLtemplate(); ?>/css/ie/ie8.css" type="text/css" />
<![endif]-->

<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo $this->API->URLtemplate(); ?>/css/ie/ie7.css" type="text/css" />
<![endif]-->

<!--[if (gte IE 6)&(lte IE 8)]>
<script type="text/javascript" src="<?php echo $this->API->URLtemplate() . '/js/respond.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $this->API->URLtemplate() . '/js/selectivizr.js'; ?>"></script>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->