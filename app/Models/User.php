<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'username',
        'email',
        // Temporary
        // 'role',
        // Temporary
        // 'user_role',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'profile_photo_url'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /** Custom Methods **/
    public function userRoles(){
        return $this->hasMany(UserRoles::class, 'user_id');
    }

    public function visitLogs(){
        return $this->hasMany(VisitLog::class, 'assigned_to');
    }    

    public function diagnosisLogsAssignedTos(){
        return $this->hasMany(DiagnosisLog::class, 'assigned_to');
    }

    public function diagnosisLogsCreatedBys(){
        return $this->hasMany(DiagnosisLog::class, 'created_by');
    }

    public function diagnosisLogsAuthorizedBys(){
        return $this->hasMany(DiagnosisLog::class, 'authorized_by');
    }

    /**User can be assigned to 1 to many admin level 2
     * (eg. User 1 can be assigned to Municipality A & Municipality B)
     */
    public function adminLevelTwos(){
        return $this->belongsToMany(AdminLevelTwo::class, 'user_admin_level_twos');
    }


    public function feedingLogsCreatedBys(){
        return $this->hasMany(FeedingLog::class, 'created_by');
    }

}
