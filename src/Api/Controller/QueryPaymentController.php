<?php
/**
 * 查询付费状态和信息
 */
namespace Xypp\PayToRead\Api\Controller;

use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\JsonResponse;
use Xypp\PayToRead\Api\Serializer\PaymentSerializer;
use Xypp\PayToRead\PaymentRepository;
use Xypp\PayToRead\PayItemRepository;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class QueryPaymentController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    protected $paymentRepo;
    protected $payItemRepo;
    public function __construct(PaymentRepository $repo,PayItemRepository $payItemRepo)
    {
        $this->paymentRepo = $repo;
        $this->payItemRepo = $payItemRepo;
    }
    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $id = Arr::get($request->getQueryParams(), 'id', -1);
        if( $id == -1 ) {
            return new JsonResponse(["code"=>400,"status"=>"missing"]);
        }
        $userMoney = $actor->money;
        $payItem = $this->payItemRepo->findById($id);
        if(!$payItem){
            return new JsonResponse(["code"=>404,"status"=>"notfound"]);
        }
        $payment = $this->paymentRepo->findById($id,$actor->id);
        if($payment){
            return new JsonResponse(["code"=>301,"status"=>"paid"]);
        }
        
        return new JsonResponse([
            "code"=>200,
            "status"=>"unpaid",
            "user_money"=>$userMoney,
            "ammount"=>$payItem->ammount
        ]);
    }
}