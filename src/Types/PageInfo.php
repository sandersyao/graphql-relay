<?php
namespace GraphQLRelay\Types;

use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;

/**
 * Relay PageInfo 
 */
class PageInfo extends AbstractObjectType
{
    public function fields()
    {
        return  [
            [
                'name'          => 'hasPreviousPage',
                'description'   => '是否有上一页',
                'type'          => Type::nonNull(Type::boolean()),
            ],
            [
                'name'          => 'hasNextPage',
                'description'   => '是否有下一页',
                'type'          => Type::nonNull(Type::boolean()),
            ],
        ];
    }   
}
