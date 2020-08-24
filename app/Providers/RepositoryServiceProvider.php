<?php 

namespace App\Providers; 

use App\Repositories\Repository; 
use App\Repositories\RepositoryInterface;
use Illuminate\Support\ServiceProvider; 

/** 
* Class RepositoryServiceProvider 
* @package App\Providers 
*/ 
class RepositoryServiceProvider extends ServiceProvider 
{ 
    /** 
    * Register services. 
    * 
    * @return void  
    */ 
    public function register() 
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
    }
}