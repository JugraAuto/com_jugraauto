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

/**
 * Methods supporting a list of Jugraauto records.
 */
class JugraautoModelCompanies extends JugraautoModelKModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'name', 'a.name',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'city_id', 'a.city_id',

            );
        }

        parent::__construct($config);
            $category_id = JRequest::getInt('filter_category_id',0);
            if($category_id)
            {
                $this->setState('filter.category_id', $category_id);
            }
    }

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
            $query = parent::getListQuery();
            $query->from('`#__jugraauto_companies` AS a');

            // Filter by category_id
            $category_id = $this->getState('filter.category_id');
            if(isset($category_id))
            {
                $query->join('', '#__jugraauto_companies_categories AS comcat ON comcat.company_id=a.id');
                $query->where('comcat.category_id = '.$category_id);
            }

            // Filter by search in title
            $search = $this->getState('filter.search');
            if (!empty($search)) 
            {
                if (stripos($search, 'id:') === 0) 
                {
                    $query->where('a.id = '.(int) substr($search, 3));
                } 
                else 
                {
                    $search = $db->Quote('%'.$db->escape($search, true).'%');
                    $query->where('( a.name LIKE '.$search.' )');
                }
            }
            $query->join('LEFT', '#__jugraauto_cities AS city ON city.id=a.city_id');
            $query->select('city.name as city_name');
//            var_dump((string)$query);            
            return $query;
        }
}
