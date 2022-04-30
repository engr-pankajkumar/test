<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Contracts\Repository;
use App\Helpers\TableHelper;
use Illuminate\Support\Facades\DB;


class CompanyRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\Company';
    }

    public function getDataforSolr() 
    {
        $companyTable = TableHelper::COMPANY;
        $sectorTable = TableHelper::SECTOR;
        $industryTable = TableHelper::INDUSTRY;

        $dataQuery      = $this->makeModel(); 

        
	    $dataQuery->select(
	    	$companyTable . '*',
	    	$sectorTable . '.sector_name',
	    	$industryTable . '.industry_name',
	    );  

        $dataQuery->join($userMasterTable, function($join) use ($userTrxTable, $userMasterTable)  {
            $join->on($userTrxTable . '.UserId', '=', $userMasterTable . '.UserId' );
        });

        
        $dataQuery->where($userTrxTable.'.ClientId', $client);

        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        
        $dateColumn = $userTrxTable.'.CreatedOn';

        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period);

        $dataQuery->groupBy($userTrxTable.'.UserId');

        	
        $dataQuery->orderBy('viewDownload', 'DESC')->limit(10);
        
        return  $dataQuery->get();
    }





}
