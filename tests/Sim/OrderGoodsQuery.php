<?php


namespace GraphQLRelay\Tests\Sim;


use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\ConnectionBuilder;
use GraphQLResolve\AbstractQuery;

class OrderGoodsQuery extends AbstractQuery
{
    public function name(): string
    {
        return  'listGoods';
    }

    public function type()
    {
        return  ConnectionBuilder::getObject(OrderGoodsType::getObject());
    }

    public function resolve(): Closure
    {
        return  function ($root, $context, $args, ResolveInfo $info) {

            return  [
                'pageInfo'  => [
                    'hasPreviousPage'   => false,
                    'hasNextPage'       => false,
                ],
                'edges'     => [
                    [
                        'id'        => 1,
                        'name'      => 'a',
                        'quantity'  => 1.0,
                        'unit'      => 'unit',
                    ],
                ],
            ];
        };
    }
}