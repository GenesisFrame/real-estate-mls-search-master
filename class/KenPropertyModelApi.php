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

 
class KenPropertyModelApi {

    private $_api = null;

    public function __construct( $options )
    {
        $this->_api = new RecloudApi($options['apiKey']);
    }

    public function getSearch($page = 1, $limit = 10, $params = array(), $orderBy=array())
    {
        $params['page']   = $page;
        $params['limit']  = $limit;

        foreach ($params as &$value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
        }

        $options = KenProperty::getInstance()->getOptions();

        $data = $this->_api->propertySearch($options['defaultState'], $params);

        if ($data['status'] == 'ERROR') {
            // @todo Excepion
            return false;
        }

        $out=array();

        $out['data']  = $data['results'];
        $out['count'] = $data['meta']['results_count'];

        return $out;
    }

    public function getPropertyDetails($id)
    {
        $options = KenProperty::getInstance()->getOptions();
        
        $data = $this->_api->propertyGetDetails($options['defaultState'], $id);

        if ($data['status'] == 'ERROR') {
            //@todo Excepion
            return false;
        }

        return $data['results'];
    }

/*
    public function getNeighborhoodPropertyPosition($id, $where)
    {
        foreach ($where as $key => $value) {
            if ( $value['field'] == 'NEIGHBORHD' ) {
                unset($where[$key]);
            }
        }

        $whereValues = $this->generateWhereText($where);

        $whereText = $whereValues['whereText'];
        $valuesWhere = $whereValues['valuesWhere'];

        $sql = 'SELECT f.LIST_NO, f.PROP_TYPE, f.lat, f.lon,
                       f.bedroom_count,
                       f.full_bath_count, f.total_area,
                       f.list_price AS price, f.photo_count, f.PROP_TYPE,
                       f.address1 FROM mls_simple_search as f
                LEFT JOIN Open_Houses s ON f.LIST_NO=s.LIST_NO
                LEFT JOIN pall_geo as t ON f.LIST_NO = t.LIST_NO ' . $whereText;

        $stmt = $this->_dbh->prepare($sql);
        $stmt->execute($valuesWhere);

        return $stmt->fetchAll();
    }*/

}
