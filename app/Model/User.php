<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $old_uid 
 * @property int $group_id 
 * @property string $username 
 * @property string $nickname 
 * @property string $password 
 * @property string $old_password 
 * @property string $salt 
 * @property string $email 
 * @property string $mobile 
 * @property string $avatar 
 * @property int $level 
 * @property int $gender 
 * @property string $birthday 
 * @property string $bio 
 * @property float $money 
 * @property float $frozen_money 
 * @property int $score 
 * @property int $frozen_score 
 * @property float $luck_money 
 * @property float $frozen_luck_money 
 * @property int $is_follow 
 * @property string $address 
 * @property string $source 
 * @property int $referee_uid 
 * @property float $lon 
 * @property float $lat 
 * @property int $shop_id 
 * @property int $successions 
 * @property int $maxsuccessions 
 * @property int $prevtime 
 * @property int $logintime 
 * @property string $loginip 
 * @property int $loginfailure 
 * @property string $joinip 
 * @property int $jointime 
 * @property int $createtime 
 * @property int $updatetime 
 * @property string $token 
 * @property string $status 
 * @property string $verification 
 * @property int $is_ip 
 * @property int $is_business 
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
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
    protected $casts = ['id' => 'int', 'old_uid' => 'integer', 'group_id' => 'integer', 'level' => 'integer', 'gender' => 'integer', 'money' => 'float', 'frozen_money' => 'float', 'score' => 'integer', 'frozen_score' => 'integer', 'luck_money' => 'float', 'frozen_luck_money' => 'float', 'is_follow' => 'integer', 'referee_uid' => 'integer', 'lon' => 'float', 'lat' => 'float', 'shop_id' => 'integer', 'successions' => 'integer', 'maxsuccessions' => 'integer', 'prevtime' => 'integer', 'logintime' => 'integer', 'loginfailure' => 'integer', 'jointime' => 'integer', 'createtime' => 'integer', 'updatetime' => 'integer', 'is_ip' => 'integer', 'is_business' => 'integer'];
}