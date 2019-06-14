<?php
namespace GraphQLRelay;

use GraphQL\Type\Definition\Type;
use LogicException;

/**
 * Relay逻辑
 *
 * Class Relay
 * @package GraphQLRelay
 */
class Relay
{
    /**
     * 类型和ID之间的分隔符
     */
    const SEPARATOR_TYPE_ID             = ':';

    /**
     * ID参数
     */
    const ID_FIELD                      = 'id';

    /**
     * 类型额外数据键
     */
    const TYPE_FIELD                    = '__type';

    /**
     * 变更ID
     */
    const MUTATION_ID                   = 'clientMutationId';

    /**
     * 合并参数
     *
     * @param array $args
     * @return array
     */
    public static function mergeConnectionArgs(array $args = []): array
    {
        $argsConnection = [
            'first'     => [
                'name'          => 'first',
                'description'   => '从头获取条数',
                'type'          => Type::int(),
            ],
            'after'     => [
                'name'          => 'after',
                'description'   => '起始游标位置',
                'type'          => Type::string(),
            ],
            'last'      => [
                'name'          => 'last',
                'description'   => '从末尾获取条数',
                'type'          => Type::int(),
            ],
            'before'    => [
                'name'          => 'before',
                'description'   => '末尾游标位置',
                'type'          => Type::string(),
            ],
        ];

        foreach ($args as $name => $argument) {

            if (
                is_string($name)
                && isset($argsConnection[$name])
            ) {
                if ($argument['type'] != $argsConnection[$name]['type']) {
                    throw new LogicException('Unexpect argument type for connection type');
                } else {

                    unset($argsConnection[$name]);
                }
            }
        }

        return  array_merge($args, $argsConnection);
    }
}
