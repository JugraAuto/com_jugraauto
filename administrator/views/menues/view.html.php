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
 * View class for a list of Jugraauto.
 */
class JugraautoViewMenues extends JView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
            JToolBarHelper::title(JText::_('COM_JUGRAAUTO_TITLE_MENU'), 'company.png');
            JugraautoHelper::addSubmenu();
            $href = JURI::base().'index.php?option=com_jugraauto&task=menues.rebuild_category_menu';
            echo '<a href="'.$href.'">'.JText::_('COM_JUGRAAUTO_REBUILD_CATEGORY_MENU').'</a>';
	}

}
