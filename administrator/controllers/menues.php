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

jimport('joomla.application.component.controller');

/**
 * city controller class.
 */
class JugraautoControllerMenues extends JController
{

    public function rebuild_category_menu()
    {
        $model = $this->getModel('Menues');
        list($result,$msg) = $model->rebuild_category_menu();
        // Redirect back to the contact form.
        $this->setRedirect(JRoute::_('index.php?option=com_jugraauto&view=menues', $result), $msg);
        return $result;

    }
}