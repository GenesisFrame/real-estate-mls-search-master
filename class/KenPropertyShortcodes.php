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

 
class KenPropertyShortcodes {


    public function __construct()
    {
    }

    public function addQueryVars( $queryWars )
    {
        $queryWars[] = 'pid';
        $queryWars[] = 'callback';

		return $queryWars;
    }
    

    public function rewriteTitle()
    {
        
    }

}
