# 付费阅读[Pay to Read]

![License](https://img.shields.io/badge/license-MIT-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/xypp/pay-to-read.svg)](https://packagist.org/packages/xypp/pay-to-read) [![Total Downloads](https://img.shields.io/packagist/dt/xypp/pay-to-read.svg)](https://packagist.org/packages/xypp/pay-to-read)

一个 [Flarum](http://flarum.org) 使用的付费阅读插件。支持了最基本的付费阅读设置操作。

## 使用说明

安装插件后，在管理员后台启用该插件，即可在Composer编辑器中看到付费可见图标。

点击图标，将插入如下BBCode：

```plain
[pay ammount=1]...这里是你设置的提示语[/pay]
```

将提示语删除，并换成你的内容，保存即可生效。


编辑帖子时，你将看到pay后面多了个ID

```plain
[pay ammount=1 id=1]
...内容
[/pay]
```

如果希望付费用户不需要重新付费，则不修改id；否则，请删除id=1这整项。

**请不要手动将ID修改为其他值，这将导致一些奇怪的问题**

> 虽然按说同一个帖子两个Block同一个ID可以做到付费一次看两个啥的emmmm
>
> 还是当作没有这回事好了。

如果发现问题，欢迎Issue留言。

> 另：本插件默认支持嵌套付费。如果不希望出现请在设置中将嵌套层数改为0

## 安装

composer:

```sh
composer require xypp/pay-to-read:"*"
```

## 更新

```sh
composer update xypp/pay-to-read:"*"
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/xypp/pay-to-read)
- [GitHub](https://github.com/xypp/pay-to-read)
