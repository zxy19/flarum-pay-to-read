<?php

use Flarum\Post\Post;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Flarum\Database\Migration;

return [
    'up' => function (Builder $schema) {
        $db = $schema->getConnection();
        $db->table('posts')->whereNotNull("content_with_payitem")
            ->where("content_with_payitem", "!=", "")
            ->update(['content' => $db->raw("CONCAT('!old!',`content_with_payitem`)")]);

        $schema->table('posts', function (Blueprint $table) {
            $table->dropColumn('content_with_payitem');
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('posts', function (Blueprint $table) {
            $table->text('content_with_payitem')->nullable();
        });
    }
];
;
