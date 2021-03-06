<?php

namespace Chali5124\LaravelH5p\Eloquents;

use DB;
use Illuminate\Database\Eloquent\Model;

class H5pContentsUserData extends Model {

    protected $primaryKey = ['content_id','user_id','sub_content_id','data_id'];
    protected $fillable = [
        'content_id',
        'user_id',
        'sub_content_id',
        'data_id',
        'data',
        'preload',
        'invalidate',
        'updated_at'
    ];

}
