<?php

namespace App\Providers;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Implementations\ArticleRepository;
use App\Repositories\Implementations\CommentRepository;
use App\Repositories\Implementations\TagRepository;
use App\Repositories\Implementations\UserRepository;
use App\Services\Contracts\ArticleServiceInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\CommentServiceInterface;
use App\Services\Contracts\TagServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Implementations\ArticleService;
use App\Services\Implementations\AuthService;
use App\Services\Implementations\CommentService;
use App\Services\Implementations\TagService;
use App\Services\Implementations\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        $this->app->bind(TagServiceInterface::class, TagService::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);

        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);

        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
