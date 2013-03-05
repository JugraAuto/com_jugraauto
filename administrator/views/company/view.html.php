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
class JugraautoViewCompany extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
                if($this->item->id)
                {
                    JFactory::getApplication()->setUserState('com_jugraauto.company_id',$this->item->id);
                }
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		$this->addDocStyle();
		parent::display($tpl);
	}
	/**
	 * Add the stylesheet to the document.
	 */
	protected function addDocStyle()
	{
            $doc = JFactory::getDocument();
            $doc->addScript("//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js");
            if($this->item->type == '1') // 2Gis
            {
                $doc->addScript("http://maps.api.2gis.ru/1.0");
            }
            elseif ($this->item->type == '2') // Yandex maps
            {
                $doc->addScript("http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU");
            }
        }
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= JugraautoHelper::getActions();

		JToolBarHelper::title(JText::_('COM_JUGRAAUTO_TITLE_COMPANY'), 'company.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('company.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('company.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('company.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('company.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('company.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('company.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
