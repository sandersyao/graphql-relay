<?php


namespace GraphQLRelay\Tests;

use GraphQL\GraphQL;
use GraphQLRelay\Relay;

/**
 * Connection协议测试用例
 *
 * Class ConnectionTest
 * @package GraphQLRelay\Tests
 */
class ConnectionTest extends SchemaInit
{
    /**
     * 使用schema测试connection协议
     */
    public function testSchemaCreate()
    {
        $id     = base64_encode(base64_encode('Order:1'));
        $query  = 'query OperationQuery ($id: ID!) {
node (id: $id) {
    id
    ... on Order{
        listGoods {
            edges {
                cursor
                node {
                    id
                    name
                }
            }
        }
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
        $this->assertEquals(base64_encode('1'), $data['data']['node']['listGoods']['edges'][0]['cursor']);
    }

    /**
     * 测试参数还原
     *
     * @covers \GraphQLRelay\Relay::getConnectionArgs
     */
    public function testRelayArgs()
    {
        $args           = [
            'userId'    => 1,
            'first'     => 2,
            'after'     => 'Mg==',
        ];
        $argsConverted  = Relay::getConnectionArgs($args);
        $this->assertEquals('2', $argsConverted['after']);
        $this->assertEquals(2, $argsConverted['first']);
        $this->assertEquals(1, $argsConverted['userId']);
    }
}