<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $username 
 * @property string $nickname 
 * @property string $password 
 * @property string $salt 
 * @property string $avatar 
 * @property string $email 
 * @property string $mobile 
 * @property float $money 
 * @property float $frozen_money 
 * @property int $loginfailure 
 * @property int $logintime 
 * @property string $lng 
 * @property string $lat 
 * @property int $bind_user_id 
 * @property string $address 
 * @property string $machine_code 
 * @property int $shop_id 
 * @property int $createtime 
 * @property int $updatetime 
 * @property string $token 
 * @property string $openid 
 * @property string $status 
 */
class Admin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'money' => 'float', 'frozen_money' => 'float', 'loginfailure' => 'integer', 'logintime' => 'integer', 'bind_user_id' => 'integer', 'shop_id' => 'integer', 'createtime' => 'integer', 'updatetime' => 'integer'];
}