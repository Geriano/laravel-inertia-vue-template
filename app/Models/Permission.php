<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as Model;

class Permission extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    'name',
    'guard_name',
  ];

  /**
   * @inheritdoc
   */
  public static function boot()
  {
    parent::boot();

    static::created(function ($permission) {
      Role::where('name', 'superuser')
          ->first()
          ->givePermissionTo($permission);
    });
  }
}
