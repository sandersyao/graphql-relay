<?php


namespace GraphQLRelay\Tests\Sim;


use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;

class OrderGoodsType extends AbstractObjectType
{
    public function name(): string
    {
        return  'OrderGoods';
    }

    public function fields()
    {
        return  function () {
            return  [
                [
                    'name'  => 'id',
                    'type'  => Type::string(),
                ],
                [
                    'name'  => 'name',
                    'type'  => Type::string(),
                ],
                [
                    'name'  => 'quantity',
                    'type'  => Type::float(),
                ],
                [
                    'name'  => 'unit',
                    'type'  => Type::string(),
                ],
            ];
        };
    }
}