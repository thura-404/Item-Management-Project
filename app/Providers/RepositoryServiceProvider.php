<?php

namespace App\Providers;

use App\Interfaces\CategoryInterface;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\ItemInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        EmployeeInterface::class => EmployeeRepository::class,
        ItemInterface::class => ItemRepository::class,
        CategoryInterface::class => CategoryRepository::class,
    ];
    
    public function register()
    {
        // Register Interface and Repository in here
        // You must place Interface in first place
        // If you dont, the Repository will not get readed.
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(ItemInterface::class, ItemRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
    }
}