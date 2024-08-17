<?php
namespace Xypp\PayToRead\Render;

use DOMElement;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Database\AbstractModel;
use Flarum\Http\RequestUtil;
use Flarum\Post\CommentPost;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Guest;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Config\Util\XmlUtils;
use Xypp\PayToRead\Utils\TagPicker;
use Symfony\Contracts\Translation\TranslatorInterface;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\Payment;

class Rendering
{
    private $translator;
    protected $settings;
    public function __construct(SettingsRepositoryInterface $settings, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->settings = $settings;
    }
    public function __invoke(\s9e\TextFormatter\Renderer $renderer, mixed $context, string $xml, ?ServerRequestInterface $request)
    {
        $post = $context;
        if (!($post instanceof CommentPost)) {
            return $xml;
        }
        $document = XmlUtils::parse($xml);
        $pays = $document->getElementsByTagName("PAY");

        if (!$request) {
            $user = new Guest();
        } else {
            $user = RequestUtil::getActor($request);
        }
        $author = $post->user()->first();
        // 帖子作者和管理员可见
        $bypass = false;
        if (!$user->isGuest() && $user->isAdmin()) {
            $bypass = true;
        }
        if ($author && !$user->isGuest() && $user->id == $author->id) {
            $bypass = true;
        }
        if (!$user->isGuest() && $user->can("post.ptr-bypassPayment")) {
            $bypass = true;
        }

        $inPost = PayItem::where("post_id", "=", $post->id)->get();
        /**
         * @var array The id that user has paid for.
         */
        $paidId = array();
        if (!$user->isGuest() && !$bypass) {
            $paid = Payment::where("user_id", "=", $user->id)->where("post_id", "=", $post->id)->get();
            foreach ($paid as $paidItem)
                $paidId[$paidItem->item_id] = true;
        }

        $paidCount = Payment::selectRaw("count(id) as cnt,item_id")->where("post_id", "=", $post->id)->groupBy("item_id")->get();

        /**
         * @var DOMElement|DOMNameSpaceNode|DOMNode $pay
         */
        $cnt = $pays->count();
        for ($i = $cnt - 1; $i >= 0; $i--) {
            $pay = $pays->item($i);
            $id = $pay->getAttribute("id");
            $amount = $pay->getAttribute("amount");
            $count = $paidCount->find($id);
            $payItem = $inPost->find($id);
            if (!$payItem) {
                $newElement = $document->createElement("PTRNOTFOUND");
                $newElement->setAttribute("id", $id);
                $newElement->setAttribute("amount", $amount);
                $pay->parentNode->replaceChild($newElement, $pay);
                continue;
            }
            $newElement = null;
            if (isset($paidId[$id]) || $bypass) {
                $newElement = $document->createElement("PTRPAID");
                foreach ($pay->childNodes as $child) {
                    $newElement->appendChild($child->cloneNode(true));
                }
            } else {
                $newElement = $document->createElement("PTRUNPAID");
            }
            $newElement->setAttribute("id", $id);
            $newElement->setAttribute("amount", $amount);
            $newElement->setAttribute("paid", $count ? $count->cnt : 0);
            $pay->parentNode->replaceChild($newElement, $pay);
        }
        $document->prefix = "";
        $xml = $document->saveXML();
        $xml = preg_replace("/<\?xml[^>]*>/", "", $xml);
        return $xml;
    }
}