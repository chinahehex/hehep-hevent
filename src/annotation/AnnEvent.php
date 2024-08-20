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
class AnnEvent
{
    /**
     * 事件名称
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
    public function __construct($value = null,string $alias = null)
    {
        Utils::argInjectProperty($this,func_get_args(),'alias');
    }

}
