<?php

namespace App\Providers;

use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Administrator;
use App\Policies\VcardPolicy;
use Laravel\Passport\Passport;
use App\Models\DefaultCategory;
use App\Policies\CategoryPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\AdministratorPolicy;
use App\Policies\DefaultCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Administrator::class => AdministratorPolicy::class,
        Category::class => CategoryPolicy::class,
        DefaultCategory::class => DefaultCategoryPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Vcard::class => VcardPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }

    }
}
