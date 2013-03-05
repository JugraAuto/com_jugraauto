<?php
/**
 * @version     1.0.0
 * @package     com_jugraauto
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Jugraauto model.
 */
class JugraautoModelCategory extends JModelForm
{
    
    var $_item = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_jugraauto');
            $id = JRequest::getInt('category_id',0);
//		// Load state from the request userState on edit or from the passed variable on default
//        if (JFactory::getApplication()->input->get('layout') == 'edit') {
//            $id = JFactory::getApplication()->getUserState('com_jugraauto.edit.category.id');
//        } else {
//            $id = JFactory::getApplication()->input->get('id');
//            JFactory::getApplication()->setUserState('com_jugraauto.edit.category.id', $id);
//        }
		$this->setState('category.id', $id);

		// Load the parameters.
		$params = $app->getParams();
        $params_array = $params->toArray();
        if(isset($params_array['item_id'])){
            $this->setState('category.id', $params_array['item_id']);
        }
		$this->setState('params', $params);

	}
        

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('category.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}

		return $this->_item;
	}
    
	public function getTable($type = 'Category', $prefix = 'JugraautoTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
    
	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_jugraauto.category', 'category', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = $this->getData(); 
        
        return $data;
	}
    
    function getCategoryName($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query 
            ->select('title')
            ->from('#__categories')
            ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }
 
    /**
     * Возвращаем наименование города
     * @param int $city_id
     * @return string
     */
    public function get_city($city_id)
    {
        $city_name = '';
        $city = $this->getTable('city');
        if($city->load($city_id))
        {
            $city_name = $city->name;
        }
        return $city_name;
    }
    /**
     * Получаем список дочерних категорий
     * @return mixed boolean or object
     */
    public function getChildren()
    {
        $id = $this->getState('category.id');
        if(!$id)
        {
            return FALSE;
        }
        $children = $this->getTable('category')->get_rows(array('parent_id'=>$id));
        if(!$children)
        {
            return FALSE;
        }
        return $children;
    }
    
    /**
     * Выясняем имеет ли категория компании
     * @return boolean 
     */
    public function getHas_company()
    {
        $id = $this->getState('category.id');
        if(!$id)
        {
            return FALSE;
        }
        if($this->getTable('companies_categories')->get_rows(array('category_id'=>$id)))
        {
            return TRUE;
        }
        return FALSE;
    }
    /**
     * Выясняем присутствует ли соотв. записи мункт меню
     * @return mixed boolean or menu ID
     */
    public function getMenu()
    {
        $alias = $this->getData()->alias;
        if(!$alias)
        {
            return FALSE;
        }
        $menu = $this->getTable('menu');
        if($menu->load(array('alias'=>$alias)))
        {
            return $menu;
        }
        return FALSE;
    }
}