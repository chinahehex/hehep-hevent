<?php
namespace hevent\tests\units;
use hehe\core\hcontainer\ContainerManager;
use hehe\core\hevent\EventManager;
use hevent\tests\common\AddNewsEvent;
use hevent\tests\common\AddOrderEvent;
use hevent\tests\common\EventListener;
use hevent\tests\common\LoginEvent;
use hevent\tests\common\LoginEventListener;
use hevent\tests\common\Order;
use hevent\tests\common\OrderEventListener;
use hevent\tests\common\User;
use hevent\tests\TestCase;

class AnnTest extends TestCase
{
    /**
     * @var \hehe\core\hcontainer\ContainerManager
     */
    protected $hcontainer;

    protected function setUp()
    {
        parent::setUp();
        $this->hcontainer = new ContainerManager();
        $this->hcontainer->addScanRule(AddNewsEvent::class,EventManager::class)
            ->startScan();
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

        $loginEvent = $this->hevent->newEvent(AddNewsEvent::class);
        $this->hevent->trigger($loginEvent,['user'=>$user]);

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

        $this->hevent->trigger('add_news_event',['user'=>$user]);

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

        $this->hevent->trigger(AddNewsEvent::class,['user'=>$user]);

        $this->assertTrue($user->result1 === '1handle');
        $this->assertTrue($user->result2 === '2addlog');
        $this->assertTrue($user->result3 === '3setSession');
    }



}
