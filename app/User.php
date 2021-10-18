<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordToken;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const VERIFIED = false;
    const ADMIN = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
		'role_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',
        'enterprise_id'
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = strtolower($valor);
    }
    public function getNameAttribute($valor)
    {
        return ucwords($valor);
    }
    public function setEmailAttribute($valor)
    {
        $this->attributes['email'] = strtolower($valor);
    }

    public function isVerified()
    {
        return $this->verified == User::VERIFIED;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN;
    }

    public static function generateVerificationToken()
    {
        return str_random(40);
    }
	
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new MailResetPasswordToken($token));
	}
	
	public function role()
	{
		return $this->belongsTo('App\Role');
	}

    public function enterprise()
    {
        return $this->belongsTo('App\Enterprise');
    }

    public function hasPermission($permission){
        if($this->admin){
            return true;
        }
        foreach ($this->role->permissions as $perm) {
            if($perm->resource."_".$perm->action == $permission){
                return true;
            }
        }
        return false;
    }

    public function type() {
        $role = Role::find($this->role_id);
        return $role->type;
    }
	
}
