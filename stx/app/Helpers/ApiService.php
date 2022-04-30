<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Config;
use Carbon\Carbon;
use Crypt;
use App\Helpers\ApiConstants;
use App\Helpers\EmailHelper;

class ApiService
{
    /**
     * Send request to API
     *
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @param boolean $isJsonInput
     * @return NULL
     */

    public static function sendRequestToApi($url, array $data = [], array $headers = [], bool $isJsonInput = true, bool $isJsonResponse = true, bool $isQueryString = false, $method = 'POST')
    {
        $output = null;
        $apiInputParams = [];

        /* to handle the ecovadis response */
        $isEcovadisCall = false;
        $ecovadisEndpoints = ['EVToken', 'EVRequest'];

        foreach ($ecovadisEndpoints as $endpoint) {
            if(strpos($url,$endpoint)) {
                $isEcovadisCall = true;
                break;
            }
        }

        // dd($isEcovadisCall);

        try {
            if ($isJsonInput) {
                $apiInputParams['body'] = json_encode($data);
            } else if($isQueryString){
                $apiInputParams['query'] = $data;
            } else {
                $apiInputParams['form_params'] = $data;
            }

            // dd($apiInputParams);
            $apiInputParams['headers'] = $headers;
            $apiInputParams['connect_timeout'] = 0;

            /* to handle the ecovadis response */
            if($isEcovadisCall) {
                $apiInputParams['http_errors'] = false;
            }

            $client = new Client();
            $response = $client->request($method, $url, $apiInputParams);

            $logExcludeURL = [];
            $logExcludeURL[] = config('constant.api.url').'/AuthManager/AuthUserInSystem';
            $logExcludeURL[] = config('constant.api.url').'/AuthManager/ChangeUserPassword';

            if(!in_array($url, $logExcludeURL))
            {
                Log::info("Method: ".$method." | URL: ".$url." | Params: ".print_r($apiInputParams, 1));
            }
            

            /* to handle the ecovadis response */
            if($isEcovadisCall) {
                Log::channel('ex_api_hits')->info("Method: ".$method." | URL: ".$url." | Params: ".print_r($apiInputParams, 1));
                return $response;
            }
            //Log::info("Method: ".$method."URL: ".$url."Params: ".print_r($apiInputParams, 1));
            $responseStatus = $response->getStatusCode();

            if ($responseStatus == Response::HTTP_OK) {
                $output = $response->getBody();
                $output = ($isJsonResponse ? json_decode($output, true) : $output);
            }
        } catch (GuzzleException $e) {

            $logExcludeURL = [];
            $logExcludeURL[] = config('constant.api.url').'/AuthManager/AuthUserInSystem';
            $logExcludeURL[] = config('constant.api.url').'/AuthManager/ChangeUserPassword';

            if(!in_array($url, $logExcludeURL))
            {
                Log::info("Method: ".$method." | URL: ".$url." | Params: ".print_r($apiInputParams, 1));
            }

            Log::error('Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
            
            self::sentFailedNotification($url);

            throw $e;

        } catch (\Exception $e) {
            
            $logExcludeURL = [];
            $logExcludeURL[] = config('constant.api.url').'/AuthManager/AuthUserInSystem';
            $logExcludeURL[] = config('constant.api.url').'/AuthManager/ChangeUserPassword';

            if(!in_array($url, $logExcludeURL))
            {
                Log::info("Method: ".$method." | URL: ".$url." | Params: ".print_r($apiInputParams, 1));
            }

            Log::error('Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
            throw $e;
        }    
        
        return $output;
    }


    /**
     * Configures the data for API according to requestType .
     *
     * @param string $data
     * @param array $requestType
     *
     * @return Json/bool
     *
     */
    public static function callService(
        $requestType,
        array $inputData = [],
        array $headers = [],
        bool $isJsonInput = true,
        bool $isJsonResponse = true,
        $source = null,
        bool $isQueryString = false,
        $method = 'POST'
    )

    {

        $filteredData = [];

        $headers['Content-Type'] = 'application/json';

        
        switch ($requestType) {
            case ApiConstants::LOGIN_SERVICE_REQUEST:
                // $endpoint = config('constant.api.endpoint.login');
                break;

            case ApiConstants::SIGNUP_SERVICE_REQUEST:
                // $endpoint = config('constant.api.endpoint.login');
                break;

            case ApiConstants::SIGNUP_VERIFY_MAIL:
                // $endpoint = config('constant.api.endpoint.login');
                break;
                
            case ApiConstants::TOKEN_APPROVED_FOR_SYSTEM_REQUEST:
                // $endpoint = config('constant.api.endpoint.login');
                break;

            case ApiConstants::AUTHORIZATION_SERVICE_REQUEST:
                // $endpoint = config('constant.api.endpoint.authorization');

                break;
            case ApiConstants::USER_LOGOUT_REQUEST:
                // $endpoint = config('constant.api.endpoint.user-logout');
                break;

            case ApiConstants::TOKEN_VALID_REQUEST:
                // $endpoint = config('constant.api.endpoint.is-token-valid');
                // $inputData = [
                //     'Token' => Auth::User()->Token,
                //     'UserId' => Auth::id()
                // ];

                // $headers['Token'] = Auth::User()->Token;
                // $headers['userid'] = Auth::id();
                break;

            case ApiConstants::AUTO_LOGIN_SERVICE_REQUEST :

                // $endpoint = config('constant.api.endpoint.auto-login');
                $filteredData = [
                    'userId' => $data['userId'],
                    'systemId' => $data['systemId'],
                    'targetSystemId' =>   config('constant.global.system_asset_id'),
                    'token' => $data['Token']
                ];
                // $headers['token'] = $data['Token'];
                break;

            case ApiConstants::GET_SMARTSOURCING_REPORTS :
                break;

            case ApiConstants::SEND_ECOVADIS_REQUEST:
                // $endpoint = config('constant.api.endpoint.user-logout');
                break;
        }

        $url  = self::getUrl($requestType, $source);

        // dd($url);

        $apiResponse = self::sendRequestToApi($url, $inputData, $headers, $isJsonInput, $isJsonResponse, $isQueryString, $method);

        return $apiResponse;
    }


    public static function getUrl($requestType, $source = null )
    {
        //$endpoint = config("constant.api.endpoint.{$requestType}");

        $endpoint = ApiConstants::apiEndpoints($requestType, $source);
        switch ($source) {
            case ApiConstants::SOURCE_SMARTSOURCING:
                $url = (config('constant.api.smartsourcing-url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCE_AMPLIFI:
                $url = (config('constant.api.amplifiapi-url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCE_PYTHON:
                $url = (config('constant.api.python-url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCE_SOLUTION:
                $url = (config('constant.api.solution-url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCE_NEWS:
                $url = (config('constant.api.news_api_url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCING_LOGIN_AUTH_SOURCE:
                $url = (config('constant.api.smartsourcing_auth_url') . '/' . $endpoint);
                break;
            case ApiConstants::ASSET_LOGIN_AUTH_SOURCE:
                $url = (config('constant.api.asset_auth_url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCE_COUPA:
                $url = (config('constant.api.coupa-url') . '/' . $endpoint);
                break;
            case ApiConstants::SOURCE_ECOVADIS:
                $url = (config('constant.api.ecovedis-url') . '/' . $endpoint);
                break;
            default:
                $url = (config('constant.api.url') . '/' . $endpoint);
        }

        return $url;
    }


    /**
     * [sentFailedNotification description]
     * @param  [type] $apiURL [description]
     * @return [type]         [description]
     */
    public static function sentFailedNotification($url)
    {

        $urlInfo = parse_url($url);
        $apiName = $urlInfo['host'] ?? $url; 

        //Send API failed information to tech team
        $toEmail = config('constant.global.asset_failure_email');
        $mailInfo = [
                            'ToMail' => $toEmail,
                            'Subject' => 'SmartRisk - API not available - '.$apiName
                    ];

        $mailData = [];
        $mailData['apiURL']  = $url; 
        $view = \View::make('emails.apifailednotification', compact('mailData'));
        $contents = $view->render();
        $mailResponse = EmailHelper::callEmailService($mailInfo, $contents);
        if($mailResponse['StatusCode'] == 200)
        {
            Log::info('API failed notification has been sent to '.$toEmail);
        }
        else
        {
            Log::info('Unable to sent API failed notification to '.$toEmail);
        }

    }


}
