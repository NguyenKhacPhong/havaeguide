<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            "App\Repositories\School\ISchoolRepository", "App\Repositories\School\SchoolRepository"
        );
        $this->app->bind(
            "App\Repositories\SchoolType\ISchoolTypeRepository", "App\Repositories\SchoolType\SchoolTypeRepository"
        );
        $this->app->bind(
            "App\Repositories\Sector\ISectorRepository",
            "App\Repositories\Sector\SectorRepository"
        );
        $this->app->bind(
            "App\Repositories\Major\IMajorRepository",
            "App\Repositories\Major\MajorRepository"
        );
        $this->app->bind(
            "App\Repositories\Question\IQuestionRepository",
            "App\Repositories\Question\QuestionRepository"
        );

        $this->app->bind(
            "App\Repositories\Page\IPageRepository",
            "App\Repositories\Page\PageRepository"
        );
        $this->app->bind(
            "App\Repositories\Comment\ICommentRepository",
            "App\Repositories\Comment\CommentRepository"
        );

        $this->app->bind(
            "App\Repositories\Post\IPostRepository",
            "App\Repositories\Post\PostRepository"
        );

        $this->app->bind(
            "App\Repositories\User\IUserRepository",
            "App\Repositories\User\UserRepository"
        );
        
        $this->app->bind(
            "App\Repositories\Slider\SliderRepository"
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
