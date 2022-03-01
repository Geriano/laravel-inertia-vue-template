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
}
