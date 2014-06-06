<?php

// No direct access.
defined('_JEXEC') or die;
$logo_image = $this->API->get('logo_image', '');

if(($logo_image == '') || ($this->API->get('logo_type', '') == 'css')) {
     $logo_image = $this->API->URLtemplate() . '/images/logo.png';
} else {
     $logo_image = $this->API->URLbase() . $logo_image;
}

$logo_text = $this->API->get('logo_text', '') != '' ? $this->API->get('logo_text', '') : $this->API->getPageName();
$logo_slogan = $this->API->get('logo_slogan', '');

?>

<?php if ($this->API->get('logo_type', 'image')!=='none'): ?>
     <?php if($this->API->get('logo_type', 'image') == 'css') : ?>
     <a href="./" id="gk-logo" class="pull-left css-logo">
     	<?php echo $logo_text . ' - ' . $logo_slogan; ?>
     </a>
     <?php elseif($this->API->get('logo_type', 'image')=='text') : ?>
     <a href="./" id="gk-logo" class="text-logo pull-left">
		<span><?php echo $logo_text; ?></span>
        <small class="gk-logo-slogan"><?php echo $logo_slogan; ?></small>
     </a>
     <?php elseif($this->API->get('logo_type', 'image')=='image') : ?>
     <a href="./" id="gk-logo" class="pull-left">
        <img src="<?php echo $logo_image; ?>" alt="<?php echo $logo_text . ' - ' . $logo_slogan; ?>" />
     </a>
     <?php endif; ?>
<?php endif; ?>