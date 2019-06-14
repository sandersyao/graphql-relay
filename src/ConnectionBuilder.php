<?php


namespace GraphQLRelay;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQLRelay\Types\PageInfo;
use GraphQLResolve\Builders\ObjectTypeBuilder;

/**
 * 连接构造器
 *
 * Class ConnectionBuilder
 * @package GraphQLRelay
 */
class ConnectionBuilder extends ObjectTypeBuilder
{
    /**
     * 名称后缀
     */
    const NAME_SUFFIX   = 'Connection';

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