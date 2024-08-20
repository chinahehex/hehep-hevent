<?php
namespace hehe\core\hevent;

class Utils
{
    /**
     * 创建实例
     * @param string $class
     * @param array $params
     * @return mixed
     */
    public static function newInstance(string $class, array $params = [])
    {
        $parameters = static::getConstructor($class);
        $my_args = [];
        $propertys = [];
        $hasPropertys = false;

        // 分离属性
        foreach ($params as $key => $value) {
            if (is_numeric($key)) {
                $my_args[$key] = $value;
            } else if (is_string($key)) {
                $propertys[$key] = $value;
            }
        }

        $args = [];
        foreach ($parameters as $index => $param) {
            list($name, $defval) = $param;
            if ($name === 'propertys') {
                $args[$index] = $propertys;
                $hasPropertys = true;
            } else {
                if (isset($my_args[$index])) {
                    $args[$index] = $my_args[$index];
                } else {
                    $args[$index] = $defval;
                }
            }
        }

        $object = new $class(...$args);
        if (!$hasPropertys && !empty($propertys)) {
            // 通过设置的方式注入
            foreach ($propertys as $name => $value) {
                $func = 'set' . ucfirst($name);
                if (method_exists($object, $func)) {
                    $object->{$func}($value);
                }
            }
        }

        return $object;
    }

    public static function getConstructor(string $class)
    {
        $parameters = (new \ReflectionClass($class))->getConstructor()->getParameters();
        $args = [];
        foreach ($parameters as $index => $param) {
            $name = $param->getName();
            $defval = null;
            if ($param->isDefaultValueAvailable()) {
                $defval = $param->getDefaultValue();
            }

            $args[$index] = [$name, $defval];
        }

        return $args;
    }

    /**
     * 注解构造参数转关联数组
     * @param array $args 构造参数
     * @param string $firstArgName 第一个构造参数对应的属性名
     * @return array
     * @throws \ReflectionException
     */
    public static function argToDict(string $class,array $args = [],string $firstArgName = ''):array
    {
        // php 注解
        $values = [];
        if (!empty($args)) {
            if (is_string($args[0]) || is_null($args[0])) {
                $arg_params = (new \ReflectionClass(get_class($class)))->getConstructor()->getParameters();
                foreach ($arg_params as $index => $param) {
                    $name = $param->getName();
                    $value = null;
                    if (isset($args[$index])) {
                        $value = $args[$index];
                    } else {
                        if ($param->isDefaultValueAvailable()) {
                            $value = $param->getDefaultValue();
                        }
                    }

                    if (!is_null($value)) {
                        $values[$name] = $value;
                    }
                }
            } else if (is_array($args[0])) {
                $values = $args[0];
            }
        }

        $value_dict = [];
        foreach ($values as $name => $value) {
            if (is_null($value)) {
                continue;
            }

            if ($name == 'value' && $firstArgName != '') {
                $value_dict[$firstArgName] = $value;
            } else {
                $value_dict[$name] = $value;
            }
        }


        return $value_dict;
    }

    /**
     * 构造参数注入注解属性
     * @param array $annotation 注解类
     * @param array $args 构造参数
     * @param string $firstArgName 第一个构造参数对应的属性名
     */
    public static function argInjectProperty($annotation,array $args = [],string $firstArgName = ''):void
    {
        $values = self::argToDict(get_class($annotation),$args,$firstArgName);

        foreach ($values as $name=>$value) {
            $annotation->{$name} = $value;
        }
    }



}
