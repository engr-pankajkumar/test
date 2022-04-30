<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\TableHelper;


class Company extends Model
{
    use HasFactory;

    protected $table = TableHelper::COMPANY;
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $primaryKey = 'id';

    // protected $fillable = [
    //     'id',
    //     'client_id',
    //     'client_name',
    //     'username',
    //     'provider',
    //     'is_active',
    //     'access_token',
    //     'expires_in',
    //     'issued_at',
    //     'expires_at'    
    // ];

    protected $guarded = [];

}
