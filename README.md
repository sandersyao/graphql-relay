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

### 接下来的工作

以上已经完成了 Relay 协议中对 node 全局对象查询方面的功能，接下来将继续另外两项功能的开发：

1. Connection & Edge 协议
1. Mutation ClientInputId 协议

希望本轮子能节省你们的开发时间。