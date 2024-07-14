<?php

use Illuminate\Database\Schema\Blueprint;
use Flarum\Database\Migration;

return Migration::addColumns('posts', [
    "content_with_payitem" => ["mediumtext", "null"]
]);
