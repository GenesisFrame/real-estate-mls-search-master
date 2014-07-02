<?php
/*
Plugin Name: MLS Search Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5.1
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/

// Stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Set the wp-content and plugin urls/paths
 */
if (!defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (!defined('WP_PLUGINS_URL'))
    define('WP_PLUGINS_URL', WP_CONTENT_URL . '/plugins');


define('KEN_PLUGIN_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('KEN_PLUGIN_NAME', plugin_basename(__FILE__));
define('KEN_PLUGIN_URL', trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))));
define('KEN_CLASS_DIR', KEN_PLUGIN_DIR . 'class' . DIRECTORY_SEPARATOR);
define('KEN_LIB_DIR', KEN_PLUGIN_DIR . 'lib' . DIRECTORY_SEPARATOR);
define('KEN_VIEW_DIR', KEN_PLUGIN_DIR . 'view' . DIRECTORY_SEPARATOR);
define('KEN_CACHE_DIR', KEN_PLUGIN_DIR . 'cache' . DIRECTORY_SEPARATOR);

ini_set('display_errors', false);
error_reporting(E_ERROR);

if (!class_exists('KenProperty')) {
    class KenProperty
    {

        /**
         * @var KenProperty
         */
        static private $_instance = null;

        private $_dataObject = null;
        
        private $_optionsName = 'wp-real-property-options';
        
        private $_options = array(
            'dataSource'        => 'api',
            'apiKey'            => 'demo-api-key',
            'pageProperty'      => 'property', ## Page name slug for display search list // Feature: set in settings
            'pagePropertyMap'   => 'property-map', ## Page name slug for display map // Feature: set in settings
            'defaultCity'       => '',
            'defaultArea'       => '',
            'defaultState'      => 'demo'
        );

        static $_keywords = '';

        /**
         * @var KenPropertyRouting
         */
        private $_routing = null;

        /**
         * Get KenProperty object
         * 
         * @return KenProperty
         */
        static public function getInstance()
        {
            if (self::$_instance == null) {
                self::$_instance = new KenProperty();
            }

            return self::$_instance;
        }

        private function __construct()
        {
            register_activation_hook(KEN_PLUGIN_NAME, array(&$this, 'pluginActivate'));
            register_deactivation_hook(KEN_PLUGIN_NAME, array(&$this, 'pluginDeactivate'));
            register_uninstall_hook(KEN_PLUGIN_NAME, array('KenProperty', 'pluginUninstall'));

            ## Register plugin widgets
            add_action('widgets_init', array(&$this, 'widgetsRegistration'));

            add_action( 'wp_ajax_kenproperty', array(&$this, 'initAjaxSitePart' ) );
            add_action( 'wp_ajax_nopriv_kenproperty', array(&$this, 'initAjaxSitePart' ) );

            add_action('init', 'session_start' );
            add_action('wp_logout', 'session_destroy');

            include_once KEN_LIB_DIR   . 'helpers.php';

            ## Load options
            $this->getOptions();

            if (is_admin()) {
                add_action('wp_print_scripts', array(&$this, 'adminLoadScripts'));
                add_action('wp_print_styles', array(&$this, 'adminLoadStyles'));
                add_action('admin_menu', array(&$this, 'adminGenerateMenu'));

            } else {
                add_action('wp_print_scripts', array(&$this, 'siteLoadScripts'));
                add_action('wp_print_styles', array(&$this, 'siteLoadStyles'));

                $this->initSitePart();
            }

        }

        /**
         * Get plugin options
         * 
         * @return array
         */
        public function getOptions()
        {
 			if (!$options = get_option($this->_optionsName)) {
				update_option($this->_optionsName, $this->_options);
                 
                return true;
			}

            $needUpdate = false;

            foreach ($this->_options as $key => $value) {

                if (!array_key_exists($key, $options)) {
                    $needUpdate = true;
                    $options[$key] = $value;
                }

            }

            if  ($needUpdate) {
                update_option($this->_optionsName, $options);
            }
            
			$this->_options = $options;

            return $this->_options;
        }

        /**
         * Set plugin options
         *
         * @return array
         */
        public function setOptions( $options )
        {
            $this->_options = $options;
            update_option($this->_optionsName, $this->_options);

            return $this->_options;
        }

        /**
         * Get routing class
         *
         * @return KenPropertyRouting
         */
        public function getRouting()
        {
            return $this->_routing;
        }

        /**
         * @return KenPropertyModel
         */
        public function getData()
        {
            if ($this->_dataObject == null) {
                $this->_dataObject = new KenPropertyModel( $this->_options );
            }

            return $this->_dataObject;
        }

        /**
         * Load admin panel
         */
        public function initAdminPart()
        {
            include_once KEN_CLASS_DIR . 'KenPropertyAdmin.php';
            include_once KEN_LIB_DIR . 'RecloudApi.php';

            $content = KenPropertyAdmin::getInstance()->execute();

            echo $content;
        }

        /**
         * Load site
         */
        public function initSitePart()
        {
            include_once KEN_LIB_DIR   . 'KenRequest.php';
            include_once KEN_CLASS_DIR . 'KenPropertyRouting.php';
            include_once KEN_CLASS_DIR . 'KenPropertySite.php';
            include_once KEN_CLASS_DIR . 'KenPropertyModel.php';

            include_once KEN_LIB_DIR . 'RecloudApi.php';

            $this->_routing    = new KenPropertyRouting();

            add_action("pre_get_posts", array(&$this, "PreActivate"));

            ## Register shortcodes
            add_shortcode( 'recloud-property', array(&$this, 'showKenProperty' ) );
            add_shortcode( 'recloud-property-map', array(&$this, 'showKenPropertyMap' ) );
            
            add_shortcode( 'recloud-search', array(&$this, 'showKenPropertyView' ) );
            add_shortcode( 'recloud-forsale', array(&$this, 'showKenPropertyView' ) );
        }

        public function rewriteTitle($title, $sep, $seplocation)
        {
            if (isset($this->propertyDetails) && !empty($this->propertyDetails)) {
                $titleArray = array();

                $titleArray[] = $this->propertyDetails['address1'];
                $titleArray[] = $this->propertyDetails['address2'];

                $titleArray = implode(',', $titleArray);

                $title = $titleArray . ' ' . $sep . ' ';
            }

            return $title;
        }

        public function generateHeader()
        {
            if (isset($this->propertyDetails) && !empty($this->propertyDetails)) {
                $keywords = array();
                $description = array();

                $keywords[] = $this->propertyDetails['address1'];
                $keywords[] = $this->propertyDetails['address2'];

                $description[] = $this->propertyDetails['address1'];
                $description[] = $this->propertyDetails['address2'];

                ///Title tag
                echo '<meta name="description" content="'. implode(',', $description) .' Property Details" />' . PHP_EOL;
                echo '<meta name="keywords" content="'. implode(',', $keywords) .' Property Details" />';
            }
        }

        public function showKenProperty()
        {
            $content = KenPropertySite::getInstance()->execute();
            return $content;
        }

        public function showKenPropertyMap()
        {
            $content = KenPropertySite::getInstance()->execute();
            return $content;
        }

        public function showKenPropertyView($atts, $content=null)
        {
            $inputContent = $content;

            $out = KenPropertySite::getInstance()->actionView($atts);

            // Assign content
            $contentOut = $inputContent . $out;

            return $contentOut;
        }

        public function initAjaxSitePart()
        {
            include_once KEN_LIB_DIR   . 'KenRequest.php';
            include_once KEN_CLASS_DIR . 'KenPropertyRouting.php';
            include_once KEN_CLASS_DIR . 'KenPropertySite.php';
            include_once KEN_CLASS_DIR . 'KenPropertyModel.php';
            include_once KEN_LIB_DIR   . 'helpers.php';
            
            $content = KenPropertySite::getInstance()->execute(true);
            echo $content;
            exit;
        }

        /**
         * Hack function. Frontcontroller for plugin
         *
         * @param  $query
         * @return bool
         */
        public function PreActivate ($query)
        {
            global $wp_query, $wp;

            if (!is_array($wp_query->query) || !is_array($query->query) || isset($wp_query->query["suppress_filters"]) || isset($query->query["suppress_filters"])) {
                return;
		    }

            if ( !isset($wp_query->query['propertyAction']) ){
                return true;
            }

            switch($wp_query->query['propertyAction']) {
                case 'map':
                    $pageName = $this->_options['pagePropertyMap'];
                    $name = $this->_options['pagePropertyMap'];
                break;

                case 'shortcode':
                    $pageName = get_query_var('pagename');
                    $name     = get_query_var('pagename');
                break;

                default:
                    $pageName = $this->_options['pageProperty'];
                    $name = $this->_options['pageProperty'];
            }

            ## Crazy hack for SEO title, keywords and description
            if (get_query_var('propertyPart') == 'viewProperty' && get_query_var('propertyId') != '') {
                $this->propertyDetails = KenProperty::getInstance()->getData()->getPropertyDetails(get_query_var('propertyId'));
            }

            add_filter('wp_title', array($this, 'rewriteTitle'), 10, 3);
            add_action('wp_head', array($this, 'generateHeader'));

            $query->query_vars["pagename"] = $pageName;

            $query->set('name', $name);
            $query->set('pagename', $pageName);
            $query->set('post_type', 'page');
            $query->set('nopaging', true);

            $query->found_posts = 1;
            $query->is_home = false;
            $query->is_page = true;
            $query->is_singular = true;
            
            return $query;
        }

        /**
         *
         * @param  $query
         * @return string
         */
        public function ClearQuery ($query)
        {
            global $wp_query;

            if(!is_array($wp_query->query) || !isset($wp_query->query["propertyAction"]))
                return $query;

            return "";
        }

        /**
         * Not use yet
         *
         * @param  $posts
         * @return array
         */
        public function Activate ($posts)
        {
            global $wp_query;

            /*$out = array();

            if ( !isset($wp_query->query['propertyAction']) ){
                return true;
            }

            foreach ($posts as &$post) {

                if ($post->post_name == $this->_options['pageProperty'] && $post->post_status == 'publish') {
                    $out[] = $post;
                }
            }

           return $out;*/

           return $posts;

            /*$posts = array((object)array(
                "ID"				=> 1,
                "comment_count"		=> 0,
                "comment_status"	=> "closed",
                "ping_status"		=> "closed",
                "post_author"		=> 1,
                "post_content"		=> '',
                "post_date"			=> date("c"),
                "post_date_gmt"		=> gmdate("c"),
                "post_excerpt"		=> '',
                "post_name"			=> "property",
                "post_parent"		=> 0,
                "post_status"		=> "publish",
                "post_title"		=> '',
                "post_type"			=> "page"
            ));*/

			return $posts;
        }

        ##
        ## Widgets initializations
        ##

        public function widgetsRegistration()
        {
            include_once KEN_CLASS_DIR . 'WidgetContactUs.php';
            include_once KEN_CLASS_DIR . 'WidgetSearchForm.php';
            include_once KEN_CLASS_DIR . 'WidgetAgent.php';

            register_widget('WidgetContactUs');
            register_widget('WidgetSearchForm');
            register_widget('WidgetAgent');
        }


        ##
        ## Loading Scripts and Styles
        ##

        public function adminLoadStyles()
        {
        }

        public function adminLoadScripts()
        {
        }

        public function adminGenerateMenu()
        {
            add_menu_page('Welcome to Real Estate Cloud', 'RE Cloud', 'manage_options', 'kenDescription', array(&$this, 'initAdminPart'));
            add_submenu_page('kenDescription', 'Real Estate Cloud Wordpress Plugin Settings', 'Settings', 'manage_options', 'kenSettings', array(&$this, 'initAdminPart'));
            add_submenu_page('kenDescription', 'Documentation', 'Documentation', 'manage_options', 'kenDocumentation', array(&$this, 'initAdminPart'));
        }

        public function siteLoadScripts()
        {
            wp_enqueue_script('jquery');

            wp_register_script('addthis', 'http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e5c04fe2e052ec1' );
            wp_enqueue_script('addthis');

            wp_register_script('googleMap', 'http://maps.google.com/maps/api/js?sensor=false' );
            wp_enqueue_script('googleMap');

            wp_register_script('kenProperty', KEN_PLUGIN_URL . 'js/ken-property.js', array(), '1.0' );
            wp_enqueue_script('kenProperty');

            wp_register_script('kenPropertyMap', KEN_PLUGIN_URL . 'js/map.js', array(), '1.0' );
            wp_enqueue_script('kenPropertyMap');
        }

        public function siteLoadStyles()
        {
            wp_register_style('kenProperty', KEN_PLUGIN_URL . 'css/ken-property.css', array(), '1.0' );
            wp_enqueue_style('kenProperty');
        }


        
        ##
        ## Plugin Activation and Deactivation
        ##

        /**
         * Activate plugin
         * @return void
         */
        public function pluginActivate()
        {
        }

        /**
         * Deactivate plugin
         * @return void
         */
        public function pluginDeactivate()
        {
        }

        /**
         * Uninstall plugin
         * @return void
         */
        static public function pluginUninstall()
        {
        }

    }
}

//instantiate the class
if (class_exists('KenProperty')) {
    KenProperty::getInstance();
}
