<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait SchoolScoped
{
    protected static function bootSchoolScoped()
    {
        static::creating(function ($model) {
            if (Auth::hasUser()) {
                $user = Auth::user();
                if (!$user->isMasterAdmin() && empty($model->school_id)) {
                    $model->school_id = $user->school_id;
                }
            }
        });

        static::addGlobalScope('school', function (Builder $builder) {
            if (Auth::hasUser()) {
                $user = Auth::user();
                
                // Avoid infinite loops when loading the current user or their role
                $modelClass = get_class($builder->getModel());
                if ($modelClass === \App\Models\User::class || $modelClass === \App\Models\Role::class) {
                    return;
                }

                if (!$user->isMasterAdmin()) {
                    $table = $builder->getModel()->getTable();
                    $builder->where($table . '.school_id', $user->school_id);
                }
            }
        });
    }
}
