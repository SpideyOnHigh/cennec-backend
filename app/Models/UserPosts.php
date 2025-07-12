<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPosts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_posts';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'activity', 'location', 'meet_at', 'meet_with', 'discussion_topic', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public static function addUserPost($request)
    {
        try {
            // add user post
            $user_post = new self();
            $user_post->user_id = $request->user()->id;
            $user_post->activity = $request->activity;
            $user_post->location = $request->location;
            $user_post->meet_at = $request->meet_at;
            $user_post->meet_with = $request->meet_with;
            $user_post->discussion_topic = $request->discussion_topic;
            $user_post->description = $request->description;
            $user_post->save();

            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
