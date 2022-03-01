<?php

namespace App\Models;

use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
  /**
   * @var array
   */
  protected $fillable = [
    'name',
    'guard_name',
  ];

  /**
   * @var array
   */
  protected $with = [
    'permissions'
  ];
}
