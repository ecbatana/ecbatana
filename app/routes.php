<?php
use Ecbatana\Router\Router as Route;

/**
 *
 * Route List
 *
 */

Route::get('/', 'HomeController@index');
Route::get('test', 'HomeController@test');
