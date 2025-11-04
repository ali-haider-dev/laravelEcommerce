<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'attachments',
        'isHot',
        'isActive',
        'category_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price'       => 'decimal:2', // Ensures the price is treated as a float with 2 decimal places
        'attachments' => 'array',     // Automatically decodes the JSON data in the attachments column
        'isHot'       => 'boolean',
        'isActive'    => 'boolean',
    ];

    // --- Relationships ---

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the user who created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the product.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}