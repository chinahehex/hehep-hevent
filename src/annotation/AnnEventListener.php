<?php
namespace hehe\core\hevent\annotation;
use hehe\core\hcontainer\ann\base\Annotation;
use Attribute;
use hehe\core\hevent\Utils;

/**
 * @Annotation("hehe\core\hevent\annotation\EventAnnotationProcessor")
 */
#[Annotation("hehe\core\hevent\annotation\EventAnnotationProcessor")]
#[Attribute]
class AnnEventListener
{
    /**
     * 事件类路径或事件别名
     * @var string
     */
    public $alias;

    /**
     * 构造方法
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     */
    public function __construct($value = null,string $name = null)
    {
        Utils::argInjectProperty($this,func_get_args(),'alias');
    }


}
