<?php

namespace App\Models\Tenancy;

use App\Models\Uam\User;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Illuminate\Support\Str;

class Tenant extends BaseTenant
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public static function getCustomColumns(): array
    {
        return [
            'name',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'tenant_user',
            'tenant_id',
            'user_id'
        );
    }
}
