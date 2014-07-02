var map;
var polygonPoints = {};
var searchPoints = {};
var neighborhood_objects = {}; // Holds all the neighborhood junk

jQuery(document).ready(function() {

    if ( jQuery('#search-map').length == 0 ) {
        return false;
    }

    jQuery('.ken-search input[name="propertySearch[city]"]').css('color', '#000');
    jQuery('.ken-search input[name="propertySearch[city]"]').val('');
    jQuery('.ken-search input[name="propertySearch[neighbourhood]"]').prop('disabled', true);
    
	var latlng = new google.maps.LatLng(42.35843100, -71.05977300);
	var myOptions = {
		zoom: 14,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	map = new google.maps.Map(document.getElementById("search-map"), myOptions); // BLAM

	// Grab the neighborhood data from the JSON file
	var neighborhoods = jQuery.parseJSON(jQuery.ajax({
        url: ajaxurl,
        data: {
          'action' : 'kenproperty',
          'propertyPart' : 'viewNeighborhoods',
          'propertyAction' : 'map'
        },
		global: false,
		type: "post",
		dataType: "json",
		async:false,
		success: function(resp) {
			//alert(resp);
		}
	}).responseText);

	// Loop through the array of neighborhood objects
	jQuery.each(neighborhoods, function(key, value) {
		// We keep track of all the stuff we render in here, along with any other accompanying properties we might need
		neighborhood_objects[value.gis_neighborhood_id] = {
			"neighborhood_name" : value.neighborhood_name,
			"center_lat" : value.center_lat,
			"center_lon" : value.center_lon,
			"selected" : false,
			"listing_markers" : []
		};
		
		// Push the boundaries into an array because Google maps freaks out if you give it an object
		var neighborhood_paths = [];
		for(var b = 0; b < value.boundaries.length; b++) {
			neighborhood_paths.push(new google.maps.LatLng(value.boundaries[b].lat, value.boundaries[b].lon));
		}
		
		// Create the polygon and stick it into our neighborhood objects array
		neighborhood_objects[value.gis_neighborhood_id].polygon = new google.maps.Polygon({
			paths: neighborhood_paths,
			strokeColor: "#FF0000",
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: "#FF0000",
			fillOpacity: 0.0
		});
		
		// Mouseover event listener
		google.maps.event.addListener(neighborhood_objects[value.gis_neighborhood_id].polygon, "mouseover",function() { 
			if(!neighborhood_objects[value.gis_neighborhood_id].selected) {
				this.setOptions({fillOpacity: 0.1}); 
			}
		}); 
		
		// Mouseout event listener
		google.maps.event.addListener(neighborhood_objects[value.gis_neighborhood_id].polygon, "mouseout",function() { 
			if(!neighborhood_objects[value.gis_neighborhood_id].selected) {
				this.setOptions({fillOpacity: 0.0});
			}
		});

		// Click event listener
		google.maps.event.addListener(neighborhood_objects[value.gis_neighborhood_id].polygon, "click",function() {

            jQuery('.ken-search input[name="propertySearch[city]"]').val('');
            clearSearchMarkers();
            
			if(!neighborhood_objects[value.gis_neighborhood_id].selected) {
				neighborhood_objects[value.gis_neighborhood_id].selected = true;
				this.setOptions({fillOpacity: 0.5});
				toggleSearchFilters();
                
				// AJAX method that searches listings goes here -- probably should POST to a URL, return JSON and loop through it
				// This is a simple example that only drops one pin to demonstrate
				
				// Example:
				//jQuery.post("/search/magic_listings_by_neighborhood/", post_data, function(result){
				//	jQuery.each(result, function(listing_key, listing) {
				
						// Create a marker (we're using the center_lat and center_lon of the polygon just as an example)
						var listing_latlng = new google.maps.LatLng(neighborhood_objects[value.gis_neighborhood_id].center_lat,neighborhood_objects[value.gis_neighborhood_id].center_lon); // Use listing lat/lon
						
						// Actual marker
						/*var listing_marker = new google.maps.Marker({
							position: listing_latlng, 
							title: "Test marker",
							animation: google.maps.Animation.DROP
						});*/
						
						// Click event for the marker
						/*google.maps.event.addListener(listing_marker, "click",function() {
							console.log('Listing marker clicked!');
						});*/
						
						// Stick the marker into our neighborhood object so we can keep track of it
						//neighborhood_objects[value.gis_neighborhood_id].listing_markers.push(listing_marker);
						
						// You probably want to store listing markers as an array keyed by list_no so you can manipulate them from elsewhere
						//neighborhood_objects[value.gis_neighborhood_id].listing_markers[listing.list_no] = listing_marker;
						
						//listing_marker.setMap(map);	// Stick it on the map!
						
				//	});
				//});
				
				// Just showing off some of the variables
				//console.log('You clicked ' + neighborhood_objects[value.gis_neighborhood_id].neighborhood_name + ' which has a neighborhood_id of ' + value.gis_neighborhood_id);
                map.setCenter(listing_latlng);
                //map.setZoom(16);

                jQuery('.ken-search input[name="propertySearch[neighbourhood]"]');

                if ( jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() == '' ) {
                    var neighbour = [];
                    var neighbourName = [];
                } else {
                    var temp = jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val();
                    var tempName = jQuery('.ken-search input[name="propertySearch[neighbourhood]"]').val();
                    
                    var neighbour = temp.split(',');
                    var neighbourName = tempName.split(',');
                }

                neighbour.push(value.gis_neighborhood_id);
                neighbourName.push(neighborhood_objects[value.gis_neighborhood_id].neighborhood_name);

                jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val( neighbour.join(',') );
                jQuery('.ken-search input[name="propertySearch[neighbourhood]"]').val( neighbourName.join(',') );
                
                getNeighborhoodProperty(value.gis_neighborhood_id);

			} else {
				neighborhood_objects[value.gis_neighborhood_id].selected = false;
				this.setOptions({fillOpacity: 0.0});

				// Clear all the markers when neighborhood is unselected
				jQuery.each(neighborhood_objects[value.gis_neighborhood_id].listing_markers, function(key, value) {
					value.setMap(null);
				});

                clearPolygonMarkers(value.gis_neighborhood_id);

                var temp = jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val();
                var tempName = jQuery('.ken-search input[name="propertySearch[neighbourhood]"]').val();

                var neighbour = temp.split(',');
                var neighbourName = tempName.split(',');

                var tempNeighbour=[];
                var tempNeighbourName=[];

                jQuery.each(neighbour, function(i, val){
                      if (val != value.gis_neighborhood_id) {
                          tempNeighbour.push(val);
                      }
                });

                jQuery.each(neighbourName, function(i, val){
                      if (val != value.neighborhood_name) {
                          tempNeighbourName.push(val);
                      }
                });

                jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val( tempNeighbour.join(',') );
                jQuery('.ken-search input[name="propertySearch[neighbourhood]"]').val( tempNeighbourName.join(',') );
			}

            if ( jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() != '' ) {
                getSearchProperty(1);
            } else {
                jQuery('#search-property-list').html('');
            }
			
		});
		
		neighborhood_objects[value.gis_neighborhood_id].polygon.setMap(map);


        // Create area names
        var listing_latlng = new google.maps.LatLng(neighborhood_objects[value.gis_neighborhood_id].center_lat,neighborhood_objects[value.gis_neighborhood_id].center_lon);

        var marker = new google.maps.Marker({
            position: listing_latlng,
            //animation: google.maps.Animation.DROP,
            map: map,
            icon: getDynamicIcon(neighborhood_objects[value.gis_neighborhood_id].neighborhood_name)
        });
	});

    jQuery('.back-to-search').click(function(){
        jQuery('.map-panel-wrapper').hide();
        jQuery('#search-property-panel').show();

        return false;
    });

    jQuery('.show-property').live('click', function() {
        var propertyId = jQuery(this).parents('.map-panel-list-item').children('input[name="property-id"]').val();
        getSelectedProperty(propertyId);
        return false;
    });

    jQuery('.show-property-li').live('click', function() {
        var propertyId = jQuery(this).children('input[name="property-id"]').val();
        getSelectedProperty(propertyId);
        return false;
    });

    jQuery('.map-pagination a').live('click', function() {
        var page = $(this).text();
        jQuery(this).addClass('current');
        //clearAllPolygonMarkers(true);
        
        getSearchProperty(page);
        return false;
    });

    jQuery('.search-filters form').submit(function(){
        var page = $('.map-pagination .current').text();

        if ( jQuery('.ken-search input[name="propertySearch[city]"]').val()=='' 
            && jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() == ''
            && jQuery('.ken-search input[name="propertySearch[mls]"]').val() == ''
            && jQuery('.ken-search input[name="propertySearch[open]"]').prop('checked') == false    ) {
            alert('Please choose a city or select neighbourhood');
            return false;
        }

        if (
                jQuery('.ken-search input[name="propertySearch[city]"]').val() != '' ||
                jQuery('.ken-search input[name="propertySearch[mls]"]').val() != '' ||
                jQuery('.ken-search input[name="propertySearch[zip-code]"]').val() != ''
           ) {

            jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val('');
            jQuery('.ken-search input[name="propertySearch[neighbourhood]"]').val('');

            clearAllPolygonMarkers(false);
        }

        if ( jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() == '') {
        } else {
            var temp = jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val();
            var neighbour = temp.split(',');

            $.each(neighbour, function(i, val){
                getNeighborhoodProperty(val);
            });

        }
        
        toggleSearchFilters();
        getSearchProperty(page);

        return false;
    });

    // Information div
    var infowindow;

    jQuery('.map-panel-list-item').live('mouseenter', function(){
        var id = jQuery(this).find('input[name="property-id"]').val();
        var area = jQuery(this).find('input[name="neighborhood-id"]').val();
        var lat = jQuery(this).find('input[name="property-lat"]').val();
        var lon = jQuery(this).find('input[name="property-lon"]').val();
        
        var infoWindowLatLon = new google.maps.LatLng(lat, lon);

        var content = jQuery(this).clone();
            jQuery('.active', content).remove();
            jQuery('.det-panel-bottom', content).remove();
            jQuery('.det-panel-description', content).css('width', '200px');

        var content = jQuery(content).html();
        
        infowindow = new google.maps.InfoWindow({
          content: content,
          position: infoWindowLatLon
        });

        if (jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() != '') {
            polygonPoints[area][id].setAnimation(google.maps.Animation.BOUNCE);
        } else {
            searchPoints[id].setAnimation(google.maps.Animation.BOUNCE);
        }

        infowindow.open(map);
    });

    jQuery('.map-panel-list-item').live('mouseleave', function(){
        var id = jQuery(this).find('input[name="property-id"]').val();
        var area = jQuery(this).find('input[name="neighborhood-id"]').val();

        if (jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() != '') {
            polygonPoints[area][id].setAnimation(null);
        } else {
            searchPoints[id].setAnimation(null);
        }

        infowindow.close();
    });

    jQuery('.show-search-criteria').live('click', function(){
        toggleSearchFilters();
    });
    
});


function getSearchData() {
     var searchData = {};

    $('.search-filters form input[type="text"], .search-filters form input[type="hidden"]').each(function(i, val){
        if ($(this).val() != $(this).attr('default') && $(this).val() != '') {
            var name = $(this).prop('name');
            searchData[name] = $(this).val();
        }
    });

    if ( $('.search-filters form input[name="propertySearch[open]"]').prop('checked') == true ) {
        searchData['propertySearch[open]'] = $('.search-filters form input[name="propertySearch[open]"]').val();
    }
    
    if ( $('.search-filters form input[name="propertySearch[status]"]').prop('checked') == true ) {
        searchData['propertySearch[status]'] = $('.search-filters form input[name="propertySearch[status]"]').val();
    }

    $('.search-filters form select').each(function(i, val){
            var name = $(this).prop('name');
            searchData[name] = $(this).val();
    });

    return searchData;
}

function generateInfowindow(lat, lon, id, type, price, bath, beds, street)
{
    var infowindow;
    var content = '<div class="det-panel-image"><img src="http://media.mlspin.com/photo.aspx?mls=' + id +'&amp;w=72&amp;h=72&amp;n=0" alt="" width="72" height="72" /></div>'
    +'<div class="det-panel-description">'
        +'<div class="det-panel-title">'
            +'<div class="" style="font-weight: bold; font-size: 13px; margin-bottom: 5px;">$' + price + '</div>'
            +'<div class="" style="font-size:11px;font-weight:normal">MLS# ' + id + '</div>'
            +'<div class="" style="font-size:11px;font-weight:normal;">' + street  + '</div>'
        +'</div>'
        +'<table class="side-features-table" style="width:200px;">'
            +'<tr>'
                +'<td>Beds: <span class="side-features-value">' + beds + '</span></td>'
                +'<td>Baths: <span class="side-features-value">' + bath + '</span></td>'
            +'</tr>'
        +'</table>'
        + type
    +'</div>';
    
    var infoWindowLatLon = new google.maps.LatLng(lat, lon);

    infowindow = new google.maps.InfoWindow({
          content: content,
          position: infoWindowLatLon
        });

    return infowindow;
}

function getSearchProperty(page) {

    $('#search-property-list').html('');
    $('#search-property-list').addClass('block-loading');

    var searchData = getSearchData();

    searchData['propertySearch[limit]'] = 50;
    searchData['action'] = 'kenproperty';
    searchData['propertyPart'] = 'search';
    searchData['page'] = page;

    clearSearchMarkers();

    jQuery.ajax({
        url: ajaxurl,
        data: searchData,
		global: false,
		type: "post",
		dataType: "html",
		async: true,
		success: function(resp) {
            $('#search-property-list').removeClass('block-loading');
			$('#search-property-list').html(resp);
            mapResize();

            if (jQuery('.ken-search input[name="propertySearch[neighbourhood-id]"]').val() == '') {
                setPinsToMap();
            } else {
                
            }
		}
	});

}

function getSelectedProperty(propertyId)
{
    $('.map-panel-wrapper').hide();
    $('#block-loading').show();

    jQuery.ajax({
        url: ajaxurl,
        data: {
          'action' : 'kenproperty',
          'propertyPart' : 'viewProperty',
          'propertyId'   : propertyId
        },
		global: false,
		type: "post",
		dataType: "html",
		async: true,
		success: function(resp) {
            $('#block-loading').hide();
			$('#view-selected-property .map-panel-item').html(resp);
            $('#view-selected-property').show();

            
            //mapResize();
		}
	});
}

function getNeighborhoodProperty(id) {

    var searchData = getSearchData();

    searchData['action'] = 'kenproperty';
    searchData['propertyPart']     = 'viewNeighborhoodProperty';
    searchData['neighborhoodId']   = id;
    //searchData['neighborhoodName'] = name;
    searchData['propertySearch[city]'] = '';
    searchData['propertySearch[zip-code]'] = '';
    searchData['propertySearch[mls]'] = '';

    clearPolygonMarkers(id);

    jQuery.ajax({
        url: ajaxurl,
        data: searchData,
		global: false,
		type: "post",
		dataType: "json",
		async: true,
		success: function(resp) {

           // var markerid = 1;
            polygonPoints[id] = {};
            
            $.each(resp, function(i, val){

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(val['lat'], val['lon']),
                    animation: google.maps.Animation.DROP,
                    map: map,
                    icon: getPinByType(val['type'])
                });



                google.maps.event.addListener(marker, 'click', function() {
                    getSelectedProperty(val['id']);
                });

                var infowindow = generateInfowindow(val['lat'], val['lon'], val['id'], val['type'], val['price'], val['bath'], val['beds'], val['street']);
                google.maps.event.addListener(marker, "mouseover", function() {
                      infowindow.open(map);
                });

                google.maps.event.addListener(marker, "mouseout", function() {
                      infowindow.close();
                });

                polygonPoints[id][val['id']] = marker;
                //markerid++;
            });

            mapResize();
		}
	});
}

function getPinByType(type) {

    var pinUrl = '/wp-content/plugins/ken-property/images/';

    if (type == 'ld') {
        pinUrl = pinUrl + 'land.png';
    } else if (type == 'sf') {
        pinUrl = pinUrl + 'single-family.png';
    } else if (type == 'mf') {
        pinUrl = pinUrl + 'multi-family.png';
    } else if (type == 'cd') {
        pinUrl = pinUrl + 'condo.png';
    } else {
        pinUrl = pinUrl + 'condo.png';
    }

    var image = new google.maps.MarkerImage(pinUrl,
		// This marker is 129 pixels wide by 42 pixels tall.
		new google.maps.Size(17, 22),
		// The origin for this image is 0,0.
		new google.maps.Point(0,0),
		// The anchor for this image is the base of the flagpole at 18,42.
		new google.maps.Point(22, 8)
	);

    return image;
}

function getDynamicIcon(name)
{
    var pinUrl = encodeURI('/wp-content/plugins/ken-property/dyname.php?name='+name);

    var image = new google.maps.MarkerImage(pinUrl,
		new google.maps.Size(110, 16),
		new google.maps.Point(0,0),
		new google.maps.Point(18, 42)
	);

    return image;
}

function setPinsToMap()
{
    // Define Marker properties
	/**/

    //var markerid = 1;
    
    $('#search-property-list .map-panel-list-item').each(function(){
        var id = $(this).find('input[name="property-id"]').val();
        var lat = $(this).find('input[name="property-lat"]').val();
        var lon = $(this).find('input[name="property-lon"]').val();
        var type = $(this).find('input[name="property-lon"]').val();

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lon),
            animation: google.maps.Animation.DROP,
            map: map,
            icon: getPinByType(type)
        });

        google.maps.event.addListener(marker, 'click', function() {
            getSelectedProperty(id);
        });

        var infoWindowLatLon = new google.maps.LatLng(lat, lon);

        var content = jQuery(this).clone();
            jQuery('.active', content).remove();
            jQuery('.det-panel-bottom', content).remove();
            jQuery('.det-panel-description', content).css('width', '200px');

        var content = jQuery(content).html();

        var infowindow = new google.maps.InfoWindow({
              content: content,
              position: infoWindowLatLon
            });
        
        google.maps.event.addListener(marker, "mouseover", function() {
              infowindow.open(map);
        });

        google.maps.event.addListener(marker, "mouseout", function() {
              infowindow.close();
        });

        searchPoints[id] = marker;
        //markerid++;
    });

}

function clearPolygonMarkers(polygonId) {

    if (polygonId in polygonPoints) {
        $.each(polygonPoints[polygonId], function(i, val){
            val.setMap(null);
        });

        delete polygonPoints[polygonId];
    }
}

function clearSearchMarkers() {

    if (searchPoints.length == 0) return true;

    $.each(searchPoints, function(i, val){
        val.setMap(null);
    });

    delete searchPoints;
    searchPoints = {};
}

function clearAllPolygonMarkers(notRemoveOverlay) {
    $.each(polygonPoints, function(i, val){

        $.each(polygonPoints[i], function(ii, vall){
            vall.setMap(null);
        });

        delete polygonPoints[i];

        if (!notRemoveOverlay) {
            neighborhood_objects[i].selected = false;
		    neighborhood_objects[i].polygon.setOptions({fillOpacity: 0.0});
        }
        
    });

}

function toggleSearchFilters()
{
    jQuery('.search-filters-group').toggle();
    jQuery('.show-search-filters').toggle();
    mapResize();
}