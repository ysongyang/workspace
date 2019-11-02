<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Annotation\AuthAnnotation;
use EasySwoole\Jwt\Exception;
use EasySwoole\Jwt\Jwt;
use FastRoute\Dispatcher;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class TokenMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    protected $noAuth = false;

    public static $accessTokenPrefix = 'accessToken_';

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        /*
        $logger = new LoggerFactory($this->container);
        $log = $logger->get('log', 'default');
        $log->info('中间件验证token');
        */
        //检测权限注解类是否不验证
        $this->checkAuthAnnotation($request);
        if ($this->noAuth == false) {
            $authorization = $this->request->header('Authorization');
            if (!$authorization) {
                return $this->response->json(
                    [
                        'errorCode' => 201,
                        'errorMsg' => '非法请求，Token不能为空！',
                    ]
                );
            }
            try {
                $jwt = Jwt::getInstance();
                $result = $jwt->decode($authorization);
                $msg = '';
                switch ($result->getStatus()) {
                    case  1:
                        $msg = '验证通过！';
                        break;
                    case  2:
                        $msg = '验证失败！';
                        break;
                    case  3:
                        $msg = 'token过期！';
                        break;
                    case -1:
                        $msg = 'token无效！';
                        break;
                    case -2:
                        $msg = '非法token！';
                        break;
                }
                if (1 == $result->getStatus()) {
                    $isValidToken = self::checkAccessToken($result, $authorization);
                    if (true == $isValidToken)
                        return $handler->handle($request);
                    else
                        return $this->response->json(['errorCode' => 201, 'errorMsg' => 'token验证失败！']);
                } else {
                    return $this->response->json(
                        [
                            'errorCode' => 201,
                            'errorMsg' => $msg,
                        ]
                    );
                }
            } catch (Exception $e) {
                return $this->response->json(
                    [
                        'errorCode' => 201,
                        'errorMsg' => $e->getMessage(),
                    ]
                );
            }
        }
        return $handler->handle($request);
    }

    /**
     * 检测自定义白名单注解类
     * @param ServerRequestInterface $request
     */
    public function checkAuthAnnotation(ServerRequestInterface $request): void
    {
        $dispatched = $request->getAttribute(Dispatched::class);
        if ($dispatched->status !== Dispatcher::FOUND) {
            return;
        }
        list($class, $method) = $dispatched->handler->callback;
        $classMethodAnnotations = AnnotationCollector::getClassMethodAnnotation($class, $method);
        //如果存在自定义白名单注解类，则取noAuth属性，为true则表示该方法为白名单，不需要鉴权
        if (isset($classMethodAnnotations[AuthAnnotation::class])) {
            $authAnnotation = $classMethodAnnotations[AuthAnnotation::class];
            $this->noAuth = $authAnnotation->noAuth;
        } else {
            $this->noAuth = false;
        }
    }

    /**
     * 校验token
     * @param object $jwtResult
     * @param string $accessToken
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function checkAccessToken(object $jwtResult, string $accessToken)
    {
        $data = $jwtResult->getData();
        $exp = $jwtResult->getExp();
        $container = ApplicationContext::getContainer();
        $redis = $container->get(\Redis::class);
        if ($redis->get(self::$accessTokenPrefix . $data['uid'])) {
            $accessTokenInfo = unserialize($redis->get(self::$accessTokenPrefix . $data['uid']));
            if ($data['uid'] == $accessTokenInfo['clientInfo']['uid'] && $accessToken == $accessTokenInfo['access_token'] && $exp == $accessTokenInfo['expires_time']) {
                return true;
            }
            return false;
        }
        return false;
    }
}