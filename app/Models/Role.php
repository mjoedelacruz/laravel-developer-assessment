<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * 1 role has many permissions
     *
     * @return void
     */
    public function permission(){
        return $this->hasMany(Permission::class);
    }
}