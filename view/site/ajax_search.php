<?php
/*
Plugin Name: Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5.1
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/
?>

<div class="map-panel-list">
<?php if ( empty($this->property['data']) ): ?>
<div class="det-panel-description">
    Nothing was found
</div>
<?php else: ?>
<ul class="map-panel-list">
 <?php foreach($this->property['data'] as $value): ?>
 <li class="map-panel-list-item show-property-li">

    <input type="hidden" name="property-id" value="<?php echo $value['LIST_NO'];?>" />
    <input type="hidden" name="property-lat" value="<?php echo $value['lat'];?>" />
    <input type="hidden" name="property-lon" value="<?php echo $value['lon'];?>" />
    <input type="hidden" name="property-type" value="<?php echo strtolower($value['PROP_TYPE']); ?>" />
    <input type="hidden" name="neighborhood-id" value="<?php echo $value['gis_neighborhood_id']; ?>" />

    <div class="det-panel-image">
        <a class="show-property" href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $value['LIST_NO'], 'propertyAddress' => implode('-', array($value['address1'], $value['address2'], $value['postal_code'])))); ?>">
         <img src="http://media.mlspin.com/photo.aspx?mls=<?php echo $value['LIST_NO'];?>&amp;w=72&amp;h=72&amp;n=0" alt="" width="72" height="72" />
        </a>
    </div>
    <div class="det-panel-description">
        <div class="det-panel-title">
            <div class="det-right active" style="float:right;">Active</div>
            <div class="" style="font-weight: bold; font-size: 13px; margin-bottom: 5px;">$<?php echo number_format($value['price'], 0, '.', ','); ?></div>

            <div class="" style="font-size:11px;font-weight:normal">MLS# <?php echo $value['LIST_NO']; ?></div>
            <div class="" style="font-size:11px;font-weight:normal;"><?php echo $value['address1']; ?></div>

        </div>
        <table class="side-features-table">
            <tr>
                <td>Beds: <span class="side-features-value"><?php echo $value['no_bedrooms']?></span></td>
                <td>Baths: <span class="side-features-value"><?php echo $value['no_full_baths']?></span></td>
            </tr>
        </table>
        <?php echo getFullPropertyType($value['PROP_TYPE']); ?>
        <div class="det-panel-bottom">

            <ul class="det-panel-func">
                    <li><a href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $value['LIST_NO'], 'propertyAddress' => implode('-', array($value['address1'], $value['address2'], $value['postal_code'])))); ?>" class="show-property showing"></a></li>
<!--                <li><a href="#" class="favorite"></a></li>-->
            </ul>

            <?php if (!empty($value['Start_Date']) && strtotime($value['Start_Date']) > strtotime('today') ): ?>
                <div class="det-panel-bottom-icon active"></div>
            <?php endif; ?>

        </div>
    </div>
    </a>
 </li>
 <?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
    
<?php echo $this->pagination; ?>
