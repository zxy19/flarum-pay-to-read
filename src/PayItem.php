<?php

namespace Xypp\PayToRead;

use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Flarum\Foundation\EventGeneratorTrait;

class PayItem extends AbstractModel
{
    // See https://docs.flarum.org/extend/models.html#backend-models for more information.

    protected $table = 'ptr_payitems';
    public static function build($postId, $userId, $ammount)
    {
        $pay = new static();
        $pay->post_id = $postId;
        $pay->author = $userId;
        $pay->ammount = $ammount;
        return $pay;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id', "author");
    }
    public function post()
    {
        return $this->belongsTo(Post::class, "id", 'post_id');
    }
}
