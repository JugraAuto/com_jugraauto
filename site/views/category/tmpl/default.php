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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_jugraauto', JPATH_ADMINISTRATOR);
$ajax_loader_src = JURI::base().'components/com_jugraauto/assets/images/ajax-loader.gif';
//var_dump($this->item);
?>
<h1>
    <?php $params = json_decode($this->item->params)?>
    <?php if($params->image):?>
        <img src ="<?=$params->image?>" alt="<?=$this->item->title?>">
    <?php endif;?>
    
    <?=$this->item->title?><img id="ajax_loader" src="<?=$ajax_loader_src?>" alt="ajax loader" style="display: none; margin-left: 10px">
</h1>
<?php if($this->item->note):?>
    <h4><?=$this->item->note?></h4>
<?php endif?>
<?php if($this->item->description):?>
    <?=$this->item->description?>
<?php endif?>
<?php if( $this->item ) : ?>
    <?php if($this->children):?>
    <ul>
            <?php foreach ($this->children as $item) : ?>
        
            <?php $href = JRoute::_(JUri::base().$item->path)?>
                    <li>
                        <a href="<?=$href;?>">
                            <?php echo $item->title; ?>
                        </a>
                    </li>
            <?php endforeach; ?>
    </ul>
    <?php endif?>
<?php endif;?>
<?php if($this->has_company):?>
<div id="com_jugraauto_company_ajax"></div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $.ajaxSetup({
            beforeSend: function (){$('#ajax_loader').show();},
            complete: function (){$('#ajax_loader').hide();}
        });
        $.ajax({
            url: "<?=$this->companies_url?>"
        }).done(function(data) {
            $('#com_jugraauto_company_ajax').html(data);
        });
    });
</script>
<?php endif;?>