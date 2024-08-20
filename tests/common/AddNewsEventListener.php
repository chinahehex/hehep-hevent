<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;
use hevent\tests\common\LoginEvent;
use hehe\core\hevent\annotation\AnnEventListener;
/**
 * 登录事件监听器
 */
class AddNewsEventListener
{
    /**
     *
     * @AnnEventListener("add_news_event")
     */
    public function handle(AddNewsEvent $event)
    {
        // 获取第一个参数
        $user = $event->user;
        $user->result1 =  $user->result1 . 'handle';

    }

    // 添加登录日志
    /**
     *
     * @AnnEventListener("add_news_event")
     */
    public static function addlog(AddNewsEvent $event)
    {
        $user = $event->user;
        $user->result2 =  $user->result2 . 'addlog';
    }

    // 设置会话
    /**
     *
     * @AnnEventListener("add_news_event")
     */
    public function setSession(AddNewsEvent $event)
    {
        $user = $event->user;
        $user->result3 =  $user->result3 . 'setSession';
    }
}
