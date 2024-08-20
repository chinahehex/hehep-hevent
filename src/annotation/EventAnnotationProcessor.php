<?php
namespace hehe\core\hevent\annotation;

use hehe\core\hcontainer\ann\base\AnnotationProcessor;
use hehe\core\hevent\EventManager;

/**
 * 事件注解处理器
 * 用于收集事件注解的信息
 */
class EventAnnotationProcessor extends AnnotationProcessor
{

    // 事件监听器集合
    protected $listeners = [];

    // 事件别名
    protected $alias = [];

    protected $annotationHandlers = [
        'AnnEvent'=>'handleEvent',
        'AnnEventListener'=>'handleListener'
    ];

    public function handleEvent($annotation,string $class):void
    {
        $annValues = $this->getProperty($annotation,false);
        if (!empty($annValues['alias'])) {
            $this->alias[$annValues['alias']] = $class;
        } else {
            $this->alias[$class] = $class;
        }
    }

    public function handleListener($annotation,string $class,string $method):void
    {
        $annValues = $this->getProperty($annotation,false);
        $reflectionMethod = new \ReflectionMethod($class,$method);
        if ($reflectionMethod->isStatic()) {
            $listener = $class . '@@' . $method;
        } else {
            $listener = $class . '@' . $method;
        }

        $this->listeners[$annValues['alias']][] = $listener;
    }

    public function handleProcessorFinish()
    {
        $listenerList = [];
        foreach ($this->listeners as $alias=>$listeners) {
            $name = $this->alias[$alias];
            $listenerList[$name] = $listeners;
        }

        EventManager::$events = [
            'alias'=>$this->alias,
            'listeners'=>$listenerList,
        ];
    }

}
