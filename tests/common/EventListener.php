<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;

/**
 * 登录事件监听器
 * Class LoginEventListener
 * @package hevent\tests\common
 */
class EventListener
{
    // 推送登录事件至队列,通知其他订阅系统
    public function handle(Event $event)
    {
        // 获取第一个参数
        $user = $event->params[0];
        $user->result1 =  $user->result1 . 'handle';

    }

    // 添加登录日志
    public static function addlog(Event $event)
    {
        $user = $event->params[0];
        $user->result2 =  $user->result2 . 'addlog';
    }

    // 设置会话
    public function setSession(Event $event)
    {
        $user = $event->params[0];
        $user->result3 =  $user->result3 . 'setSession';
    }
}
