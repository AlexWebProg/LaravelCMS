<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth']], function () {
    // Рабочий стол
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/','IndexController')->name('admin.main');
    });
    // Пользователи
    Route::group(['namespace' => 'User', 'prefix' => 'users'], function () {
        Route::get('/','IndexController')->name('admin.user.index');
        Route::get('/create','CreateController')->name('admin.user.create')->middleware('admin');
        Route::post('/','StoreController')->name('admin.user.store')->middleware('admin');
        Route::get('/{user}','ShowController')->name('admin.user.show');
        Route::get('/{user}/edit','EditController')->name('admin.user.edit')->middleware('admin');
        Route::patch('/{user}','UpdateController')->name('admin.user.update')->middleware('admin');
        Route::delete('/{user}','DeleteController')->name('admin.user.delete')->middleware('admin');
    });
    // Заявки по подпискам
    Route::group(['namespace' => 'SubRequest', 'prefix' => 'subRequests'], function () {
        Route::get('/{status}','IndexController')->name('admin.subRequests.index');
        Route::get('/{status}/{subRequest}','ShowController')->name('admin.subRequests.show');
        Route::post('/{subRequest}/addComment','AddCommentController')->name('admin.subRequests.addComment');
        Route::patch('/{subRequest}/close','CloseController')->name('admin.subRequests.close');
    });
    // Подписки
    Route::group(['namespace' => 'Subscribe', 'prefix' => 'subscribes'], function () {
        Route::get('/index','IndexController')->name('admin.subscribes.index');
        Route::get('/indexJSON','IndexJSONController');
        Route::get('/edit/{subscribe}/{action_type?}','EditController')->name('admin.subscribes.edit');
        Route::patch('/update/{subscribe}','UpdateController')->name('admin.subscribes.update');
        Route::patch('/sendOne/{subscribe}','SendController@sendOneSubscribe')->name('admin.subscribes.sendOne');
        Route::patch('/sendMany','SendController@sendManySubscribes')->name('admin.subscribes.sendMany');
        Route::patch('/updateStatus/{subscribe}','UpdateStatusController')->name('admin.subscribes.updateStatus');

        Route::get('/create/chooseSubscriber','ChooseSubscriberController')->name('admin.subscribes.create.chooseSubscriber');
        Route::post('/create/chooseSubscriber','SetSubscriberController')->name('admin.subscribes.create.setSubscriber');

        Route::get('/create/{subscriber}/{orderFromTilda?}','CreateController')->name('admin.subscribes.create');
        Route::put('/create','StoreController')->name('admin.subscribes.store');

        Route::post('/excel/export','ExcelExportController')->name('admin.subscribes.excelExport');

        Route::patch('/assemblyMany','AssemblyController@assemblyManySubscribes')->name('admin.subscribes.assemblyMany');
    });
    // Параметры подписок
    Route::group(['namespace' => 'SubscribeSettings', 'prefix' => 'subscribeSettings'], function () {
        Route::get('/{type}','IndexController')->name('admin.subscribeSettings.index');
        Route::post('/checkSubscribeSettingUse', 'CheckUseController');
        Route::post('/deleteSubscribeSetting', 'DeleteController');
        Route::patch('/{type}','UpdateController')->name('admin.subscribeSettings.update');
    });
    // Подписчики
    Route::group(['namespace' => 'Subscriber', 'prefix' => 'subscribers'], function () {
        Route::get('/index','IndexController')->name('admin.subscribers.index');
        Route::get('/edit/{subscriber}','EditController')->name('admin.subscribers.edit');
        Route::patch('/update/{subscriber}','UpdateController')->name('admin.subscribers.update');
        Route::get('/create','CreateController')->name('admin.subscribers.create');
        Route::put('/create','StoreController')->name('admin.subscribers.create');
    });
    // Товары
    Route::group(['namespace' => 'SubscribeProduct', 'prefix' => 'subscribeProducts'], function () {
        Route::get('/index','IndexController')->name('admin.subscribeProducts.index');
        Route::get('/edit/{subscribeProduct}','EditController')->name('admin.subscribeProducts.edit');
        Route::patch('/update/{subscribeProduct}','UpdateController')->name('admin.subscribeProducts.update');
        Route::get('/create','CreateController')->name('admin.subscribeProducts.create');
        Route::put('/create','StoreController')->name('admin.subscribeProducts.create');
        Route::delete('/{subscribeProduct}','DeleteController')->name('admin.subscribeProducts.delete');
    });
    // Состав типов подписок
    Route::group(['namespace' => 'SubscribeTypeConsist', 'prefix' => 'subscribeTypeConsist'], function () {
        Route::get('/index/{month}','IndexController')->name('admin.subscribeTypeConsist.index');
        Route::get('/edit/{subscribeType}/{month}','EditController')->name('admin.subscribeTypeConsist.edit');
        Route::patch('/update/{subscribeType}/{month}','UpdateController')->name('admin.subscribeTypeConsist.update');
    });
    // Сборка подписок
    Route::group(['namespace' => 'SubscribeConsist', 'prefix' => 'subscribeConsist'], function () {
        Route::get('/index/{month}','IndexController')->name('admin.subscribeConsist.index');
        Route::get('index/indexJSON/{month}','IndexJSONController');
        Route::get('/edit/{subscribe}/{month}','EditController')->name('admin.subscribeConsist.edit');
        Route::patch('/update/{subscribe}/{month}','UpdateController')->name('admin.subscribeConsist.update');
        Route::post('/PDFExport/{month}','PDFExportController')->name('admin.subscribeConsist.PDFExport');
    });
    // Заявки на подписку из Тильды
    Route::group(['namespace' => 'SubscribeTilda', 'prefix' => 'subscribeTilda'], function () {
        Route::get('/index/{processed}','IndexController')->name('admin.subscribeTilda.index');
        Route::get('/createSubscribe/{orderFromTilda}','CreateSubscribeController')->name('admin.subscribeTilda.createSubscribe');
        /*Route::get('/show/{order}','ShowController')->name('admin.subscribeTilda.show');*/
    });
    // Тестовые функции
    Route::group(['namespace' => 'Test', 'prefix' => 'test'], function () {
        Route::get('/go','TestController');
    });
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
