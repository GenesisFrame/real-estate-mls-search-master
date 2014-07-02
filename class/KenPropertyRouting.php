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

 
class KenPropertyRouting {

    private $_slug = 'kenproperty';

    public function KenPropertyRouting()
    {
        add_filter("rewrite_rules_array", array($this, "insertRules"));
        add_filter('query_vars', array($this, 'addQueryVars') );
		add_filter('wp_title' , array($this, 'rewriteTitle') );
        add_action('wp_loaded', array($this, 'flushRules') );
    }


    public function addQueryVars( $queryWars )
    {
        $queryWars[] = 'propertyPart';
        $queryWars[] = 'propertyId';
        $queryWars[] = 'propertyAction';
        $queryWars[] = 'propertyAddress';
        $queryWars[] = 'propertySearch';
        $queryWars[] = 'propertyCallback';

		return $queryWars;
    }

    public function rewriteTitle()
    {
        
    }

    public function flushRules()
    {
        $rules = get_option( 'rewrite_rules' );

        if ( ! isset( $rules['property/.*-MLS-(.*?)\/?$'] ) ) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }

    public function insertRules($incomingRules)
    {
        $rules = array(
			'property/.*-MLS-(.*?)\/?$' => 'index.php?propertyPart=viewProperty&propertyId=$matches[1]&propertyAction=default',
            'property/?$' => 'index.php?propertyAction=default',
		);

        return $rules + $incomingRules;
    }

    static public function generateUrl($value)
    {
        if ( is_array($value) ) {

            if (!isset($value['propertyAction'])) {
                $value['propertyAction'] = 'default';
            }

            if (array_key_exists('propertyId', $value) && array_key_exists('propertyAddress', $value)) {
                return self::genPropertyUrl($value['propertyId'], $value['propertyAddress']);
            } else if (count($value) == 1  && $value['propertyAction'] == 'default') {
                return self::genPropertyBaseUrl();
            }
            
            $link = http_build_query($value);
        }

        return '/?' . $link;
    }

    static private function genPropertyUrl($id, $address)
    {
        $cacheFalse = '?v=1.0'; //isAuthUser() ? '?v=1.0' : '';

        $address = str_replace(array(' ', '_', '+', ')', '(', '.', ',', '/', '\\'), '-', $address);
        return '/property/' . $address  . '-MLS-' . $id . '/' . $cacheFalse;
    }

    static private function genPropertyBaseUrl()
    {
        return '/property/';
    }

}
