<?php


namespace GraphQLRelay\Tests\Sim;

use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;
use GraphQLResolve\Contracts\HasInterface;
use GraphQLRelay\Types\NodeInterface;

/**
 * 模拟订单类型
 *
 * Class Order
 * @package GraphQLRelay\Tests\Sim
 */
class OrderType extends AbstractObjectType
    implements HasInterface
{
    /**
     * 类型名
     *
     * @return string
     */
    public function name(): string
    {
        return  'Order';
    }

    /**
     * 获取字段
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return  function () {

            return  NodeInterface::mergeFields([
                [
                    'name'  => 'sn',
                    'type'  => Type::string(),
                ],
                OrderGoodsQuery::fetchOptions(),
            ]);
        };
    }

    /**
     * 实现接口
     *
     * @return array
     */
    public function implements(): array
    {
        return  [
            NodeInterface::getObject(),
        ];
    }
}