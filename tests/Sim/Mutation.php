<?php


namespace GraphQLRelay\Tests\Sim;


use GraphQLResolve\AbstractObjectType;

/**
 * 变更根节点
 *
 * Class Mutation
 * @package GraphQLRelay\Tests\Sim
 */
class Mutation extends AbstractObjectType
{
    /**
     * 变更列表
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return  function () {

            return  [
                CreateOrderQuery::fetchOptions(),
            ];
        };
    }
}