# graphql-relay

基于 webonyx/graphql-php 的 relay 协议实现

## 动机

1. 基于 graphql-resolve 项目，实现 relay 协议;

## 状态

不稳定

## 例子

### node查询 & node接口 & global id

定义个名为order的例子查询，假设它会返回两个字段，id和sn（当前版本需要注意的是id是必填的，以后会考虑将其和实际业务做成映射关系管理起来，当然也会默认采用id这个字段，做到向后兼容）。需要注意的是，其必须引用 IsNodeQuery Trait 该特性提供对应的解析方法，为返回值增加类型标识数据来告诉 Node 接口当前要解析的类型是什么。

```php
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Traits\IsNodeQuery;
use GraphQLResolve\AbstractQuery;

class OrderQuery extends AbstractQuery
{
    use IsNodeQuery;

    /**
     * 查询名
     *
     * @return string
     */
    public function name(): string
    {
        return  'order';
    }

    /**
     * 返回类型
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public function type()
    {
        return  OrderType::getObject();
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function resolve(): Closure
    {
        return  function ($root, $args, $context, ResolveInfo $resolveInfo) {

            return  [
                'id'    => '1',
                'sn'    => 'abc',
            ];
        };
    }
}
```

声明一个Order类型作为以上查询的返回类型。需要注意的是，该类型必须实现 Node 接口（GraphQL 语言中的的接口，由 NodeInterface 类型提供）。因为node查询返回的类型为Node接口。

```php
use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;
use GraphQLResolve\Contracts\HasInterface;
use GraphQLRelay\Types\NodeInterface;

/**
 * 模拟订单类型
 *
 * Class Order
 * @package GraphQLRelay\Tests\Sim
 */
class OrderType extends AbstractObjectType
    implements HasInterface
{
    /**
     * 类型名
     *
     * @return string
     */
    public function name(): string
    {
        return  'Order';
    }

    /**
     * 获取字段
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return  function () {

            return  NodeInterface::mergeFields([
                [
                    'name'  => 'sn',
                    'type'  => Type::string(),
                ],
            ]);
        };
    }

    /**
     * 实现接口
     *
     * @return array
     */
    public function implements(): array
    {
        return  [
            NodeInterface::getObject(),
        ];
    }
}
```



还是需要先定义根节点查询并且将定义好的order查询和node查询作为其字段 (Field)，这里的node查询为本Relay包提供的。

```php
use GraphQLRelay\Queries\Node;
use GraphQLResolve\AbstractObjectType;

/**
 * 根查询
 *
 * Class Query
 * @package GraphQLRelay\Tests\Sim
 */
class Query extends AbstractObjectType
{
    /**
     * 字段
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return function () {

            return [
                OrderQuery::fetchOptions(),
                Node::fetchOptions(),
            ];
        };
    }
}
```

完成以上 Schema 结构配置和解析模拟之后，我们需要实例化Schema并使用该结构解析出对应的值：

```php
//以下这行已经解释了 global id 的算法
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
    //这个例子中省略了 Schema 实例的创建过程
    $schema,
    $query,
    $rootValue,
    $context,
    $variableValues,
    $operationName
);
$data = $result->toArray();
var_dump($data); 
```

经过以上处理将会打印结果如下：

```php
array(1) {
  ["data"]=>
  array(1) {
    ["node"]=>
    array(2) {
      ["id"]=>
      string(16) "VDNKa1pYSTZNUT09"
      ["sn"]=>
      string(3) "abc"
    }
  }
}
```

### Connection & Edge 协议 

实际的业务场景里，订单一般都会有商品，我们在这里使用连接（Connection）进行关联。
再来看看订单类型：

```php
use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;
use GraphQLResolve\Contracts\HasInterface;
use GraphQLRelay\Types\NodeInterface;

/**
 * 模拟订单类型
 *
 * Class Order
 * @package GraphQLRelay\Tests\Sim
 */
class OrderType extends AbstractObjectType
    implements HasInterface
{
    /**
     * 类型名
     *
     * @return string
     */
    public function name(): string
    {
        return  'Order';
    }

    /**
     * 获取字段
     *
     * @return \Closure|mixed
     */
    public function fields()
    {
        return  function () {

            return  NodeInterface::mergeFields([
                [
                    'name'  => 'sn',
                    'type'  => Type::string(),
                ],
                OrderGoodsQuery::fetchOptions(),
            ]);
        };
    }

    /**
     * 实现接口
     *
     * @return array
     */
    public function implements(): array
    {
        return  [
            NodeInterface::getObject(),
        ];
    }
}
```

我们在返回字段中加入了一个查询字段在类型 OrderGoodsQuery 中定义：

```php
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\ConnectionBuilder;
use GraphQLRelay\Relay;
use GraphQLResolve\AbstractQuery;

/**
 * 订单商品查询
 *
 * Class OrderGoodsQuery
 * @package GraphQLRelay\Tests\Sim
 */
class OrderGoodsQuery extends AbstractQuery
{
    /**
     * 字段名
     *
     * @return string
     */
    public function name(): string
    {
        return  'listGoods';
    }

    /**
     * 获取查询参数
     *
     * @return array
     */
    public function args()
    {
       return   Relay::mergeConnectionArgs();
    }

    /**
     * 返回类型
     *
     * @return \GraphQL\Type\Definition\ObjectType|mixed
     */
    public function type()
    {
        return  ConnectionBuilder::getObject(OrderGoodsType::getObject(), function ($nodeData) {

            return  $nodeData['id'];
        });
    }

    /**
     * 解析
     *
     * @return Closure
     */
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
```
需要注意的是以上代码中使用了 ConnectionBuilder 来创建连接（Connection）类型，考虑到各种连接的结构大同小异，所以这里没有让开发者自行创建一个对应的链接类，而是采用构建者的方式按照"模板"来创建类型。

而模板的变量只有一个：对应的节点类型，通过参数传入。我们接下来看一下节点的代码：

```php
use GraphQL\Type\Definition\Type;
use GraphQLResolve\AbstractObjectType;

/**
 * 订单商品模拟
 *
 * Class OrderGoodsType
 * @package GraphQLRelay\Tests\Sim
 */
class OrderGoodsType extends AbstractObjectType
{
    /**
     * 名称
     *
     * @return string
     */
    public function name(): string
    {
        return  'OrderGoods';
    }

    /**
     * 返回字段
     *
     * @return \Closure|mixed
     */
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
```
节点又回到了我们最简单的类型。

最后让我们测试一下Relay-Connection协议的效果：

```php
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
var_dump($data);
```
以上代码将输出：

```php
array(1) {
  ["data"]=>
  array(1) {
    ["node"]=>
    array(2) {
      ["id"]=>
      string(16) "VDNKa1pYSTZNUT09"
      ["listGoods"]=>
      array(1) {
        ["edges"]=>
        array(1) {
          [0]=>
          array(2) {
            ["cursor"]=>
            string(13) "5d0389139f092"
            ["node"]=>
            array(2) {
              ["id"]=>
              string(1) "1"
              ["name"]=>
              string(1) "a"
            }
          }
        }
      }
    }
  }
}
```

### Mutation input & clientMutationId 协议

创建订单并获得可预期的结果，通过clientMutationId对应请求的结果，我们需要继承 AbstractObjectType 抽象类来声明一个变更。
```php
use Closure;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQLRelay\Queries\AbstractRelayMutation;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * 创建订单模拟
 *
 * Class CreateOrderQuery
 * @package GraphQLRelay\Tests\Sim
 */
class CreateOrderQuery extends AbstractRelayMutation
{
    /**
     * 名字
     *
     * @return string
     */
    public function name(): string
    {
        return  'createOrder';
    }

    /**
     * 获取输出对象
     *
     * @return InputObjectType
     */
    public function getInputObject(): InputObjectType
    {
        return  OrderInput::getObject();
    }

    /**
     * 获取返回对象
     *
     * @return ObjectType
     */
    public function getPayloadObject(): ObjectType
    {
        return  OrderCreated::getObject();
    }

    /**
     * 解析
     *
     * @return Closure
     */
    public function getResolve(): Closure
    {
        return  function ($root, $args, $context, ResolveInfo $info) {

            return  [
                'id'    => 1,
                'sn'    => 'abc',
            ];
        };
    }
}
```
Relay 协议中对变更有两个主要约束：

1. 输入参数有且只有一个input，且类型为InputObject类型；
1. 如果参数中带有clientMutationId则，输出结果中必然有clientMutationId属性。

对于后者我没有做变通，目前只是强制添加的clientMutationId属性。

```php
use GraphQLRelay\Types\AbstractRelayPayloadObject;

/**
 * 创建订单输出结果
 *
 * Class OrderCreated
 * @package GraphQLRelay\Tests\Sim
 */
class OrderCreated extends AbstractRelayPayloadObject
{
    /**
     * 获取输出字段
     *
     * @return mixed
     */
    public function fields()
    {
        return  OrderType::getInstance()->fields();
    }
}
```

以上的输出类型代码中直接引用Order类型的字段，这种写法可以复用很多业务逻辑。

```php
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
```

在输入参数中，目前只有一个参数userId。

我们来看看测试效果，不过这里就省略了根变更声明：

```php
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
var_dump($data);
```

输出结果如下：

```php
array(1) {
  ["data"]=>
  array(1) {
    ["createOrder"]=>
    array(3) {
      ["id"]=>
      string(28) "VDNKa1pYSkRjbVZoZEdWa09qRT0="
      ["sn"]=>
      string(3) "abc"
      ["clientMutationId"]=>
      string(13) "5d038dc1ee27c"
    }
  }
}
```

以上三种协议均已经实现，只不过该项目还处于不稳定状态，请勿部署于生产环境。

## 接下来要做的：

1. connection 的 cursor 参数解析还是个问题，目前只是将callable参数传入 builder 来解决输出问题，但并没有解决参数问题。
1. 进一步完善测试用例；

希望本轮子能节省你们的开发时间，也欢迎大家的PR。