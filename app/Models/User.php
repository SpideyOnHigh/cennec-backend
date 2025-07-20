<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\WelcomeUserNotification;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    public function userRole()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    public function invitationCode()
    {
        return $this->hasOne(InvitationCodeMaster::class, 'id');
    }

    public function userCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name');
    }

    public function sendWelcomeUserNotification($token)
    {
        $this->notify(new WelcomeUserNotification($this, $token));
    }

    public function interests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function firstProfileImage()
    {
        return $this->hasOne(UserProfileImage::class)->orderByDesc('created_at');
    }

    public function profileImage()
    {
        return $this->hasOne(UserProfileImage::class);
    }

    public function profileImages()
    {
        return $this->hasMany(UserProfileImage::class);
    }

    public function favoritedUsers()
    {
        return $this->hasMany(UserFavourite::class, 'favorited_by_user_id')->with('favoritedUser');
    }

    public static function getUserInfo($user_id)
    {
        try {
            $userDetail = [];
            $userImages = UserProfileImage::where('user_id', $user_id)
                ->get();
            $defaultImage = $userImages->where('is_default', true)->value('image_name');
            if (!is_null($defaultImage)) {
                $defaultImage = concatAppUrl($defaultImage);
            } else {
                $defaultImage = null;
            }
            $user_bio = UserDetail::where('user_id', $user_id)->first();
            $userDetail['bio'] = $user_bio ? $user_bio->bio : '';
            $userDetail['default_profile_picture'] = $defaultImage;
            $userDetail['profile_pictures'] = $userImages->map(function ($image) {
                return [
                    'image_id' => $image->id,
                    'image_url' => concatAppUrl($image->image_name),
                    'is_default' => boolval($image->is_default),
                ];
            });
            $user_latest_post = UserPosts::where('user_id', $user_id)
                ->take(5)
                ->orderBy('created_at', 'desc')
                ->get();
            $userDetail['latest_posts'] = $user_latest_post;
            return $userDetail;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
