<?php

namespace Xypp\PayToRead\Listener;

use Flarum\Post\Event\Saving;
use Flarum\Post\Post;
use Flarum\Post\PostRepository;
use Flarum\Settings\SettingsRepositoryInterface;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\PayItemRepository;
use Xypp\PayToRead\Utils\TagPicker as TagPicker;

class PostSaving
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var PostRepository
     */
    protected $posts;

    protected $payItemRepository;
    /**
     * @param SettingsRepositoryInterface $settings
     * @param PostRepository              $posts
     */
    public function __construct(
        SettingsRepositoryInterface $settings,
        PostRepository $posts,
        PayItemRepository $payItemRepository
    ) {
        $this->settings = $settings;
        $this->posts = $posts;
        $this->payItemRepository = $payItemRepository;
    }

    public function handle(Saving $event)
    {
        $post = $event->post;
        $user = $event->actor;
        if (!$post instanceof Post) {
            return;
        }
        if (isset($post->attributes['content']) && $post->attributes['content'] && !isset($post->content) || !is_string($post->content)) {
            return;
        }
        [$tags, $post->content] = TagPicker::TagPicker($post->content);
        $sss = print_r($post, true);
        $oldPost = $this->posts->query()
            ->where('id', '=', $post->id)
            ->first();
        $oldTags = [];
        if ($oldPost) {
            [$oldTags, $_] = TagPicker::TagPicker($oldPost->content);
        }
        $laterPostId = [];
        $appearedId = array();
        foreach ($tags as $tag) {
            $payItem = null;
            $id = $tag['params']['id'];
            if ($tag['params']['new']) {
                $tmpId = $id;
                $postIdSto = $post->id;
                if (!$postIdSto) {
                if (!$postIdSto) {
                    $postIdSto = 0;
                }
                $payItem = PayItem::build($postIdSto, $user->id, $tag['params']['amount']);
                $payItem->save();
                $id = $payItem->id;
                if ($postIdSto == 0) {
                    array_push($laterPostId, $id);
                if ($postIdSto == 0) {
                    array_push($laterPostId, $id);
                }
                $post->content = str_replace("[newId]#" . $tmpId . "#[/newId]", $id, $post->content);
            } else {
                $payItem = $this->payItemRepository->findById($id);
                if ($payItem) {
                    $payItem->amount = $tag['params']['amount'];
                    $payItem->save();
                }
            }
            $appearedId[$id] = true;
        }
        $rmIdList = [];
        foreach ($oldTags as $tag) {
            if (
                !$tag['params']['new'] && $tag['params']['id']
                && !isset($appearedId[$tag['params']['id']])
            ) {
                array_push($rmIdList, $tag['params']['id']);
            }
        }
        PayItem::whereIn("id", $rmIdList)->where("post_id", "=", $post->id)->delete();
        if (count($laterPostId)) {
            $post->afterSave(function ($post) use ($laterPostId) {
                PayItem::whereIn("id", $laterPostId)->update(["post_id" => $post->id]);
        PayItem::whereIn("id", $rmIdList)->where("post_id", "=", $post->id)->delete();
        if (count($laterPostId)) {
            $post->afterSave(function ($post) use ($laterPostId) {
                PayItem::whereIn("id", $laterPostId)->update(["post_id" => $post->id]);
            });
        }
        return;
    }
}
