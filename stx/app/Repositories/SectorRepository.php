<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Contracts\Repository;
use App\Helpers\TableHelper;
use Illuminate\Support\Facades\DB;


class SectorRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\Sector';
    }

    function fetchSectors() 
    {
        $sectorTable = TableHelper::SECTOR;

        $dataQuery      = $this->makeModel(); 

        
	    $dataQuery->select(
	    	$sectorTable . '.id',
	    	$sectorTable . '.sector_name',
	    );  

        
        
        $dataQuery->where($sectorTable.'.status', 1);

        $dataQuery->orderBy('is_favourite', 'DESC');
        $dataQuery->orderBy('sector_name', 'ASC');
        
        return  $dataQuery->get();

    }


}
