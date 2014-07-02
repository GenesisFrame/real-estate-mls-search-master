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

 
class RecloudApi {

    private $_apiPropertyAddress = 'http://api.recloud.me/1.0/';
    
    private $_apiAddress = 'http://beta.recloud.me/api/lead_gateway/';
    private $_apiAuthAddress = 'http://beta.recloud.me/api/auth_gateway/';
    
    private $_responseType = 'json';
    private $_apiKey = '';
    private $_defaultData = array(
        'client_id'     => 7,
		'created_date'  => 0,
	    'office_id'     => 46
    );

    static private $_curlIsEnable = false;

    static protected $_curlOpts   = array(
		CURLOPT_FAILONERROR 	=> 1,
		CURLOPT_RETURNTRANSFER	=> 1,
		CURLOPT_TIMEOUT         => 10,
		CURLOPT_POST			=> 0,
		CURLOPT_SSL_VERIFYPEER  => false
	);

    public function __construct($apiKey)
    {
        ##Check for CURL
        if (function_exists('curl_init')) {
            if (is_callable('curl_exec')) {
                self::$_curlIsEnable = true;
            }
        }

        $this->_apiKey = $apiKey;
    }


    public function requestShowing($data)
    {
        $sendData = array(
            'first_name'    => isset($data['firstName']) && !empty($data['firstName']) ? $data['firstName'] : '',
            'last_name'     => isset($data['lastName']) && !empty($data['lastName']) ? $data['lastName'] : '',
            'email_address' => isset($data['email']) && !empty($data['email']) ? $data['email'] : '',
            'primary_phone' => isset($data['phone']) && !empty($data['phone']) ? $data['phone'] : '',
            'internal_notes' => isset($data['message']) && !empty($data['message']) ? $data['message'] : '',
        );

        return $this->request($sendData);
    }

    public function contactUs($data)
    {
        $sendData = array(
            'first_name'    => isset($data['firstName']) && !empty($data['firstName']) ? $data['firstName'] : '',
            'last_name'     => isset($data['lastName']) && !empty($data['lastName']) ? $data['lastName'] : '',
            'email_address' => isset($data['email']) && !empty($data['email']) ? $data['email'] : '',
            'internal_notes' => isset($data['message']) && !empty($data['message']) ? $data['message'] : '',
        );

        return $this->request($sendData);
    }

    public function registration($data)
    {
        $sendData = array(
            'first_name'     => isset($data['firstName']) && !empty($data['firstName']) ? $data['firstName'] : '',
            'last_name'      => isset($data['lastName']) && !empty($data['lastName']) ? $data['lastName'] : '',
            'email_address'  => isset($data['email']) && !empty($data['email']) ? $data['email'] : '',
            'primary_phone'  => isset($data['phone']) && !empty($data['phone']) ? $data['phone'] : '',
            'password'       => isset($data['password']) && !empty($data['password']) ? md5($data['password']) : '',
        );

        return $this->request($sendData);
    }

    public function login($data)
    {
        $sendData = array(
            'email'     => isset($data['email']) && !empty($data['email']) ? $data['email'] : '',
            'pass'      => isset($data['password']) && !empty($data['password']) ? md5($data['password']) : '',
            'URL'       => $this->_apiAuthAddress,
        );

        return $this->request($sendData, 'post', true);
    }

    public function propertyGetDetails($location='sf', $id)
    {
        $params=array();
        $params['URL']      = $this->_apiPropertyAddress . $location . '/' . 'Property.getDetails' . '/' . $id;
        $params['api_key']  = $this->_apiKey;

        return $this->request($params, 'get', true);
    }

    public function propertySearch($location='sf', $params)
    {
        $params['URL']      = $this->_apiPropertyAddress . $location . '/' . 'Property.search';
        $params['api_key']  = $this->_apiKey;
        
        return $this->request($params, 'get', true);
    }

    public function checkApiKey( $location )
    {
        $params['URL']      = $this->_apiPropertyAddress . 'check' . '/' . $location . '/' . $this->_apiKey . '/';
        //$params['api_key']  = $this->_apiKey;

        return $this->request($params, 'get', true);
    }

    ##### System Functions #####

    /**
     * Execute HTTP query
     *
     * @param array $params
     * @param string $method
     */
    protected function request( $params, $method='post', $noMerge = false )
    {
        $this->_defaultData['created_date'] = time();

        $execParams = array(
            'api_key'      => $this->_apiKey,
            'return_type'  => $this->_responseType,
            'auth_type'    => 1,
            'ip_address'   => $_SERVER['REMOTE_ADDR'],
            'lead_source_id' => 2,
            'refer_url'    => $_SERVER['HTTP_REFERER'],
            'submit_page'  => $_SERVER['HTTP_REFERER'],
        );

        if (!$noMerge) {
            $params = array_merge($params, $execParams, $this->_defaultData);
        }

        if (self::$_curlIsEnable) {
            $return = $this->curlRequest( $params, $method );
        } else {
            $return = $this->httpRequest( $params, $method );
        }

        if (!$return) return false;

        return $this->parseResponse( $return );
    }

    /**
     * Parse request
     *
     * @param string $response
     * @return mixed DOMDocument or array
     */
    private function parseResponse ( $response )
    {
         if ($this->_responseType == 'json') {
             return json_decode( $response, true );
         } elseif ( $this->_responseType == 'xml' ) {
             $dom = new DOMDocument('1.0', 'utf-8');
             $dom->preserveWhiteSpace = false;
             $dom->loadXML($response);

             return $dom;
         } else {
             return $response;
         }
    }

    /**
     * Execute CURL request
     *
     * @param array $params Query params
     * @param string $method Request method
     *
     * @return string Curl Response
     */
    private function curlRequest( $params, $method='post' )
    {
        $ch = curl_init();

        if (!$ch)  {
            throw new Exception('Cannot init curl', 1);
        }

         if ( isset($params['URL']) ) {
            $url = $params['URL'];
            unset($params['URL']);
        } else {
            $url = $this->_apiAddress;
        }

        curl_setopt_array($ch, self::$_curlOpts);
        $sendData = http_build_query($params) ;

        if (strtolower($method)  == 'get') {
            $url .= '?' . $sendData;
        } else {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        }

        //echo $url;
        //exit;

        curl_setopt($ch, CURLOPT_URL, $url);

		$result = curl_exec( $ch );

		$err     = curl_errno( $ch );
    	$errmsg  = curl_error( $ch );

    	if ( $err != 0 ) {
    		throw new Exception('Curl error: ' . $err . '-' . $errmsg, 2);
    	}

    	curl_close( $ch );

    	return $result;
    }

    /**
     * Execute HTTP request
     *
     * @param array $params Query params
     * @param string $method Request method
     *
     * @return string Response
     */
    private function httpRequest($params, $method = 'get')
    {
        if (isset($params['URL'])) {
            $url = $params['URL'];
            unset($params['URL']);
        } else {
            $url = $this->_apiAddress;
        }

        $sendData = http_build_query($params);

        if (strtolower($method) == 'get') {
            $request = $url . '?' . $sendData;
            return file_get_contents($request);
        } else {
            return $this->postRequest($url, $sendData);
        }
    }

    /**
     * Execute POST request
     *
     * @param $url
     * @param $postData
     *
     * @return string Response
     */
    private function postRequest( $url, $postData )
    {
        $context =
        array('http' =>
        array('method' => 'POST',
                    'user_agent' => self::$_curlOpts[CURLOPT_USERAGENT],
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                                'Content-Length: ' . strlen($postData),
                    'content' => $postData));
        $contextId = stream_context_create($context);
        $sock = fopen($url, 'r', false, $contextId);

        if ($sock) {
            throw new Exception('Cannot open socket connection', 3);
        }

        $result = '';
        if ($sock) {
            while (!feof($sock)) {
                $result .= fgets($sock);
            }
            fclose($sock);
        }

        return $result;
    }

}
