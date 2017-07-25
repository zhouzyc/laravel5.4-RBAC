#laravel5.4 RBAC 但单权限管理后台

操作

composer update

数据迁移

php artisan migrate
数据填充

php artisan db:seed --class=AdminUserTableSeeder

php artisan db:seed --class=AdminNoteTableSeeder

后台账号admin  密码 123456

有问题可以Q609435061