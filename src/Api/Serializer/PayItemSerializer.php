<?php

namespace Xypp\PayToRead\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Xypp\PayToRead\PayItem;
use InvalidArgumentException;

class PayItemSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'pay-items';

    /**
     * {@inheritdoc}
     *
     * @param PayItem $model
     * @throws InvalidArgumentException
     */
    protected function getDefaultAttributes($model)
    {
        if (! ($model instanceof PayItem)) {
            throw new InvalidArgumentException(
                get_class($this).' can only serialize instances of '.PayItem::class
            );
        }

        // See https://docs.flarum.org/extend/api.html#serializers for more information.

        return [
            // ...
        ];
    }
}
