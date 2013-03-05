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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class JugraautoViewCategory extends JView {

    protected $state;
    protected $item;
    protected $form;
    protected $params;
    protected $_model;


    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->_model = $this->getModel();
        
        $app	= JFactory::getApplication();
        $user		= JFactory::getUser();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        // Если запись присутствует в меню, 
        // то делаем перенаправление на соотв. пункт меню.
        //Itemid=164
        $menu = $this->get('Menu');
        if($menu->id)
        {
            $Itemid = JRequest::getInt('Itemid');
            // Если ссылка не через меню или через другой пункт меню
            // то делаем редирект на найденый пункт меню
            if(!$Itemid OR $Itemid != $menu->id)
            {
                $uri = JRoute::_(JUri::base()).$menu->path;
//        var_dump($uri);
                JFactory::getApplication()->redirect($uri);
            }
        }
        $this->has_company = $this->get('Has_company');
        // Если категория имеет компании, то делаем перенаправление на список компаний
        if($this->get('Has_company'))
        {
            $this->companies_url = JRoute::_(JURI::base().'index.php?option=com_jugraauto&view=companies&tmpl=component&category_id='.$this->item->id);
//            JFactory::getApplication()->redirect($url);
        }
        
        $this->children = $this->get('Children');
        
        $this->params = $app->getParams('com_jugraauto');
//        $this->form = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        
        
        if($this->_layout == 'edit') {
            
            $authorised = $user->authorise('core.create', 'com_jugraauto');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        
        $this->_prepareDocument();

        parent::display($tpl);
    }


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;
                $doc = JFactory::getDocument();
                
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_jugraauto_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
   
}