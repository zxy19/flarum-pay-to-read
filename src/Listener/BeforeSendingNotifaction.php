<?php

namespace Xypp\PayToRead\Listener;

use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Locale\Translator;
use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Illuminate\Support\Arr;
use Xypp\PayToRead\Utils\TagPicker;

class BeforeSendingNotifaction
{
    protected Translator $translator;
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }
    public function __invoke(BlueprintInterface $blueprint, $recipients)
    {
        if (isset($blueprint->post)) {
            $post = $blueprint->post;
            if (!$post instanceof CommentPost)
                return $recipients;
            $content = $post->content;
            [$tags, $_] = TagPicker::TagPicker($content);
            $earlist = -1;
            for ($i = count($tags) - 1; $i >= 0; $i--) {
                [$st, $_] = $tags[$i]['start_tag'];
                [$_, $ed] = $tags[$i]['end_tag'];
                if ($st < $earlist || $earlist == -1) {
                    $earlist = $st;
                } else
                    continue;
                $content = substr_replace(
                    $content,
                    $this->translator->trans("xypp-pay-to-read.forum.paymentTipMail")
                    ,
                    $st,
                    $ed - $st + 1
                );
            }
            $post->content = $content;
            $post->setContentAttribute($content);
        }
        return $recipients;
    }
}