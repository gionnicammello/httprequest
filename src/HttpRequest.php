<?php


namespace gionnicammello\Httprequest;


/**
 * Class HttpRequest
 * An helper class to obtain and process global variables
 * It add the addFLash() utility to pass a value to the next page.
 * Flash variable are keept alive only for 1 more request.
 * Class follow singleton pattern to be instantiate only 1 time per request
 * @package gionnicammello\Httprequest
 */
class HttpRequest
{
    const FLASH_NAME='ABC_GcApplicationFlash_XYZ'; //this will be uset as the key to store flash data in SESSION


    protected $get=null; //will reference to the array of GET global variable
    protected $post=null; //will reference to the array of POST global variable
    protected $session=null; //will reference to the array of SESSION global variable
    protected $server=null; //will reference to the array of SERVER global variable

    protected $flash=null; // flash property to send a variable to the next request

    protected static $instance=null; //singleton instance



    /**
     * HttpRequest constructor.
     * constructor is protected because singleton pattern
     * use HttpRequest::create() to instantiate
     * Global variable are passed by reference
     */
    protected function __construct()
    {
        //session_start();
        if ( !defined('STDIN') ){ //Start session if not CLI for testing purpose with PHPunit
            if (session_status() == PHP_SESSION_NONE) { //Starting session if not already started
                session_start();
            }
        }
        $this->get=&$_GET;
        $this->post=&$_POST;
        $this->session=&$_SESSION;
        $this->server=&$_SERVER;
        $this->flash=isset($this->session[SELF::FLASH_NAME])?$this->session[SELF::FLASH_NAME]:null;
        $this->clearFlash(); //remove flash from session
    }






    /**
     * Instantiate HttpRequest object
     * Global variable are passed by reference
     * @return HttpRequest
     */
    public static function create()
    {
        if(SELF::$instance===null){
            SELF::$instance=new SELF();
        }
        return SELF::$instance;
    }




    /**
     * retrieve data from global variable referenced in class properties.
     * return null without sistem warning/notice if the property[key] doen't exist.
     * If $key argument is not passed this method will return the entire property
     * @param $property
     * @param null $key
     * @return mixed|null
     */
    public function retrieve($property, $key=null)
    {
        if($key===null){
            return $this->{$property};
        }
        return isset($this->{$property}[$key])?$this->{$property}[$key]:null;
    }




    /**
     * retrieve data from global variable referenced in class "$get" property.
     * If $key argument is not passed this method will return the entire property
     * @param null $key
     * @return mixed|null
     */
    public function get($key=null)
    {
        return $this->retrieve('get',$key);
    }




    /**
     * retrieve data from global variable referenced in class "$post" property.
     * If $key argument is not passed this method will return the entire property
     * @param null $key
     * @return mixed|null
     */
    public function post($key=null)
    {
        return $this->retrieve('post',$key);
    }




    /**
     * retrieve data from global variable referenced in class "$session" property.
     * If $key argument is not passed this method will return the entire property
     * @param null $key
     * @return mixed|null
     */
    public function session($key=null)
    {
        return $this->retrieve('session',$key);
    }







    /**
     * retrieve data from flash property.
     * If $key argument is not passed this method will return the entire property
     * @param null $key
     * @return mixed|null
     */
    public function flash($key=null)
    {
        return $this->retrieve('flash',$key);
    }





    /**
     * Add a value to be passed to the next request
     * @param $value
     */
    public function addFlash($value)
    {
        $this->session[SELF::FLASH_NAME]=$value;
        $this->flash=$this->session[SELF::FLASH_NAME];
    }





    /**
     * Return the entire url called from the client
     * @return string
     */
    public function getUrl()
    {
        return (isset($this->server['HTTPS']) && $this->server['HTTPS'] === 'on' ? "https" : "http").'://'.$this->server['HTTP_HOST'].$this->server['REQUEST_URI'];
    }





    /**
     * Return the base url called from the client
     * @return string
     */
    public function getBaseUrl()
    {
        return (isset($this->server['HTTPS']) && $this->server['HTTPS'] === 'on' ? "https" : "http").'://'.$this->server['HTTP_HOST'];
    }





    /**
     * Return the page/route called from the clent
     * @return string
     */
    public function getRoute()
    {
        return ltrim(strtok($this->server['REQUEST_URI'], '?'), '/');
    }





    /**
     * Return the method used to make the client request
     * @return string
     */
    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }


    /**
     * clear flash data stored in session
     * Unset the Flash on the SESSION global variable
     */
    public function clearFlash()
    {
        unset($this->session[SELF::FLASH_NAME]);
    }





    /**
     * Unset SESSION global variable
     */
    protected function clearSession()
    {
        unset($this->session);
    }




}