# hehep-hevent

## 介绍
- hehep-hevent 是一个PHP 用户事件组件
- 基本概念:事件,事件监听器,事件注解,事件管理器

## 安装
- **gitee下载**:
```
git clone git@gitee.com:chinahehex/hehep-hevent.git
```

- **github下载**:
```
git clone git@github.com:chinahehex/hehep-hevent.git
```
- 命令安装：
```
composer require hehex/hehep-hevent
```


## 组件配置

```php
$eventConf = [
    // 预定义"login"事件别名集合
    'alias'=>[
        // 定义名称为"login"事件
        'login'=>'admin\service\LoginEvent'
    ],
    
    // 预定义"login"事件监听器集合
    'listeners'=>[
        'login'=>[
            'admin\service\LoginEvent',// 创建LoginEvent新实例,并调用其handle
            'admin\service\LoginEvent@@addlog',// 调用LoginEvent 静态方法 addlog
            'admin\service\LoginEvent@setSession'// 创建LoginEvent新实例,并调用其setSession
        ]           
    ]
];


```

## 事件管理器
- 说明
```
类名:hehe\core\hevent\EventManager
作用:预定义事件监听器，预定义事件别名，触发事件，获取事件对象，注解收集
```

- 示例代码
```php
use hehe\core\hevent\EventManager;

// 创建事件管理器
$hevent = new EventManager([]);

// 设置事件别名
$hevent->setAlias('login_event',LoginEvent::class);

// 创建事件对象
$event = $hevent->newEvent(LoginEvent::class);

// 绑定事件监听器
$hevent->listen(LoginEvent::class,LoginEventListener::class);

// 触发事件
$hevent->trigger(LoginEvent::class,['user'=>[]]);

```


## 事件
- 说明
```
基类:hehe\core\hevent\base\Event,自定义事件必须继承此类
作用:存储事件数据，定义事件监听器集合
```

- 定义事件
```php
namespace apiadmin\behaviors;

use hehe\core\hevent\base\Event;

/**
 * 登录事件
 */
class LoginEvent extends Event
{   
    /**
    * @var User
     */
    public $user;
    
    // 定义事件的监听器集合
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
```
- 事件示例代码
```php
use hehe\core\hevent\EventManager;
$hevent = new EventManager([]);

// 准备事件数据
$user = new User();

// 触发事件
$hevent->trigger(LoginEvent::class,['user'=>$user]);

```
- 定义事件别名
```php
use hehe\core\hevent\EventManager;
$hevent = new EventManager([]);

$hevent->setAlias('login_event',LoginEvent::class);

// 准备事件数据
$user = new User();

// 使用事件类触发事件方式1
$hevent->trigger(LoginEvent::class,['user'=>$user]);

// 使用别名触发事件方式2
$hevent->trigger('login_event',['user'=>$user]);

```

- 获取事件对象
```php
use hehe\core\hevent\EventManager;
use apiadmin\behaviors\LoginEvent;

$hevent = new EventManager([]);
// 默认Event事件类创建对象
$event = $hevent->newEvent();

// 指定事件类创建对象
$event = $hevent->newEvent(LoginEvent::class);

// 指定事件别名创建对象
$event = $hevent->newEvent('login_event');

// 触发事件
$hevent->trigger($event,['user'=>$user]);

```

## 事件监听器
- 说明
```
类名:事件监听器可为任意类,可默认定义handle方法
```

- 定义事件监听器
```php
namespace apiadmin\behaviors;
use hehe\core\hevent\base\Event;
use apiadmin\behaviors\LoginEvent;
// 定义登录事件处理器
class LoginEventListener
{
    // 推送登录事件至队列,通知其他订阅系统
    public function handle(LoginEvent $event)
    {
        $user = $event->user;
    }

    // 添加登录日志
    public static function addlog(LoginEvent $event)
    {
        $user = $event->user;
    }

    // 设置会话
    public function setSession(Event $event)
    {
        $user = $event->user;
    }
}

```

- 事件与监听器绑定
```php
use hehe\core\hevent\EventManager;
$hevent = new EventManager([]);

// 事件类与监听器绑定
$hevent->listen(LoginEvent::class,LoginEventListener::class);
$hevent->listen(LoginEvent::class,LoginEventListener::class . '@@addlog');
$hevent->listen(LoginEvent::class,LoginEventListener::class . '@setSession');

// 事件别名与监听器绑定
$hevent->setAlias('login_event',LoginEvent::class);
$hevent->listen('login_event',LoginEventListener::class);
$hevent->listen('login_event',LoginEventListener::class . '@@addlog');
$hevent->listen('login_event',LoginEventListener::class . '@setSession');

```

## 触发事件
```php
use hehe\core\hevent\EventManager;
$hevent = new EventManager([]);
// 绑定事件监听器
$hevent->listen(LoginEvent::class,LoginEventListener::class);

// 指定事件类触发事件
$hevent->trigger(LoginEvent::class,['user'=>$user]);

// 指定事件别名触发事件
$hevent->trigger(LoginEvent::class,['user'=>$user]);

// 事件对象触发事件
$event = $hevent->newEvent(LoginEvent::class);
$hevent->trigger($event,['user'=>$user]);

```

## 事件数据
- 设置事件数据
```php

// 事件数据
$user = new User();

// 创建事件对象时传递构造参数
$event = new LoginEvent($user);

// 创建事件对象时传递构造参数
$event = $hevent->newEvent(LoginEvent::class,$user);

// 通过setParams设置事件数据
$event = new LoginEvent();
$event->setParams(['user'=>$user,'logintime'=>date('Y-m-d H:i:s')]);

// 触发事件时提供参数
$hevent->trigger($event,['user'=>$user,'logintime'=>date('Y-m-d H:i:s')]);

```

- 获取事件数据
```php
use hehe\core\hevent\base\Event;
use apiadmin\behaviors\LoginEvent;
class LoginEventListener
{
    // 默认监听器方法
    public function handle(LoginEvent $event)
    {
        $user = $event->user;
    }

    // 添加登录日志
    public static function addlog(LoginEvent $event)
    {
        $user = $event->user;
    }

    // 设置会话
    public function setSession(Event $event)
    {
        // 获取事件属性数据
        $user = $event->user;
        
        // 获取事件非属性数据
        $logintime = $event->getParam('logintime');
        
        // 获取所有参数
        $params = $event->params;
    }
}
```

## 事件注解
- 说明
```
事件注解类:hehe\core\hevent\annotation\AnnEvent
事件注解监听器类:hehe\core\hevent\annotation\AnnEventListener
事件注解处理器类:hehe\core\hevent\annotation\EventAnnotationProcessor
```

- 注解事件类
```php
namespace apiadmin\behaviors;

use hehe\core\hevent\base\Event;
use hehe\core\hevent\annotation\AnnEvent;

/**
 * 登录事件
 * @AnnEvent("user_login")
 */
class LoginEvent extends Event
{
    public $user;
}
```

- 注解事件监听器
```php
namespace admin\service;
use hehe\core\hevent\annotation\AnnEventListener;
use hehe\core\hevent\base\Event;

class LoginEventListener
{
    /**
     * 设置会话 
     * @AnnEventListener("user_login")
     * @param Event $event
     */
    public function handle(Event $event)
    {
        // 逻辑代码
    }

    /**
     * 添加登录日志 
     * @AnnEventListener("user_login")
     * @param Event $event
     */
    public static function addlog(Event $event)
    {
        // 逻辑代码
    }

    /**
     * 设置会话 
     * @AnnEventListener("user_login")
     * @param Event $event
     */
    public function setSession(Event $event)
    {
        // 逻辑代码
    }
}

```

- 注解示例代码
```php
use hehe\core\hevent\EventManager;
use apiadmin\behaviors\LoginEvent;
$hevent = new EventManager([]);
// 测试样例
$user = new User();

// 无需主动设置监听器
$this->hevent->trigger('user_login',['user'=>$user]);
$this->hevent->trigger(LoginEvent::class,['user'=>$user]);

```


