<?php


namespace GraphQLRelay\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQLRelay\Relay;
use GraphQLResolve\AbstractObjectType;
use GraphQLResolve\Builders\ObjectTypeBuilder;
use Closure;

/**
 * 变更返回抽象类型
 *
 * Class AbstractRelayPayloadObject
 * @package GraphQLRelay\Types
 */
abstract class AbstractRelayPayloadObject extends AbstractObjectType
{
    /**
     * 覆盖build方法
     *
     * @return ObjectType
     */
    protected function build(): ObjectType
    {
        $fields = $this->fields();
        $listFields = $fields instanceof Closure
                    ? $fields()
                    : $fields;
        $listFields[]   = [
            'name'          => Relay::MUTATION_ID,
            'type'          => Type::string(),
            'description'   => '变更ID',
        ];

        return  ObjectTypeBuilder::getInstance()
            ->name($this->name())
            ->description($this->description())
            ->fields($listFields)
            ->build();
    }
}