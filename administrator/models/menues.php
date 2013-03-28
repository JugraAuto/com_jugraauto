<?php
/**
 * @version     1.0.0
 * @package     com_jugraauto
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/kmodellist.php'; 

// Include the JLog class.
jimport('joomla.log.log');
/**
 * Methods supporting a list of Jugraauto records.
 */
class JugraautoModelMenues extends JugraautoModelKModelList
{

    protected $table_name = 'category';


	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
            // Create a new query object.
            $db		= $this->getDbo();
            $query	= $db->getQuery(true);

            // Select the required fields from the table.
            $query->select('*');
            $query->from('`#__categories`');
            $query->where('`extension` = "com_jugraauto"');

            return $query;
        }
        
        
        public function rebuild_category_menu()
        {
            // Initialise a basic logger with no options (once only).
            JLog::addLogger(array());

            $return = array(1,JText::_('COM_JUGRAAUTO_OK_ADD_MENU_RECORDS'));
            // Находим ИД сомпонента
            $component = JTable::getInstance('extension');
            $component->load(array('name'=>'com_jugraauto'));
            $extension_id = $component->extension_id;
            
            $query = $this->getListQuery();
            $query->order('`parent_id`');
            $this->_db->setQuery($query);
//                 var_dump((string)$query);exit;
//        var_dump($this->_db->loadObjectList());exit;
             
             $categories = $this->_db->loadObjectList();
             $menutype = 'mainmenu';
             foreach ($categories as $category)
             {
                // Если у категории есть родитель, то ищем и родителя для этого пункта меню
                $parent_id = '1';
                if((int)$category->parent_id > 1)
                {
                    list($parent_id, $msg) = $this->_get_parent_menu_id($category->parent_id);

                    if(!$parent_id)
                    {
                        // Add a message.
                        JLog::add($msg.' '.  json_encode($category));
                        $return = array(0,JText::_('COM_JUGRAAUTO_ERROR_ADD_MENU_RECORDS'));
                    }
                    $menutype = $msg;
                }

                 // Есл еще нет такого пункта меню, то создаем его
                 if(!$this->_menu_category_exist($category->alias,$parent_id))
                 {
                    list($result, $msg) = $this->_rebuild_menu($category, $extension_id, $parent_id, $menutype);
                    if(!$result)
                    {
                        // Add a message.
                        JLog::add($msg.' '.  json_encode($category));
                        $return = array(0,JText::_('COM_JUGRAAUTO_ERROR_ADD_MENU_RECORDS'));
                    }
                 }
             }
             return $return;
        }
        
        
        private function _rebuild_menu($category, $extension_id, $parent_id, $menutype)
        {
            // Создаем пункт меню с этой компанией
            $menu = $this->getTable('Menu','JugraautoTable');
            $menu->setLocation($parent_id, 'last-child');
            // Если еще не создан пункт меню - создаем, если создан - переписываем алиас и путь
            $data = array(
                        'menutype' => $menutype,
                        'title'=>$category->title,
                        'lft'=>$category->lft,
                        'rgt'=>$category->rgt,
                        'alias'=>$category->alias,
                        'path'=>$category->path,
                        'link' => 'index.php?option=com_jugraauto&view=category&category_id='.$category->id,
                        'type' => 'component',
                        'published' => '1',
                        'parent_id' => $parent_id,
                        'level' => $category->level,
                        'component_id' => $extension_id,
                        'access' => '1',
                        'language' => '*',
                        );
            // Convert to the JObject before adding the params.
//            $properties = $menu->getProperties(1);
//            $result = JArrayHelper::toObject($properties, 'JObject');
//            // Convert the params field to an array.
//            $registry = new JRegistry;
//            $registry->loadString($menu->params);
//            $result->params = $registry->toArray();
//            $result->params = array_merge($result->params, array('item_id'=>$this->id));
//            $data['params'] = json_encode($result->params);
//            $menu->bind($data);
            
            if(!$menu->save($data))
            {
                var_dump($data);
                                echo '<hr/>';;
                var_dump($menu);exit;
                $msg = $this->_db->getErrorMsg();
                JFactory::getApplication()
                        ->enqueueMessage($msg, 'error');
                return array(0, $msg);
            }
            else
            {
                
                $sql = "UPDATE  `#__menu` SET  `path` =  '$category->path' WHERE  `jos_menu`.`id` = $menu->id";
                $this->_db->setQuery($sql);
                $this->_db->query();
            }
            return array(1,JText::_('COM_JUGRAAUTO_OK_ADD_MENU_RECORD'));
        }
         
        private function _get_parent_menu_id($id)
        {
            $query	= $this->_db->getQuery(true);

            // Select the required fields from the table.
            $query->select('*');
            $query->from('`#__menu`');
            $query->where('`link` LIKE "%option=com_jugraauto&view=category%"');
            $this->_db->setQuery($query);
//                 var_dump((string)$query);exit;
            $items = $this->_db->loadObjectList();
            if (!$items)
            {
                return array(0,  JText::_('COM_JUGRAAUTO_NOT_FIND_PARENT_MENU_CANDIDATS'));
            }
            foreach ($items as $item)
            {
                $params = explode('&', $item->link);
                if($params)
                {
                    foreach ($params as $param)
                    {
                        $element = explode('=', $param);
                        if($element[0] == 'category_id' AND $element[1] == $id)
                        {
                            return array($item->id,$item->menutype);
                        }
                    }
                }
            }
            return array(0,  JText::_('COM_JUGRAAUTO_NOT_FIND_PARENT_MENU_ID'));
        }
        
        /**
         * Проверяем существует ли пункт меню с таким path
         * @param type string
         * @return bolean 
         */
        private function _menu_category_exist($alias,$parent_id)
        {
            $query	= $this->_db->getQuery(true);

            // Select the required fields from the table.
            $query->select('`id`');
            $query->from('`#__menu`');
            $query->where('`alias` = "'.$alias.'"');
            $query->where('`parent_id` = "'.$parent_id.'"');
            $this->_db->setQuery($query);
            return $this->_db->loadResult();
            
        }
}
