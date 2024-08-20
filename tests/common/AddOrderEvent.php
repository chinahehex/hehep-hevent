<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;

class AddOrderEvent extends Event
{
    /**
     * @var Order
     */
    public $order;

    protected $listeners = [
        AddOrderEventListener::class,
        AddOrderEventListener::class . '@@addlog',
        AddOrderEventListener::class . '@setSession'
    ];

}
