<?php

namespace App\Task;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * rule 5 位时以分钟级规则执行，6 位时以秒级规则执行
 * @Crontab(name="Foo", rule="*\/5 * * * * *", callback="execute", memo="这是一个示例的定时任务")
 */
class FooTask
{

    /**
     * @Inject
     * @var \Hyperf\Contract\StdoutLoggerInterface
     */
    private $logger;

    public function execute()
    {
        $list = Db::connection('dingdong')->select('select * from dg_user where renew=0 order by Id desc limit 10');
        if ($list) {
            Db::beginTransaction();
            foreach ($list as $key => $val) {
                if ($val['WechatId']) {
                    $wechatData = Db::connection('dingdong')->selectOne('select * from dg_wechat where WechatOpenid=?', [$val['WechatId']]);
                    if ($wechatData) {
                        #先转换user表
                        $userData['old_uid'] = $val['Id'];
                        $userData['group_id'] = 1;
                        $userData['username'] = $val['Id'] . '_' . $val['Phone'];
                        $userData['nickname'] = $wechatData['WechatName'];
                        $userData['old_password'] = isset($val['Pwd']) ? $val['Pwd'] : '';
                        $userData['mobile_fix'] = $val['PhoneFix'];
                        $userData['mobile'] = $val['Phone'];
                        $userData['avatar'] = $wechatData['WechatHeadimgurl'];
                        $userData['gender'] = $wechatData['WechatSex'];
                        $userData['is_agent'] = $val['IsAgent'];
                        $userData['jointime'] = strtotime($val['CreateTime']);
                        $userData['createtime'] = strtotime($val['CreateTime']);
                        #return ['json'=>$userData];
                        $user_id = Db::table('user')->insertGetId($userData);
                        if ($user_id) {
                            #转换third表
                            $thirdData['user_id'] = $user_id;
                            $thirdData['union_id'] = $wechatData['WechatUnionid'];
                            $thirdData['platform'] = 'wechat';
                            $thirdData['openid'] = $wechatData['WechatOpenid'];
                            $thirdData['openname'] = $wechatData['WechatName'];
                            $thirdData['avatar'] = $wechatData['WechatHeadimgurl'];
                            $thirdData['gender'] = $wechatData['WechatSex'];
                            $thirdData['province'] = $wechatData['WechatProvince'];
                            $thirdData['city'] = $wechatData['WechatCity'];
                            $thirdData['is_subscribe'] = $wechatData['WechatSubscribe'];
                            $thirdData['createtime'] = strtotime($wechatData['CreateTime']);
                            $thirdData['updatetime'] = strtotime($wechatData['CreateTime']);
                            $third_id = Db::table('third')->insertGetId($thirdData);
                            if ($third_id) {
                                Db::connection('dingdong')->table('user')->where('Id', $val['Id'])->update(['renew' => 1]);
                                Db::connection('dingdong')->table('wechat')->where('WechatOpenid', $val['WechatId'])->update(['renew' => 1]);
                                Db::commit();
                                $this->logger->info("third_id：" . $third_id . "执行成功!");
                            } else {
                                $this->logger->info("执行失败!");
                                Db::rollBack();
                            }
                        }


                    }
                } else {
                    #先转换user表
                    $userData['old_uid'] = $val['Id'];
                    $userData['group_id'] = 1;
                    $userData['username'] = $val['Id'] . '_' . $val['Phone'];
                    $userData['nickname'] = '';
                    $userData['old_password'] = isset($val['Pwd']) ? $val['Pwd'] : '';
                    $userData['mobile_fix'] = $val['PhoneFix'];
                    $userData['mobile'] = $val['Phone'];
                    $userData['avatar'] = '';
                    $userData['gender'] = 0;
                    $userData['is_agent'] = $val['IsAgent'];
                    $userData['jointime'] = strtotime($val['CreateTime']);
                    $userData['createtime'] = strtotime($val['CreateTime']);
                    #return ['json'=>$userData];
                    $user_id = Db::table('user')->insertGetId($userData);
                    if ($user_id) {
                        Db::connection('dingdong')->table('user')->where('Id', $val['Id'])->update(['renew' => 1]);
                        Db::commit();
                        $this->logger->info("user_id：" . $user_id . "执行成功!");
                    } else {
                        Db::rollBack();
                        $this->logger->info("执行失败!");
                    }
                }
            }
        }
        $this->logger->info("没有可执行的数据!");
    }
}
