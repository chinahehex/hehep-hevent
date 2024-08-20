<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;

use hehe\core\hevent\annotation\AnnEvent;

/**
 * @AnnEvent("add_news_event")
 */
class AddNewsEvent extends Event
{
    /**
     * @var User
     */
    public $user;

}
