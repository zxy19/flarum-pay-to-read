<?php
namespace Xypp\PayToRead\Serializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Database\AbstractModel;
use Flarum\Settings\SettingsRepositoryInterface;
use Xypp\PayToRead\Utils\TagPicker;
use Symfony\Contracts\Translation\TranslatorInterface;
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
    public function __invoke(PostSerializer $serializer, AbstractModel $post, array $attributes){
        set_error_handler(function ($error_no, $error_msg, $error_file, $error_line) {
        }, E_ALL | E_STRICT);
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
            [$tags,$_]=TagPicker::TagPickerHTML($content,false);
            $paidId = array();
            if (!$user->isGuest() && !$bypass) {
                $paid = Payment::where("user_id","=",$user->id)->where("post_id","=",$post->id)->get();
                foreach($paid as $paidItem){
                    $paidId[$paidItem->item_id]=true;
                }
            }
            $earlist = -1;
            for($i = count($tags) - 1;$i >= 0;$i -- ){
                $exceedMaxStack = ($tags[$i]['params']['depth'] > $maxStack);
                if(!isset($paidId[$tags[$i]['params']['id']]) && !$bypass && !$exceedMaxStack){
                    [$st,$_]=$tags[$i]['start_tag'];
                    [$_,$ed]=$tags[$i]['end_tag'];
                    if($st < $earlist || $earlist == -1){
                        $earlist = $st;
                    }else continue;
                    $content=substr_replace($content,
                    "<div class=\"ptr-block ptr-payment-require\" data-ammount=\""
                    .$tags[$i]['params']['ammount']
                    ."\" data-id=\""
                    .$tags[$i]['params']['id']
                    ."\"><span>Payment Required</span><button class=\"ptr-pay-btn\">"
                    .$this->translator->trans('xypp-pay-to-read.forum.payment-req.btn')
                    ."</button>"
                    ."</div>"
                    ,$st,$ed-$st+1);
                }else{
                    [$st,$ed]=$tags[$i]['end_tag'];
                    if($st < $earlist || $earlist == -1){
                        $earlist = $st;
                    }else continue;
                    $content=substr_replace($content,"</div>",$st,$ed-$st+1);
                }
            }
            $content = str_ireplace("<pay-to-read","<div class=\"ptr-block ptr-paid\"",$content);
            $attributes["contentHtml"] = $content;
        }
        return $attributes;
    }
}

?>