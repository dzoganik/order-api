# Order-api
This is a simple Symfony application to create and update orders (delivery time) using REST API.

## Used Technologies
- Symfony 6.0.9
- PHP 8.1.5
- MySQL 8
- Nginx
- Docker
- Adminer

## Request Examples
### Creating new order
#### Request
```
POST /orders HTTP/1.1
Host: localhost:8080
Content-Type: application/json
Content-Length: 438

{
    "partnerId": "88884334",
    "orderId": "100007",
    "deliveryDate": "2022-08-01",
    "orderValue": "150.00",
    "orderItems": [
        {
            "productId": "asdff",
            "title": "titttle",
            "price": "140.00",
            "quantity": "2"
        },
        {
            "productId": "eeee",
            "title": "titttle2",
            "price": "150.20",
            "quantity": "2.5"
        }
    ]
}
```
#### Response Body
```
{
    "orderId": "100007",
    "partnerId": "88884334"
}
```
### Updating delivery time
#### Request
```
PATCH /orders/100005 HTTP/1.1
Host: localhost:8080
Partner-ID: 888843
Content-Type: application/json
Content-Length: 30

{"deliveryDate": "2022-09-03"}
```
#### Response Body
```
{
    "orderId": "100005",
    "partnerId": "888843",
    "deliveryDate": "2022-09-03"
}
```

## Testing
Create a test database:
- Add .env.test.local file with:
```
DATABASE_URL="mysql://root:${MYSQL_ROOT_PASSWORD}@mysql8:3306/${MYSQL_DATABASE}?serverVersion=8&charset=utf8mb4"
```
- Run:
```
bin/console --env=test doctrine:database:create
bin/console --env=test doctrine:schema:create
```
- Add an order to the test database:
```
bin/console --env=test doctrine:fixtures:load
```
DB changes are automatically rollbacked.
