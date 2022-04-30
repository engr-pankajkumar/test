<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Contracts\Repository;
use App\Helpers\TableHelper;
use Illuminate\Support\Facades\DB;


class IndustryRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\Industry';
    }


}
