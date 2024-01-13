<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInfo extends Model
{
    use HasFactory;

    /**
     * The primary key associated with table.
     * 
     * @var string
    */

    protected $primaryKey = 'admin_id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'account_id',
        'first_name',
        'last_name',
        'phone',
    ];
}
