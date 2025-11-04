<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        // 'subtotal' is a generated column, do not include it in $fillable
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    /**
     * Indicates if the model should be timestamped.
     * * Since the table only has `created_at` and not `updated_at`, 
     * we disable Eloquent's default timestamp management.
     *
     * @var bool
     */
    public $timestamps = false;

    // --- Relationships ---

    /**
     * Get the order that the item belongs to.
     */
    public function order()
    {
        // Assuming your main order model is named 'Order'
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the product associated with the order item.
     */
    public function product()
    {
        // Assuming your product model is named 'Product'
        return $this->belongsTo(Product::class, 'product_id');
    }
}