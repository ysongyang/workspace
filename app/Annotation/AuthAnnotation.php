<?php
/**
 * Notes: 白名单注解类.
 * User: ysongyang
 * Site: https://zz1.com.cn
 * Date: 2019/11/2 10:32
 */
declare(strict_types=1);

namespace App\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target("ALL")
 */
class AuthAnnotation extends AbstractAnnotation
{

    /**
     * @var boolean
     */
    public $noAuth = false;

    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->bindMainProperty('noAuth', $value);
    }
}