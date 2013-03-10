<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Import JTableMenu
//JLoader::register('JTableMenu', JPATH_PLATFORM . '/joomla/database/table/menu.php');
require_once dirname(__FILE__) . '/ktable.php'; 
/**
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 */
//class JugraautoTableMenu extends JTableMenu
class JugraautoTableMenu extends JugraautoTableKtable
{
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        $this->asset_name = 'menu';
        parent::__construct('#__menu', 'id', $db);
    }
}
