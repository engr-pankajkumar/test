<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Contracts\Repository;
use App\Helpers\TableHelper;
use Illuminate\Support\Facades\DB;


class ExampleRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\UserTrx';
    }


    public function getViewDownload($client,  $frequency, $year, $period) 
    {
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $userMasterTable    = TableHelper::MST_USER;

        $dataQuery      = $this->makeModel(); 

		$rawCountQuery = "COUNT(". $userTrxTable . ".UserId) as viewDownload";
        
	    $dataQuery->select(
	    	$userTrxTable . '.UserId',
	    	$userMasterTable . '.FirstName',
	    	$userMasterTable . '.LastName',
	        DB::raw($rawCountQuery)
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

    public function topReports($client, $frequency, $year, $period) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $categoryTable  = TableHelper::MST_CATEGORY;

        $mappingTable  = TableHelper::REPORT_CATEGORY_MAPPING;

        $dataQuery      = $this->makeModel(); 

        // AssetTypeId, TitleAssetId, count(TitleAssetId)
        
        $rawCountQuery = "count(". $userTrxTable . ".TitleAssetId) as ViewDownloadCount";
        
        $dataQuery->select(
            $reportMasterTable . '.ReportID',
            $reportMasterTable . '.ReportTitle',
            $reportTypeTable . '.ReportType',
            $categoryTable . '.CategoryName',
            DB::raw($rawCountQuery)
        );  

        $dataQuery->join($reportMasterTable, function($join) use ($userTrxTable, $reportMasterTable)  {
            $join->on($userTrxTable . '.TitleAssetId', '=', $reportMasterTable . '.ReportID' );
        });

        $dataQuery->join($reportTypeTable, function($join) use ($reportTypeTable, $reportMasterTable)  {
            $join->on($reportMasterTable . '.ReportTypeId', '=', $reportTypeTable . '.ReportTypeId' );
        });

        $dataQuery->join($mappingTable, function($join) use ($mappingTable, $reportMasterTable)  {
            $join->on($reportMasterTable . '.ReportId', '=', $mappingTable . '.ReportId' );
        });

        $dataQuery->join($categoryTable, function($join) use ($mappingTable, $categoryTable)  {
            $join->on($mappingTable . '.CategoryId', '=', $categoryTable . '.CategoryId' );
        });

        
        $dataQuery->where($userTrxTable.'.ClientId', $client);

        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);

        $dateColumn = $userTrxTable.'.CreatedOn';

        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period);

        $dataQuery->groupBy($userTrxTable.'.TitleAssetId');
            
        $dataQuery->orderBy('ViewDownloadCount', 'DESC')->limit(10);
        
        return  $dataQuery->get();
    }

    public function getReportAccessed($client, $frequency, $year, $period) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $assetTypeTable  = TableHelper::MST_ASSET_TYPE;

        $userTable  = TableHelper::MST_USER;

        $actionTable  = TableHelper::MST_ACTION_STATUS;

        $cs  = TableHelper::MST_COST_STRUCTURE;

        $cd  = TableHelper::MST_COST_DRIVER;

        $cp  = TableHelper::MST_COMMODITY;

        $dataQuery      = $this->makeModel(); 

        // AssetTypeId, TitleAssetId, count(TitleAssetId)
        
        $rawQuery = "count(". $userTrxTable . ".TitleAssetId) as ViewDownloadCount";

        $rawQuery = "
            CASE
                WHEN ". $assetTypeTable . ".AssetTypeId = " . config('constant.asset_cost_structures') . " THEN (select CSName from " . $cs . " where csId = SUBSTRING_INDEX(". $userTrxTable . ".TitleAssetId, '|', 1) limit 1)
                WHEN ". $assetTypeTable . ".AssetTypeId = " . config('constant.asset_cost_drivers') . " THEN (select CDName from " . $cd . " where CDId = SUBSTRING_INDEX(". $userTrxTable . ".TitleAssetId, '|', 1) limit 1)
                WHEN ". $assetTypeTable . ".AssetTypeId = " . config('constant.asset_commodity') . " THEN (select CommodityName from " . $cp . " where CommodityId = SUBSTRING_INDEX(". $userTrxTable . ".TitleAssetId, '|', 1) limit 1)
                WHEN  ". $assetTypeTable . ".AssetTypeId  IN (" . config('constant.asset_as_reports') . ") THEN (select ReportTitle from " . $reportMasterTable . " where ReportID = ". $userTrxTable . ".TitleAssetId limit 1)
            END
            as AssetTitle,
            CASE
                WHEN ". $assetTypeTable . ".AssetTypeId = " . config('constant.asset_cost_structures') . " THEN SUBSTRING_INDEX(". $userTrxTable . ".TitleAssetId, '|', -1)
                WHEN ". $assetTypeTable . ".AssetTypeId = " . config('constant.asset_cost_drivers') . " THEN SUBSTRING_INDEX(". $userTrxTable . ".TitleAssetId, '|', -1)
                WHEN ". $assetTypeTable . ".AssetTypeId = " . config('constant.asset_commodity') . " THEN SUBSTRING_INDEX(". $userTrxTable . ".TitleAssetId, '|', -1)
                WHEN ". $assetTypeTable . ".AssetTypeId IN (" . config('constant.asset_as_reports') . ") THEN 'N/A'
            END
            as AssetTypeCountry,
            IF(". $userTrxTable . ".ActionStatusId = 1, 1,0) View,
            IF(". $userTrxTable . ".ActionStatusId = 2, 1,0) Download,

            CASE
                WHEN ". $assetTypeTable . ".AssetTypeId IN (" . config('constant.asset_as_reports') . ") THEN (select ReportType from " . $reportTypeTable . " where ReportTypeId IN (select ReportTypeId from " . $reportMasterTable . " where ReportID = ". $userTrxTable . ".TitleAssetId))
            END
            as ReportType";
        
        $dataQuery->select(
            $assetTypeTable . '.AssetType',
            // $userTrxTable . '.TitleAssetId',
            $userTable . '.FirstName',
            $userTable . '.LastName',
            DB::raw($rawQuery),
            $userTrxTable . '.CreatedOn'
        );  

        
        $dataQuery->join($userTable, function($join) use ($userTrxTable, $userTable)  {
            $join->on($userTrxTable . '.UserId', '=', $userTable . '.UserId' );
        });

        $dataQuery->join($assetTypeTable, function($join) use ($userTrxTable, $assetTypeTable)  {
            $join->on($userTrxTable . '.AssetTypeId', '=', $assetTypeTable . '.AssetTypeId' );
        });

        // $dataQuery->join($actionTable, function($join) use ($userTrxTable, $actionTable)  {
        //     $join->on($userTrxTable . '.ActionStatusId', '=', $actionTable . '.ActionStatusId' );
        // });

        $dataQuery->where($userTrxTable.'.ClientId', $client);

        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        // $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);

        $dateColumn = $userTrxTable.'.CreatedOn';

        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period);

        $dataQuery->limit(1000);
                    
        return  $dataQuery->get();
    }


    public function topReportsForContentSummary($frequency, $year, $period, $reportType = null, $forViewDownload = null ) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $categoryTable  = TableHelper::MST_CATEGORY;

        $mappingTable  = TableHelper::REPORT_CATEGORY_MAPPING;

        $clientMasterTable     = TableHelper::MST_CLIENT;

        $dataQuery      = $this->makeModel(); 

        // AssetTypeId, TitleAssetId, count(TitleAssetId)
        
        $rawCountQuery = "count(". $userTrxTable . ".TitleAssetId) as ViewDownloadCount";
        
        $columns = [
            $reportMasterTable . '.ReportID',
            $reportMasterTable . '.ReportTitle',
            DB::raw($rawCountQuery)
        ];

        if($forViewDownload) {
             $dataQuery->select(
                $clientMasterTable . '.ClientName',
                DB::raw($rawCountQuery)
            ); 
         } else {
            $dataQuery->select(
                $reportMasterTable . '.ReportID',
                $reportMasterTable . '.ReportTitle',
                DB::raw($rawCountQuery)
            );  
         }

        $dataQuery->join($reportMasterTable, function($join) use ($userTrxTable, $reportMasterTable)  {
            $join->on($userTrxTable . '.TitleAssetId', '=', $reportMasterTable . '.ReportID' );
        });

        if($forViewDownload) {
            $dataQuery->join($clientMasterTable, function($join) use ($userTrxTable, $clientMasterTable)  {
                $join->on($userTrxTable . '.ClientId', '=', $clientMasterTable . '.ClientId' );
            });
        }



        /*$dataQuery->join($reportTypeTable, function($join) use ($reportTypeTable, $reportMasterTable)  {
            $join->on($reportMasterTable . '.ReportTypeId', '=', $reportTypeTable . '.ReportTypeId' );
        });

        $dataQuery->join($mappingTable, function($join) use ($mappingTable, $reportMasterTable)  {
            $join->on($reportMasterTable . '.ReportId', '=', $mappingTable . '.ReportId' );
        });

        $dataQuery->join($categoryTable, function($join) use ($mappingTable, $categoryTable)  {
            $join->on($mappingTable . '.CategoryId', '=', $categoryTable . '.CategoryId' );
        });*/

        
        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        // $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);
        $dataQuery->whereIn($userTrxTable.'.AssetTypeId', config('constant.asset_reports_notaccessed'));


        if($reportType) {
            if(is_array($reportType)) {
                $dataQuery->whereIn($reportMasterTable.'.ReportTypeId', $reportType);
            } else {
                $dataQuery->where($reportMasterTable.'.ReportTypeId', $reportType);
            }
        }


        $dateColumn = $userTrxTable.'.CreatedOn';

        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period);

        if($forViewDownload) {
            $dataQuery->groupBy($userTrxTable.'.ClientId');
        } else {
            $dataQuery->groupBy($userTrxTable.'.TitleAssetId');
        }
  
        $dataQuery->orderBy('ViewDownloadCount', 'DESC');

        if( ! $forViewDownload) {
            $dataQuery->limit(5);
        }
        
        return  $dataQuery->get();
    }

    public function topCategoriesForContentSummary($frequency, $year, $period, $reportType = null) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $categoryTable  = TableHelper::MST_CATEGORY;

        $mappingTable  = TableHelper::REPORT_CATEGORY_MAPPING;

        $dataQuery      = $this->makeModel(); 

        // AssetTypeId, TitleAssetId, count(TitleAssetId)
        
        $rawCountQuery = "count(". $categoryTable . ".CategoryId) as CategoryCount";
        
        $dataQuery->select(
            // $reportMasterTable . '.ReportID',
            // $reportMasterTable . '.ReportTitle',
            // $reportTypeTable . '.ReportType',
            $categoryTable . '.CategoryName',
            DB::raw($rawCountQuery)
        );  

        $dataQuery->join($reportMasterTable, function($join) use ($userTrxTable, $reportMasterTable)  {
            $join->on($userTrxTable . '.TitleAssetId', '=', $reportMasterTable . '.ReportID' );
        });

        /*$dataQuery->join($reportTypeTable, function($join) use ($reportTypeTable, $reportMasterTable)  {
            $join->on($reportMasterTable . '.ReportTypeId', '=', $reportTypeTable . '.ReportTypeId' );
        });*/

        $dataQuery->join($mappingTable, function($join) use ($mappingTable, $reportMasterTable)  {
            $join->on($reportMasterTable . '.ReportId', '=', $mappingTable . '.ReportId' );
        });

        $dataQuery->join($categoryTable, function($join) use ($mappingTable, $categoryTable)  {
            $join->on($mappingTable . '.CategoryId', '=', $categoryTable . '.CategoryId' );
        });

        
        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);

        if($reportType) {
            if(is_array($reportType)) {
                $dataQuery->whereIn($reportMasterTable.'.ReportTypeId', $reportType);
            } else {
                $dataQuery->where($reportMasterTable.'.ReportTypeId', $reportType);
            }
        }


        $dateColumn = $userTrxTable.'.CreatedOn';

        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period);

        $dataQuery->groupBy($categoryTable.'.CategoryId');
            
        $dataQuery->orderBy('CategoryCount', 'DESC')->limit(5);
        
        return  $dataQuery->get();
    }

    public function reportSummary($frequency, $year, $period, $summaryTypes = null, $onlyQuery = null) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $categoryTable  = TableHelper::MST_CATEGORY;

        $mappingTable  = TableHelper::REPORT_CATEGORY_MAPPING;

        $dataQuery      = $this->makeModel(); 

        $rawCountQuery = "COUNT(". $userTrxTable . ".TitleAssetId) as ViewDownloadCount";

        $dateColumn = $userTrxTable.'.CreatedOn';

        if($frequency == config('constant.frequency_monthly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("MONTH(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("MONTH(". $dateColumn . ")");
        }

        if($frequency == config('constant.frequency_weekly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("WEEK(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("WEEK(". $dateColumn . ")");
        }

        if($frequency == config('constant.frequency_quarterly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("QUARTER(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("QUARTER(". $dateColumn . ")");
        }

        if($frequency == config('constant.frequency_yearly') || $frequency == config('constant.frequency_ytd') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("MONTH(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("MONTH(". $dateColumn . ")");
        }

       
        if($summaryTypes) {
            $typeInfo = explode('-', $summaryTypes);

            if($typeInfo[1] == 'report')  {
                 $dataQuery->join($reportMasterTable, function($join) use ($userTrxTable, $reportMasterTable)  {
                    $join->on($userTrxTable . '.TitleAssetId', '=', $reportMasterTable . '.ReportID' );
                });
                $dataQuery->where($reportMasterTable.'.ReportTypeId', $typeInfo[0]);
            }

            if($typeInfo[1] == 'asset')  {
                $dataQuery->where($userTrxTable.'.AssetTypeId', $typeInfo[0]);
            }
        }
        
        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        // $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);


        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, null);



        $dataQuery->groupBy(DB::raw("YEAR(". $dateColumn . ")"));
        // $dataQuery->groupBy($groupByFreqColumn);
        $dataQuery->groupBy('period');

        if($onlyQuery) {
            return $dataQuery;
        }
        

        // $dataQuery->orderBy('ViewDownloadCount', 'DESC');

        
        return  $dataQuery->get();
    }

    public function categorySummary($frequency, $year, $period, $categoryId = null) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $categoryTable  = TableHelper::MST_CATEGORY;

        $mappingTable  = TableHelper::REPORT_CATEGORY_MAPPING;


        $dataQuery      = $this->makeModel(); 

        // AssetTypeId, TitleAssetId, count(TitleAssetId)
        
        $rawCountQuery = "COUNT(". $mappingTable . ".CategoryId) as ViewDownloadCount";

        $dateColumn = $userTrxTable.'.CreatedOn';

        $limit = null;
        
        if($frequency == config('constant.frequency_monthly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("MONTH(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("MONTH(". $dateColumn . ")");
            $limit = config('constant.max_months_data');
        }

        if($frequency == config('constant.frequency_weekly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("WEEK(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("WEEK(". $dateColumn . ")");

            $limit = config('constant.max_week_data');
        }

        if($frequency == config('constant.frequency_quarterly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("QUARTER(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("QUARTER(". $dateColumn . ")");
        }

        if($frequency == config('constant.frequency_yearly') || $frequency == config('constant.frequency_ytd') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("MONTH(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

            $groupByFreqColumn = DB::raw("MONTH(". $dateColumn . ")");
            $limit = config('constant.max_months_data');
        }


        $dataQuery->join($mappingTable, function($join) use ($userTrxTable, $mappingTable)  {
            $join->on($userTrxTable . '.TitleAssetId', '=', $mappingTable . '.ReportID' );
        });

        
        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);

        if($categoryId) {
            $dataQuery->where($mappingTable.'.CategoryId', $categoryId);
        }


        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, null);


        $dataQuery->groupBy(DB::raw("YEAR(". $dateColumn . ")"));
        // $dataQuery->groupBy(DB::raw("MONTH(". $dateColumn . ")"));
        $dataQuery->groupBy($groupByFreqColumn);

        if($limit) {
            $dataQuery->limit($limit);
        }

        
        
  
        // $dataQuery->orderBy('ViewDownloadCount', 'DESC');

        
        return  $dataQuery->get();
    }

    

    private function queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period)
    {
        $whereYear = "YEAR(". $dateColumn .")";
        $whereWeek = "WEEK(". $dateColumn . ", 1)";
        $whereMonth = "MONTH(". $dateColumn . ")";
        $whereQuarter = "QUARTER(". $dateColumn . ")";
        $whereYTD = "DATE(". $dateColumn . ")";

        $minMonthInterval = config('constant.min_months_data');
        $minWeekInterval = config('constant.min_week_data');
        $minQuarterInterval = config('constant.min_quarter_data');

        switch ($frequency) {
            case config('constant.frequency_ytd'):
                $dataQuery->where(DB::raw($whereYTD), '>=', config('constant.ytd_date'));
                break;

            case config('constant.frequency_weekly'):
                if($period) {
                    $dataQuery->where(DB::raw($whereYear), $year);
                    $dataQuery->where(DB::raw($whereWeek), $period);
                } else {
                    $dataQuery->whereRaw($dateColumn .' > CURDATE() - INTERVAL '. $minWeekInterval .' WEEK' );
                }
                break;

            case config('constant.frequency_monthly'):
                if($period) {
                    $dataQuery->where(DB::raw($whereYear), $year);
                    $dataQuery->where(DB::raw($whereMonth), $period);
                } else {
                    $dataQuery->whereRaw($dateColumn .' > CURDATE() - INTERVAL '. $minMonthInterval .' MONTH' );
                }
                break;

            case config('constant.frequency_quarterly'):
                if($period) {
                    $dataQuery->where(DB::raw($whereYear), $year);
                    $dataQuery->where(DB::raw($whereQuarter), $period);
                } else {
                    $dataQuery->whereRaw($dateColumn . ' > CURDATE() - INTERVAL '. $minQuarterInterval .' QUARTER' );
                }
                break;
            case config('constant.frequency_yearly'):
                if($year) {
                    $dataQuery->where(DB::raw($whereYear), $year);
                } else {
                    $dataQuery->whereRaw($dateColumn . ' > CURDATE() - INTERVAL 1 YEAR' );
                }
                break;
            
            default:
                $dataQuery->where(DB::raw($whereYTD), '>=', config('constant.ytd_date'));
                break;
        }

    }

    public function reportSummaryCount($frequency, $year, $period, $summaryTypes = null, $past = null) 
    {
        
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $categoryTable  = TableHelper::MST_CATEGORY;

        $mappingTable  = TableHelper::REPORT_CATEGORY_MAPPING;

        $dataQuery      = $this->makeModel(); 

        $rawCountQuery = "COUNT(". $userTrxTable . ".TitleAssetId) as ViewDownloadCount";

        $dataQuery->select(
            $userTrxTable . '.TitleAssetId'
        );  


        if($summaryTypes) {
            $typeInfo = explode('-', $summaryTypes);

            if($typeInfo[1] == 'report')  {
                 $dataQuery->join($reportMasterTable, function($join) use ($userTrxTable, $reportMasterTable)  {
                    $join->on($userTrxTable . '.TitleAssetId', '=', $reportMasterTable . '.ReportID' );
                });
                $dataQuery->where($reportMasterTable.'.ReportTypeId', $typeInfo[0]);
            }

            if($typeInfo[1] == 'asset')  {
                $dataQuery->where($userTrxTable.'.AssetTypeId', $typeInfo[0]);
            }
        }
        
        
        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        // $dataQuery->whereIn($userTrxTable.'.AssetTypeId', [config('constant.asset_type_reports')]);
        $dateColumn = $userTrxTable.'.CreatedOn';

        // $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, null);

        $this->countByFrequency($dataQuery, $dateColumn, $frequency, $year, $period, $past);
        
        return  $dataQuery->count($userTrxTable . '.TitleAssetId');
    }

    public function getViewDownloadCount($client,  $frequency, $year, $period, $past = null) 
    {
        $userTrxTable  = TableHelper::TRX_USER_ASSET;
        $userMasterTable    = TableHelper::MST_USER;

        $dataQuery      = $this->makeModel(); 

        $rawCountQuery = "COUNT(". $userTrxTable . ".UserId) as viewDownload";
        
        $dataQuery->select(
            DB::raw($rawCountQuery)
        );  

       
        $dataQuery->where($userTrxTable.'.ClientId', $client);

        $dataQuery->whereIn($userTrxTable.'.ActionStatusId', config('constant.view_download_status'));
        
        $dateColumn = $userTrxTable.'.CreatedOn';

        $this->countByFrequency($dataQuery, $dateColumn, $frequency, $year, $period, $past );

        // $dataQuery->groupBy($userTrxTable.'.UserId');

            
        
        return  $dataQuery->count($userTrxTable . ".UserId");
    }

    private function countByFrequency($dataQuery, $dateColumn, $frequency, $year, $period, $past = null)
    {
        $whereYear = "YEAR(". $dateColumn .")";
        $whereWeek = "WEEK(". $dateColumn . ", 1)";
        $whereMonth = "MONTH(". $dateColumn . ")";
        $whereQuarter = "QUARTER(". $dateColumn . ")";
        $whereYTD = "DATE(". $dateColumn . ")";

        $whereWeekofYear = "WEEKOFYEAR(". $dateColumn . ")";

        $minMonthInterval = config('constant.min_months_data');
        $minWeekInterval = config('constant.min_week_data');
        $minQuarterInterval = config('constant.min_quarter_data');

        switch ($frequency) {
            case config('constant.frequency_ytd'):

                $dataQuery->where(DB::raw($whereYTD), '>=', config('constant.ytd_date'));

                if($past) {
                    $dataQuery->whereRaw($dateColumn .' <  CURDATE() - INTERVAL 1 WEEK' );
                } else {
                    // $dataQuery->whereRaw($dateColumn .' < CURDATE() - INTERVAL 1 WEEK' );
                   
                }
                break;
        
            case config('constant.frequency_weekly'):
                $dataQuery->where(DB::raw($whereYTD), '>=', config('constant.ytd_date'));

                if($period !== 0) {

                    if($past) {
                        $pastYear = $year;

                        if($period ) {
                            // $pastYear = $year - 1;
                            $pastPeriod = $period - 1;
                        } else  {
                            $pastYear = $year - 1;

                            $date = strtotime("31 December $pastYear");
                            $pastPeriod  = gmdate("W", $date);
                        }
                            
                        $dataQuery->where(DB::raw($whereYear), $pastYear);
                        $dataQuery->where(DB::raw($whereWeek), $pastPeriod);
                    } else {
                        $dataQuery->where(DB::raw($whereYear), $year);
                        $dataQuery->where(DB::raw($whereWeek), $period);
                    }

                } else {
                    if($past) {
                        $dataQuery->whereRaw($whereWeekofYear .' =  WEEKOFYEAR(CURDATE() - INTERVAL 1 WEEK)' );
                    } else {
                        $dataQuery->whereRaw($whereWeekofYear .' =  WEEKOFYEAR(CURDATE())' );
                    }
                }
                break;


            case config('constant.frequency_monthly'):
                
                if($period) {
                    if($past) {
                        $date = date_create($year . "-" . $period);
                        $pastDate = date_format($date,"Y-m-d");

                        $finalPastDate = date("Y-m-d", strtotime("-1 month", strtotime($pastDate)));
                        
                        $pastYear = date('Y', strtotime($finalPastDate));
                        $pastMonth = date('n', strtotime($finalPastDate));

                        
                        $dataQuery->where(DB::raw($whereYear), $pastYear);
                        $dataQuery->where(DB::raw($whereMonth), $pastMonth);
                    } else {
                        $dataQuery->where(DB::raw($whereYear), $year);
                        $dataQuery->where(DB::raw($whereMonth), $period);
                    }
                }  else {
                    if($past) {
                        $dataQuery->whereRaw($whereMonth .' =  MONTH(CURDATE() - INTERVAL 1 MONTH)' );
                        $dataQuery->whereRaw($whereYear .'  =  YEAR(CURDATE() - INTERVAL 1 MONTH)' );
                    } else {
                        $dataQuery->whereRaw($whereMonth .' =  MONTH(CURDATE())' );
                        $dataQuery->whereRaw($whereYear .'  =  YEAR(CURDATE())' );
                    }
                }
                break;

            case config('constant.frequency_quarterly'):
                $dataQuery->where(DB::raw($whereYTD), '>=', config('constant.ytd_date'));

                if($period ) {
                    if($past) {
                        $pastYear = $year;

                        if($period  == 1) {
                            $pastPeriod = 4;
                            $pastYear = $year - 1;
                        }  else {
                            $pastPeriod = $period - 1;
                        }


                        $dataQuery->where(DB::raw($whereYear), $pastYear);
                        $dataQuery->where(DB::raw($whereQuarter), $pastPeriod);
                    } else {
                        $dataQuery->where(DB::raw($whereYear), $year);
                        $dataQuery->where(DB::raw($whereQuarter), $period);
                    }

                } else {
                    if($past) {
                        $dataQuery->whereRaw($whereQuarter .' =  QUARTER(CURDATE() - INTERVAL 1 QUARTER)' );
                    } else {
                        $dataQuery->whereRaw($whereQuarter .' =  QUARTER(CURDATE())' );
                    }
                } 
                
                break; 
                
            case config('constant.frequency_yearly'):
               if($past) {
                    $dataQuery->whereRaw($whereYear .'  =  YEAR(CURDATE() - INTERVAL 1 YEAR)' );
                } else {
                    $dataQuery->whereRaw($whereYear .'  =  YEAR(CURDATE())' );
                }
                break; 
            
            default:
                if($past) {
                    $dataQuery->whereRaw($dateColumn .' =  CURDATE() - INTERVAL 1 YEAR' );
                } else {
                    // $dataQuery->whereRaw($dateColumn .' < CURDATE() - INTERVAL 1 WEEK' );
                }
                break; 
        }       

    }

    public function reportSummaryWithResources($frequency, $year, $period, $summaryTypes = null) 
    {
        
        $userActivityLogTable  = TableHelper::USER_ACTIVITY_LOG;
        $reportMasterTable = TableHelper::MST_REPORT;
        
        $reportTypeTable  = TableHelper::MST_REPORT_TYPE;

        $dataQuery      = DB::table($userActivityLogTable);

        $rawCountQuery = "COUNT(". $userActivityLogTable . ".UserActivityLogId) as ViewDownloadCount";

        $dateColumn = $userActivityLogTable.'.createdDate';

        if($frequency == config('constant.frequency_monthly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("MONTH(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

        }

        if($frequency == config('constant.frequency_weekly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("WEEK(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

        }

        if($frequency == config('constant.frequency_quarterly') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("QUARTER(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

        }

        if($frequency == config('constant.frequency_yearly') || $frequency == config('constant.frequency_ytd') ) {
            $dataQuery->select(
                DB::raw($rawCountQuery),
                DB::raw("MONTH(". $dateColumn . ") as period"),
                DB::raw("YEAR(". $dateColumn . ") as year"),
                DB::raw("DATE(". $dateColumn . ") as date")
            );  

        }
        
        $dataQuery->whereIn($userActivityLogTable.'.PageName', config('constant.resources'));
        $dataQuery->where($userActivityLogTable.'.SectionofPage', 'like',  '%' . config('constant.resources_view_activity') . '%');

        $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, null);



        $dataQuery->groupBy(DB::raw("YEAR(". $dateColumn . ")"));
        $dataQuery->groupBy('period');

        $dataQuery->union($this->reportSummary($frequency, $year, $period, $summaryTypes, true));

        // return DB::table(function ($query) use($dataQuery) {
        //     $query->selectRaw()
        //         ->from('x')
                
        // }, 'x')->get();

        return DB::table(DB::raw('('.$dataQuery->toSql().') as sub'))
    ->selectRaw('sum(sub.ViewDownloadCount) as ViewDownloadCount, sub.year, sub.period, sub.date')
    ->groupBy('year', 'period')
    ->mergeBindings($dataQuery)
    ->get();

        

        // $dataQuery->orderBy('ViewDownloadCount', 'DESC');

        
        return  $dataQuery->get();
    }


}
