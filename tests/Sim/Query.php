<?php


namespace GraphQLRelay\Tests\Sim;

use GraphQLRelay\Queries\Node;
use GraphQLResolve\AbstractObjectType;

/**
 * 根查询
 *
 * Class Query
 * @package GraphQLRelay\Tests\Sim
 */
class Query extends AbstractObjectType
{
    /**
     * 字段
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return function () {

            return [
                OrderQuery::fetchOptions(),
                Node::fetchOptions(),
            ];
        };
    }
}