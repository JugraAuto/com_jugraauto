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
            if (!parent::store($updateNulls))
            {
                return FALSE;
            }
            // Создаем пункт меню с этой компанией
            $menu = $this->getTable('menu');
            // Если еще не создан пункт меню - создаем, если создан - переписываем алиас и путь
            if(!$this->menu_id)
            {
                $client_id = JFactory::getUser()->id;
                $component_id = $this->getTable('extension')->load(array('name'=>'com_jugraauto'))->id;
                if( $menu->save(array(
                        'menutype'=>'com_jugraauto',
                        'title'=>$this->name,
                        'alias'=>$this->alias,
                        'path'=>$this->path,
                        'link'=>'index.php?option=com_jugraauto&view=company',
                        'type'=>'component',
                        'component_id'=>$component_id,
                        'client_id'=>$client_id,
                        )
                    ))
                {
                        var_dump($menu);exit;
                    $this->menu_id = $menu->id;
                }
                else 
                {
                    JFactory::getApplication()
                            ->enqueueMessage(JText::_('COM_JUGRAAUTO_ERROR_SAVE_NEW_MENU_RECORD'), 'error');
                    return FALSE;
                }
            }
            else 
            {
                if(!$menu->save(array(
                        'id'=>$this->menu_id,
                        'alias'=>$this->alias,
                        'path'=>$this->path,
                        )
                    ))
                {
                    JFactory::getApplication()
                            ->enqueueMessage(JText::_('COM_JUGRAAUTO_ERROR_EDIT_MENU_RECORD'), 'error');
                    return FALSE;
                }
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
