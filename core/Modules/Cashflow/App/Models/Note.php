<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Note extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_notes';
    public $timestamps = false;
    public function created_by()
    {
        return $this->belongsTo('App\Models\User',"created_by_id")->withDefault();
    }
    protected static function booted()
    {
        static::creating(function ($note) {
            $note->created_by_id = Auth::id();
        });


    }
}