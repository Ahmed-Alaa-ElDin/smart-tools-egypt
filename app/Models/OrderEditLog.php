<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'edited_by',
        'snapshot_before',
        'snapshot_after',
        'changes_summary',
        'reason',
    ];

    protected $casts = [
        'snapshot_before' => 'array',
        'snapshot_after' => 'array',
        'changes_summary' => 'array',
    ];

    /**
     * The order that was edited.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The admin who performed the edit.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
