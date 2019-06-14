<?php


namespace GraphQLRelay\Tests;

use GraphQL\GraphQL;

/**
 * 变更测试用例
 *
 * Class MutationTest
 * @package GraphQLRelay\Tests
 */
class MutationTest extends SchemaInit
{
    /**
     * 测试clientMutationId
     */
    public function testSchemaCreate()
    {
        $id         = 1;
        $mutationId = uniqid();
        $query      = 'mutation TestMutation ($order: OrderInput!) {
createOrder (input: $order) {
    id
    sn
    clientMutationId
}}';
        $rootValue = null;
        $variableValues = [
            'order'     => [
                'clientMutationId'  => $mutationId,
                'userId'            => $id,
            ],
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
        $this->assertEquals($mutationId, $data['data']['createOrder']['clientMutationId']);
    }
}