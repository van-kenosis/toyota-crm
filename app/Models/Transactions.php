<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'transactions';

    public function inquiry(){
        return $this->belongsTo(Inquiry::class, 'inquiry_id', 'id')->with(['inquiryType', 'customer']);
    }

    public function application(){
        return $this->belongsTo(Application::class, 'application_id', 'id')->with(['vehicle', 'updatedBy']);
    }

    public function inventory(){
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

   
}
