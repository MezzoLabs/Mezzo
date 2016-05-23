<?php


namespace MezzoLabs\Mezzo\Modules\Posts;


use App\Post;
use App\User;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Modules\Posts\Domain\Observers\UserObserver;
use MezzoLabs\Mezzo\Modules\Posts\Http\Transformers\PostTransformer;
use MezzoLabs\Mezzo\Modules\Posts\Schema\Content\Blocks\PostBlock;

class PostsModule extends ModuleProvider
{
    protected $options = [
        'icon' => 'ion-ios-paper'
    ];

    protected $models = [
        \App\Post::class
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContentBlocks([
            PostBlock::class
        ]);
    }

    /**
     * Called when module is ready, model reflections are loaded.
     *
     * @return mixed
     */
    public function ready()
    {
        $this->loadViews();

        $this->registerTransformers([
            Post::class => PostTransformer::class
        ]);

        \App\User::observe(app(UserObserver::class));

    }
}