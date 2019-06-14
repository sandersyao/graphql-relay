<?php


namespace GraphQLRelay\Tests\Sim;


use GraphQL\Type\Definition\Type;
use GraphQLRelay\Types\AbstractRelayInputObject;

class OrderInput extends AbstractRelayInputObject
{
    public function fields()
    {
        return  function () {
            return  [
                [
                    'name'  => 'userId',
                    'type'  => Type::string(),
                ],
            ];
        };
    }
}