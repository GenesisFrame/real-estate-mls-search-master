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

 
class KenPropertyAdmin {

    static private $_instance = null;

    private $_action = '';
    private $_executeFunction = '';

    private function __construct()
    {
        ## Load necessary files
        include_once KEN_LIB_DIR . 'KenRequest.php';
    }

    /**
     * @return KenPropertyAdmin
     */
    static public function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new KenPropertyAdmin();
        }

        return self::$_instance;
    }


    public function execute()
    {
        $page = KenRequest::query('page');

        if ( empty($page) ) {
            return;
        }

        $this->_action = $page;
        $this->_executeFunction = 'action' . ucfirst($page);

        if (method_exists( $this, $this->_executeFunction) ) {
        } else {
            $this->_executeFunction = 'action404';
        }

        return call_user_func( array(&$this, $this->_executeFunction), array() );
    }

    /**
     * @return string
     */
    public function actionKenDescription()
    {
        $output = $this->renderTemplate();
		return $output;
    }

    public function actionKenDocumentation()
    {
        $output = $this->renderTemplate();
		return $output;
    }

    /**
     * @return string
     */
    public function actionKenSettings()
    {
        $this->options = KenProperty::getInstance()->getOptions();

        if (isset($_POST['wp_property_save'])) {
            
            $this->options['apiKey']            = KenRequest::query('apiKey');
            $this->options['googleMapKey']      = KenRequest::query('googleMapKey');
            $this->options['mlsLogin']          = KenRequest::query('mlsLogin');
            $this->options['mlsPassword']       = KenRequest::query('mlsPassword');
            $this->options['defaultState']      = KenRequest::query('defaultState');
            $this->options['defaultCity']       = KenRequest::query('defaultCity');
            $this->options['defaultArea']       = KenRequest::query('defaultArea');

            $this->options['dataSource']        = 'api';

            $this->options['pageProperty'] = KenRequest::query('pageProperty');
            reInsertPagePropertyShortcodes(KenRequest::query('pageProperty'));

            $this->options = KenProperty::getInstance()->setOptions($this->options);
        }

        $api = new RecloudApi($this->options['apiKey']);

        $this->checkKey = $api->checkApiKey( $this->options['defaultState'] );
        $this->errorCode = $this->checkKey['error']['error_no'];
        
        $output = $this->renderTemplate();

		return $output;
    }

    public function action404()
    {
        
    }

    private function renderTemplate($template = '')
    {
        $dir = KEN_VIEW_DIR . 'admin' . DIRECTORY_SEPARATOR;
        
        if ( empty($template) ) {
            $file =  $dir . $this->_action . '.php';
        } else {
            $file =  $dir . $template . '.php';
        }

        ob_start ();
		include_once $file;
		$output = ob_get_contents ();
		ob_end_clean ();

        return $output;
    }

}
