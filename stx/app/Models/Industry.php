<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\TableHelper;

class Industry extends Model
{
    use HasFactory;
    protected $table = TableHelper::INDUSTRY;
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'sector_id',
        'industry_name',
        'status',
    ];
}
