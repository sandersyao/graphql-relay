<?php


namespace GraphQLRelay;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQLRelay\Types\PageInfo;
use GraphQLResolve\Builders\ObjectTypeBuilder;

class ConnectionBuilder extends ObjectTypeBuilder
{
    const NAME_SUFFIX   = 'Connection';

    public function name (ObjectType $node)
    {
        parent::name($node->name . self::NAME_SUFFIX);

        return  $this;
    }

    public function fields (ObjectType $node)
    {
        parent::fields([
            [
                'name'          => 'pageInfo',
                'description'   => $node->name . '列表分页信息',
                'type'          => PageInfo::getObject(),
            ],
            [
                'name'          => 'edges',
                'description'   => $node->name . '列表数据',
                'type'          => Type::listOf(EdgeBuilder::getObject($node)),
            ],
        ]);

        return  $this;
    }

    public static function getObject (ObjectType $node) {

        return  self::getInstance()->name($node)->fields($node)->build();
    }
}