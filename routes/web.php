<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('totvs-soap-wsEdu', function () {
    $client = new \Zend\Soap\Client('http://177.184.8.118/TOTVSBusinessConnect/wsEdu.asmx?wsdl');
    print_r($client->getOptions());
    print_r($client->getFunctions());
    print_r($client->getTypes());
});

Route::get('totvs-soap-wsDataServer', function () {
    $client = new \Zend\Soap\Client('http://177.184.8.118/TOTVSBusinessConnect/wsDataServer.asmx?wsdl');
    print_r($client->getOptions());
    print_r($client->getFunctions());
    print_r($client->getTypes());
});

Route::get('totvs-soap-wsConsultaSQL', function () {
    $client = new \Zend\Soap\Client('http://177.184.8.118/TOTVSBusinessConnect/wsConsultaSQL.asmx?wsdl');
    print_r($client->getOptions());
    print_r($client->getFunctions());
    print_r($client->getTypes());
});

Route::get('totvs-soap-wsProcess', function () {
    $client = new \Zend\Soap\Client('http://177.184.8.118/TOTVSBusinessConnect/wsProcess.asmx?wsdl');
    print_r($client->getOptions());
    print_r($client->getFunctions());
    print_r($client->getTypes());
});

Route::get('totvs-soap-wsDataServer2', function () {
    $client = new \Zend\Soap\Client('http://177.184.8.118:8051/wsDataServer/MEX?wsdl');
    print_r($client->getOptions());
    print_r($client->getFunctions());
    print_r($client->getTypes());
});
