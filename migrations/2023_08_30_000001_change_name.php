<?php

use Illuminate\Database\Schema\Blueprint;
use Flarum\Database\Migration;

return Migration::renameColumn("ptr_payment","inpost","item_id");


