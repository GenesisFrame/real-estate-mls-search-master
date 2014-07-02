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

 
class KenPropertySite {

    static private $_instance = null;

    private $_action = '';
    private $_executeFunction = '';

    private function __construct()
    {
        $this->options = KenProperty::getInstance()->getOptions();
    }

    /**
     * @return KenPropertySite
     */
    static public function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new KenPropertySite();
        }

        return self::$_instance;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function execute($isAjax = false)
    {
        global $wp_query;

        if ($isAjax) {
            $page = KenRequest::query('propertyPart');
        } else {
            $page = get_query_var('propertyPart');
        }

        if (empty($page)) {
            $page = 'default';
        }

        if ($isAjax) {
            $this->_executeFunction = 'actionAjax' . ucfirst($page);
            $this->_action = 'ajax_' . $page;
        } else {
            $this->_executeFunction = 'action' . ucfirst($page);
            $this->_action = $page;
        }

        if (method_exists( $this, $this->_executeFunction) ) {
        } else {
            $this->_executeFunction = 'action404';
        }

        if ($isAjax) {
            $content = call_user_func( array(&$this, $this->_executeFunction), array() );
        } else {
            $content = call_user_func( array(&$this, $this->_executeFunction), array() );
            $additionalContent = $this->renderTemplate('_dialogs');
            $content = $content . $additionalContent;
        }

        return $content;
    }

    public function actionDefault()
    {
        $page  = KenRequest::query('rcpage', 1);
        $limit = KenRequest::query('limit', 10);

        $searchData = KenRequest::query('propertySearch');

        if (!empty($this->options['defaultCity']) && !isset($searchData['city'])) {
            $searchData['city'] = $this->options['defaultCity'];
        }
        
        if (!empty($this->options['defaultArea']) && !isset($searchData['neighbourhood'])) {
            $searchData['neighbourhood'] = $this->options['defaultArea'];
        }

        if ( isset($searchData['limit']) && (int)$searchData['limit'] != 0) {
            $limit = $searchData['limit'];
        }

        if ($this->options['dataSource'] == 'api') {
            $query = $this->getRequestParams($searchData);
        } else {
            $query = $this->generateSearchQuery($searchData);
        }
        
        $this->property = KenProperty::getInstance()->getData()->getSearchData($page, $limit, $query['where'], $query['orderBy']);

		$this->pages = intval(ceil($this->property['count'] / $limit));

        $this->pagination = $this->generatePagination($page, $this->pages);

        return $this->renderTemplate();
    }

    public function actionView( $params=array() )
    {
        $page    = KenRequest::query('rcpage', 1);
        $limit   = KenRequest::query('limit', 10);
        $orderby = KenRequest::query('orderby', '');

        $searchData = array();
        $searchData = KenRequest::query('propertySearch');

        if (!empty($this->options['defaultCity']) && !isset($searchData['city']) && !isset($params['city'])) {
            $searchData['city'] = $this->options['defaultCity'];
        }

        foreach ($params as $key => $value) {
            $tKey = str_replace('_', '-', $key);
            $searchData[$tKey] = $value;
        }

        if (!empty($orderby)) {
            $searchData['orderby'] = $orderby;
        }

        if ( isset($searchData['limit']) && (int)$searchData['limit'] != 0) {
            $limit = $searchData['limit'];
        }

        if ($this->options['dataSource'] == 'api') {
            $query = $this->getRequestParams($searchData);
        } else {
            $query = $this->generateSearchQuery($searchData);
        }

        $this->property = KenProperty::getInstance()->getData()->getSearchData($page, $limit, $query['where'], $query['orderBy']);

		$this->pages = intval(ceil($this->property['count'] / $limit));

        $pageName = get_query_var('pagename');

        if (!empty($pageName)) {
            $urlParams = array('urlData' => array('pagename' => $pageName, 'propertyAction' => 'shortcode'));
        } else {
            $urlRequestParams = parse_url($_SERVER['REQUEST_URI']);
            parse_str($urlRequestParams['query'], $output);

            $urlParams = array(
                'urlPath'   => $urlRequestParams['path'],
                'urlData'   => $output
            );
            
            $urlParams['disableAction'] = true;
        }

        $this->pagination = $this->generatePagination($page, $this->pages, $urlParams);

        return $this->renderTemplate('default', $urlParams);
    }

    public function actionSoldtable( $params=array() )
    {
        $searchData = array();
        
        if (!empty($this->options['defaultCity']) && !isset($searchData['city']) && !isset($params['city'])) {
            $searchData['city'] = $this->options['defaultCity'];
        }

        foreach ($params as $key => $value) {
            $tKey = str_replace('_', '-', $key);
            $searchData[$tKey] = $value;
        }

        $searchData['status'] = array('SLD');

        if ($this->options['dataSource'] == 'api') {
            $query = $this->getRequestParams($searchData);
        } else {
            $query = $this->generateSearchQuery($searchData);
        }

        $this->property = KenProperty::getInstance()->getData()->getSearchData(1, 100000, $query['where'], $query['orderBy']);
        
        return $this->renderTemplate('soldtable');
    }

    public function actionViewProperty()
    {
        $propertyId = get_query_var('propertyId');

        if ($propertyId == '') {
            return $this->renderTemplate('404');
        }

        $this->property = KenProperty::getInstance()->getData()->getPropertyDetails($propertyId);

        if ( empty($this->property) ) {
            return $this->renderTemplate('404');
        }

        return $this->renderTemplate();
    }

    public function actionContacts()
    {
        $api = new RecloudApi($this->options['apiKey']);

        $sendData = array(
            'firstName' => KenRequest::query('username'),
//            'lastName'  => 'test2',
            'email'     => KenRequest::query('email'),
            'message'   => KenRequest::query('message')
        );

        $this->result = $api->contactUs($sendData);
        
        return $this->renderTemplate();
    }

    public function actionRequestShowing()
    {
        $api = new RecloudApi($this->options['apiKey']);

        $sendData = array(
            'firstName' => KenRequest::query('firstname'),
            'lastName'  => KenRequest::query('lastname'),
            'email'     => KenRequest::query('email'),
            'phone'     => KenRequest::query('phone'),
            'message'   => KenRequest::query('comment')
        );

        $this->result = $api->requestShowing($sendData);

        return $this->renderTemplate();
    }

    public function actionRegistration()
    {
        $api = new RecloudApi($this->options['apiKey']);

        $sendData = array(
            'firstName'  => KenRequest::query('firstname'),
            'lastName'   => KenRequest::query('lastname'),
            'email'      => KenRequest::query('email'),
            'phone'      => KenRequest::query('phone'),
            'password'   => KenRequest::query('password')
        );

        if ( empty($sendData['email']) || empty($sendData['firstName']) || empty($sendData['password'])) {
            return $this->renderTemplate('404');
        }

        $sendData['password'] = md5($sendData['password']);

        $this->result = $api->registration($sendData);

        if ( isset($this->result['result']['errors']) && empty($this->result['result']['errors']) ) {
            ## Auth user
            $_SESSION['ken-property-auth'] = true;
            $_SESSION['ken-user-first-name'] = $sendData['firstName'];
            $_SESSION['ken-user-last-name']  = $sendData['lastName'];
            $_SESSION['ken-user-login']      = $sendData['email'];
        }

        return $this->renderTemplate();
    }

    public function actionLogin()
    {
        $api = new RecloudApi($this->options['apiKey']);

        $sendData = array(
            'email'    => KenRequest::query('login-email'),
            'password' => KenRequest::query('login-password'),
        );

        $this->result = $api->login($sendData);

        if (empty($this->result)) {
            $this->userNotFound = true;
        } else {
            $_SESSION['ken-property-auth'] = true;
            $_SESSION['ken-user-first-name'] = $this->result['first_name'];
            $_SESSION['ken-user-last-name'] = $this->result['last_name'];
            $_SESSION['ken-user-login'] = $sendData['email'];
        }

        return $this->renderTemplate();
    }

    public function actionLogout()
    {
        unset($_SESSION['ken-property-auth']);
        
        return $this->renderTemplate();
    }

    public function actionMortgage()
    {
        $this->period = 12;

        function calculateMortgage($balance,$rate,$term, $period){
            
           $N = $term * $period;
           $I = ($rate/100)/$period;
           $v = pow((1+$I),$N);
           $t = ($I*$v)/($v-1);
           $result = $balance*$t;
           return $result;
        }

        $this->submitted = false;
        $this->balance =  KenRequest::query('balance');
        $this->rate    =  KenRequest::query('rate');
        $this->term    =  KenRequest::query('term');
        $this->downpayment    =  (float)KenRequest::query('downpayment');
        $this->balanceDownpayment = $this->balance - $this->downpayment;

        if ( !empty($this->balance) && !empty($this->rate) && !empty($this->term) ) {
            $this->submitted = true;

            $this->pay = round(calculateMortgage($this->balanceDownpayment, $this->rate, $this->term, $this->period), 2);
        }

        return $this->renderTemplate();
    }

    public function actionViewOnMap()
    {
        return $this->renderTemplate();
    }

    public function action404()
    {
         return $this->renderTemplate();
    }

    /* Ajax requests */

    public function actionAjaxViewNeighborhoods()
    {
        $data = file_get_contents(KEN_CACHE_DIR . 'neighborhoods.json');
        return $data;
    }


    public function actionAjaxSearch()
    {
        $page = KenRequest::query('rcpage', 1);
        $limit = KenRequest::query('limit', 10);

        $searchData = KenRequest::query('propertySearch');

        if (isset($searchData['limit']) && (int)$searchData['limit'] != 0) {
            $limit = $searchData['limit'];
        }

        if ($this->options['dataSource'] == 'api') {
            $query = $this->getRequestParams($searchData);
        } else {
            $query = $this->generateSearchQuery($searchData);
        }

        $this->property = KenProperty::getInstance()->getData()->getSearchData($page, $limit, $query['where'], $query['orderBy']);
        $this->pages = intval(ceil($this->property['count'] / $limit));
        
        $this->pagination = $this->generatePagination($page, $this->pages, array('class'=>'map-pagination'));

        return $this->renderTemplate();
    }

    public function actionAjaxViewProperty()
    {
        $propertyId = KenRequest::query('propertyId');

        if ($propertyId == 0) {
            return $this->renderTemplate('404');
        }

        $this->property = KenProperty::getInstance()->getData()->getPropertyDetails($propertyId);

        if ( empty($this->property) ) {
            return $this->renderTemplate('404');
        }

        return $this->renderTemplate();
    }

    public function actionAjaxViewNeighborhoodProperty()
    {
        $searchData = KenRequest::query('propertySearch');

        $params = array(
            'disableCity' => true,
            'disableMls'  => true,
            'disableZip'  => true
        );

        $query = $this->generateSearchQuery($searchData, $params);

        $neighborhoodId = KenRequest::query('neighborhoodId');

        $query['where'][] = array('field' => 'gis_neighborhood_id', 'condition' => '=', 'data' => $neighborhoodId);

        $property = KenProperty::getInstance()->getData()->getNeighborhoodPropertyPosition($neighborhoodId, $query['where']);

        $out = array();

        if (is_array($property)) {
            
            foreach ($property as $value) {
                $out[] = array(
                    'id'    => $value['LIST_NO'],
                    'lat'   => $value['lat'],
                    'lon'   => $value['lon'],
                    'type'  => getFullPropertyType(strtolower($value['PROP_TYPE'])),
                    'beds'        => $value['bedroom_count'],
                    'bath'        => $value['full_bath_count'],
                    'area'        => $value['total_area'],
                    'price'       => number_format($value['price']),
                    'street'      => $value['address1'],
                );
            }

        } else {
            return '';
        }

        return json_encode($out);
    }

    private function renderTemplate($template = '', $data=array())
    {
        $dir = KEN_VIEW_DIR . 'site' . DIRECTORY_SEPARATOR;

        if ( empty($template) ) {
            $file =  $dir . $this->_action . '.php';
        } else {
            $file =  $dir . $template . '.php';
        }

        if (!file_exists($file)) {
            $file = $dir . '404' . '.php';
        }

        ob_start ();
		include_once $file;
		$output = ob_get_contents ();
		ob_end_clean ();

        return $output;
    }


    private function getRequestParams($searchData)
    {
        $params = array();

        $params['order_by']         = $searchData['orderby'];
        $params['type']             = $searchData['type'];
        $params['open']             = $searchData['open'];
        $params['status']           = $searchData['status'];

        $params['neighborhood_id']  = $searchData['neighbourhood-id'];
        $params['zip_code']         = $searchData['zip-code'];
        $params['town_name']        = $searchData['city'];
        $params['list_no']          = $searchData['mls'];
        $params['area_name']        = $searchData['neighbourhood'];

        $params['address']          = $searchData['address'];
        $params['street_number']    = $searchData['street_number'];
        $params['street_name']      = $searchData['street_name'];

        $params['list_price_min']   = $searchData['price-from'];
        $params['list_price_max']   = $searchData['price-to'];

        $params['beds_min']         = $searchData['beds-from'];
        $params['beds_max']         = $searchData['beds-to'];

        $params['baths_min']        = $searchData['bath-from'];
        $params['baths_max']        = $searchData['bath-to'];

        $params['total_area_min']   = $searchData['sqft-from'];
        $params['total_area_max']   = $searchData['sqft-to'];

        $params['year_min']         = $searchData['year-from'];
        $params['year_max']         = $searchData['year-to'];

        //$params['list_date_min']    = $searchData[''];
        //$params['list_date_max']    = $searchData[''];

        if (empty($params['type'])) {
            $params['type'] = array('CC', 'SF' , 'MF', 'LD');
        }

        if (empty($params['order_by'])) {
            $params['order_by'] = 'price-high';
        }

        if (empty($params['status'])) {
            $params['status'] = array('ACT', 'NEW', 'PCG', 'EXT', 'BOM', 'RAC');
        }

        return array('where' => $params, 'orderBy' => $params['order_by']);
    }

    private function generateSearchQuery( $searchData )
    {
        $orderBy = array();

        if (!isset($searchData['orderby'])) $searchData['orderby'] = '';

        switch ($searchData['orderby']) {
            case 'price-high':
                $orderBy['field'] = 'list_price';
                $orderBy['type'] = 'DESC';
            break;

            case 'price-low':
                $orderBy['field'] = 'list_price';
                $orderBy['type'] = 'ASC';
            break;

            case 'new':
                $orderBy['field'] = 'update_date';
                $orderBy['type'] = 'DESC';
            break;

            default:
                $orderBy['field'] = 'list_price';
                $orderBy['type'] = 'DESC';
        }

        $searchTypes = isset($searchData['type']) ? $searchData['type'] : array();
        $existsTypes = array();

        foreach ($searchTypes as $value) {
            $existsTypes[] = $value;
        }

        if (empty($existsTypes)) {
            $existsTypes = array('CC', 'SF' , 'MF', 'LD');
        }

        $city = array();
        
        if  ( !isset($searchData['open']) && (!isset($searchData['city']) || empty($searchData['city'])) ) {
            $city = array($this->options['defaultCity']);
        }

        $zip = array();
        $mls = array();
        $neighbourhood = array();
        $areas = array();
        $status = array('ACT', 'NEW', 'PCG', 'EXT', 'BOM', 'RAC');

        if (isset($searchData['neighbourhood-id']) && !empty($searchData['neighbourhood-id'])) {
            $params['disableCity'] = true;
            $params['disableMls']  = true;
            $params['disableZip']  = true;

            $neighbourhood = explode(',', $searchData['neighbourhood-id']);
        }

        if ( isset($searchData['zip-code']) && !empty($searchData['zip-code']) ) {
            $temp = explode(',', $searchData['zip-code']);

            foreach ($temp as $value) {
                if (empty($value)) continue;
                 $zip[] = trim($value);
            }
        }

        if (!empty($zip)) {
            $params['disableCity'] = true;
            $params['disableMls']  = true;
        }

        if ( isset($searchData['city']) && !empty($searchData['city']) ) {
            $city = array();
            $temp = explode(',', $searchData['city']);

            foreach ($temp as $value) {
                if (empty($value)) continue;
                $city[] = trim($value);
            }
        }

        if ( isset($searchData['mls']) && !empty($searchData['mls']) ) {
            $temp = explode(',', $searchData['mls']);

            foreach ($temp as $value) {
                if (empty($value)) continue;

                $mls[] = trim($value);
            }
        }

        if ( isset($searchData['status']) && !empty($searchData['status']) ) {
            $temp = explode(',', $searchData['status']);

            $searchData['status'] = array();

            foreach ($temp as $value) {
                if (empty($value)) continue;

                $searchData['status'][] = trim($value);
            }
        }

        if ( isset($searchData['neighbourhood']) && !empty($searchData['neighbourhood']) ) {
            $temp = explode(',', $searchData['neighbourhood']);

            foreach ($temp as $value) {
                if (empty($value)) continue;

                $areas[] = trim($value);
            }

            $params['disableCity'] = true;
            $params['disableMls']  = true;
            $params['disableZip']  = true;
        }

        if (isset($params['disableCity'])) {
            $city = array();
        }

        if (isset($params['disableMls'])) {
            $mls = array();
        }

        if (isset($params['disableZip'])) {
            $zip = array();
        }

        ## Find only new records
        if  (isset($searchData['status']) && $searchData['status'] == 'NEW') {
            $status = array('NEW');
        }

        if (!empty($mls)) {

            ## if is set mls number do not use other filters
            $where = array(
                array('field' => 'f.list_no', 'condition' => 'IN', 'data' => $mls),
                array('field' => 'status', 'condition' => 'IN', 'data' => $status),
            );

            return array(
                'where'     => $where,
                'orderBy'   => $orderBy
            );
        }

        $where = array(
            array('field' => 'gis_neighborhood_id', 'condition' => 'IN', 'data' => $neighbourhood),
            array('field' => 'status', 'condition' => 'IN', 'data' => $status),

            array('field' => 'f.list_no', 'condition' => 'IN', 'data' => $mls),

            array('field' => 'town_name', 'condition' => 'IN', 'data' =>  $city),
            array('field' => 'postal_code', 'condition' => 'IN', 'data' => $zip),
            array('field' => 'address', 'condition' => '>', 'data' => $searchData['address']),
            array('field' => 'list_price', 'condition' => '>', 'data' => $searchData['price-from']),
            array('field' => 'list_price', 'condition' => '<', 'data' => $searchData['price-to']),
            array('field' => 'bedroom_count', 'condition' => '>', 'data' => $searchData['beds-from']),
            array('field' => 'bedroom_count', 'condition' => '<', 'data' => $searchData['beds-to']),
            array('field' => 'full_bath_count', 'condition' => '>', 'data' =>  $searchData['bath-from']),
            array('field' => 'full_bath_count', 'condition' => '<', 'data' => $searchData['bath-to']),
            array('field' => 'tax_year', 'condition' => '>', 'data' => $searchData['year-from']),
            array('field' => 'tax_year', 'condition' => '<', 'data' => $searchData['year-to']),
            array('field' => 'total_area', 'condition' => '>', 'data' => $searchData['sqft-from']),
            array('field' => 'total_area', 'condition' => '<', 'data' => $searchData['sqft-to']),
            array('field' => 'prop_type', 'condition' => 'IN', 'data' => $existsTypes),
        );

        if (!empty($areas)) {
             $where[] = array('field' => 'NEIGHBORHD', 'condition' => 'IN', 'data' =>  $areas);
        }

        if  (isset($searchData['open']) && $searchData['open'] == 'on') {
            $where[] = array('field' => 'Start_Date', 'condition' => '>', 'data' => date('Y-m-d 00:00:00'));
        }

        return array(
            'where'     => $where,
            'orderBy'   => $orderBy
        );
    }

    private function generatePagination($page, $pagesCount, $params=array() )
    {
        $output='';

        if (!isset($params['class'])) {
            $params['class'] = 'li-pages clearfix wp-paginate pagination';
        }

        if ($pagesCount > 1) {

            $range  = 2;
            $anchor = 1;
            $gap    = 1;

            $output  .= '<ol class="' . $params['class'] . '">';
            $ellipsis = '<li><span class="gap">...</span></li>';

            $min_links = $range * 2 + 1;
            $block_min = min($page - $range, $pagesCount - $min_links);
            $block_high = max($page + $range, $min_links);
            $left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
            $right_gap = (($block_high + $anchor + $gap) < $pagesCount) ? true : false;

            $tempData = isset($params['urlData']) ? $params['urlData'] : array();

            if (isset($params['urlPath'])) {
                $tempData['rcpage'] = $page-1;
                $prevlink = $params['urlPath'] . '?' . http_build_query($tempData);
                $tempData['rcpage'] = $page+1;
			    $nextlink = $params['urlPath'] . '?' . http_build_query($tempData);
            } else {
            	$urlData  = isset($params['urlData']) ? $params['urlData'] : array();
                $linkData = generateSearchString($urlData, true);
                $linkData = array_merge($urlData, $linkData);
            	
                $linkData['rcpage'] = $page-1;
                $prevlink = KenPropertyRouting::generateUrl($linkData);
                $linkData['rcpage'] = $page+1;
			    $nextlink = KenPropertyRouting::generateUrl($linkData);
            }
            if ($page > 1 ) {
				$output .= sprintf('<li><a href="%s" class="prev">Prev</a></li>', $prevlink);
			}

            function paginateLoop($start, $max, $page = 0, $params=array()) {

                $output = "";

                $urlData  = isset($params['urlData']) ? $params['urlData'] : array();
                $linkData = generateSearchString($urlData, true);

                $linkData = array_merge($urlData, $linkData);

                for ($i = $start; $i <= $max; $i++) {

                    $linkData['rcpage'] = $i;

                    if (isset($params['urlPath'])) {
                        $p = $params['urlPath'] . '?' . http_build_query($linkData);
                    } else {
                        $p = KenPropertyRouting::generateUrl($linkData);
                    }
                    $output .= ($page == intval($i))
                        ? "<li><span class='page current'>$i</span></li>"
                        : "<li><a href='$p' data-page='" . $i . "' title='$i' class='page'>$i</a></li>";
                }
                return $output;
		    }

            if ($left_gap && !$right_gap) {
                $output .= sprintf('%s%s%s',
                    paginateLoop(1, $anchor, 0, $params),
                    $ellipsis,
                    paginateLoop($block_min, $pagesCount, $page, $params)
                );
            }
            else if ($left_gap && $right_gap) {
                $output .= sprintf('%s%s%s%s%s',
                    paginateLoop(1, $anchor, 0, $params),
                    $ellipsis,
                    paginateLoop($block_min, $block_high, $page, $params),
                    $ellipsis,
                    paginateLoop(($pagesCount - $anchor + 1), $pagesCount, 0, $params)
                );
            }
            else if ($right_gap && !$left_gap) {
                $output .= sprintf('%s%s%s',
                    paginateLoop(1, $block_high, $page, $params),
                    $ellipsis,
                    paginateLoop(($pagesCount - $anchor + 1), $pagesCount, 0, $params)
                );
            }
            else {
                $output .= paginateLoop(1, $pagesCount, $page, $params);
            }

            if ($page < $pagesCount) {
				$output .= sprintf('<li><a href="%s" class="next">Next</a></li>', $nextlink);
			}

            $output .= "</ol>";
        }

        return $output;
    }

}
