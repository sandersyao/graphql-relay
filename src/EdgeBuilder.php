<?php


namespace GraphQLRelay;


use GraphQL\Type\Definition\Type;
use GraphQLResolve\Builders\ObjectTypeBuilder;
use GraphQL\Type\Definition\ObjectType;

class EdgeBuilder extends ObjectTypeBuilder
{
    /**
     * 名称后缀
     */
    const NAME_SUFFIX   = 'Edge';

    /**
     * 根据node设置名称
     *
     * @param ObjectType $node
     * @return $this
     */
    public function name (ObjectType $node)
    {
        parent::name($node->name . self::NAME_SUFFIX);

        return  $this;
    }

    /**
     * 根据node设置返回字段
     *
     * @param ObjectType $node
     * @return $this
     */
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

    /**
     * 获取对象
     *
     * @param ObjectType $node
     * @return mixed
     */
    public static function getObject (ObjectType $node): ObjectType
    {
        return  self::getInstance()->name($node)->fields($node)->build();
    }
}