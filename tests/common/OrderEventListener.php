<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;
use hevent\tests\common\LoginEvent;

/**
 * 登录事件监听器
 * Class LoginEventListener
 * @package hevent\tests\common
 */
class OrderEventListener
{
    // 推送登录事件至队列,通知其他订阅系统
    public function handle(Event $event)
    {
        // 获取第一个参数
        $order = $event->getParam('order');
        $order->result1 =  $order->result1 . 'handle';

    }

    // 添加登录日志
    public static function addlog(Event $event)
    {
        $order = $event->getParam('order');
        $order->result2 =  $order->result2 . 'addlog';
    }

    // 设置会话
    public function setSession(Event $event)
    {
        $order = $event->getParam('order');
        $order->result3 =  $order->result3 . 'setSession';
    }
}
