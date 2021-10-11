<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class Actress extends Model
{
    use HasFactory, Notifiable ;
    protected $guarded = ['id'];

    public function notifyTelegram($file)
    {
        $text="Создана новая актриса: $this->name \n
        Возраст $this->age лет\n
        Страна: $this->country
        ";

        Telegram::sendMessage(
            [
               'chat_id'=>config('telegram.channel_id'),
               'parse_mode'=>'HTML',
               'text'=>$text
            ]
        );

        Telegram::sendPhoto(
            [
                'chat_id'=>config('telegram.channel_id'),
                'photo'=>InputFile::createFromContents(file_get_contents(storage_path('app/' . $file)),request()->file('file'))
            ]
        );

    }

    public function films()
    {
        return $this->belongsToMany(Film::class);
    }
}
