<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicy extends Model
{
    protected $table = 'privacy_policy';
    
    protected $fillable = ['isi'];
}
