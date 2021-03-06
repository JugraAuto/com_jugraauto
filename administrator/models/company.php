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

jimport('joomla.application.component.modeladmin');

/**
 * Jugraauto model.
 */
class JugraautoModelcompany extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_JUGRAAUTO';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Company', $prefix = 'JugraautoTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_jugraauto.company', 'company', array('control' => 'jform', 'load_data' => $loadData));
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
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jugraauto.edit.company.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__jugraauto_companies');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

        public function save($data) 
        {
//            $alias = $data['alias'];
//            $parent_id = $data['category_id'];
//            list($name, $alias) = $this->generateNewTitle($parent_id, $alias, $title);
            if(!parent::save($data))
            {
                return FALSE;
            }
            // Обновляем записи в таблице связи компаний с категориями
            $company_id = $this->getState($this->getName().'.id');
            if($company_id)
            {
                // Удаляем все записи с текущей компанией.
                $query = 'DELETE FROM `#__jugraauto_companies_categories` WHERE `company_id` ="'.$company_id.'"'; 
                $this->_db->setQuery($query);
                // Сохраняем новые записи с текущей компанией.
                if($this->_db->query())
                {
                    $categories = $_POST['jform']['category'];
                    $query_0 = 'INSERT INTO `#__jugraauto_companies_categories` (`company_id`,`category_id`) VALUES ';
                    foreach ($categories as $category_id)
                    {
                        $query = $query_0.'("'.$company_id.'","'.$category_id.'")';
                        $this->_db->setQuery($query);
                        if(!$this->_db->query())
                        {
                            JFactory::getApplication()
                            ->enqueueMessage(JText::_('COM_JUGRAAUTO_ERROR_UPDATE_COMPANY_CATEGORIES_TABLE'), 'error');
                            
                            var_dump($this->_db->getErrorMsg());
                        }
                    }
                }
            }
            return TRUE;
        }
	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $parent_id  The id of the parent.
	 * @param   string   $alias      The alias.
	 * @param   string   $title      The title.
	 *
	 * @return  array  Contains the modified title and alias.
	 *
	 * @since	1.7
	 */
//	protected function generateNewTitle($parent_id, $alias, $title)
//	{
//		// Alter the title & alias
//		$table = $this->getTable();
//		while ($table->load(array('alias' => $alias, 'parent_id' => $parent_id)))
//		{
//			$title = JString::increment($title);
//			$alias = JString::increment($alias, 'dash');
//		}
//
//		return array($title, $alias);
//	}
}