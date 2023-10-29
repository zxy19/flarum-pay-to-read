<?php

use Illuminate\Database\Schema\Blueprint;
use Flarum\Database\Migration;

return Migration::createTable(
    'ptr_payitems',
    function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('post_id');
        $table->unsignedInteger('author');
        $table->float('amount');
    }
);
