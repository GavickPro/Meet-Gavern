<?php

/**
 *
 * GK Menu class
 *
 * based on T3 Framework menu class
 *
 * @version             3.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2012 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

if (!defined('_GK_MENU_CLASS')) {
    define('_GK_MENU_CLASS', 1);
    require_once (dirname(__file__) . DS . "GKBase.class.php");

    class GKMenu extends GKMenuBase {
        function __construct($params) {
            $params->set('gkmenu', 1);
            parent::__construct($params);
            $doc = JFactory::getDocument();
            $doc->addStyleDeclaration('.gkcol { width: '.$this->getParam('menu_col_width', 200).'px; }');
        }

        function beginMenu($startlevel = 0, $endlevel = 10) {
            echo "<nav id=\"gkExtraMenu\" class=\"gkMenu\">\n";
        }

        function endMenu($startlevel = 0, $endlevel = 10) {
            echo "\n</nav>";
        }

        function beginMenuItems($pid = 0, $level = 0, $return = false) {
            if ($level) {
                if ($this->items[$pid]->gkparams->get('group')) {
                    $data = "<div class=\"gk-group-content\">";
                } else {
                    $style = $this->getParam('gk-style', 1);
                    if (!method_exists($this, "beginMenuItems$style")) $style = 1; //default
                    $data = call_user_func_array(array($this, "beginMenuItems$style"), array($pid, $level, true));
                }
                
                if ($return) return $data; else echo $data;
            }
        }

        function endMenuItems($pid = 0, $level = 0, $return = false) {
            if ($level) {
                if ($this->items[$pid]->gkparams->get('group')) {
                    $data = "</div>";
                } else {
                    $style = $this->getParam('gk-style', 1);
                    if (!method_exists($this, "endMenuItems$style")) $style = 1; //default
                    $data = call_user_func_array(array($this, "endMenuItems$style"), array($pid, $level, true));
                }
                
                if ($return) return $data; else echo $data;
            }
        }

        function beginSubMenuItems($pid = 0, $level = 0, $pos, $i, $return = false) {
            $data = '';
            if (isset($this->items[$pid]) && $level && !$this->items[$pid]->gkparams->get('group')) {
                $data .= "<div class=\"gkcol gkcol".($this->items[$pid]->gkparams->get('cols'))." ".($pos?" $pos":"")."\">";
            }
            if (@$this->children[$pid]) $data .= "<ul class=\"gkmenu level$level\">";
            
            if ($return) return $data; else echo $data;
        }

        function endSubMenuItems($pid = 0, $level = 0, $return = false) {
            $data = '';
            if (@$this->children[$pid]) $data .= "</ul>";
            
            if (isset($this->items[$pid]) && $level && !$this->items[$pid]->gkparams->get('group')) $data .= "</div>";
          
            if ($return) return $data; else echo $data;
        }

        function beginSubMenuModules($item, $level = 0, $pos, $i, $return = false) {
			$data = '';
           	if ($level && !$item->gkparams->get('group')) {
				$data .= "<div class=\"gkcol gkcol".($this->items[$pid]->gkparams->get('cols'))." ".($pos?" $pos":"")."\">";
			}
			
			if ($return) return $data; else echo $data;
		}


        function endSubMenuModules($item, $level = 0, $return = false) {
            $data = '';
            if ($level && !$item->gkparams->get('group')) $data = "</div>";
            
            if ($return) return $data;
            else echo $data;
        }

        function genClass($mitem, $level, $pos) {
            $iParams = new JParameter($mitem->params);
            $cls =  ($pos ? " $pos" : "");
            if (@$this->children[$mitem->id] || (isset($mitem->content) && $mitem->content)) {
                if ($mitem->gkparams->get('group'))
                    $cls .= " group";
                else
                    if ($level < $this->getParam('endlevel') && isset($this->children[$mitem->id][0]))
                    $cls .= " haschild";
            }
            $active = in_array($mitem->id, $this->open);
            if (!preg_match('/group/', $cls))
                $cls .= ($active ? " active" : "");
            if ($mitem->gkparams->get('class'))
                $cls .= " " . $mitem->gkparams->get('class');
            return $cls;
        }

        function beginMenuItem($mitem = null, $level = 0, $pos = '') {
            $active = trim($this->genClass($mitem, $level, $pos));
            if ($active) $active = " class=\"$active\"";
            echo "<li $active>";
            if ($mitem->gkparams->get('group')) echo "<div class=\"group\">";
        }
        function endMenuItem($mitem = null, $level = 0, $pos = '') {
            if ($mitem->gkparams->get('group')) echo "</div>";
            echo "</li>";
        }

        function beginMenuItems1($pid = 0, $level = 0, $return = false) {
            $cols = $pid && $this->getParam('gkmenu') && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
            $width = 0;
            for ($col = 0; $col < $cols; $col++) $width += $this->getParam('menu_col_width', 200);
            
            $style = "#menu" . ($this->items[$pid]->id) . " > div,\n#menu" . ($this->items[$pid]->id) . " > div > .childcontent-inner { width: ".$width."px; }\n";
            
            $type_class = $this->items[$pid]->gkparams->get('subcontent') == 'pos' ? 'module' : 'childcontent';
            if($type_class == 'module') $style = '';
            
            $doc = JFactory::getDocument();
            $doc->addStyleDeclaration($style);
            
            $data = "<div class=\"$type_class\">\n<div class=\"$type_class-inner\">\n";
            if ($return) return $data; else echo $data;
        }

		function endMenuItems1($pid=0, $level=0, $return = false){
			$data = "\n</div>\n</div>";
			if($return) return $data; else echo $data;
		}
		
		function getParam($paramName, $default = null) {
            return $this->_params->get($paramName, $default);
        }
    }
}