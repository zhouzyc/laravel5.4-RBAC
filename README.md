#laravel5.4 RBAC 但单权限管理后台

操作

composer update

数据迁移

php artisan migrate

数据填充

php artisan db:seed --class=AdminUserTableSeeder

php artisan db:seed --class=AdminNoteTableSeeder

http://127.0.0.1/Login/index.html

后台账号admin  密码 123456

文件夹划分

文件的分类有 Backstage 后台,Web前台。
Controllers,models,views  都有划分

有问题可以Q609435061
