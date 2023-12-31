<?php

namespace Xypp\PayToRead\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Xypp\PayToRead\Command\CreatePayment;
use Xypp\PayToRead\Api\Serializer\PaymentSerializer;

class CreatePaymentController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PaymentSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }


    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // See https://docs.flarum.org/extend/api.html#api-endpoints for more information.

        $actor = RequestUtil::getActor($request);
        $data = $request->getParsedBody();
        
        $model = $this->bus->dispatch(
            new CreatePayment($actor, $data)
        );
        
        return $model;
    }
}
