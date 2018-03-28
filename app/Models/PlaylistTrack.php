<?php
/**
 * Created by PhpStorm.
 * User: dazli
 * Date: 28/03/2018
 * Time: 2:55 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistTrack extends Model
{
    protected $table = 'playlist_tracks';

    protected $fillable = [
        'playlist_id','track_id',
    ];
}