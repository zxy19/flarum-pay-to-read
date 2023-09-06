<?php
namespace Xypp\PayToRead\Event;
use Flarum\Post\Post;
use Flarum\User\User;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\Payment;

class PurchaseEvent{
    public $user;
    public $payment;
    public $payItem;
    public function __construct(User $user, Payment $payment, PayItem $payItem){
        $this->user = $user;
        $this->payment = $payment;
        $this->payItem = $payItem;
    }
}