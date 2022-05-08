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

        // $this->queryByFrequency($dataQuery, $dateColumn, $frequency, $year, $period);

        $dataQuery->groupBy($userTrxTable.'.UserId');

        	
        $dataQuery->orderBy('viewDownload', 'DESC')->limit(10);
        
        return  $dataQuery->get();
    }

    public function getStocks($where = [], $filters = [], $columnOrders = []) 
    {
        $companyTable = TableHelper::COMPANY;

        $dataQuery      = $this->makeModel(); 

        $dataQuery->select(
            '*'
        );  

        if(count($where)) {
            $dataQuery->where($where);
        }

        foreach (config('constant.notNullOrder') as $key => $column) {
            $dataQuery->whereNotNull($column);
        }

        if(count($filters)) {
            // dd($filters);
            foreach ($filters as $cols => $rows) {
                $dbColumn = config('constant.columnHeader')[$cols];
                # code...
                $dataQuery->where(function($query) use($dbColumn, $rows){
                    $index = 0;
                    foreach ($rows as $row) {
                        // dd($row);
                        $range = explode('-', $row);

                        $fromVal  = trim($range[0]);
                        $toVal  = trim($range[1]);

                        if($index == 0) {
                            $whereClause = 'where';
                            $whereBetweenClause = 'whereBetween';
                        } else {
                            $whereClause = 'orWhere';
                            $whereBetweenClause = 'orWhereBetween';
                        }
                       
                        if($fromVal == config('constant.filterOptionPrefix')['lte']) {
                            $query->{$whereClause}($dbColumn, '<=',  (float)$toVal);
                        } elseIf($fromVal == config('constant.filterOptionPrefix')['gte']) {
                            $query->{$whereClause}($dbColumn, '>=',  (float)$toVal);
                        } else {
                            $query->{$whereBetweenClause}($dbColumn, [(float)$fromVal, (float)$toVal]);
                        }
                            
                       
                        $index++;
                    }
               });
            }
        }

        // >where(function($query) use ($new_start_date, $new_end_date){
        //           $query->whereBetween('starting_date', [$new_start_date,$new_end_date])
        //                 ->orWhereBetween('ending_date', [$new_start_date,$new_end_date]);
        //         })


        // $searchOrder = config('constant.searchOrder');

        // foreach ($searchOrder as $column => $order) {
        //     $dataQuery->orderBy($column, $order);
        // }

        if(count($columnOrders)) {
            foreach ($columnOrders as $column => $order) {
                if($order) {
                    $dbColumn = config('constant.columnHeader')[$column];
                    // echo $column . '----' . $order;
                    $dataQuery->orderBy($dbColumn, $order);
                }
            }

        }

       
            
        
        
        return  $dataQuery->get();
    }





}
