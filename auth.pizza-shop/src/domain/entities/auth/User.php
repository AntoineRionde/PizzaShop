<?php

namespace pizzashop\auth\api\domain\entities\auth;
use Illuminate\Database\Eloquent\Model;
use pizzashop\shop\domain\dto\auth\UserDTO;

class User extends Model
{

    protected $connection = 'users';
    protected $table = 'users';
    protected $primaryKey = 'email';
    public $timestamps = false;
    protected $fillable = ['email', 'password', 'active', 'activation_token', 'activation_token_expiration_date', 'refresh_token', 'refresh_token_expiration_date', 'reset_passwd_token', 'reset_passwd_token_expiration_date', 'username'];

    public function userToDTO(){
        return new UserDTO();
    }
    public function userToDTOforCreate(){
        return new UserDTO();
    }
}