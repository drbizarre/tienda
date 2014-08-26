<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'StoreController@showIndex');


/*Route::get('template/{name}', function ($name) {
   $name = ucwords(str_replace('-', ' ', $name));
   return View::make('template')->with('name', $name); 
});*/