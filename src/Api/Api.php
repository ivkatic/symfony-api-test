<?php

namespace App\Api;

use App\Api\Cache;

/**
 * Main API class
 * 
 * @since 1.0.0
 * @package API
 */
class Api extends Cache {

    /**
     * Array that holds all services that can be used (Github, Twitter, ...)
     *
     * @var array
     * @since 1.0.0
     */
    private $services;

    /**
     * Sets Github as default services, used fi service is not defined in methods
     *
     * @var string
     * @since 1.0.0
     */
    private $default_serv;

    /**
     * Holds the server response code
     * Depends on Client headers
     *
     * @var int
     * @since 1.0.0
     */
    public $request_check;

    public function __construct() {
        parent::__construct();
        $this->init_services();
    }

    /**
     * GET data from service api
     * 
     * @todo Add caching in here?
     *
     * @param string $params
     * @param string $route
     * @param string $service
     * @return object $obj service reponse
     * 
     * @since 1.0.0 
     */
    public function get($params, $route, $service = '') {
        if('' !== $service) {
            $service = $this->services[$service];
        } else if('' === $service) {
            $service = $this->default_serv;
        }

        $url = $service['uri'].$route.$params;
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP',
                    $service['auth']
                ]
            ]
        ];

        $json = file_get_contents($url, false, stream_context_create($opts));
        $obj = json_decode($json);
        return $obj;
    }

    /**
     * Checks Client Request headers and sets Reponse Code
     *
     * @return void
     * @since 1.0.0
     */
    protected function checkRequest() {
        $requestHeaders = getallheaders();
 
        if(!isset($requestHeaders['Content-Type'])) {
            $this->request_check = 400;
        } else if( isset($requestHeaders['Content-Type']) && $requestHeaders['Content-Type'] !== 'application/vnd.api+json' ) {  
            $this->request_check = 415;
        } else if(isset($requestHeaders['Accept']) && $requestHeaders['Accept'] !== 'application/vnd.api+json') {
            $this->request_check = 406;
        } else {
            $this->request_check = 200;
        }
    }
 
    /**
     * Initializes services array
     * Add new service simply by adding it as array
     * 'uri' and 'auth' are required
     *
     * @return void
     * @since 1.0.0
     */
    private function init_services() {
        $this->services = [
            'github' => [
                'uri' => 'https://api.github.com',
                'auth' => 'Authorization: '.$_ENV['GITHUB_TOKEN'],
            ]
        ];

        $this->default_serv = $this->services['github'];
    }
}