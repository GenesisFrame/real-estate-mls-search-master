<div id="rep-page">
    <div class="li-filters clearfix">
        <div class="de-prev">
            <?php if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'])): ?>
                <a href="<?php echo $_SERVER['HTTP_REFERER']?>" class="det-nav-back">Back to Search</a>
            <?php else: ?>
                <a href="<?php echo KenPropertyRouting::generateUrl(array()); ?>" class="det-nav-back">Back</a>
            <?php endif; ?>
        </div>
        <div class="de-print">
            <a class="addthis_counter addthis_pill_style" style="float:right; margin-left:15px;"></a>
            <a href="#" onclick="printPage(); return false;">Print this List</a>
        </div>
    </div>
    <div class="de-main-prop clearfix">
        <div class="de-main-prop-photo de-prop-gallery">
            <div class="main-photo">
                <?php if ($this->property['status'] == 'SLD' && !isAuthUser() ) : ?>
                        <img width="320" height="245" src="<?php echo KEN_PLUGIN_URL ?>images/plzlogin.png" alt="" />
                <?php else: ?>
                    <?php if ($this->options['defaultState'] == 'demo'): ?>
                        <img src="http://plugin.freedomestate.org/pic/<?php echo rand(1, 100)?>.jpg" width="320px" height="245px" />
                    <?php else: ?>
                        <img src="http://mls-cdn.recloud.me/<?php echo $this->options['defaultState'] ?>/464x320/<?php echo $this->property['row_id'];?>/1.png" width="320px" height="245px" />
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <ul class="de-tumb-photo">
                <?php if ($this->property['status'] == 'SLD' && !isAuthUser() ): ?>
                <?php else: ?>

                    <?php if ($this->options['defaultState'] == 'boston') : ?>
                        <?php if($this->property['photo_count'] > 1) : ?>
                            <?php for($i = 0; $i <= $this->property['photo_count']-1; $i++): ?>
                            <li>
                                <a data-width="320" data-height="245" href="http://mls-cdn.recloud.me/boston/464x320/<?php echo $this->property['row_id'];?>/<?php echo $i;?>.png">
                                    <img src="http://mls-cdn.recloud.me/<?php echo $this->options['defaultState'] ?>/100x100/<?php echo $this->property['row_id'];?>/<?php echo $i;?>.png" width="60px" height="45px" id="slidethumb-<?php echo $i;?>"/>
                                </a>
                            </li>
                            <?php endfor; ?>
                        <?php endif; ?>
                    <?php else : ?>
                         <?php if( !empty($this->property['photos'])): ?>
                                <?php foreach($this->property['photos'] as $picName): ?>
                                <li>
                                    <?php if ($this->options['defaultState'] == 'demo') : ?>
                                        <?php
                                            $photoId = rand(1, 100);
                                        ?>
                                        <a data-width="320" data-height="245" href="http://plugin.freedomestate.org/pic/<?php echo $photoId ?>.jpg">
                                            <img src="http://plugin.freedomestate.org/pic/<?php echo $photoId ?>.jpg" width="60px" height="45px" id="slidethumb-<?php echo $picName;?>" />
                                        </a>
                                    <?php else: ?>
                                        <a data-width="320" data-height="245" href="http://mls-cdn.recloud.me/<?php echo $this->options['defaultState'] ?>/464x320/<?php echo $this->property['row_id'];?>/<?php echo $picName ?>.png">
                                            <img src="http://mls-cdn.recloud.me/<?php echo $this->options['defaultState'] ?>/100x100/<?php echo $this->property['row_id'];?>/<?php echo $picName ?>.png" width="60px" height="45px" id="slidethumb-<?php echo $picName;?>" />
                                        </a>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                         <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

        </div>
        <div class="de-main-prop-right">
            <div class="li-house-item-prop-price">
                <?php if ($this->property['status'] == 'SLD' && !isAuthUser() ): ?>
                    <span style="font-size: medium;">Please <?php echo getAuthLink(); ?> to see the price</span>
                <?php else: ?>
                    $<?php echo number_format($this->property['price'], 0, '.', ','); ?>
                <?php endif; ?>
            </div>
            <div class="li-house-item-prop-place"><?php echo $this->property['address1'] ?> &bull; <?php echo $this->property['address2']?>, <?php echo $this->property['zip_code']?></div>
            <div class="li-house-item-prop-mls">MLS# <?php echo $this->property['list_no'] ?><!-- &nbsp;&nbsp;|&nbsp;&nbsp;Status: Active--></div>
            <table cellspacing="0" cellpadding="0" class="li-house-item-prop-list">
                <tr>
                    <?php if (!empty($this->property['no_bedrooms'])): ?>
                        <td>Beds: <strong><?php echo $this->property['no_bedrooms']?></strong></td>
                    <?php endif; ?>

                    <?php if (!empty($this->property['no_full_baths'])): ?>
                        <td>Baths: <strong><?php echo $this->property['no_full_baths']?></strong></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php if (!empty($this->property['square_feet'])): ?>
                        <td>Sqft: <strong><?php echo $this->property['square_feet']?></strong></td>
                    <?php endif; ?>

                    <?php if(!empty($this->property['year_built'])): ?>
                        <td>Yrblt: <strong><?php echo $this->property['year_built']?></strong></td>
                    <?php endif; ?>
                </tr>
            </table>

            <!-- FreeTellaFriend BEGIN -->
                <script type="text/javascript">
                (function() {
                var s=document.createElement('script');s.type='text/javascript';s.async = true;
                s.src='http://serv1'+'.freetellafriend.com/share_addon.js';
                var j =document.getElementsByTagName('script')[0];j.parentNode.insertBefore(s,j);
                })();
                </script>
            <!-- FreeTellaFriend END -->

            <ul class="de-house-links clearfix">
                <li><a class="de-request-showing" href="#">Request Showing</a></li>
                <li><a href="http://www.freetellafriend.com/?share" onclick="return sa_tellafriend()">Email to a Friend</a></li>
                <li><a target="_blank" href="http://maps.google.com/maps?q=<?php echo $this->property['address1'] ?>,<?php echo $this->property['address2']?>,<?php echo $this->property['zip_code']?>&hl=en&t=h&z=19">View on the Map</a></li>
                <li><a href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'mortgage', 'balance' => $this->property['price'])) ?>">Mortgage Calc</a></li>
            </ul>
            <div class="de-notice">
                <?php if (!isAuthUser()): ?>
                    Please <?php echo getAuthLink(); ?> to view property description and additional information. MLSPin rules require us that you must be logged in before we can display this information.
                <?php endif; ?>
            </div>
        </div>

        <!-- AddThis Button BEGIN -->

    </div>
    <h2 class="de-line">MAP </h2>
    <div class="de-map" id="tmaps_listingmap" style="height: 200px;">Sorry, Unable to map the location</div>
        <script type="text/javascript">
                var latlng = new google.maps.LatLng(<?php echo $this->property['lat'].', '.$this->property['lon'];?>);
                var myOptions = {
                    zoom: 16,
                    center: latlng,
                    zoomControl: true,
                    draggable: true,
                    scrollwheel: false,
                    navigationControl: true,
                    disableDefaultUI:false,
                    mapTypeControl:true,
                    panControl:true,
                    scaleControl:true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var map = new google.maps.Map(document.getElementById("tmaps_listingmap"), myOptions); // BLAM
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
        </script>
    <div class="clearfix">
        <div class="de-block45l">
            <h2 class="de-line"> Features </h2>
            <ul>
                <?php if ( !empty($this->property['prop_type']) ): ?>
                    <li>Property Type: <?php echo isAuthUser() ? getFullPropertyType($this->property['prop_type']) : getAuthLink() ;?></li>
                <?php endif; ?>

                <?php if ( !empty($this->property['lot_size']) ): ?>
                    <li>Lot Size: <?php echo isAuthUser() ? $this->property['lot_size'] : getAuthLink() ;?></li>
                <?php endif; ?>

                <?php if ( !empty($this->property['sewer_and_water']) ): ?>
                    <li>Sewer: <?php echo isAuthUser() ? escapeValues($this->property['sewer_and_water']) : getAuthLink() ;?></li>
                <?php endif; ?>

                <?php if ( !empty($this->property['cooling']) ): ?>
                    <li>Cool: <?php echo isAuthUser() ? escapeValues($this->property['cooling']) : getAuthLink() ;?></li>
                <?php endif; ?>

                <?php if ( !empty($this->property['heating']) ): ?>
                    <li>Heat: <?php echo isAuthUser() ? escapeValues($this->property['heating']) : getAuthLink() ;?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="de-block45r">
            <h2 class="de-line">Remarks</h2>
            <?php echo isAuthUser() ? $this->property['remarks'] : getAuthLink() ?>
        </div>
    </div>

    <h2 class="de-line">Additional Information</h2>
    <ul>
        <?php if ( !empty($this->property['no_full_baths']) ): ?>
            <li>Full Baths: <?php echo isAuthUser() ? $this->property['no_full_baths'] : getAuthLink() ?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['no_living_levels']) ): ?>
            <li>Levels: <?php echo isAuthUser() ? $this->property['no_living_levels'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['hot_water']) ): ?>
            <li>Hot Water: <?php echo isAuthUser() ? escapeValues($this->property['hot_water']) : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['taxes']) ): ?>
            <li>Property Tax: <?php echo isAuthUser() ? escapeValues($this->property['taxes']) : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['foundation']) ): ?>
            <li>Basement/Foundation: <?php echo isAuthUser() ? escapeValues($this->property['foundation']) : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['garage_parking']) ): ?>
            <li>Garage spaces: <?php echo isAuthUser() ? $this->property['garage_parking'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['garage_spaces']) ): ?>
            <li>Parking spaces: <?php echo isAuthUser() ? $this->property['garage_spaces'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['hoa_fee']) ): ?>
            <li>Fee: <?php echo isAuthUser() ? $this->property['hoa_fee'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['home_own_association']) ): ?>
            <li>Fee includes: <?php echo isAuthUser() ? $this->property['home_own_association'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['assessments']) ): ?>
            <li>Assessed Value: <?php echo isAuthUser() ? $this->property['assessments'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['exterior_features']) ): ?>
            <li>Exterior Features: <?php echo isAuthUser() ? escapeValues($this->property['exterior_features']) : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['appliances']) ): ?>
            <li>Appliances: <?php echo isAuthUser() ?  escapeValues($this->property['appliances']) : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['kitchen_description']) ): ?>
            <li>Kitchen Features: <?php echo isAuthUser() ?  $this->property['kitchen_description'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['style']) ): ?>
            <li>Style: <?php echo isAuthUser() ?  $this->property['style'] : getAuthLink() ?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['status']) ): ?>
            <li>Status: <?php echo isAuthUser() ?  getStatusType($this->property['status']) : getAuthLink() ; ?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['disclosures']) ): ?>
            <li>Disclosures: <?php echo isAuthUser() ? $this->property['disclosures'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['orig_price']) ): ?>
            <li>Original price: <?php echo isAuthUser() ? $this->property['orig_price'] : getAuthLink() ;?></li>
        <?php endif; ?>

        <?php if ( !empty($this->property['market_time_property']) ): ?>
            <li>Days on Market: <?php echo isAuthUser() ? $this->property['market_time_property'] : getAuthLink() ;?></li>
        <?php endif; ?>
    </ul>

	 <?php
		$val = get_object_vars($this);
		$app = new KenPropertyModelApi($val['options']);
		$set = array();
		$set['zip_code'] = $this->property['zip_code'];
		$set['list_price_min'] = $this->property['price'] - $this->property['price']*0.1;
		$set['list_price_max'] = $this->property['price'] + $this->property['price']*0.1;
		$set['order_by'] = 'price-high';
		$set['type']['0'] = 'SF';
		$set['type']['1'] = 'MF';
		$set['type']['2'] = 'CC';
		$set['status']['0'] = 'ACT';
		$set['status']['1'] = 'NEW';
		$set['status']['2'] = 'PCG';
		$set['status']['3'] = 'EXT';
		$set['status']['4'] = 'BOM';
		$set['status']['5'] = 'RAC';

		$rek = $app->getSearch($p = 1, $lim = 6, $set, $orderBy=array());

		$i = 1;
		if (!empty($rek['data']) && count($rek['data']) > 1) {
			echo '<h2 class="de-line">Other Listings</h2><div class="latestlistings">';
			foreach ($rek['data'] as $k => $v) {
				if ($i <= 5) {
					if ($v['list_no'] == $this->property['list_no']) {
						$i = $i - 1;
					}
					else {
					?>
						<div class="listing200 <?php echo ($i == 1) ? 'first':''?>" style=" margin-bottom: 10px; margin-left: 10px; padding-top: 5px;">
							<a href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $v['list_no'], 'propertyAddress' => implode('-', array($v['address1'], $v['address2'], $v['postal_code'])))); ?>" alt="<?php echo $v['address1'] ?> for sale" title="<?php echo $v['address1'] ?> <?php echo $v['address2'] ?> for sale">
			                    <?php if ($this->options['defaultState'] == 'demo') : ?>
			                        <img src="http://plugin.freedomestate.org/pic/<?php echo rand(1, 100)?>.jpg" width="200px" height="100px"/>
			                    <?php elseif ($v['status'] == 'SLD' && !isAuthUser() ): ?>
			                        <img src="<?php echo KEN_PLUGIN_URL ?>images/plzlogin.png" width="200px" height="100px"/>
			                    <?php else: ?>
			                        <img src="http://mls-cdn.recloud.me/<?php echo $this->options['defaultState'] ?>/464x320/<?php echo $v['row_id'];?>/1.png" width="200px" height="100px" />
			                    <?php endif; ?>
			                </a>
							<a href="<?php echo KenPropertyRouting::generateUrl(array('propertyPart' => 'viewProperty', 'propertyId' => $v['list_no'], 'propertyAddress' => implode('-', array($v['address1'], $v['address2'], $v['postal_code'])))); ?>" class="listing200-text" alt="<?php echo $v['address1'] ?> for sale" title="<?php echo $v['address1'] ?> <?php echo $v['address2'] ?> for sale">
							<?php echo $v['address1'] ?> <span class="listing200-desc"> <?php echo $v['address2'] ?><br>
					Beds: <?php echo $v['bedroom_count']?> &#8226; Baths: <?php echo $v['full_bath_count']?> &#8226; Sqft: <?php echo $v['total_area']?> </span> <span class="listing200-money">
					                    <?php if ($v['status'] == 'SLD' && !isAuthUser() ): ?>
					                        Please <?php echo getAuthLink(); ?> to see the price
					                    <?php else: ?>
					                        $<?php echo number_format($v['list_price'], 0, '.', ','); ?>
					                    <?php endif; ?>
					                </span>
							</a> 
						</div>					
			<?php	}
					$i++;
				}
			}
			echo '<div class="clear"></div>';
			echo '</div>';
		}
	?>


    <div class="share-it">
        <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                <a class="addthis_button_tweet"></a>
                <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
                <a class="addthis_counter addthis_pill_style"></a>
        </div>
    </div>


    <div class="de-notice" style="color:#aaa">
        The property listing data and information, or the Images, set forth herein were provided to MLS Property Information Network, Inc. from third party sources,
        including sellers, lessors and public records, and were compiled by MLS Property Information Network, Inc.
        The property listing data and information, and the Images, are for the personal, non-commercial use of consumers having
        a good faith interest in purchasing or leasing listed properties of the type displayed to them
        and may not be used for any purpose other than to identify prospective properties which such consumers
        may have a good faith interest in purchasing or leasing.
        MLS Property Information Network, Inc. and its subscribers disclaim any and all representations and warranties as to the accuracy
        of the property listing data and information, or as to the accuracy of any of the Images, set forth herein. Last updated: ( <?php echo $this->property['update_date']?> )
        Listing courtesy of: <?php echo $this->property['agent_name']?> at  <?php echo $this->property['office_name']?>
    </div>
