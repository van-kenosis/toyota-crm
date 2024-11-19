<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inquiry';

    protected $fillable = [
        'users_id',
        'customer_first_name',
        'customer_last_name',
        'contact_number',
        'unit',
        'variant',
        'color',
        'gender',
        'address',
        'transaction',
        'age',
        'source',
        'remarks',
        'date',
        'transactional_status',
        'updated_by'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
}
