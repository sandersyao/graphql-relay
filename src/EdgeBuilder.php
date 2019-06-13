<?php


namespace GraphQLRelay;


use GraphQL\Type\Definition\Type;
use GraphQLResolve\Builders\ObjectTypeBuilder;
use GraphQL\Type\Definition\ObjectType;
use GraphQLRelay\Types\PageInfo;

class EdgeBuilder extends ObjectTypeBuilder
{
    const NAME_SUFFIX   = 'Edge';

    public function name (ObjectType $node)
    {
        parent::name($node->name . self::NAME_SUFFIX);

        return  $this;
    }

    public function fields (ObjectType $node)
    {
        parent::fields([
            [
                'name'          => 'cursor',
                'description'   => $node->name . '列表游标',
                'type'          => Type::string(),
                'resolve'       => function () {

                    return  uniqid();
                }
            ],
            [
                'name'          => 'node',
                'description'   => $node->name . '数据',
                'type'          => $node,
                'resolve'       => function ($root) {

                    return  $root;
                }
            ],
        ]);

        return  $this;
    }

    public static function getObject (ObjectType $node) {

        return  self::getInstance()->name($node)->fields($node)->build();
    }
}