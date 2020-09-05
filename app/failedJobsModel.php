<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class failedJobsModel extends Model
{
    protected $table= 'failed_jobs';
    public $primaryKey='id';
}
