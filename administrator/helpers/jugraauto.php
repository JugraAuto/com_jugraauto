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

/**
 * Jugraauto helper.
 */
class JugraautoHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
                JSubMenuHelper::addEntry(
                        JText::_('COM_JUGRAAUTO_SUBMENU_CATEGORIES'), 
                        'index.php?option=com_categories&view=categories&extension=com_jugraauto', 
                        $vName == 'categories');
		JSubMenuHelper::addEntry(
			JText::_('COM_JUGRAAUTO_TITLE_COMPANIES'),
			'index.php?option=com_jugraauto&view=companies',
			$vName == 'companies'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_JUGRAAUTO_TITLE_CITIES'),
			'index.php?option=com_jugraauto&view=cities',
			$vName == 'cities'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_jugraauto';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
