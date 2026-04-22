<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $table = 'tamu'; // PASTIKAN INI ADA!
    protected $fillable = ['nama', 'alamat', 'no_hp', 'keperluan_kunjungan', 'paraf', 'foto', 'ip_address'];
}