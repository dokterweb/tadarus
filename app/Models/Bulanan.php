<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulanan extends Model
{
    protected $fillable = ['siswa_id', 'payment_id', 'bulan_id', 'bulan_bill', 'bulan_status', 'bulan_number_pay', 'bulan_date_pay', 'bukti_bulan'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function bulan()
    {
        return $this->belongsTo(Bulan::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
