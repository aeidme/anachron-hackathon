<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetModel extends Model
{
    protected $table= 'budget';
    public $primaryKey='budget_id';

    protected $fillable = [
        'user_id', 'clothes', 'housing', 'food', 'goals', 'transport'
    ];
}
