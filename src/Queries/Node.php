<?php
namespace GraphQLRelay\Queries;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLResolve\AbstractQuery;
use GraphQLResolve\Tools\QueryMap;
use GraphQLRelay\Relay;
use GraphQLRelay\Types\NodeInterface;
use Closure;

/**
 * Node查询
 *
 * Class Node
 * @package GraphQLRelay\Queries
 */
class Node extends AbstractQuery
{
    /**
     * 参数
     *
     * @return array
     */
    public function args(): array
    {
        return  [
            'id'    => [
                'type'          => Type::nonNull(Type::id()),
                'description'   => 'global ID',
            ],
        ];
    }

    /**
     * 返回类型
     *
     * @return \GraphQL\Type\Definition\InterfaceType
     */
    public function type()
    {
        return NodeInterface::getObject();
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function resolve(): Closure
    {
        return function ($current, $args, $context, ResolveInfo $info) {

            return  $this->resolveGlobalId($current, $args, $context, $info);
        };
    }

    /**
     * 获取解析结果
     *
     * @param $value
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed|null
     */
    protected function resolveGlobalId($value, $args, $context, ResolveInfo $info)
    {
        $idInfo         = self::fromGlobalId($args[Relay::ID_FIELD]);
        $queryClass     = $this->getClass($idInfo['typeName']);
        $queryCallback  = [$queryClass, 'nodeResolve'];

        if (is_callable($queryCallback)) {

            $args[Relay::ID_FIELD]   = $idInfo[Relay::ID_FIELD];

            return  call_user_func($queryCallback, $value, $args, $context, $info);
        }

        return  null;
    }

    /**
     * 获取类
     *
     * @param string $typeName
     * @return string
     */
    public function getClass(string $typeName)
    {
        return  QueryMap::get(lcfirst($typeName));
    }

    /**
     * 根据全局ID获取信息
     *
     * @param string $globalId
     * @return array
     */
    public static function fromGlobalId(string $globalId): array
    {
        list($typeName, $id) = explode(Relay::SEPARATOR_TYPE_ID, base64_decode(base64_decode($globalId)), 2);

        return [
            'typeName'  => $typeName,
            'id'        => $id,
        ];
    }
}
