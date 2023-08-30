<?php

/*
 * This file is part of xypp/pay-to-read.
 *
 * Copyright (c) 2023 小鱼飘飘.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Xypp\PayToRead;

use Flarum\Extend;
use Flarum\Extend\Model;
use Flarum\Post\Event\Saving as EventPostSaving;
use Flarum\Post\Event\Deleting as EventPostDeleting;
use Flarum\Post\Post;
use s9e\TextFormatter\Configurator;
use Xypp\PayToRead\Serializer\PostRender;
use Flarum\Api\Serializer\PostSerializer;
return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),
    (new Extend\Event())
        ->listen(EventPostSaving::class, Listener\PostSaving::class)
        ->listen(EventPostDeleting::class, Listener\PostDeleting::class),
    (new Extend\Model(Payment::class))
        ->belongsTo("user",User::class,"id","user_id")
        ->belongsTo("post",Post::class,"id","post_id")
        ->belongsTo("payItem",PayItem::class,"id","item_id"),
    (new Extend\Model(PayItem::class))
        ->hasMany("payItem",PayItem::class,"item_id","id")
        ->belongsTo("user",User::class,"id","user_id")
        ->belongsTo("post",Post::class,"id","post_id"),
    (new Extend\Model(Post::class))
        ->hasMany("payment",Payment::class,"user_id","id"),
    (new Extend\Formatter)
        ->configure(function (Configurator $config) {
            $config->BBCodes->addCustom(
                '[pay ammount={TEXT1} id={TEXT2}]{TEXT}[/pay]',
                '<pay-to-read data-ammount={TEXT1} data-id={TEXT2}>{TEXT}</pay-to-read>'
            );
        }),
    (new Extend\Routes('api'))
        ->post('/pay-to-read/payment/pay', 'ptr.payment.create', Api\Controller\CreatePaymentController::class)
        ->get('/pay-to-read/payment/', 'ptr.payment.get', Api\Controller\QueryPaymentController::class),
    (new Extend\ApiSerializer(PostSerializer::class))
        ->attributes(PostRender::class),
    (new Extend\Settings())
        ->default('xypp.ptr.max-stack', 3),
    
];