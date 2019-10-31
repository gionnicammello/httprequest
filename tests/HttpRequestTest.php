<?php


use gionnicammello\Httprequest\HttpRequest;
use PHPUnit\Framework\TestCase;


class testHttpRequest extends TestCase
{
    public $httpRequest;
    public function setUp()
    {
        $_SESSION=array();
        $_GET=array('variabile'=>'abc');
        $_POST=array();
        $_SERVER=Array
        (
            'HTTP_HOST'=>'81.81.81.81',
            'REQUEST_URI'=>'/test_httprequest.php?prova2=3',
            'REQUEST_METHOD'=>'GET',
        );
        $this->httpRequest=$this->createHttpRequest();

    }


    protected function createHttpRequest()
    {
        return \gionnicammello\Httprequest\HttpRequest::create();
    }



    public function testSetUpIsWorking()
    {
        $this->assertEquals($_GET['variabile'],'abc');
        $this->assertNotEquals($_GET['variabile'],'abcd');
    }

    public function testHttpRequestisHttpRequestClass()
    {
        $this->assertInstanceOf(HttpRequest::class,$this->httpRequest);
    }

    public function testHttpRequestSingleton()
    {
        $httpRequest2=\gionnicammello\Httprequest\HttpRequest::create($_SERVER,$_GET,$_POST,$_SESSION);
        $this->assertEquals($this->httpRequest,$httpRequest2);
    }


    public function testGetReturnSingleKey()
    {
        $testvar=['variabile'=>'abc'];
        $this->assertEquals($testvar['variabile'],$this->httpRequest->get('variabile'));


    }


    public function testGetReturnEntireSet()
    {
        $testvar=['variabile'=>'abc'];
        $this->assertEquals($testvar,$this->httpRequest->get());
    }


    public function testCanSetFlash()
    {
        $var='flash';
        $this->httpRequest->addFlash($var);
        $this->assertEquals($this->httpRequest->flash(),$var);
    }

    public function testFlashIsKeepForTheNextRequest()
    {
        $this->assertEquals($this->httpRequest->flash(),'flash');
    }



    public function testCanRetrieveFromGet()
    {
        $variabile=$this->httpRequest->retrieve('get','variabile');
        $this->assertEquals($variabile,'abc');
    }
    public function testCanSetSessionVariable()
    {
        $_SESSION['variabile']='123';
        $this->assertEquals('123',$this->httpRequest->session('variabile'));
    }

    public function testsFlashIsStoredInSession()
    {
        $var='flash';
        $this->httpRequest->addFlash($var);
        $this->assertEquals($this->httpRequest->session(HttpRequest::FLASH_NAME),'flash');
    }


    public function testCanRetrieveRoute()
    {
        $this->assertEquals($this->httpRequest->getRoute(),'test_httprequest.php');
    }


    public function testCanRetrieveRequestMethod()
    {
        $this->assertEquals($this->httpRequest->getMethod(),'GET');
    }

    public function testCanRetrieveRequestBaseUrl()
    {
        $this->assertEquals($this->httpRequest->getBaseUrl(),'http://81.81.81.81');
    }

    public function testCanRetrieveRequestUrl()
    {
        $this->assertEquals($this->httpRequest->getUrl(),'http://81.81.81.81/test_httprequest.php?prova2=3');
    }
}