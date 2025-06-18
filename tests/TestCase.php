<?php
namespace hevent\tests;

use hehe\core\hevent\EventManager;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var EventManager
     */
    protected $hevent;

    // 单个测试之前(每个测试方法之前调用)
    protected function setUp():void
    {
        $ev_config = [];

        $this->hevent = new EventManager($ev_config);
    }

    // 单个测试之后(每个测试方法之后调用)
    protected function tearDown():void
    {

    }

    // 整个测试类之前
    public static function setUpBeforeClass():void
    {

    }

    // 整个测试类之前
    public static function tearDownAfterClass():void
    {

    }


}
