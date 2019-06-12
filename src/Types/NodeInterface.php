<?php

namespace GraphQLRelay\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use GraphQLResolve\AbstractInterface;
use Closure;

/**
 * Node接口
 *
 * Class NodeInterface
 * @package App\Types
 */
class NodeInterface extends AbstractInterface
{
    /**
     * 返回接口名
     *
     * @return string
     */
    public function name(): string
    {
        return  'Node';
    }

    /**
     * 获取字段
     *
     * @return array|mixed
     */
    public function fields()
    {
        return [
            [
                'name'      => Relay::ID_FIELD,
                'type'      => Type::nonNull(Type::id()),
                'resolve'   => function($value, $args, $context, ResolveInfo $info) {

                    return  self::getGlobalId($info->parentType->name, $value[$info->fieldName]);
                }
            ],
        ];
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function resolveType(): Closure
    {
        return function ($value) {

            if (isset($value[Relay::TYPE_FIELD])) {

                return $value[Relay::TYPE_FIELD];
            }

            return  null;
        };
    }

    /**
     * 获取全局唯一ID
     *
     * @param string $typeName
     * @param string $id
     * @return string
     */
    public static function getGlobalId(string $typeName, string $id): string
    {
        return  base64_encode(base64_encode($typeName . Relay::SEPARATOR_TYPE_ID . $id));
    }
}
