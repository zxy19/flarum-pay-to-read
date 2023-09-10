<?php

namespace Xypp\PayToRead\Command;

use Flarum\User\User;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Xypp\PayToRead\Event\PurchaseEvent;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\Payment;
use Xypp\PayToRead\PaymentRepository;
use Xypp\PayToRead\PayItemRepository;

class CreatePaymentHandler
{
    protected $repository;
    protected $payItemRepository;
    protected $events;
    public function __construct(
        PaymentRepository $repository,
        PayItemRepository $payItemRepository,
        Dispatcher $events
    )
    {
        $this->repository = $repository;
        $this->payItemRepository = $payItemRepository;
        $this->events = $events;
    }

    public function handle(CreatePayment $command)
    {
        $user = $command->actor;
        $data = $command->data;
        if(!$data){
            $data = [];
        }
        $id = Arr::get($data,"id",-1);
        if($id == -1){
            return Payment::build(-1,-1,-3,false);
        }
        $payItem = $this->payItemRepository->findById($id);
        if(!$payItem){
            return Payment::build(-1,-1,-1,false);
        }
        $amount = floatval($payItem->amount);
        if($user->money >= $amount){
            User::where("id","=",$user->id)->lockForUpdate()->decrement('money',$amount);
            User::where("id","=",$payItem->author)->lockForUpdate()->increment('money',$amount);
            $payment = Payment::build(
                $payItem->post_id,
                $payItem->id,
                $user->id
            );
            $payment->save();
            $this->events->dispatch(
                new PurchaseEvent(
                    $user,
                    $payment,
                    $payItem
                )
            );
            return $payment;
        }
        return Payment::build(-1,-1,-2,false);
    }
}
