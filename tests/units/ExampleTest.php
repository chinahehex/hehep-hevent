<?php
namespace hevent\tests\units;
use hevent\tests\common\AddOrderEvent;
use hevent\tests\common\EventListener;
use hevent\tests\common\LoginEvent;
use hevent\tests\common\LoginEventListener;
use hevent\tests\common\Order;
use hevent\tests\common\OrderEventListener;
use hevent\tests\common\User;
use hevent\tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    // 单个测试之后(每个测试方法之后调用)
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testEvent1()
    {
        // 测试样例
        $user = new User();
        $user->result1 = '1';
        $user->result2= '2';
        $user->result3= '3';

        $loginEvent = $this->hevent->newEvent(LoginEvent::class,$user);
        $this->hevent->trigger($loginEvent);

        $this->assertTrue($user->result1 === '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }

    public function testEvent2()
    {
        // 测试样例
        $user = new User();
        $user->result1 = '1';
        $user->result2= '2';
        $user->result3= '3';

        $loginEvent = new LoginEvent($user);
        $this->hevent->trigger($loginEvent);

        $this->assertTrue($user->result1 === '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }

    public function testEvent3()
    {
        // 测试样例
        $user = new User();
        $user->result1 = '1';
        $user->result2= '2';
        $user->result3= '3';

        $this->hevent->listen('login_event',EventListener::class);
        $this->hevent->listen('login_event',EventListener::class . '@@addlog');
        $this->hevent->listen('login_event',EventListener::class . '@setSession');
        $this->hevent->trigger('login_event',[$user]);

        $this->assertTrue($user->result1 === '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }

    public function testEvent4()
    {
        // 测试样例
        $user = new User();
        $user->result1 = '1';
        $user->result2= '2';
        $user->result3= '3';

        $this->hevent->setAlias('login_event',LoginEvent::class);
        $this->hevent->listen('login_event',LoginEventListener::class);
        $this->hevent->listen('login_event',LoginEventListener::class . '@@addlog');
        $this->hevent->listen('login_event',LoginEventListener::class . '@setSession');
        $this->hevent->trigger('login_event',['user'=>$user,'ok'=>'ffff']);

        $this->assertTrue($user->result1 === '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }



    public function testEvent5()
    {
        // 测试样例
        $user = new User();
        $user->result1 = '1';
        $user->result2= '2';
        $user->result3= '3';

        $loginEvent = $this->hevent->newEvent(LoginEvent::class,$user);
        $loginEvent->removeListener(LoginEventListener::class);

        $this->hevent->trigger($loginEvent);

        $this->assertTrue($user->result1 !== '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }

    public function testEvent6()
    {
        // 测试样例
        $order = new Order();
        $order->result1 = '1';
        $order->result2= '2';
        $order->result3= '3';

        $this->hevent->trigger(AddOrderEvent::class,['order'=>$order]);

        $this->assertTrue($order->result1 === '1handle');
        $this->assertTrue($order->result2 === '2addlog');
        $this->assertTrue($order->result3 === '3setSession');
    }

    public function testEvent7()
    {
        // 测试样例
        $order = new Order();
        $order->result1 = '1';
        $order->result2= '2';
        $order->result3= '3';

        $this->hevent->listen('order_event',OrderEventListener::class);
        $this->hevent->listen('order_event',OrderEventListener::class . '@@addlog');
        $this->hevent->listen('order_event',OrderEventListener::class . '@setSession');
        $this->hevent->trigger('order_event',['order'=>$order]);

        $this->assertTrue($order->result1 === '1handle');
        $this->assertTrue($order->result2 === '2addlog');
        $this->assertTrue($order->result3 === '3setSession');
    }

    public function testEvent8()
    {
        // 测试样例
        $user = new User();
        $user->result1 = '1';
        $user->result2= '2';
        $user->result3= '3';

        $this->hevent->setAlias('login_event',LoginEvent::class);
        $this->hevent->listen('login_event',LoginEventListener::class);
        $this->hevent->listen('login_event',LoginEventListener::class . '@@addlog');
        $this->hevent->listen('login_event',LoginEventListener::class . '@setSession');
        $this->hevent->trigger(LoginEvent::class,['user'=>$user,'ok'=>'ffff']);

        $this->assertTrue($user->result1 === '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }


}
