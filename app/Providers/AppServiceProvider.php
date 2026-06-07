<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Policies\ReplyPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void 
    {
        //
    }

    public function boot(): void
{
    \Illuminate\Pagination\Paginator::useBootstrapFive();
}
}