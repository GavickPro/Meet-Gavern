<?php

/**
 *
 * Main menu class
 *
 * based on T3 Framework menu class
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright           Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

jimport( 'joomla.html.parameter' );

if (!defined('_GK_BASE_MENU_CLASS')) {
    define('_GK_BASE_MENU_CLASS', 1);

    class GKMenuBase extends JObject {
        var $_params = null;
        var $children = null;
        var $open = null;
        var $items = null;
        var $Itemid = 0;
        var $showSeparatedSub = false;
        var $_tmpl = null;

        function __construct($params) {
            $acl = JFactory::getACL();
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $active = $menu->getActive();
            $active_id = isset($active) ? $active->id : $menu->getDefault()->id;      
            $this->_params = $params;
            $this->Itemid = $active_id;      
        }
        
        function getParam($paramName, $default = null) {
            return ($this->_params->get($paramName, null)) ? $this->_params->get($paramName, null) : $default;
        }

        function loadMenu($menuname = 'mainmenu') {
            $list = array();
            $db = JFactory::getDbo();
            $acl =JFactory::getACL();
            $user = JFactory::getUser();
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $aid = max ($user->getAuthorisedViewLevels());
            //find active element or set default
            $active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
            $this->open = $active->tree;
            $rows = $menu->getItems('menutype', $menuname);
            if (!count($rows)) return;
            $children = array();
            $this->items = array();

            foreach ($rows as $index => $v) {
                if (isset($v->title)) $v->name = $v->title;
                if (isset($v->parent_id)) $v->parent = $v->parent_id;
                $v->name = str_replace('&', '&amp;', str_replace('&amp', '&', $v->name));
                if ($v->access <= $aid) {
                    $ptr = $v->parent;
                    $list = @$children[$ptr] ? $children[$ptr] : array();
                    $v->gkparams = new JObject();
                    $v->gkparams = $gkparams = new JObject(json_decode($v->params));

                    if ($gkparams) {
                        foreach (get_object_vars($gkparams) as $gk_name => $gk_value) {
                            if (preg_match('/gk_(.+)/', $gk_name, $matches)) {
                                if (is_array($gk_value))
                                    $gk_value = implode(',', $gk_value);
                                $v->gkparams->set($matches[1], $gk_value);
                            }
                        }
                    }
                    // set cols to 1
                    if ($v->gkparams->get('group')) $v->gkparams->set('cols', 1);
                        
                    if ($v->gkparams->get('subcontent')=='pos') {
                        $modules = $this->loadModules ($v->gkparams);
                        if ($modules && count($modules)>0) {
                            $v->content = "";
                            $total = count($modules);
                            $cols =  min($v->gkparams->get('cols'), $total);
                            for ($col=0;$col<$cols;$col++) {
                                $pos = ($col == 0 ) ? 'first' : (($col == $cols-1) ? 'last' :'');
                                if ($cols > 1) $v->content .= $this->beginSubMenuModules($v->id, 1, $pos, $col, true);
                                $i = $col;
                                while ($i<$total) {
                                    $mod = $modules[$i];
                                    $i += $cols;
                                    $mod_params = new JObject(json_decode($mod->params));
                                    $v->content .= "<jdoc:include type=\"modules\" name=\"{$mod->position}\" style=\"".$v->gkparams->get('style','none')."\" />";
                                    
                                }
                                if ($cols > 1) $v->content .= $this->endSubMenuModules($v->id, 1, true);
                            }
                        
                            $v->cols = $cols;
                            $v->content = trim($v->content);
                            $this->items[$v->id] = $v;
                        }
                    }
                    // friendly links
                    $v->flink = $v->link;

                    switch ($v->type) {
                        case 'separator':
                            continue;
                        case 'url':
                            if ((strpos($v->link, 'index.php?') === 0) && (strpos($v->link, 'Itemid=') === false)) {
                                $v->flink = $v->link . '&Itemid=' . $v->id;
                            }
                            break;
                        case 'alias':
                            $v->flink = 'index.php?Itemid=' . $v->gkparams->get('aliasoptions');
                            break;
                        default:
                            $router = JSite::getRouter();
                            ($router->getMode() == JROUTER_MODE_SEF) ? $v->flink = 'index.php?Itemid=' . $v->id : $v->flink .= '&Itemid=' . $v->id;
                            break;
                    }
                    
                    $v->url = $v->flink = JRoute::_($v->flink);

                    if ($v->home == 1) $v->url = JURI::base();
                    // class suffix
                    if (!isset($v->clssfx)) {
                        $v->clssfx = $v->gkparams->get('pageclass_sfx', '');
                        if ($v->gkparams->get('cols')) {
                            $v->cols = $v->gkparams->get('cols');
                            $v->col = array();
                            for ($i = 0; $i < $v->cols; $i++) {
                                if ($v->gkparams->get("col$i"))
                                    $v->col[$i] = $v->gkparams->get("col$i");
                            }
                        }
                    }

                    $v->_idx = count($list);
                    array_push($list, $v);
                    $children[$ptr] = $list;
                    $this->items[$v->id] = $v;
                }
            }

            $this->children = $children;

            foreach ($this->items as $v) {
                if (($v->gkparams->get('subcontent') || $v->gkparams->get('modpos')) && !isset($this->
                    children[$v->id]) && (!isset($v->content) || !$v->content)) {
                    $this->remove_item($this->items[$v->id]);
                    unset($this->items[$v->id]);
                }
            }
        }

        function remove_item($item) {
            $result = array();
            
            foreach ($this->children[$item->parent] as $o) {
                if ($o->id != $item->id) $result[] = $o;
            }
            
            $this->children[$item->parent] = $result;
        }

        function parseTitle($title) {
            $title = str_replace(array('\\[', '\\]'), array('%open%', '%close%'), $title);
            $regex = '/([^\[]*)\[([^\]]*)\](.*)$/';
            
            if (preg_match($regex, $title, $matches)) {
                $title = $matches[1];
                $params = $matches[2];
                $desc = $matches[3];
            } else {
                $params = '';
                $desc = '';
            }
            
            $title = str_replace(array('%open%', '%close%'), array('[', ']'), $title);
            $desc = str_replace(array('%open%', '%close%'), array('[', ']'), $desc);
            $result = new JParameter('');
            $result->set('title', trim($title));
            $result->set('desc', trim($desc));
            
            if ($params) {
                if (preg_match_all('/([^\s]+)=([^\s]+)/', $params, $matches)) {
                    for ($i = 0; $i < count($matches[0]); $i++) {
                        $result->set($matches[1][$i], $matches[2][$i]);
                    }
                }
            }
            
            return $result;
        }

        function loadModules($params) {
            //Load module
            $modules = array();
            switch ($params->get ('subcontent')) {
                case 'pos':
                    $poses = $params->get ('subcontent_pos_positions','');
                    if (!$poses) $poses = $params->get ('subcontent-pos-positions','');

                    $poses = preg_split ('/,/', $poses);
                    foreach ($poses as $pos) {
                        $modules = array_merge ($modules, $this->getModules ($pos));
                    }
                    return $modules;
                    break;
                default:
                    return $this->loadModules_ ($params); 
            }
            return null;
        }
        
        function loadModules_($params) {
            $modules = array();
            if (($modid = $params->get('modid'))) {
                $ids = preg_split ('/,/', $modid);
                foreach ($ids as $id) {
                    if ($module=$this->getModule ($id)) $modules[] = $module;
                }
                return $modules;
            } 
            
            if (($modname = $params->get('modname'))) {
                $names = preg_split ('/,/', $modname);
                foreach ($names as $name) {
                    if (($module=$this->getModule (0, $name))) $modules[] = $module;
                }
                return $modules;
            }
            
            if (($modpos = $params->get('modpos'))) {
                $poses = preg_split ('/,/', $modpos);
                foreach ($poses as $pos) {
                    $modules = array_merge ($modules, $this->getModules ($pos));
                }
                return $modules;
            }
            return null;
        }
        
        function getModules ($position) {
            return JModuleHelper::getModules ($position);
        }

        function getModule ($id=0, $name='') {
            $Itemid = $this->Itemid;
            $app    = JFactory::getApplication();
            $user   = JFactory::getUser();
            $groups = implode(',', $user->authorisedLevels());
            $db     = JFactory::getDbo();

            $query = new JDatabaseQuery;
            $query->select('id, title, module, position, content, showtitle, params, mm.menuid');
            $query->from('#__modules AS m');
            $query->join('LEFT','#__modules_menu AS mm ON mm.moduleid = m.id');
            $query->where('m.published = 1');
            $query->where('m.id = '.$id);
            
            $date = JFactory::getDate();
            $now = $date->toMySQL();
            $nullDate = $db->getNullDate();
            $query->where('(m.publish_up = '.$db->Quote($nullDate).' OR m.publish_up <= '.$db->Quote($now).')');
            $query->where('(m.publish_down = '.$db->Quote($nullDate).' OR m.publish_down >= '.$db->Quote($now).')');
    
            $clientid = (int) $app->getClientId();
    
            if (!$user->authorise('core.admin',1)) {
                $query->where('m.access IN ('.$groups.')');
            }
            $query->where('m.client_id = '. $clientid);
            if (isset($Itemid)) {
                $query->where('(mm.menuid = '. (int) $Itemid .' OR mm.menuid <= 0)');
            }
            $query->order('position, ordering');
    
            // Filter by language
            if ($app->isSite() && $app->getLanguageFilter()) {
                $query->where('m.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
            }
    
            // Set the query
            $db->setQuery($query);
            $cache      = JFactory::getCache ('com_modules', 'callback');
            $cacheid    = md5(serialize(array($Itemid, $groups, $clientid, JFactory::getLanguage()->getTag(), $id)));
    
            $module = $cache->get(array($db, 'loadObject'), null, $cacheid, false);
            
            if (!$module) return null;
            
            $negId  = $Itemid ? -(int)$Itemid : false;
            // The module is excluded if there is an explicit prohibition, or if
            // the Itemid is missing or zero and the module is in exclude mode.
            $negHit = ($negId === (int) $module->menuid)
                    || (!$negId && (int)$module->menuid < 0);

            // Only accept modules without explicit exclusions.
            if (!$negHit)
            {
                //determine if this is a custom module
                $file               = $module->module;
                $custom             = substr($file, 0, 4) == 'mod_' ?  0 : 1;
                $module->user       = $custom;
                // Custom module name is given by the title field, otherwise strip off "com_"
                $module->name       = $custom ? $module->title : substr($file, 4);
                $module->style      = null;
                $module->position   = strtolower($module->position);
                $clean[$module->id] = $module;
            }
            return $module;

        }

        function genMenuItem($item, $level = 0, $pos = '', $ret = 0, $desc = true) {
            $data = '';
            $tmp = $item;
            $tmpname = ($this->getParam('gkmenu') && !$tmp->params->get('menu_text', 1 )) ? '' : $tmp->name;
            $active = $this->genClass($tmp, $level, $pos);
            
            if ($active) $active = " class=\"$active\"";

            $id = 'id="menu' . $tmp->id . '"';
            $tmpname = str_replace('"','&quot;', $tmpname);
            $txt = '';

            if ($tmp->params->get('menu_image', 0)) {
                $txt .= '<img src="'.$tmp->params->get('menu_image', 0).'" alt="'.$tmpname.'" />';
            }
            
            $txt .= $tmpname;

            if ($tmp->gkparams->get('desc') && $desc) {
                $txt .= '<small>' . JText::_($tmp->gkparams->get('desc')) . '</small>';
            }
            
            $title = "title=\"$tmpname\"";

            if ($tmp->type == 'menulink') {
                $menu = JFactory::getApplication()->getMenu();
                $alias_item = clone ($menu->getItem($tmp->query['Itemid']));
                if(!$alias_item) return false;
                else $tmp->url = $alias_item->link;
            }

            $rel = "";
            if ($tmp->gkparams->get('gk_rel')) {
                            $rel = " rel=\"nofollow\"";
            }
            if ($txt != '') {
                if ($tmp->type == 'separator') {
                    $data = '<a href="#" ' . $active . ' ' . $id . ' ' . $title . ' ' . $rel . '>' . $txt . '</a>';
                } else {
                    if ($tmp->url != null) {
                        switch ($tmp->browserNav) {
                            default:
                            case 0:
                                // _top
                                $data = '<a href="' . $tmp->url . '" ' . $active . ' ' . $id . ' ' . $title .
                                    ' ' . $rel . '>' . $txt . '</a>';
                                break;
                            case 1:
                                // _blank
                                $data = '<a href="' . $tmp->url . '" target="_blank" ' . $active . ' ' . $id .
                                    ' ' . $title . ' ' . $rel . '>' . $txt . '</a>';
                                break;
                            case 2:
                                $data = '<a href="' . $tmp->url . '" target="_blank" ' . $active . ' ' . $id .
                                    ' ' . $title . ' ' . $rel . '>' . $txt . '</a>';
                                break;
                        }
                    } else {
                        $data = '<a ' . $active . ' ' . $id . ' ' . $title . ' ' . $rel . '>' . $txt . '</a>';
                    }
                }
            }
            
            if ($this->getParam('gkmenu')) {
                if ($tmp->gkparams->get('group') && $data)
                    $data = "<header>$data</header>";
                if (isset($item->content) && $item->content) {
                    if ($item->gkparams->get('group')) {
                        $data .= "<div class=\"group-content\">".($item->content)."</div>";
                    } else {
                        $data .= $this->beginMenuItems($item->id, $level + 1, true);
                        $data .= $item->content;
                        $data .= $this->endMenuItems($item->id, $level + 1, true);
                    }
                }
            }
            if ($ret)
                return $data;
            else
                echo $data;

        }
        function setParam($paramName, $paramValue) {
            return $this->_params->set($paramName, $paramValue);
        }

        function beginMenu($startlevel = 0, $endlevel = 10) {
            echo "<div>";
        }
        function endMenu($startlevel = 0, $endlevel = 10) {
            echo "</div>";
        }

        function beginMenuItems($pid = 0, $level = 0) {
            echo "<ul>";
        }
        function endMenuItems($pid = 0, $level = 0) {
            echo "</ul>";
        }
        function beginSubMenuItems($pid = 0, $level = 0, $pos = '', $i, $return = false) {}
        function endSubMenuItems($pid = 0, $level = 0, $return = false) {}
        function beginMenuItem($mitem = null, $level = 0, $pos = '') {
            $active = $this->genClass($mitem, $level, $pos);
            if ($active) $active = " class=\"$active\"";
            echo "<li $active>";
        }
        function endMenuItem($mitem = null, $level = 0, $pos = '') {
            echo "</li>";
        }
        function genClass($mitem, $level, $pos) {
            $active = in_array($mitem->id, $this->open);
            $cls = ($level ? "" : "menu-item{$mitem->_idx}") . ($active ? " active" : "") . ($pos ? " $pos-item" : "");
            if (@$this->children[$mitem->id] && (!$level || $level < $this->getParam('endlevel'))) $cls .= " haschild";
            if ($mitem->gkparams->get('class')) $cls .= ' ' . $mitem->gkparams->get('class');
            return $cls;
        }
        function hasSubMenu($level = 0) {
            $pid = $this->getParentId($level);
            if (!$pid)
                return false;
            return $this->hasSubItems($pid);
        }
        function hasSubItems($id) {
            return (@$this->children[$id]) ? true : false;
        }
        function genMenu($startlevel = 0, $endlevel = -1) {
            $this->setParam('startlevel', $startlevel);
            $this->setParam('endlevel', $endlevel == -1 ? 10 : $endlevel);
            $this->beginMenu($startlevel, $endlevel);

            if ($this->getParam('startlevel') == 0) {
                //First level
                $this->genMenuItems(1, 0);
            } else {
                //Sub level
                $pid = $this->getParentId($this->getParam('startlevel'));
                if ($pid) $this->genMenuItems($pid, $this->getParam('startlevel'));
            }
            $this->endMenu($startlevel, $endlevel);
        }
        function genMenuItems($pid, $level, $desc = true) {
            if (@$this->children[$pid]) {
                $j = 0;
                $cols = $pid && $this->getParam('gkmenu') && isset($this->items[$pid]) && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
                $total = count($this->children[$pid]);
                $tmp = $pid && isset($this->items[$pid]) ? $this->items[$pid] : new stdclass();
                if ($cols > 1) {
                    $fixitems = count($tmp->col);
                    if ($fixitems < $cols) {
                        $fixitem = array_sum($tmp->col);
                        $leftitem = $total - $fixitem;
                        $items = ceil($leftitem / ($cols - $fixitems));
                        for ($m = 0; $m < $cols && $leftitem > 0; $m++) {
                            if (!isset($tmp->col[$m]) || !$tmp->col[$m]) {
                                if ($leftitem > $items) {
                                    $tmp->col[$m] = $items;
                                    $leftitem -= $items;
                                } else {
                                    $tmp->col[$m] = $leftitem;
                                    $leftitem = 0;
                                }
                            }
                        }

                        $cols = count($tmp->col);
                        $tmp->cols = $cols;
                    }
                } else {
                    $tmp->col = array($total);
                }

                $this->beginMenuItems($pid, $level);
                for ($col = 0; $col < $cols && $j < $total; $col++) {
                    $pos = ($col == 0) ? 'first' : (($col == $cols - 1) ? 'last' : '');
                    $this->beginSubMenuItems($pid, $level, $pos, $col);
                    $i = 0;
                    while ($i < $tmp->col[$col] && $j < $total) {
                      
                        $row = $this->children[$pid][$j];
                        $pos = ($i == 0) ? 'first' : (($i == count($this->children[$pid]) - 1) ? 'last' :
                            '');

                        $this->beginMenuItem($row, $level, $pos);
                        $this->genMenuItem($row, $level, $pos, 0, $desc);

                        if ($this->getParam('gkmenu') && $row->gkparams->get('group'))
                            $this->genMenuItems($row->id, $level);
                        else
                            if ($level < $this->getParam('endlevel'))
                                $this->genMenuItems($row->id, $level + 1);

                        $this->endMenuItem($row, $level, $pos);
                        $j++;
                        $i++;
                    }
                    $this->endSubMenuItems($pid, $level);
                }
                $this->endMenuItems($pid, $level);
            }
        }

        function getParentId($level) {
            if (!$level || (count($this->open) < $level)) return 1;
            return $this->open[count($this->open) - $level];
        }
    }
}

// EOF