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

 
interface InterfaceKenPropertyData {

    public function getSearch($page, $limit, $where, $orderBy);
    public function getPropertyDetails($id);
}
