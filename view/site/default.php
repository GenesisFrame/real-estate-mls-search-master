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

<div id="rep-page">
  <div class="li-filters clearfix">
      <form action="" method="get" id="order-form">

        <?php if (!isset($data['disableAction'])): ?>
            <input type="hidden" name="propertyAction" value="<?php if (isset($data['urlData']['propertyAction'])): ?><?php echo $data['urlData']['propertyAction'] ?><?php else: ?>default<?php endif;?>" />
        <?php endif; ?>

        <?php
          $fields = generateSearchString(array('exclude' => array('orderby', 'limit')));
          if (!is_array($fields)) {
              echo $fields;
          } else {
              echo implode("<br/>", $fields);
          }
        ?>

        <div class="li-sort clearfix">
            <div class="li-sort-title">Show</div>
            <select name="propertySearch[limit]" class="li-sort-select">
                <option value="10" <?php echo isset($_GET['propertySearch']['limit']) && (int)$_GET['propertySearch']['limit'] == 10 ? 'selected':''?>>10</option>
                <option value="20" <?php echo isset($_GET['propertySearch']['limit']) && (int)$_GET['propertySearch']['limit'] == 20 ? 'selected':''?>>20</option>
                <option value="50" <?php echo isset($_GET['propertySearch']['limit']) && (int)$_GET['propertySearch']['limit'] == 50 ? 'selected':''?>>50</option>
            </select>

        </div>
        <div class="li-show clearfix">
            <div class="li-show-title">Order by</div>
            <select name="propertySearch[orderby]" class="li-show-select">
                <option value="">Select order</option>
                <option value="price-high" <?php echo isset($_GET['propertySearch']['orderby']) && $_GET['propertySearch']['orderby'] == 'price-high' ? 'selected':'' ?>>Price high-to-low</option>
                <option value="price-low" <?php echo isset($_GET['propertySearch']['orderby']) && $_GET['propertySearch']['orderby'] == 'price-low' ? 'selected':'' ?>>Price low-to-high</option>
                <option value="new" <?php echo isset($_GET['propertySearch']['orderby']) && $_GET['propertySearch']['orderby'] == 'new' ? 'selected':'' ?>>Newest first</option>
            </select>

        </div>
        <div class="li-print"><a onclick="printPage(); return false;" href="#">Print this List</a></div>
      </form>
  </div>

   <?php if ( empty($this->property['data']) ) : ?>
        <div class="li-house-item clearfix" style="text-align: center;">
            Nothing was found
        </div>
   <?php else: ?>
     <?php foreach($this->property['data'] as $value) : ?>
          <div class="li-house-item clearfix">
        	<div class="li-house-item-photo">
                <a href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $value['list_no'], 'propertyAddress' => implode('-', array($value['address1'], $value['address2'], $value['postal_code'])))); ?>">
                    <?php if ($this->options['defaultState'] == 'demo') : ?>
                        <img src="http://plugin.freedomestate.org/pic/<?php echo rand(1, 100)?>.jpg" width="180px" height="135px" />
                    <?php elseif ($value['status'] == 'SLD' && !isAuthUser() ): ?>
                        <img src="<?php echo KEN_PLUGIN_URL ?>images/plzlogin.png" width="180px" height="135px" />
                    <?php else: ?>
                        <img src="http://mls-cdn.recloud.me/<?php echo $this->options['defaultState'] ?>/464x320/<?php echo $value['row_id'];?>/1.png" width="180px" height="135px" />
                    <?php endif; ?>
                </a>
            </div>
            <div class="li-house-item-prop">
            	<div class="li-house-item-prop-price">
                    <?php if ($value['status'] == 'SLD' && !isAuthUser() ): ?>
                        <span style="font-size: medium;">Please <?php echo getAuthLink(); ?> to see the price</span>
                    <?php else: ?>
                        $<?php echo number_format($value['list_price'], 0, '.', ','); ?>
                    <?php endif; ?>
                </div>
                <div class="li-house-item-prop-place"><?php echo $value['address1'] ?></div>
                <div class="li-house-item-prop-mls">MLS: <?php echo $value['list_no'] ?></div>
                <table cellspacing="0" cellpadding="0" class="li-house-item-prop-list">
                    <tr>
                        <?php if(!empty($value['bedroom_count'])): ?>
                            <td>Beds: <strong><?php echo $value['bedroom_count']?></strong></td>
                        <?php endif; ?>

                        <?php if(!empty($value['full_bath_count'])): ?>
                            <td>Baths: <strong><?php echo $value['full_bath_count']?></strong></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if(!empty($value['total_area'])): ?>
                            <td>Sqft: <strong><?php echo $value['total_area']?></strong></td>
                        <?php endif; ?>

                        <?php if( !empty($value['prop_type']) ) :?>
                         <td>Type: <strong><?php echo getFullPropertyType($value['prop_type']); ?></strong></td>
                        <?php endif; ?>
                    </tr>
                </table>

            </div>

            <ul class="li-house-item-links">
                <li><a href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $value['list_no'], 'propertyAddress' => implode('-', array($value['address1'], $value['address2'], $value['postal_code'])))); ?>">View Details</a></li>
                <li><a class="de-request-showing" href="#">Request Showing</a></li>
            </ul>
          </div>
     <?php endforeach; ?>
   <?php endif; ?>

   <?php echo $this->pagination; ?>


