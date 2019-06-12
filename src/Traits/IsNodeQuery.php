<?php
namespace GraphQLRelay\Traits;

use GraphQL\Type\Definition\ResolveInfo;
use Closure;
use GraphQLRelay\Relay;

/**
 * Node查询
 */
trait IsNodeQuery
{
    /**
     * 解析
     *
     * @return Closure
     */
    abstract public function resolve(): Closure;

    /**
     * 获取实例
     *
     * @return mixed
     */
    abstract static public function getInstance();

    /**
     * 节点解析
     *
     * @param $value
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed
     */
    static public function nodeResolve($value, $args, $context, ResolveInfo $info)
    {
        $callback   = static::getInstance()->resolve();
        $result     = $callback($value, $args, $context, $info);
        $result[Relay::TYPE_FIELD]  = static::getInstance()->type();

        return      $result;
    }
}
