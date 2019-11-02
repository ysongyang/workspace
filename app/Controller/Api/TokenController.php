<?php
/**
 * Notes: Token类.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/10/31 14:57
 */
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Exception\FooException;
use App\Service\AdminServiceInterface;
use App\Service\UserServiceInterface;
use App\Utlis\Send;
use EasySwoole\Jwt\Exception;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use EasySwoole\Jwt\Jwt;
use Hyperf\Utils\ApplicationContext;

/**
 * @Controller(prefix="v1")
 * @package App\Controller\Api
 */
class TokenController extends AbstractController
{

    /**
     * @Value("token")
     */
    protected $tokenConfig;

    /**
     * accessToken存储前缀
     *
     * @var string
     */
    public static $accessTokenPrefix = 'accessToken_';
    public static $refreshAccessTokenPrefix = 'refreshAccessToken_';

    /**
     * @var mixed|\Redis
     */
    protected $redis;

    /**
     * @Inject
     * @var UserServiceInterface
     */
    private $userServer;

    /**
     * @Inject
     * @var AdminServiceInterface
     */
    private $adminServer;

    /**
     * TokenController constructor.
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct()
    {
        $container = ApplicationContext::getContainer();
        $this->redis = $container->get(\Redis::class);
    }

    /**
     * @PostMapping(path="token")
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createToken()
    {
        $clientInfo = $this->request->all();
        if (!$clientInfo['uid']) {
            Send::sendError(201, 'uid不能为空!');
        }
        #检测是否存在该用户
        if (isset($clientInfo['source'])) {
            if ($clientInfo['source'] == 'admin') {
                if (!$this->adminServer->getInfoByUid((int)$clientInfo['uid'])) {
                    Send::sendError(201, '非法用户!');
                }
            }
            if ($clientInfo['source'] == 'user') {
                if (!$this->userServer->getInfoByUid((int)$clientInfo['uid'])) {
                    Send::sendError(201, '非法用户!');
                }
            }
        }
        $res = $this->setAccessToken($clientInfo);
        return $res;
    }

    /**
     * 设置AccessToken
     * @param array $clientInfo
     * @return array|bool|string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setAccessToken(array $clientInfo)
    {
        try {
            //生成令牌
            //设置加密方式 支持AES 与 HMACSHA256 设置密钥默认为EasySwoole
            $obj = Jwt::getInstance()->algMethod('AES')->setSecretKey($this->tokenConfig['tokenSecretKey'])->publish();
            $obj->setExp(time() + $this->tokenConfig['tokenExp']);
            $obj->setData($clientInfo);
            $accessToken = $obj->__toString();
            $refresh_token = self::getRefreshToken((int)$clientInfo['uid']);
            $accessTokenInfo = [
                'access_token' => $accessToken,//访问令牌
                'create_time' => time(), //生成时间
                'expires_in' => $this->tokenConfig['tokenExp'], //有效期
                'expires_time' => time() + $this->tokenConfig['tokenExp'],      //过期时间时间戳
                'refresh_token' => $refresh_token,//刷新的token
                'refresh_expires_time' => time() + $this->tokenConfig['tokenExp'],      //过期时间时间戳
                'clientInfo' => $clientInfo,//用户信息
            ];
            self::saveAccessToken((int)$clientInfo['uid'], $accessTokenInfo);  //保存本次token
            self::saveRefreshToken((int)$clientInfo['uid'], $refresh_token);
            return $accessTokenInfo;
        } catch (Exception $e) {
            throw new FooException('Failed to generate JWT token...' . $e->getMessage(), 500);
        }

    }

    /**
     * 生成AccessToken
     * @param int $lenght
     * @return bool|string
     */
    protected function buildAccessToken($lenght = 32)
    {
        //生成AccessToken
        $str_pol = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
        return substr(str_shuffle($str_pol), 0, $lenght);
    }

    /**
     * 刷新用的token检测是否还有效
     * @param int $uid
     * @return bool|string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getRefreshToken(int $uid = 0)
    {

        $refreshToke = $this->redis->get(self::$refreshAccessTokenPrefix . $uid) ? $this->redis->get(self::$refreshAccessTokenPrefix . $uid) : self::buildAccessToken();
        return $refreshToke;
    }

    /**
     * 存储
     * @param int $uid
     * @param array $accessTokenInfo
     * @return bool
     */
    protected function saveAccessToken(int $uid, array $accessTokenInfo)
    {
        //存储accessToken
        return $this->redis->set(self::$accessTokenPrefix . $uid, serialize($accessTokenInfo), $this->tokenConfig['tokenExp']);
    }

    /**
     * 刷新token存储
     * @param int $uid
     * @param string $refresh_token
     * @return bool
     */
    protected function saveRefreshToken(int $uid, string $refresh_token)
    {
        //存储RefreshToken
        return $this->redis->set(self::$refreshAccessTokenPrefix . $uid, $refresh_token, $this->tokenConfig['tokenExp']);
    }
}