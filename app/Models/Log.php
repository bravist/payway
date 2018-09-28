<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 多态定义
     * @return [type] [description]
     */
    public function logger()
    {
        return $this->morphTo();
    }
}
