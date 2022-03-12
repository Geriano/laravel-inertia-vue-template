<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JsonSerializable;
use stdClass;

class Menu extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'parent_id',
        'route_or_url',
        'icon',
        'active',
        'position',
        'routes',
        'deleteable',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'permissions',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(static::class, 'parent_id', 'id')->with(['childs', 'parent'])->orderBy('position', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne(static::class, 'id', 'parent_id')->withCount('childs');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @return Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function name() : Attribute
    {
        return new Attribute(
            set: fn ($value) => mb_strtolower($value),
        );
    }

    /**
     * @return Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function routes() : Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn (string|array|stdClass|Collection|JsonSerializable $value) => is_string($value) ? $value : json_encode($value),
        );
    }
}
