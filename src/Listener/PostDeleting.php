<?php

namespace Xypp\PayToRead\Listener;

use Flarum\Post\Event\Deleting;
use Flarum\Post\Post;
use Flarum\Post\PostRepository;
use Flarum\Settings\SettingsRepositoryInterface;
use Xypp\PayToRead\PayItem;
use Xypp\PayToRead\PayItemRepository;
use Xypp\PayToRead\Utils\TagPicker as TagPicker;

class PostDeleting
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
    )
    {
        $this->settings = $settings;
        $this->posts = $posts;
        $this->payItemRepository = $payItemRepository;
    }

    public function handle(Deleting $event)
    {
        $oldPost = $event->post;
        $user = $event->actor;
        [$oldTags,$_]=TagPicker::TagPicker($oldPost->content);
        $rmIdList = [];
        foreach($oldTags as $tag){
            array_push($rmIdList,$tag['params']['id']);
        }
        PayItem::where("id","in",$rmIdList)->delete();
        return;
    }
}
