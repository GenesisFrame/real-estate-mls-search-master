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



function getFullPropertyType( $type )
{
    $types = array(
        'SF' => 'Single Family',
        'MF' => 'Multi-family',
        'MH' => 'Mobile Home',
        'CC' => 'Condo',
        'LD' => 'Land',
        'CO' => 'Commercial Opportunity',
        'CI' => 'Commercial Investment'
    );

    if (array_key_exists( strtoupper($type), $types ) ) {
        return $types[ strtoupper($type) ];
    }
    else {
        return $type;
    }

}

function escapeValues($values)
{
    $temp = explode(',', $values);

    if  (count($temp) == 0) {
        return '';
    }

    $out = array();
    foreach ( $temp as $value ) {
        if ( !empty($value) ) {
            $out[] = $value;
        }
    }

    return implode(',', $out);
}


function getStatusType( $type )
{
    $types = array (
        'ACT'  =>  'Active',
        'NEW'  =>  'New',
        'PCG'  =>  'Price Change',
        'EXT'  =>  'Extended',
        'BOM'  =>  'Back on Market',
        'UAG'  =>  'Under Agreement',
        'RAC'  =>  'Reactivated',
        'SLD'  =>  'Sold',
        'EXP'  =>  'Expired',
        'CAN'  =>  'Cancelled',
        'WDN'  =>  'Withdrawn',
        'RNT'  =>  'Rented',
    );

    if (array_key_exists( strtoupper($type), $types ) ) {
        return $types[ strtoupper($type) ];
    }
    else {
        return '';
    }

}

function generateSearchString($options = array(), $generateLink=false)
{
    $out=array();
    $data = KenRequest::query('propertySearch');

    if (empty($data)) return $out;
    

    foreach ($data as $key=>$value) {
        if (isset($options['exclude']) && !in_array(strtolower($key), $options['exclude'])) {
            if (!$generateLink) {
                if (is_array($value)) {
                    foreach ($value as $tValue) {
                        $out[] = '<input type="hidden" name="propertySearch['. strtolower($key) .'][]" value="'. $tValue .'" />';
                    }
                } else {
                    $out[] = '<input type="hidden" name="propertySearch['. strtolower($key) .']" value="'. $value .'" />';
                }
            } else {
                if (is_array($value)) {
                    foreach ($value as $tValue) {
                        $out['propertySearch['. strtolower($key) .']'] = $tValue;
                    }
                } else {
                    $out['propertySearch['. strtolower($key) .']'] = $value;
                }
            }
        } else if (isset($options['include']) && !in_array(strtolower($key), $options['include'])) {
            if (!$generateLink) {
                if (is_array($value)) {
                    foreach ($value as $tValue) {
                        $out[] = '<input type="hidden" name="propertySearch['. strtolower($key) .'][]" value="'. $tValue .'" />';
                    }
                } else {
                    $out[] = '<input type="hidden" name="propertySearch['. strtolower($key) .']" value="'. $value .'" />';
                }
            } else {
                if (is_array($value)) {
                    foreach ($value as $tValue) {
                        $out['propertySearch['. strtolower($key) .']'] = $tValue;
                    }
                } else {
                    $out['propertySearch['. strtolower($key) .']'] = $value;
                }
            }
        } else {
            if (!$generateLink) {
                if (is_array($value)) {
                    foreach ($value as $tValue) {
                        $out[] = '<input type="hidden" name="propertySearch['. strtolower($key) .'][]" value="'. $tValue .'" />';
                    }
                } else {
                    $out[] = '<input type="hidden" name="propertySearch['. strtolower($key) .']" value="'. $value .'" />';
                }
            } else {
                if (is_array($value)) {
                    foreach ($value as $tValue) {
                        $out['propertySearch['. strtolower($key) .']'] = $tValue;
                    }
                } else {
                    $out['propertySearch['. strtolower($key) .']'] = $value;
                }
            }
        }

    }

    if (!$generateLink) {
        return implode(PHP_EOL, $out);
    } else {
        return $out;
    }
}

function isAuthUser()
{
    if (isset($_SESSION['ken-property-auth']) && $_SESSION['ken-property-auth'] == true) {
        return true;
    }

    return false;
}

function getAuthLink()
{
    echo '<a class="auth-button login"  href="#"><strong>LOG IN</strong></a>';
}

function reParentDropdown( $default = '', $parent = 0, $level = 0 ) {
	global $wpdb, $post_ID;
	$items = $wpdb->get_results( $wpdb->prepare("SELECT ID, post_parent, post_title, post_name FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'page' ORDER BY menu_order", $parent) );

	if ( $items ) {
		foreach ( $items as $item ) {
			// A page cannot be its own parent.
			if (!empty ( $post_ID ) ) {
				if ( $item->ID == $post_ID ) {
					continue;
				}
			}
			$pad = str_repeat( '&nbsp;', $level * 3 );
			if ( $item->post_name == $default)
				$current = ' selected="selected"';
			else
				$current = '';

			echo "\n\t<option class='level-$level' value='$item->post_name' $current>$pad " . esc_html($item->post_title) . "</option>";
			reParentDropdown( $default, $item->ID, $level +1 );
		}
	} else {
		return false;
	}
}

function reInsertPagePropertyShortcodes( $pageName = '') {
	global $wpdb, $post_ID;

	//$item = $wpdb->get_row( $wpdb->prepare("SELECT ID, post_parent, post_title, post_name FROM $wpdb->posts WHERE post_name = %d AND post_type = 'page' LIMIT 1", $pageName) );

    $inputData = array(
        'post_content' => '[recloud-property]',
    );

    $wpdb->update( $wpdb->posts, $inputData, array( 'post_name' => $pageName ));
}
