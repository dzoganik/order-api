# order-api

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
DB changes are automatically rollbacked.
