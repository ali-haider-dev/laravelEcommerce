<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'payment_status',
        'order_status',
        'shipping_address',
        'billing_address',
        'payment_method',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // --- Relationships ---

    /**
     * Get the user who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        // One order can have many order items
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    /**
     * Get the user who created the order record (if different from the user who placed the order).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the order record.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}