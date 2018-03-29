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



class Playlist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];


    public static $rules = [
        'name' => 'required|string|min:3|max:255',
    ];


    public static $messages = [
        'required' => 'The :attribute field cannot be blank!',
        'min' => 'The playlist name must be longer than 3 characters!',
    ];


    public static function validate(array $data){
        return Validator::make($data, static::$rules, static::$messages);
    }

    public function tracks(){
        return $this->belongsToMany('App\Models\Track', 'playlist_tracks')->withTimestamps();
    }

}