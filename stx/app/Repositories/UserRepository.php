<?php
namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Contracts\Repository;
use App\Helpers\TableHelper;
use Illuminate\Support\Facades\DB;


use App\Helpers\AppHelper;

use App\Helpers\ApiService;
use App\Helpers\ApiConstants;
use App\Helpers\ServiceHelper;
use Carbon\Carbon;

class UserRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\User';
    }


}
