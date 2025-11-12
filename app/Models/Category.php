<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_categories'; // Use your specific table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'created_by',
        'updated_by',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * We don't need Eloquent to auto-manage 'created_at' and 'updated_at' 
     * because you've set 'DEFAULT CURRENT_TIMESTAMP' in the SQL definition.
     * However, if you plan to update these via the model, setting it to true 
     * is often more convenient. I'll leave it as the default (true) for flexibility,
     * but you can set it to false if you want the database to handle ALL timestamps.
     *
     * @var bool
     */
    // public $timestamps = true; 

    // --- Relationships (Recommended) ---

    /**
     * Get the user who created the category.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    /**
     * Get the user who last updated the category.
     */

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}