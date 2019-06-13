<?php


namespace GraphQLRelay\Tests;

use GraphQL\Type\Schema;
use GraphQLRelay\Tests\Sim\Query;
use PHPUnit\Framework\TestCase;

/**
 * 测试用例初始化
 *
 * Class SchemaInit
 * @package GraphQLRelay\Tests
 */
class SchemaInit extends TestCase
{
    /**
     * 基境schema
     *
     * @var
     */
    protected $schema;

    /**
     * 测试基境建立
     */
    public function setUp()
    {
        parent::setUp();
        $this->schema = new Schema([
            'query' => Query::getObject(),
        ]);
    }
}