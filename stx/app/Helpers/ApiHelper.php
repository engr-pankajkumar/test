<?php
namespace App\Helpers;

use Illuminate\Http\Response;
use Facades\App\Modules\Category\Src\Repositories\ReportRepository as Reports;
use Facades\App\Modules\Category\Src\Repositories\ReportTypeRepository as ReportType;
use Illuminate\Support\Facades\Auth;


class ApiHelper
{
    /**
     * Valid token flag
     *
     * @var string
     */
    const VALID_TOKEN_FLAG = 'valid';

    /**
     * Invalid token flag
     *
     * @var string
     */
    const INVALID_TOKEN_FLAG = 'invalid';

     /**
     * call smart sourcing api to get reports
     *
     * @return mixed|\ArrayAccess[]|array[]|\ArrayAccess|array|Closure
     */


    public static function checkSystemPriviledge($data = [])
    {
        $userId = $data['userId'] ?? Auth::id();
        $apiInputData = [
            'systemAssetId' => [
                config('constant.global.system_asset_id')
            ],
            'userId' => [
                $userId
            ],
            'isActive' => ApiConstants::IS_ACTIVE_FLAG
        ];

        $headers = [
            'Token' => ((isset($data['Token']) && !empty($data['Token'])) ? $data['Token'] : Auth::User()->Token),
            'userId' => $userId,
            'systemId' => config('constant.global.system_asset_id'),
        ];

        // $headers['Token'] = ((isset($data['Token']) && !empty($data['Token'])) ? $data['Token'] : Auth::User()->Token);
        // $headers['userid'] = $userId;
        // $headers['systemId'] = config('constant.global.system_asset_id');

        $apiResponse = ApiService::callService(ApiConstants::AUTHORIZATION_SERVICE_REQUEST, $apiInputData, $headers);
        // dd($apiResponse);
        $privilegeId = config('constant.global.privilege_id');
        $systemAssetId = config('constant.global.system_asset_id');
        $adminPrivilegeId = config('constant.global.admin_privilege_id');

        if (isset($apiResponse['result']) && is_array($apiResponse['result'])) {
            $filtered['access'] = collect($apiResponse['result'])->filter(function ($row, $key) use($systemAssetId, $privilegeId)  {
                return ($row['systemAssetId'] == $systemAssetId && $row['privilegeId'] == $privilegeId );
            });

            $filtered['admin'] = collect($apiResponse['result'])->filter(function ($row, $key) use($systemAssetId,$adminPrivilegeId)  {
                return ($row['systemAssetId'] == $systemAssetId && $row['privilegeId'] == $adminPrivilegeId );
            });

            return $filtered;
            // if(count($filtered)) {
            //     return true;
            // }
            // return false;
        }
    }

}
