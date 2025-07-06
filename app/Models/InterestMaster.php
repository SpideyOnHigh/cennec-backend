<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterestMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function sponsorName()
    {
        return $this->belongsTo(User::class, 'sponsor_id')->select('username');
    }

    public function interestCategory()
    {
        return $this->belongsTo(InterestCategoryMaster::class, 'interest_category_id')->select('interest_category_name');
    }
}
