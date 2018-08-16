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

Route::get('/', 'HomeController@index')->name('index');

Route::group([
    'prefix' => '/admin',
    'as' => 'admin.',
    'middleware' => ['auth']
], function () {
    Route::get('/dashboard', 'HomeController@adminIndex')->name('index');
    Route::group(
        [
            'prefix' => '/users',
            'as' => 'users.',
        ],
        function () {
            Route::get('/', 'UserController@index')->name('index');
            Route::get('/edit/{id}', 'UserController@edit')->name('edit');
            Route::get('/show', 'UserController@show')->name('show');
            Route::put('/update/{id}', 'UserController@update')->name('update');
            Route::post('/block', 'UserController@toggleBlock')->name('block');
            Route::get('/option/{option}', 'UserController@option')->name('option');
        }
    );
    Route::group(
        [
            'prefix' => 'product',
            'as' => 'product.'
        ],
        function () {
            Route::get('/', 'Admin\ProductController@index')->name('list');
            Route::delete('{product?}', 'Admin\ProductController@destroy')->name('destroy');
            Route::get('{product}', 'Admin\ProductController@show')->name('show');
        }
    );
    Route::resource('/categories', 'CategoryController');
    Route::group([
        'prefix' => 'comments',
        'as' => 'comments.'
    ], function () {
        Route::get('/', 'CommentController@index')->name('index');
        Route::delete('/destroy/{id}', 'CommentController@destroy')->name('destroy');
    });

    Route::group(
        [
            'prefix' => 'config',
            'as' => 'config.',
        ],
        function () {
            Route::get('/', 'Admin\ConfigController@index')->name('index');
            Route::put('/', 'Admin\ConfigController@save')->name('save');
        });
    Route::group(['prefix' => 'sliders'], 
        function () {
            Route::get('/', 'Admin\SliderController@index')->name('slide.index');
            Route::delete('/{slider?}', 'Admin\SliderController@destroy')->name('slide.destroy');
            Route::get('/add', 'Admin\SliderController@add')->name('slide.add');
            Route::post('/add', 'Admin\SliderController@store')->name('slide.store');
            Route::get('/{slider}/edit', 'Admin\SliderController@edit')->name('slide.edit');
            Route::put('/{slider}/edit', 'Admin\SliderController@update')->name('slide.update');
        }
    );
    Route::delete('/categories/delete', 'CategoryController@destroy')->name('categories.delete');
    Route::resource('/categories', 'CategoryController');
    Route::group([
        'prefix' => '/reports',
        'as' => 'reports.'
    ], function () {
        Route::get('/', 'ReportController@index')->name('index');
        Route::delete('/destroy', 'ReportController@destroy')->name('destroy');
    });
    Route::group([
        'prefix' => '/orders',
        'as' => 'orders.',
    ], function () {
        Route::get('/', 'OrderController@index')->name('index');
        Route::delete('/destroy/{id}', 'OrderController@destroy')->name('destroy');
    });
});

Route::group(
    [
        'prefix' => '/register',
        'as' => 'register.'
    ],
    function () {
        Route::group([
            'namespace' => 'Auth',
        ], function () {
            Route::get('/verify/{verify_token}', 'RegisterController@verify')->name('verify');
            Route::get('/resendEmail', 'RegisterController@showResendForm')->name('showResendForm');
            Route::post('/resendEmail', 'RegisterController@resendEmail')->name('resendEmail');
        });
    }
);

Route::group([
    'prefix' => '/client',
    'as' => 'client.',
], function () {
    Route::group([
        'prefix' => '/products',
        'as' => 'products.'
    ], function () {
        Route::get('/show/{id}', 'ProductController@show')->name('show');
        Route::post('/comment/{id}', 'CommentController@store')->name('comment')->middleware('auth');
    });
    Route::post('/report/{id}', 'ReportController@store')->name('report.store')->middleware('auth');
    Route::delete('/comment/destroy/{id}', 'CommentController@clientDestroy')->name('destroyComment')->middleware('auth');

    Route::group([
        'prefix' => '/user',
        'as' => 'user.',
        'middleware' => [
                'auth'
            ]
        ], function () {
            Route::get('/profile', 'UserController@profile')->name('profile');
            Route::put('/update/{id}', 'UserController@updateProfile')->name('update');
        });
});

Route::group([
    'prefix' => '/password',
    'as' => 'password.',
    'middleware' => 'auth',
    'namespace' => 'Auth'
], function () {
    Route::get('/edit', 'ChangePasswordController@edit')->name('edit');
    Route::put('/update', 'ChangePasswordController@update')->name('update');
});

Auth::routes();

Route::get('lang/{lang}', 'LangController@changeLanguage')->name('language.change');
Route::get('/auth/google', 'Auth\SocialAuthController@redirectToProvider')->name('google.login');
Route::get('/auth/google/callback', 'Auth\SocialAuthController@handleProviderCallback')->name('google.callback');
Route::get('category/{slug}', 'Client\CategoryController@index')->name('client.category');

Route::group(
    [
        'middleware' => 'auth',
        'prefix' => 'product',
        'as' => 'client.product.'
    ], 
    function () {
        Route::get('/new', 'Client\ProductController@postProduct')->name('new');
        Route::post('/new', 'Client\ProductController@store')->name('store');
        Route::post('/upload-images', 'Client\ProductController@uploadImage')->name('upload.image');
    }
);
Route::get('profile/{user}', 'Client\ProfileController@index')->name('client.profile');
Route::post('profile/{user}/rate', 'Client\ProfileController@rating')->name('client.profile.rate');
Route::get('loadmore', 'HomeController@loadMoreProduct')->name('loadmore');

Route::group([
    'prefix' => '/order',
    'middleware' => 'auth'
], function () {
    Route::get('{product}', 'ProductController@order')->name('client.order.view');
    Route::post('{product}', 'ProductController@buy')->name('client.order.buy');
});

