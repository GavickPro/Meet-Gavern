<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	Templates.strapped
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		3.0
 */

// no direct access
defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the submenu style, you would use the following include:
 * <jdoc:include type="module" name="test" style="submenu" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

$position_counter = array();

function modChrome_gk_style($module, $params, $attribs) {	
	// ToDo: remember that spanX is not proper in all layout settings
	global $position_counter;
	
	$pos = '';
	$modamount = 6;
	
	if(isset($attribs['modamount'])) {
		$modamount = (int) $attribs['modamount'];
	}
	
	if(isset($attribs['modpos'])) {
		$pos = $attribs['modpos'];
		
		if(isset($position_counter[$pos])) {
			$position_counter[$pos] += 1;
		} else {
			$position_counter[$pos] = 1;
		}
	}
	
	if (!empty ($module->content)) {		
		$modnum_class = '';
		
		if(isset($attribs['modnum'])) {
			$num = $attribs['modnum'];

			if($num >= $modamount) {
				$numrest = $num % $modamount;
				
				if($numrest == 0) {
					$modnum_class = ($modamount == 4) ? ' span3' : ' span2';
				} else {
					if($pos != '') {
						 if($position_counter[$pos] > $num - $numrest) {
						 	if($numrest == 1) {
						 		$modnum_class = ' span12';
						 	} elseif($numrest == 2) {
						 		$modnum_class = ' span6';
						 	} elseif($numrest == 3) {
						 		$modnum_class = ' span4';
						 	} elseif($numrest == 4) {
						 		$modnum_class = ' span3';
						 	} elseif($numrest == 5) {
						 		if(
						 			$position_counter[$pos] % 6 == 1 ||
						 			$position_counter[$pos] % 6 == 5
						 		) {
						 			$modnum_class = ' span3';	
						 		} else {
						 			$modnum_class = ' span2';
						 		}
						 	}
						 } else {
						 	$modnum_class = ($modamount == 4) ? ' span3' : ' span2';
						 }
					} else {
						$modnum_class = ' span2';
					}
				}
			} else {
				if($num == 1) {
					$modnum_class = ' span12';
				} elseif($num == 2) {
					$modnum_class = ' span6';
				} elseif($num == 3) {
					$modnum_class = ' span4';
				} elseif($num == 4) {
					$modnum_class = ' span3';
				} elseif($num == 5) {
					if(
						$position_counter[$pos] % 6 == 1 ||
						$position_counter[$pos] % 6 == 5
					) {
						$modnum_class = ' span3';	
					} else {
						$modnum_class = ' span2';
					}
				} 
			}
		}
		
		if($pos != '' && isset($position_counter[$pos])) { 
			if($position_counter[$pos] == 1) {
				echo '<div class="row-fluid">' . "\n";
			}
		}
		
		if($pos != '' && isset($position_counter[$pos])) {
			if($position_counter[$pos] > 1 && $position_counter[$pos] % $modamount == 1) {
				echo '</div>' . "\n";
				echo '<div class="row-fluid">' . "\n";
			}
		}
		
		echo '<'.($params->get('module_tag', 'div')).' class="box ' . $params->get('moduleclass_sfx') . $modnum_class . '">';
		echo '<div>';
		
		if($module->showtitle) {
			$icons = array();	
			preg_match('(icon([\-a-zA-Z0-9]){1,})', $module->title, $icons);
			// icon text (if exists)
			$icon = '';
			//
			if(count($icons) > 0) {
				$icon = '<i class="'.$icons[0].'"></i> ';
			}
			//
			$title = preg_replace('@(\[icon([\-a-zA-Z0-9]){1,}\])@', '', $module->title);
			//
			echo '<'.($params->get('header_tag', 'h3')).' class="header '.($params->get('header_class', '')).'">'.$icon.$title.'</'.($params->get('header_tag', 'h3')).'>';
		}
	
		echo '<div class="content">' . $module->content . '</div>';
		echo '</div>';
		echo '</'.($params->get('module_tag', 'div')).'>';
		
		if($pos != '' && isset($position_counter[$pos])) {
			if($position_counter[$pos] == $attribs['modnum']) {
				echo '</div>' . "\n";
			}
		}
	}
}

// EOF