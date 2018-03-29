<?php
/**
 * Created by PhpStorm.
 * User: dazli
 * Date: 28/03/2018
 * Time: 2:55 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;



class Track extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'artist_names',
    ];


    public static $rules = [
        'title' => 'required|string|min:3|max:255',
        'artist_names' => 'required|string|max:255',
    ];


    public static $messages = [
        'required' => 'The :attribute field cannot be blank!',
        'min' => 'The track title must be longer than 3 characters!',
    ];


    public static function validate(array $data){
        return Validator::make($data, static::$rules, static::$messages);
    }

    public function playlists(){
        return $this->belongsToMany('App\Models\Playlist','playlist_tracks')->withTimestamps();
    }


}