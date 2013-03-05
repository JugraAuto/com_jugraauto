<?php
/**
 * @version     1.0.0
 * @package     com_jugraauto
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */
// no direct access
defined('_JEXEC') or die;
?>
<style type="text/css">
    div.com_jugraauto_item{
        float: left;
        width: 200px;
        height: 200px;
        border: 1px activecaption solid;
        border-radius: 5px;
        margin: 5px;
        padding: 5px;
    }
</style>
<div class="items">
        <?php foreach ($this->items as $item) : ?>
        <?php $href = JRoute::_('index.php?option=com_jugraauto&view=company&id=' . (int) $item->id)?>
            <div class="com_jugraauto_item">
                <a href="<?=$href;?>"><?php echo $item->name; ?></a>
                <a href="<?=$href;?>"><img src="<?=$item->logo?>" alt="<?=$item->name?>"></a>
                <br/>
                <?php $address = $item->city_name.', '.$item->street_type.' '.$item->street.', '.$item->house.' '.$item->address_else?>
                <b><?=JTEXT::_('CON_JUGRAAUTO_ADDRESS').': '?></b><br/><?=$address?>
            </div>
        <?php endforeach; ?>
</div>
<div class="pagination">
    <p class="counter">
        <?php echo $this->pagination->getPagesCounter(); ?>
    </p>
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
