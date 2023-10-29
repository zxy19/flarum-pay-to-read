<?php

namespace Xypp\PayToRead;

use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Flarum\Foundation\EventGeneratorTrait;
use DateTime;
use Flarum\Post\Post;
use Flarum\User\User;

class Payment extends AbstractModel
{
    // See https://docs.flarum.org/extend/models.html#backend-models for more information.

    protected $table = 'ptr_payment';
    public static function build($postId,$itemId, $userId,bool $paid = true)
    {
        $payment = new static();
        $payment->post_id = $postId;
        $payment->user_id = $userId;
        $payment->item_id = $itemId;
        if($paid)
            $payment->purchase_time = new DateTime();
        return $payment;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
