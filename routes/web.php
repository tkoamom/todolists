<?php

use App\Http\Controllers\CatalogController;
use App\Models\Catalog;
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

Route::get('/', function () {
    return redirect('/catalog');
});

Auth::routes();

Route::get('/home', function () {
    return redirect('/catalog');
});

Route::resource('catalog', 'App\Http\Controllers\CatalogController');

Route::post('/catalog/share/{id}', [CatalogController::class, 'share'])->name('share');
