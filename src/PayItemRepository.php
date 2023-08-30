<?php

namespace Xypp\PayToRead;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;
use Xypp\PayToRead\PayItem;

class PayItemRepository
{
    /**
     * @return Builder
     */
    public function query()
    {
        return PayItem::query();
    }

    public function findByUserName(int $user):array
    {
        return PayItem::where("author","=",$user)->get();
    }
    public function findById(int $id):PayItem|null{
        return PayItem::where("id","=",$id)->first();
    }
}
