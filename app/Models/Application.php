<?php

namespace App\Models;

use Database\Seeders\InquiryTypeSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'application';

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id')->with('team');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    public function trans(){
        return $this->belongsTo(Transactions::class, 'transaction_id', 'id');
    }

    public function inquiry()
    {
        return $this->hasOneThrough(
            Inquiry::class,
            Transactions::class,
            'id', // Foreign key on the transactions table
            'id', // Foreign key on the inquiry table
            'transaction_id', // Local key on the application table
            'inquiry_id' // Local key on the transactions table
        )->with('inquiryType'); // Eager load the inquiry_type relationship
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function bank(){
        return $this->belongsTo(Banks::class, 'bank_id', 'id');
    }
    
}
