<?php

namespace Xypp\PayToRead;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;
use Xypp\PayToRead\Payment;

class PaymentRepository
{
    /**
     * @return Builder
     */
    public function query()
    {
        return Payment::query();
    }

    /**
     * @param int $id
     * @param User $actor
     * @return Payment
     */
    public function findOrFail($id, User $actor = null): Payment|null
    {
        return Payment::findOrFail($id);
    }

    public function findById(int $id,int $userId): Payment|null
    {
        return Payment::where("item_id","=",$id)
                    ->where("user_id","=",$userId)
                    ->first();
    }
}
