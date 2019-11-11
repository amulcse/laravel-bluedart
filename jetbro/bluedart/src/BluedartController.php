<?php

namespace jetbro\bluedart;
use App\Http\Controllers\Controller;
use jetbro\bluedart\DebugSoapClient;
use Request;
use jetbro\bluedart\Bluedartlog;
use SoapHeader;

class BluedartController extends Controller
{
    public $pickupRequest = [];
    
    public function checkPincode($pincode=0){
            $soap = new DebugSoapClient('https://netconnect.bluedart.com/Ver1.7/ShippingAPI/Finder/ServiceFinderQuery.svc?wsdl',
                    array(
                    'trace' 							=> 1,  
                    'style'								=> SOAP_DOCUMENT,
                    'use'									=> SOAP_LITERAL,
                    'soap_version' 				=> SOAP_1_2
                    ));
                    $soap->__setLocation('https://netconnect.bluedart.com/Ver1.7/ShippingAPI/Finder/ServiceFinderQuery.svc');
                    
                    $soap->sendRequest = true;
                    $soap->printRequest = false;
                    $soap->formatXML = true;
                    
                    $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true);
                    $soap->__setSoapHeaders($actionHeader);

                $params = array('pinCode' => $pincode, 'profile' => array('Api_type' => 'S', 'LicenceKey'=> env('BLUEDART_LICENCE_KEY'), 'LoginID'=> env('BLUEDART_LOGIN_ID'), 'Version'=>'1.3') );
                
                #var_dump($params);
                #echo '<h2>Parameters</h2><pre>'; print_r($params); echo '</pre>';
                // Here I call my external function
                $result = $soap->__soapCall('GetServicesforPincode',array($params));
                #echo "<br>";
                // var_dump($result);

                $log = new Bluedartlog;
                $log->request = json_encode($params);
                $log->response = json_encode($result->GetServicesforPincodeResult);
                $log->errormesssage = $result->GetServicesforPincodeResult->ErrorMessage;
                $log->save();

                $response = [];
                $response['errorcode'] = $result->GetServicesforPincodeResult->IsError ;
                $response['message'] = $result->GetServicesforPincodeResult->PincodeDescription ;
                
                
                /* for debug */
                // echo '<pre>';
                // print_r(print_r($result));exit;
    }

    public function createPickup(){

    }

}
