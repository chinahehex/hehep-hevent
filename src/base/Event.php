<?php
namespace hehe\core\hevent\base;

/**
 * 事件基类
 *<B>说明：</B>
 *<pre>
 *  必须继承此类
 *</pre>
 */
class Event
{
    /**
     * 事件名称
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @var string
     */
    protected $name = '';

	/**
	 * 事件监听器列表
	 *<B>说明：</B>
	 *<pre>
     * 略
	 *</pre>
	 * @var array
	 */
    protected $listeners = [];

	/**
	 * 当前事件参数
	 *<B>说明：</B>
	 *<pre>
	 *  略
	 *</pre>
	 * @var array
	 */
	public $params = [];

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

    /**
     * 获取事件名称
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @return string
     */
	public function getName():string
    {
        return $this->name;
    }

    public function setName(string $name):void
    {
        $this->name = $name;
    }

    /**
     * 设置事件参数
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @param array $params
     * @return void
     */
	public function setParams(array $params):self
    {
        if (!empty($params)) {
            foreach ($params as $name => $value) {
                if (is_numeric($name)) {
                    continue;
                }

                if (property_exists($this, $name)) {
                    $this->{$name} = $value;
                }
            }
        }

        $this->params = $params;

        return $this;
    }

    /**
     * 获取事件参数项
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @param string|int $key
     * @param string $default
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * 获取所有事件参数
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @return array|null
     */
    public function getParams():?array
    {
        return $this->params;
    }

    /**
     * 添加事件监听器
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @param string|array $listener 事件监听器
     * @return void
     */
    public function listen(string $listener):void
    {
        $this->listeners[] = $listener;
    }

    public function setListeners(array $listeners):void
    {
        $this->listeners = array_merge($this->listeners,$listeners);
    }

    /**
     * 清空监听器
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     */
    public function cleanListener():void
    {
        $this->listeners = [];
    }

    /**
     * 移除监听器
     * @param string $listener 监听器器路径
     * @return void
     */
    public function removeListener(string $listener):void
    {
        $pos = array_search($listener,$this->listeners);
        if ($pos !== false) {
            unset($this->listeners[$pos]);
        }

    }

	/**
	 * 触发事件
	 *<B>说明：</B>
	 *<pre>
	 * 略
	 *</pre>
	 * @return boolean
	 */
	public function trigger()
	{
        $result = true;
        $this->listeners = array_unique($this->listeners);

		foreach ($this->listeners as $listener) {
            // 定位监听器的方法
            if (strpos($listener,'@@') !== false) {
                $listenerArr = explode("@@",$listener);
                $call = [$listenerArr[0],$listenerArr[1]];
            } else if (strpos($listener,'@') !== false) {
                $listenerArr = explode("@",$listener);
                $call = [new $listenerArr[0],$listenerArr[1]];
            } else {
                $call = [new $listener,'handle'];
            }

            $result = call_user_func_array($call,[$this]);

			if ($result === false) {
				break;
			}
		}

		return $result;
	}

}
