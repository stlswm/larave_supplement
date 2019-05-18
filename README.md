# laravel supplement

#### 介绍
laravel功能补充

laravel 模型助手补全laravel缺少的注释

路由生成器：自动生成laravel路由

用法：

```
<?php
use stlswm\LaravelSupplement\Router\Generator;
Generator::start('控制器根目录','输出配置文件目录');
```

用法在函数注释里增加@router
 
格式：@router+空格+请求方法+空格+路由

举例：
```
/**
 * 日志详细信息
 *
 * @param string $id
 *
 * @return Factory|View|string
 * @Author wm
 * @router any /log/logger/info/{id}
 *
 */
``` 
 
生成的api文件内容如下

```
<?php

use Illuminate\Support\Facades\Route;

Route::any("/api/log/logger/info/{id}","Log\\LoggerController@info");
```

#### 软件架构
php7.2


#### 安装教程

```
composer require stlswm/laravel-supplement
```

#### 参与贡献

1. Fork 本仓库
2. 新建 Feat_xxx 分支
3. 提交代码
4. 新建 Pull Request


#### 码云特技

1. 使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2. 码云官方博客 [blog.gitee.com](https://blog.gitee.com)
3. 你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解码云上的优秀开源项目
4. [GVP](https://gitee.com/gvp) 全称是码云最有价值开源项目，是码云综合评定出的优秀开源项目
5. 码云官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6. 码云封面人物是一档用来展示码云会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)