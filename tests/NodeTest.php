<?php


namespace GraphQLRelay\Tests;

use GraphQL\GraphQL;

class NodeTest extends SchemaInit
{
    public function testSchemaCreate()
    {
        $id     = base64_encode(base64_encode('Order:1'));
        $query  = 'query OperationQuery ($id: ID!) {
node (id: $id) {
    id
    ... on Order{
        sn
    }
}}';
        $rootValue = null;
        $variableValues = [
            'id'    => $id,
        ];
        $context = [];
        $operationName = null;

        $result = GraphQL::executeQuery(
            $this->schema,
            $query,
            $rootValue,
            $context,
            $variableValues,
            $operationName
        );
        $data = $result->toArray();
        $this->assertEquals($id, $data['data']['node']['id']);
    }
}