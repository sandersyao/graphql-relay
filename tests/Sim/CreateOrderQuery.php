<?php


namespace GraphQLRelay\Tests\Sim;

use Closure;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQLRelay\Queries\AbstractRelayMutation;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * 创建订单模拟
 *
 * Class CreateOrderQuery
 * @package GraphQLRelay\Tests\Sim
 */
class CreateOrderQuery extends AbstractRelayMutation
{
    /**
     * 名字
     *
     * @return string
     */
    public function name(): string
    {
        return  'createOrder';
    }

    /**
     * 获取输出对象
     *
     * @return InputObjectType
     */
    public function getInputObject(): InputObjectType
    {
        return  OrderInput::getObject();
    }

    /**
     * 获取返回对象
     *
     * @return ObjectType
     */
    public function getPayloadObject(): ObjectType
    {
        return  OrderCreated::getObject();
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function getResolve(): Closure
    {
        return  function ($root, $args, $context, ResolveInfo $info) {

            return  [
                'id'    => 1,
                'sn'    => 'abc',
            ];
        };
    }
}