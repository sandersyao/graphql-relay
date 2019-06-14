<?php


namespace GraphQLRelay\Queries;


use Closure;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQLRelay\Relay;
use GraphQLResolve\AbstractQuery;

abstract class AbstractRelayMutation extends AbstractQuery
{
    /**
     * 获取参数对象
     *
     * @return InputObjectType
     */
    abstract public function getInputObject(): InputObjectType;

    /**
     * 获取返回对象
     *
     * @return ObjectType
     */
    abstract public function getPayloadObject(): ObjectType;

    /**
     * 获取解析
     *
     * @return Closure
     */
    abstract public function getResolve(): Closure;

    public function args(): array
    {
        $input  = $this->getInputObject();

        return  [
            [
                'name'  => 'input',
                'type'  => Type::nonNull($input),
            ],
        ];
    }

    public function type()
    {
        return  $this->getPayloadObject();
    }

    public function resolve(): Closure
    {
        return  function ($root, $args, $context, ResolveInfo $info) {

            $closure    = $this->getResolve();
            $result     = $closure($root, $args, $context, $info);
            $result[Relay::MUTATION_ID] = $args['input'][Relay::MUTATION_ID];

            return  $result;
        };
    }
}