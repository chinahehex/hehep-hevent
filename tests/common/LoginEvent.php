<?php
namespace hevent\tests\common;

use hehe\core\hevent\base\Event;

class LoginEvent extends Event
{
    /**
     * @var User
     */
    public $user;


    protected $ok = '';

    protected $listeners = [
        LoginEventListener::class,
        LoginEventListener::class . '@@addlog',
        LoginEventListener::class . '@setSession'
    ];

    public function __construct(?User $user,array $propertys = [])
    {
        $this->user = $user;
        parent::__construct($propertys);
    }

}
