<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function boot() {
        parent::boot();

        // create a event to happen on saving
        static::creating(function($table) {
            // Set create user
            if (Auth::check()) {
                $loginId = Auth::id();
                $table->created_by = $loginId;
            }
        });

        // create a event to happen on updating
        static::updating(function($table) {
            if (Auth::check()) {
                $loginId = Auth::id();
                $table->updated_by = $loginId;
            }
        });
    }

}
