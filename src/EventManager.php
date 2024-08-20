<?php
namespace hehe\core\hevent;

use hehe\core\hevent\base\Event;

/**
 *　用户事件管理器
 *<B>说明：</B>
 *<pre>
 *  略
 *</pre>
 */
class EventManager
{
    /**
     * 注解事件定义集合
     *<B>说明：</B>
     *<pre>
     *  格式['alias'=>[事件别名,事件类路径],'listeners'=>[事件别名或事件类路径,事件监听器集合]]
     *</pre>
     * @var array
     */
    public static $events = [];

    /**
     * 事件别名集合
     * @var array<事件别名,事件类路径>
     */
    protected $alias = [];

    /**
     * 事件监听器集合
     * @var array<事件别名或事件类路径,事件监听器集合>
     */
    protected $listeners = [];

	/**
	 * 构造方法
	 *<B>说明：</B>
	 *<pre>
	 *  略
	 *</pre>
	 * @param array $attrs
	 */
	public function __construct(array $attrs = [])
	{
		if (!empty($attrs)) {
			foreach ($attrs as $name => $value) {
				$this->{$name} = $value;
			}
		}
	}

	public function setAlias(string $alias,string $eventClass):void
	{
		$this->alias[$alias] = $eventClass;
	}

    /**
     * 事件别名转换为事件类路径
     * @param string $alias
     * @return string
     */
	protected function aliasToClass(string $alias):string
	{
		if (isset($this->alias[$alias])) {
			return $this->alias[$alias];
		} else if (isset(static::$events['alias'][$alias])){
		    return static::$events['alias'][$alias];
        }  else {
            return $alias;
        }
    }

    public function listen(string $event,string $listener):void
    {
        $event = $this->aliasToClass($event);

        $this->listeners[$event][] = $listener;
    }

    /**
     * 是否存在某个事件监听器
     * @param string $event
     * @return bool
     */
    public function hasListener(string $event):bool
    {
        $event = $this->aliasToClass($event);

        if (isset($this->listeners[$event])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 移除某个事件监听器
     * @param string $event
     * @param string $listener
     */
    public function removeListener(string $event,string $listener = ''):void
    {
        $event = $this->aliasToClass($event);

        if ($listener === '') {
            unset($this->listeners[$event]);
        } else {
            $pos = array_search($listener,$this->listeners[$event]);
            if ($pos !== false) {
                unset($this->listeners[$event][$pos]);
            }
        }
    }

    /**
     * 获取事件新对象
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @param string $event 事件标识
     * @param array $args 构造参数
     * @return Event
     */
	public function newEvent(string $event = '',...$args):Event
    {
        $event = $this->aliasToClass($event);

        $ev_attrs = [];
        if (!empty($args)) {
            $ev_attrs = $args;
        }

        $listeners = [];
        if (isset(static::$events['listeners'][$event])) {
            $listeners = array_merge($listeners,static::$events['listeners'][$event]);
        }

        if (isset($this->listeners[$event])) {
            $listeners = array_merge($listeners,$this->listeners[$event]);
        }

        $ev_attrs['name'] = $event;
        $eventClass = Event::class;
        if (isset($ev_attrs['class'])) {
            $eventClass = $ev_attrs['class'];
            unset($ev_attrs['class']);
        } else if (strpos($event,'\\') !== false) {
            $eventClass = $event;
        }

        /** @var Event $ev */
        $ev = Utils::newInstance($eventClass,$ev_attrs);

        $ev->setListeners($listeners);

        return $ev;
    }

    /**
     * 触发事件
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @param string|Event $event 事件名称
     * @param array|mixed $params 事件数据
     * @return void
     */
    public function trigger($event,array $params = []):void
    {
        if ($event instanceof Event) {
            $ev = $event;
        } else {
            $ev = $this->newEvent($event);
        }

        if (empty($ev)) {
            throw new \Exception('event is empty');
        }

        $ev->setParams($params);
        $ev->trigger();
    }

}
