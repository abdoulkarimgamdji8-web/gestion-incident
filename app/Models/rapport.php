<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class rapport extends Model
{
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(intervention::class);
    }
}
