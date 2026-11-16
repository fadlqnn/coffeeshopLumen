<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'gender',
        'birth_date', 'city', 'membership_level', 'loyalty_points',
    ];

    // has_many: satu customer punya banyak order
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // Poin 5: format waktu 05-11-2018 08:40:30
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }
}