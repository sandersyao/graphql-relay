<?php

namespace GraphQLRelay\Tests\Sim;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\ConnectionBuilder;
use GraphQLRelay\Relay;
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
     * 获取查询参数
     *
     * @return array
     */
    public function args()
    {
       return   Relay::mergeConnectionArgs();
    }

    /**
     * 返回类型
     *
     * @return \GraphQL\Type\Definition\ObjectType|mixed
     */
    public function type()
    {
        return  ConnectionBuilder::getObject(OrderGoodsType::getObject(), function ($nodeData) {

            return  $nodeData['id'];
        });
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function resolve(): Closure
    {
        return  function ($root, $context, $args, ResolveInfo $info) {

            //$args   = Relay::getConnectionArgs($args);

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