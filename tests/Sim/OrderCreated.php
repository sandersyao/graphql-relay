<?php


namespace GraphQLRelay\Tests\Sim;


use GraphQLRelay\Types\AbstractRelayPayloadObject;

/**
 * 创建订单输出结果
 *
 * Class OrderCreated
 * @package GraphQLRelay\Tests\Sim
 */
class OrderCreated extends AbstractRelayPayloadObject
{
    /**
     * 获取输出字段
     *
     * @return mixed
     */
    public function fields()
    {
        return  OrderType::getInstance()->fields();
    }
}