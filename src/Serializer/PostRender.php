<?php
namespace Xypp\PayToRead\Serializer;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Database\AbstractModel;
use Flarum\Settings\SettingsRepositoryInterface;
use Xypp\PayToRead\Utils\TagPicker;
use Symfony\Contracts\Translation\TranslatorInterface;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\Payment;
class PostRender
{
    private $translator;
    protected $settings;
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->translator = resolve(TranslatorInterface::class);
        $this->settings = $settings;
    }
    public function __invoke(BasicPostSerializer $serializer, AbstractModel $post, array $attributes){
        $maxStack = intval($this->settings->get('xypp.ptr.max-stack',3));
        if (isset($attributes["contentHtml"])) {
            $user=$serializer->getActor();
            $author = $post->user()->first();
            // 帖子作者和管理员可见
            $bypass = false;
            if (!$user->isGuest() && $user->isAdmin()) {
                $bypass = true;
            }
            if($author && !$user->isGuest() && $user->id == $author->id){
                $bypass = true;
            }
            if(!$user->isGuest() && $user->can("post.ptr-bypassPayment")){
                $bypass = true;
            }
            $content = $attributes["contentHtml"];
            [$tags,$_]=TagPicker::TagPickerHTML($content);
            $paidId = array();
            if (!$user->isGuest() && !$bypass) {
                $paid = Payment::where("user_id","=",$user->id)->where("post_id","=",$post->id)->get();
                foreach($paid as $paidItem){
                    $paidId[$paidItem->item_id]=true;
                }
            }
            $earlist = -1;
            $inPostId = [];
            foreach(PayItem::where("post_id","=",$post->id)->get() as $item){
                $inPostId[$item->id]=true;
            }
            for($i = count($tags) - 1;$i >= 0;$i -- ){
                $exceedMaxStack = ($tags[$i]['params']['depth'] > $maxStack);
                if(!isset($inPostId[$tags[$i]['params']['id']])){
                    [$st,$_]=$tags[$i]['start_tag'];
                    [$_,$ed]=$tags[$i]['end_tag'];
                    if($st < $earlist || $earlist == -1){
                        $earlist = $st;
                    }else continue;
                    $content=substr_replace($content,
                    "<div class=\"ptr-block ptr-render ptr-notfound\" data-id=\""
                    .$tags[$i]['params']['id']
                    ."\"></div>"
                    ,$st,$ed-$st+1);
                }
                elseif(!isset($paidId[$tags[$i]['params']['id']]) && !$bypass && !$exceedMaxStack){
                    [$st,$_]=$tags[$i]['start_tag'];
                    [$_,$ed]=$tags[$i]['end_tag'];
                    if($st < $earlist || $earlist == -1){
                        $earlist = $st;
                    }else continue;
                    $content=substr_replace($content,
                    "<div class=\"ptr-block ptr-render ptr-payment-require\" data-amount=\""
                    .$tags[$i]['params']['amount']
                    ."\" data-id=\""
                    .$tags[$i]['params']['id']
                    ."\"></div>"
                    ,$st,$ed-$st+1);
                }else{
                    [$st,$ed]=$tags[$i]['end_tag'];
                    if($st < $earlist || $earlist == -1){
                        $earlist = $st;
                    }else continue;
                    $content=substr_replace($content,"</div>",$st,$ed-$st+1);
                }
            }
            $content = str_ireplace("<div class=\"pay-to-read\"","<div class=\"ptr-block ptr-paid  ptr-render\"",$content);
            $items = Payment::selectRaw("count(id) as cnt,item_id")->where("post_id","=",$post->id)->groupBy("item_id")->get();
            foreach($items as $item){
                $content = str_ireplace("data-id=\"".$item->item_id."\"","data-id=\"".$item->item_id."\" data-haspaid-cnt=\"".$item->cnt."\"",
                $content);
            }
            $attributes["contentHtml"] = $content;
        }
        return $attributes;
    }
}
