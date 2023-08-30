<?php

namespace Xypp\PayToRead\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Xypp\PayToRead\Payment;
use InvalidArgumentException;

class PaymentSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'payments';

    /**
     * {@inheritdoc}
     *
     * @param Payment $model
     * @throws InvalidArgumentException
     */
    protected function getDefaultAttributes($model)
    {
        if (! ($model instanceof Payment)) {
            throw new InvalidArgumentException(
                get_class($this).' can only serialize instances of '.Payment::class
            );
        }

        // See https://docs.flarum.org/extend/api.html#serializers for more information.
        $err = "";
        if($model->purchase_time == null){
            if($model->user_id == -1){
                $err = "missing";
            }else if($model->user_id == -2){
                $err = "no_enough_money";
            }else{
                $err = "internal_err";
            }
        }
        return [
            "post_id"=>$model->post_id,
            "inpost"=>$model->inpost,
            "user_id"=>$model->user_id,
            "purchase_time"=>$model->purchase_time,
            "error"=>$err
        ];
    }
}
