<?php
namespace App\Helpers;

class ApiConstants
{

    /**
     * IS ACTIVE flag
     *
     * @var integer
     */
    const IS_ACTIVE_FLAG = 1;

    /**
     * IS INACTIVE flag
     *
     * @var integer
     */
    const IS_INACTIVE_FLAG = 0;

    /**
     * cdi api source
     *
     * @var string
     */
    const SOURCE_CDI = 'cdi';

    /**
     * python api source
     *
     * @var string
     */
    const SOURCE_PYTHON = 'python';

    /**
     * amplifi api source
     *
     * @var string
     */
    const SOURCE_AMPLIFI = 'amplifi';


    /**
     * ecovadis api source
     *
     * @var string
     */
    const SOURCE_ECOVADIS = 'ecovadis';

    /**
     * smartsourcing api source
     *
     * @var string
     */
    const SOURCE_SMARTSOURCING = 'smart_sourcing';

    /**
     * smartsourcing api source
     *
     * @var string
     */
    const SOURCE_COUPA = 'coupa';

   /**
     * Label for login service request
     *
     * @var string
     */
    const LOGIN_SERVICE_REQUEST = 'login';

/**
     * Label for signup service request
     *
     * @var string
     */
    const SIGNUP_SERVICE_REQUEST = 'signup';
    const SIGNUP_VERIFY_MAIL = 'signup_verify';


    /**
     * Label for token-for-system service request
     *
     * @var string
     */
    const TOKEN_APPROVED_FOR_SYSTEM_REQUEST = 'token-for-system';


    const SOURCING_LOGIN_AUTH_SOURCE = 'sourcing_login_auth_source';

    const SOURCING_LOGIN_AUTH_REQUEST = 'sourcing_login_auth_request';

    const ASSET_LOGIN_AUTH_SOURCE  = 'asset_login_auth_source';

    const ASSET_LOGIN_AUTH_REQUEST = 'asset_login_auth_request';
    /**
     * Label for forgot password service request
     *
     * @var string
     */
    const FORGOTPASSWORD_SERVICE_REQUEST = 'forgot-password';
    /**
     * Label for authorization service request
     *
     * @var string
     */
    const AUTHORIZATION_SERVICE_REQUEST = 'authorization';
    /**
     * Label for user info service request
     *
     * @var string
     */
    const USER_INFO_SERVICE_REQUEST = 'userinfo';
    /**
     * Label for user logout service request
     *
     * @var string
     */
    const USER_LOGOUT_REQUEST = 'user-logout';
    /**
     * Label for is valid token service request
     *
     * @var string
     */
    const TOKEN_VALID_REQUEST = 'is-token-valid';
    /**
     * Label for get client users service request
     *
     * @var string
     */
    const GET_CLIENT_USERS = 'client-user';
    /**
     * Label for change password service request for client users
     *
     * @var string
     */
    const CHANGE_CLIENT_USER_PASSWORD = 'change_client_user_password';

    const CHANGE_CLIENT_USER_PASSWORD_DEFAULT = 'change_client_user_password';

    const GET_CHANGE_PASSWORD = 'get-change-password';

    const GET_CHANGE_PASSWORD_DEFAULT = 'get-change-password';

     /**
     * Label for auto login service request to get token
     *
     * @var string
     */
    const AUTO_LOGIN_SERVICE_REQUEST = 'auto-login';

    const GET_DPP_SMARTSOURCING_CATEGORY = 'get_dpp_smartsourcing_category';


    const EMAIL_VALIDATION            = 'email_validation';

    const GET_FORGOT_PASSWORD         = 'get-forgot-password';

    const SAVE_FORGOT_PASSWORD        = 'save-forgot-password';

    const GET_SMARTSOURCING_REPORTS   = 'get-smart-sourcing-reports';

    const GET_CLIENT_CREDENTIALS   = 'get_client_credenatials';
    const GET_ASSET_ECOVADIS_DATA   = 'get_asset_ecovadis_data';

    const SEND_ECOVADIS_REQUEST   = 'send_ecovadis_request';
    const GET_ECOVADIS_TOKEN      = 'get_ecovadis_token';

    
    const CC_LIMIT = 100;

    const CC_CHUNK_SIZE = 100;

    const ASSET_ECOVADIS_LIMIT = 100;
    const ASSET_ECOVADIS_CHUNK_SIZE = 100;

    


    // Defination of these constant define in CostStructureUtil and CostDriverUtil
    const TYPE_CS = 'cs';
    const TYPE_CS_DATA = 'csdata';
    const CS_LIMIT = 2000;
    const MAX_CS_LIMIT = 2000;
    const CS_DATA_FREQUENCY=36;

    const TYPE_CD = 'cd';
    const TYPE_CD_DATA = 'cddata';
    const CD_LIMIT = 20000;
    const MAX_CD_LIMIT = 2000;
    const CD_DATA_FREQUENCY=40;


    const CS_CD_CHUNK_SIZE = 1000;
    const SOURCING_CHUNK_SIZE = 1000;

    const TYPE_CP = 'cp';
    const TYPE_CP_DATA = 'cpdata';
    const CP_LIMIT = 2000;
    const MAX_CP_LIMIT=2000;
    const CP_DATA_FREQUENCY=40;

    const SOURCE_NEWS = 'news';
    const GET_NEWS   = 'get_news';

    const USER_ACTIVITY_LOGGER = 'Tracker';
    const USER_TRACKER = 'InsertUserTracker';

    /**
     * smartsourcing api source
     *
     * @var string
     */
    const SOURCE_SOLUTION = 'solution';
    const GET_AMPLIFI_SOLUTIONS       = 'get_amplifi_solutions';
    /**
     * Label for Client List request
     *
     * @var string
     */
    const CLIENT_LISTING_SERVICE_REQUEST = 'clientlisting';

     /**
     * Label for Client users request
     *
     * @var string
     */
    const CLIENT_USERS_SERVICE_REQUEST = 'clientusers';

    /**
     * Label for CLient CL ROle
     *
     * @var string
     */
    const CLIENT_CL_LISTING = 'clientcllisting';

    /**
     * Label for CLient CL ROle
     *
     * @var string
     */
    const REPORT_QUESTIONS = 'report_questions';


    const GET_SUPPLIERS = 'suppliers';



    public static function apiEndpoints($requestType, $source = null)
    {
        if(!$source) {
            $source = self::SOURCE_CDI;
        }
        $endpoints = [
                self::SOURCE_CDI =>  [
                self::LOGIN_SERVICE_REQUEST                 => 'AuthManager/AuthUserInSystem',
                self::SIGNUP_SERVICE_REQUEST                => 'signup/user-signup',
                self::SIGNUP_VERIFY_MAIL                => 'signup/verify-mail',

                self::TOKEN_APPROVED_FOR_SYSTEM_REQUEST     => 'TokenManager/IsTokenApprovedForSystem',
                self::AUTHORIZATION_SERVICE_REQUEST         => 'AuthorizationManager/GetAuthorization',
                self::USER_INFO_SERVICE_REQUEST             => 'UserManager/GetUsersInfo',
                self::USER_LOGOUT_REQUEST                   => 'AuthManager/UserLogout',
                self::TOKEN_VALID_REQUEST                   => 'TokenManager/IsTokenApprovedForSystem',
                self::FORGOTPASSWORD_SERVICE_REQUEST        => 'AuthManager/ForgetPassword',
                self::GET_CLIENT_USERS                      => 'UserManager/GetAllUsers',
                self::GET_CHANGE_PASSWORD                   => 'AuthManager/GetChangePassword',
                self::GET_CHANGE_PASSWORD_DEFAULT           => 'AuthManager/GetChangePassword',
                self::CHANGE_CLIENT_USER_PASSWORD_DEFAULT   => 'AuthManager/ChangeUserPassword',
                self::EMAIL_VALIDATION                      => 'AuthManager/EmailValidation',
                self::GET_FORGOT_PASSWORD                   => 'AuthManager/GetForgotPassword',
                self::SAVE_FORGOT_PASSWORD                  => 'AuthManager/SaveForgotPassword',
                self::AUTO_LOGIN_SERVICE_REQUEST            => 'AuthManager/AutoLogin',
                self::CLIENT_LISTING_SERVICE_REQUEST        => 'ClientManager/GetAllClientsDetails',
                self::CLIENT_USERS_SERVICE_REQUEST  => 'UserManager/GetAllUsers',
                self::CLIENT_CL_LISTING => 'ClientManager/GetClientDetails'
            ],
            self::SOURCE_AMPLIFI =>  [
                self::LOGIN_SERVICE_REQUEST         => 'AuthManager/AuthUserInSystem',
            ],
            self::SOURCE_SMARTSOURCING =>  [
                self::GET_SMARTSOURCING_REPORTS     => 'DPPReport/DPPReportMappingByReportId',
            ],

            self::SOURCE_PYTHON =>  [
                self::GET_CLIENT_CREDENTIALS        => 'getClientCredentials',
                self::GET_ASSET_ECOVADIS_DATA       => 'getEvData',
            ],

            
            self::SOURCE_ECOVADIS =>  [
                self::SEND_ECOVADIS_REQUEST     => config('constant.api.ecovedis-api_version') . '/' .'EVRequest',
                self::GET_ECOVADIS_TOKEN        => 'EVToken'
            ],

            
            
            self::SOURCE_SOLUTION =>  [
                self::GET_AMPLIFI_SOLUTIONS        => 'Asset/GetSolutionsByUserId'
            ],
            self::SOURCE_NEWS =>  [
                self::GET_NEWS     => 'news/getNews/',
            ],

            self::USER_ACTIVITY_LOGGER => [
                self::USER_TRACKER    => 'Tracker/InsertUserTracker/',
            ],

            self::SOURCING_LOGIN_AUTH_SOURCE => [
                self::SOURCING_LOGIN_AUTH_REQUEST => 'AuthManager/AuthUserInSystem',
            ],
            self::ASSET_LOGIN_AUTH_SOURCE => [
                self::ASSET_LOGIN_AUTH_REQUEST => 'AuthManager/AuthUserInSystem',
            ],
            self::SOURCE_COUPA =>  [
                self::GET_SUPPLIERS     => 'suppliers',
            ],
        ];

        return $endpoints[$source][$requestType];
    }



}
