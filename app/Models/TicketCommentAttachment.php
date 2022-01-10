<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;

class TicketCommentAttachment extends Model
{
    protected $fillable = [
        'ticket_comment_id',
        'path',
        'details',
    ];

    protected $casts = [
        'details' => AsCollection::class,
    ];

    public function comment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TicketComment::class);
    }
}
