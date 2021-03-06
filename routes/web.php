<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/404',"Admin\IndexController@error");
Route::get('/code',"Admin\IndexController@code");
Route::get('/login',"Admin\IndexController@login");
Route::post('/login',"Admin\IndexController@dologin");


Route::prefix("/")->middleware(['adminLogin'])->namespace('Admin')->group(function(){
    Route::get('/loginOut',"IndexController@loginOut");
    Route::get('/index',"IndexController@index");
    Route::get('/adminSys',"AdminSysController@index");
    Route::post('/adminSys',"AdminSysController@update");
    Route::any('/{controller?}/{action?}', function ($controllerName = 'admin', $actionName = 'getList', \Illuminate\Http\Request $request) {
        $app = app();
        $cameledController = ucfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $controllerName))));
        $cameledAction = ucfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $actionName))));
        try{
            $controller = $app->make(sprintf('\App\Http\Controllers\Admin\%sController', $cameledController));
            return $controller->callAction($cameledAction, array($request));
        } catch(Exception $e) {
            if ($e instanceof ReflectionException || $e instanceof BadMethodCallException) {
                abort(404);
            } else {
                throw $e;
            }
        }
    });
});

