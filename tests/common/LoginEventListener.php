<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;
use hevent\tests\common\LoginEvent;

/**
 * 登录事件监听器
 * Class LoginEventListener
 * @package hevent\tests\common
 */
class LoginEventListener
{
    // 推送登录事件至队列,通知其他订阅系统
    public function handle(LoginEvent $event)
    {
        // 获取第一个参数
        $user = $event->user;
        $user->result1 =  $user->result1 . 'handle';

    }

    // 添加登录日志
    public static function addlog(LoginEvent $event)
    {
        $user = $event->user;
        $user->result2 =  $user->result2 . 'addlog';
    }

    // 设置会话
    public function setSession(LoginEvent $event)
    {
        $user = $event->user;
        $user->result3 =  $user->result3 . 'setSession';
    }
}
