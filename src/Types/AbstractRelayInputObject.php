<?php


namespace GraphQLRelay\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use GraphQLRelay\Relay;
use GraphQLResolve\AbstractInputObjectType;
use GraphQLResolve\Builders\InputObjectTypeBuilder;
use Closure;

abstract class AbstractRelayInputObject extends AbstractInputObjectType
{
    /**
     * 覆盖build方法
     *
     * @return InputObjectType
     */
    protected function build(): InputObjectType
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

        return InputObjectTypeBuilder::getInstance()
            ->name($this->name())
            ->description($this->description())
            ->fields($listFields)
            ->build();
    }
}