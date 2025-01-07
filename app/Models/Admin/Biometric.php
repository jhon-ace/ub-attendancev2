<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Biometric extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'authenticable_id', 'authenticable_type', 'public_key', 'challenge', 'revoked'];

    protected $hidden = ['public_key'];

    public function getTable()
    {
        return config('biometric-auth.table', parent::getTable());
    }

    public function instance(): MorphTo
    {
        return $this->morphTo('authenticable');
    }
}
