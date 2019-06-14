<?php

namespace GraphQLRelay\Tests\Sim;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\ConnectionBuilder;
use GraphQLResolve\AbstractQuery;

/**
 * 订单商品查询
 *
 * Class OrderGoodsQuery
 * @package GraphQLRelay\Tests\Sim
 */
class OrderGoodsQuery extends AbstractQuery
{
    /**
     * 字段名
     *
     * @return string
     */
    public function name(): string
    {
        return  'listGoods';
    }

    /**
     * 返回类型
     *
     * @return \GraphQL\Type\Definition\ObjectType|mixed
     */
    public function type()
    {
        return  ConnectionBuilder::getObject(OrderGoodsType::getObject());
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function resolve(): Closure
    {
        return  function ($root, $context, $args, ResolveInfo $info) {

            return  [
                'pageInfo'  => [
                    'hasPreviousPage'   => false,
                    'hasNextPage'       => false,
                ],
                'edges'     => [
                    [
                        'id'        => 1,
                        'name'      => 'a',
                        'quantity'  => 1.0,
                        'unit'      => 'unit',
                    ],
                ],
            ];
        };
    }
}