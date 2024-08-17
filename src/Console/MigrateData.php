<?php

namespace Xypp\PayToRead\Console;

use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Illuminate\Console\Command;

class MigrateData extends Command
{
    /**
     * @var string
     */
    protected $signature = 'xypp-ptr:migrate';

    /**
     * @var string
     */
    protected $description = 'Migrate data from old version to new version';

    public function handle()
    {
        $this->info("Starting migration...");

        $haveNotMigrate = false;

        $this->withProgressBar(Post::where("type", "comment")->get()->getIterator(), function (CommentPost $post) use (&$haveNotMigrate) {
            $content = $post->getAttribute("content");
            if ($post->getAttribute("content_with_payitem")) {
                $this->error("You have not run the command `php flarum migrate`. Please run it first.");
                throw new \Exception("You have not run the command `php flarum migrate`. Please run it first.");
            }
            if (str_starts_with($content, "!old!")) {
                $content = substr($content, 5);
                $post->setContentAttribute($content);
                if ($haveNotMigrate) {
                    $post->setAttribute("content_with_payitem", null);
                }
            }
            $post->save();
        });

        $this->info("Migration completed.");
    }
}