<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EmailHelper
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
    public static function sendRequestToApi($url, $method = 'GET', array $data = [], array $headers = [], bool $isJsonInput = true, bool $isJsonResponse = true)
    {
        $output = null;
        $apiInputParams = [];

        try {
            if ($isJsonInput) {
                $apiInputParams['body'] = json_encode($data);
            } else {
                $apiInputParams['form_params'] = $data;
            }

            $apiInputParams['headers'] = $headers;
            $apiInputParams['connect_timeout'] = 0;
            // dd($apiInputParams);
            $client = new Client();
            $response = $client->request($method, $url, $apiInputParams);
            $responseStatus = $response->getStatusCode();
            
            if ($responseStatus == Response::HTTP_OK) {
                $output = $response->getBody();
                $output = ($isJsonResponse ? json_decode($output, true) : $output);
            }
        } catch (GuzzleException $e) { 
            Log::error('Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
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
    public static function callEmailService(array $mailInfo, $contents)
    {
        $method = 'POST';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $appEnv = config('constant.global.system_app_env');
        if($appEnv != 'production')
        {
            $toEmail = config('constant.global.local_email_recipient');
        }
        else
        {
            $toEmail = $mailInfo['ToMail'];
        }
        
        $bccEmail = config('constant.email.bcc_mail_account');
        $mailData = [
            'FromMail'    => config('constant.email.tsc_mail_from'),
            'Provider'    => config('constant.email.tsc_mail_provider'),
            'ToMail'      => $toEmail,
            'CC'          => $mailInfo['CC'] ?? null,
            'BCC'         => $mailInfo['BCC'] ?? $bccEmail,
            'Subject'     => $mailInfo['Subject'],
            'MailMessage' => $contents,
            'FilePath'    => $mailInfo['FilePath'] ?? null,
        ];
       
        $url = config('constant.email.tsc_mail_url');
        $mailSent =  self::sendRequestToApi($url, $method, $mailData, $headers, false);
        return $mailSent;
    }

    /**
     * Replace email template placeholders with values and return email body
     *
     * @param string $emailBody
     * @param array $emailPlaceHolders
     * @return string
     */
    public static function generateEmailBody($emailBody, array $emailPlaceHolders) {
        $emailPlaceHolders["%LOGO%"]  = asset('/images/tsc-logo.png');
        return strtr($emailBody, $emailPlaceHolders);
    }
}
