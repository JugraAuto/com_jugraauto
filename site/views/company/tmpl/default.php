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
$address = $this->item->street_type.' '.$this->item->street.', '.$this->item->house;
//var_dump($this->item);
?>
<?php if( $this->item ) : ?>
<style type="text/css">
        div.two-gigs{
        width:500px; 
        height:400px;
/*        padding: 15px;*/
        margin: 20px;    
                
</style>
<script type="text/javascript">
        <?php if($this->item->type == 1): ?> // 2Gis
        // Создаем обработчик загрузки страницы:
            DG.autoload(function() {
                // Создаем объект карты, связанный с контейнером:
                var myMap = new DG.Map('myMapId');
                // Устанавливаем центр карты и коэффициент масштабирования:
//                myMap.setCenter(new DG.GeoPoint(60.596190,56.858066),15);
                myMap.setCenter(new DG.GeoPoint(<?=$this->item->pointy?>,<?=$this->item->pointx?>),15);
                // Добавляем элемент управления коэффициентом масштабирования:
                myMap.controls.add(new DG.Controls.Zoom());
                // Создаем балун:
                var myBalloon = new DG.Balloons.Common({
                    // Местоположение на которое указывает балун: 
//                    geoPoint: new DG.GeoPoint(60.596190,56.858066),
                    geoPoint: new DG.GeoPoint(<?=$this->item->pointy?>,<?=$this->item->pointx?>),
                    // Устанавливаем текст, который будет отображатся при открытии балуна:
                    contentHtml: '<b><?=$this->item->name?></b><br/><?=$address?>'
                });
                // Создаем маркер:
                var myMarker = new DG.Markers.Common({
                    // Местоположение на которое указывает маркер:
                    geoPoint: new DG.GeoPoint(<?=$this->item->pointy?>,<?=$this->item->pointx?>),
                    // Функция, вызываемая при клике по маркеру
                    clickCallback: function() {
                        if (! myMap.balloons.getDefaultGroup().contains(myBalloon)) {
                            // Если балун еще не был добавлен на карту, добавляем его:
                            myMap.balloons.add(myBalloon);
                        } else {
                            // Показываем уже ранее добавленный на карту балун
                            myBalloon.show();
                        }
                    }
                });
                // Добавить маркер на карту:
                myMap.markers.add(myMarker);        
            }); 
        <?php elseif($this->item->type == '2'):?> // Yandex maps
            ymaps.ready(init);
            var myMap;

            function init(){     
                myMap = new ymaps.Map ("myMapId", {
                    center: [<?=$this->item->pointx?>, <?=$this->item->pointy?>],
                    zoom: 15
            });
            // Создание экземпляра элемента управления
            myMap.controls.add(
                new ymaps.control.ZoomControl()
            );
            myPlacemark = new ymaps.Placemark([<?=$this->item->pointx?>, <?=$this->item->pointy?>], { 
                content: '<?=$this->item->name?>', 
                balloonContent: '<b><?=$this->item->name?></b><br/><?=$address?>' 
            });

            myMap.geoObjects.add(myPlacemark);            }
        <?php endif;?>
    
</script>
    <div class="item_fields">
        <ul class="fields_list">
			<li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_COMPANY_NAME'); ?>:
			<?php echo $this->item->name; ?></li>
			<li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_COMPANY_CITY_ID'); ?>:
			<?php echo $this->_model->get_city($this->item->city_id); ?></li>
			<li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_ADDRESS'); ?>:
			<?php echo $this->item->city_name.', '.$this->item->street_type.' '.$this->item->street.', '.$this->item->house.' '.$this->item->address_else; ?></li>
                        <?php if($this->item->email):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_EMAIL'); ?>:
                            <?php echo $this->item->email; ?></li>
                        <?php endif?>    
                        <?php if($this->item->fio):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_FIO'); ?>:
                            <?php echo $this->item->fio; ?></li>
                        <?php endif?>    
                        <?php if($this->item->phone):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_PHONE'); ?>:
                            <?php echo $this->item->phone; ?></li>
                        <?php endif?>    
                        <?php if($this->item->fax):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FORM_LBL_FAX'); ?>:
                            <?php echo $this->item->fax; ?></li>
                        <?php endif?>    
                        <?php if($this->item->logo):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FORM_FIELD_LOGO_LABEL'); ?>:
                            <img src="<?php echo $this->item->logo; ?>" alt="JText::_('COM_JUGRAAUTO_FORM_FIELD_LOGO_LABEL')"/></li>
                        <?php endif?>    
                        <?php if($this->item->desc):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FIELD_CUSTOMCODE_LABEL'); ?>:
                            <?php echo $this->item->desc; ?></li>
                        <?php endif?>
                        <?php if($this->item->type):?>
                            <li><?php echo JText::_('COM_JUGRAAUTO_FORM_FIELD_IMAGE_LABEL'); ?>:
                            <div class="two-gigs" id="myMapId"></div></li>
                        <?php else:?>
                            <?php if($this->item->image):?>
                                <li><?php echo JText::_('COM_JUGRAAUTO_FORM_FIELD_IMAGE_LABEL'); ?>:
                                <img src="<?php echo $this->item->image; ?>" alt="JText::_('COM_JUGRAAUTO_FORM_FIELD_IMAGE_LABEL')"/></li>
                            <?php endif?>
                        <?php endif?>
        </ul>
    </div>
<?php endif ?>
