<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\TableHelper;

class Sector extends Model
{
    use HasFactory;

    protected $table = TableHelper::SECTOR;
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'sector_name',
        'status',
    ];
}
