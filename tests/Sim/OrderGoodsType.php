<?php


namespace GraphQLRelay\Tests\Sim;

use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;

/**
 * 订单商品模拟
 *
 * Class OrderGoodsType
 * @package GraphQLRelay\Tests\Sim
 */
class OrderGoodsType extends AbstractObjectType
{
    /**
     * 名称
     *
     * @return string
     */
    public function name(): string
    {
        return  'OrderGoods';
    }

    /**
     * 返回字段
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return  function () {
            return  [
                [
                    'name'  => 'id',
                    'type'  => Type::string(),
                ],
                [
                    'name'  => 'name',
                    'type'  => Type::string(),
                ],
                [
                    'name'  => 'quantity',
                    'type'  => Type::float(),
                ],
                [
                    'name'  => 'unit',
                    'type'  => Type::string(),
                ],
            ];
        };
    }
}