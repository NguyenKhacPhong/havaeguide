<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    //Khai báo softDeletes
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'phone',
        'avatar',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasPermission($route){
        $routes = $this->routes();
        return in_array($route, $routes) ? true : false;
    }

    public function routes(){
        $data = [];
        foreach($this->getRoles as $role){
            $permissions = json_decode($role->permissions);
            // dd($permissions);
            foreach ($permissions as $permission){
                if(!in_array($permission, $data)){
                    array_push($data,$permission);
                }
            }
        }
        return $data;
    }
    //cac route đã được gán cho người dùng này
    public function getRoles(){
        return $this->belongsToMany(Roles::class,'user_roles', 'user_id', 'role_id');
    }

}
