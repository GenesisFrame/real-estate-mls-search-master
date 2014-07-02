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


class KenPropertyModel
{

    /**
     * @var InterfaceKenPropertyData
     */
    private $_data = null;

    public function __construct($options)
    {
        include_once KEN_CLASS_DIR . 'InterfaceKenPropertyData.php';
        include_once KEN_CLASS_DIR . 'KenPropertyModelApi.php';

        $this->_data = new KenPropertyModelApi($options);

        return $this->_data;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getSearchData($page=1, $limit=10, $where=array(), $orderBy=array() )
    {
        return $this->getData()->getSearch($page, $limit, $where, $orderBy);
    }

    public function getPropertyDetails($id)
    {
        return $this->getData()->getPropertyDetails($id);
    }

    public function getNeighborhoodPropertyPosition($id, $where=array())
    {
        return $this->getData()->getNeighborhoodPropertyPosition($id, $where);
    }

}
