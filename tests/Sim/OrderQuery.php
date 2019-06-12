<?php


namespace GraphQLRelay\Tests\Sim;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Traits\IsNodeQuery;
use GraphQLResolve\AbstractQuery;

class OrderQuery extends AbstractQuery
{
    use IsNodeQuery;

    /**
     * 查询名
     *
     * @return string
     */
    public function name(): string
    {
        return  'order';
    }

    /**
     * 返回类型
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public function type()
    {
        return  OrderType::getObject();
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function resolve(): Closure
    {
        return  function ($root, $args, $context, ResolveInfo $resolveInfo) {

            return  [
                'id'    => '1',
                'sn'    => 'abc',
            ];
        };
    }
}