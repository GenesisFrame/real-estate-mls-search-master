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

<div class="map-panel-title">
    <span class="map-panel-left" style="margin-bottom: 5px;">MLS: <span class="marked"><?php echo $this->property['LIST_NO'] ?></span></span>
    <span class="map-panel-left" style="margin-bottom: 5px;">Street: <span class="marked"><?php echo $this->property['address1'] ?></span></span>
    <div class="map-panel-right">$<?php echo number_format($this->property['price'], 0, '.', ','); ?></div>
</div>
<div class="clear">
    <div class="det-gallery-image ken-property-image" style="width:256px; height: 158px;">
        <?php if ($this->property['photo_count'] > 0): ?>
            <img src="http://media.mlspin.com/photo.aspx?mls=<?php echo $this->property['LIST_NO'];?>&amp;w=256&amp;h=158&amp;n=0" alt="" />
        <?php endif;?>
    </div>
    <ul class="det-gallery-nav">
        <?php if($this->property['photo_count'] > 1): ?>
            <?php for($i = 0; $i <= $this->property['photo_count']-1; $i++): ?>
                <li>
                    <a href="http://media.mlspin.com/photo.aspx?mls=<?php echo $this->property['LIST_NO'];?>&amp;w=256&amp;h=158&amp;n=<?php echo $i;?>">
                        <img src="http://media.mlspin.com/photo.aspx?mls=<?php echo $this->property['LIST_NO'];?>&amp;w=60&amp;h=45&amp;n=<?php echo $i;?>" width="60px" height="45px" id="slidethumb-<?php echo $i;?>"/>
                    </a>
                </li>
            <?php endfor; ?>
         <?php endif; ?>
    </ul>
</div>
<table class="side-features-table">
    <tr>
        <td>Beds: <span class="side-features-value"><?php echo $this->property['no_bedrooms']?></span></td>
        <td>Baths: <span class="side-features-value"><?php echo $this->property['no_full_baths']?></span></td>
        <td>Sqft: <span class="side-features-value"><?php echo $this->property['square_feet']?></span></td>
        <td>Yrblt: <span class="side-features-value"><?php echo $this->property['year_built']?></span></td>
    </tr>
</table>
<div class="det-panel-bottom">
    <?php if (!empty($value['Start_Date']) && strtotime($value['Start_Date']) > time() ): ?>
        <div class="det-panel-bottom-icon active"></div>
    <?php endif; ?>
        
    <ul class="det-panel-func">
<!--        <li><a class="showing" href="#"></a></li>-->
<!--        <li><a class="favorite" href="#"></a></li>-->
    </ul>
</div>

<?php $isAuth = isAuthUser(); if ($isAuth): ?>
 <div class="det-subtitle">Remarks</div>
 <div class="clear">
    <p><?php echo $this->property['remarks'] ?></p>
 </div>
<?php endif; ?>

<div class="map-panel-block">
    <a target="_blank" href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $this->property['LIST_NO'], 'propertyAddress' => implode('-', array($this->property['address1'], $this->property['address2'], $this->property['postal_code'])))); ?>" class="content-link-small">View Property Details</a>
</div>
