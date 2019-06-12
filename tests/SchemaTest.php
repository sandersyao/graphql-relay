<?php


namespace GraphQLRelay\Tests;

use GraphQL\Type\Schema;
use GraphQLRelay\Tests\Sim\Query;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    protected $schema;

    public function setUp()
    {
        parent::setUp();
        $this->schema = new Schema([
            'query' => Query::getObject(),
        ]);
    }
}