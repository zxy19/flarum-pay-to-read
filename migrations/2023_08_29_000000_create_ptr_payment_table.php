<?php

use Illuminate\Database\Schema\Blueprint;

use Flarum\Database\Migration;
use Flarum\Post\Post;
use Flarum\User\User;

return Migration::createTable(
    'ptr_payment',
    function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('post_id');
        $table->unsignedInteger('item_id');
        $table->unsignedInteger('user_id');
        $table->dateTime('purchase_time');
    }
);

