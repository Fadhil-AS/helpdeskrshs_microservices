<?php

namespace App\Services\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    protected $connection = 'chatbot';
    protected $table = 'data_chatbot';
    public $timestamps = false;
    protected $fillable = ['id','nama_file'];
}
