<?php

/**
 * @version     1.0.0
 * @package     com_jugraauto
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */
// No direct access
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/ktable.php'; 

/**
 * company Table class
 */
class JugraautoTableCompany extends JugraautoTableKtable {

    protected $asset_name;

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        $this->asset_name = 'company';
        parent::__construct('#__jugraauto_companies', 'id', $db);
        
    }
	/**
	 * Override check function
	 *
	 * @return  boolean
	 *
	 * @see     JTable::check
	 * @since   11.1
	 */
	public function check()
	{
		// Check for a title.
		if (trim($this->name) == '')
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MUSTCONTAIN_A_TITLE_CATEGORY'));
			return false;
		}
		$this->alias = trim($this->alias);
		if (empty($this->alias))
		{
			$this->alias = $this->name;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		return parent::check();
	}
 
	/**
	 * Override store function
	 *
	 * @return  boolean
	 *
	 */
        public function store($updateNulls = false) {
            // Создаем пункт меню с этой компанией
            $menu = $this->getTable('menu');
            // Если еще не создан пункт меню - создаем, если создан - переписываем алиас и путь
            $component = JTable::getInstance('extension');
            $component->load(array('name'=>'com_jugraauto'));
            $data = array(
                        'title'=>$this->name,
                        'alias'=>$this->alias,
                        'path'=>$this->alias,
                        'menutype' => 'com_jugraauto',
                        'link' => 'index.php?option=com_jugraauto&view=company',
                        'type' => 'component',
                        'component_id' => $component->extension_id,
                        'published' => '1',
                        'parent_id' => '1',
                        'level' => '1',
                        'access' => '1',
                        );
            if(!$this->menu_id)
            {
                // Если уже есть пункт меню с таким алиасом, то записываем его ИД в $this->menu_id
                if($this->alias AND $menu->load(array('alias'=> $this->alias)))
                {
                    $data['id'] = $menu->id;
                }
            }
            else 
            {
                $data['id'] = $this->menu_id;
            }
            if(!$menu->save($data))
            {
                JFactory::getApplication()
                        ->enqueueMessage(JText::_('COM_JUGRAAUTO_ERROR_EDIT_MENU_RECORD'), 'error');
                return FALSE;
            }
            $this->menu_id = $menu->id;
//                var_dump($data);
//                var_dump(get_object_vars($menu));exit;
            if( !parent::store($updateNulls))
            {
                return FALSE;
            }
            $menu->load($this->menu_id);
            // Convert to the JObject before adding the params.
            $properties = $menu->getProperties(1);
            $result = JArrayHelper::toObject($properties, 'JObject');
            // Convert the params field to an array.
            $registry = new JRegistry;
            $registry->loadString($menu->params);
            $result->params = $registry->toArray();
            $result->params = array_merge($result->params, array('item_id'=>$this->id));
            $data['params'] = json_encode($result->params);
            if(!$menu->save($data))
            {
                JFactory::getApplication()
                        ->enqueueMessage(JText::_('COM_JUGRAAUTO_ERROR_EDIT_MENU_RECORD'), 'error');
                return FALSE;
            }
            return TRUE;
        }
	/**
	 * Override delete function
	 *
	 * @return  boolean
	 *
	 */
        
        public function delete($pk = null) {
            if (!parent::delete($pk))
            {
                return FALSE;
            }
            // Удаляем соотв запись в меню
            if($this->menu_id)
            {
                return $this->getTable('menu')->delete($this->menu_id);
            }
            return TRUE;
        }
}
