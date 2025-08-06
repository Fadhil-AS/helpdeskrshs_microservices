<?php

namespace App\Services\Chatbot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ChatbotFactory;

class Chatbot extends Model
{
    use HasFactory;

    protected $connection = 'chatbot';
    protected $table = 'data_chatbot';
    public $timestamps = true;
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['data', 'nama_file'];

    protected static function newFactory(): ChatbotFactory
    {
        return ChatbotFactory::new();
    }
}
