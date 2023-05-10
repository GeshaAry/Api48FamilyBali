<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ArticleUserController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\VideoGalleryController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\MerchandiseCategoryController;
use App\Http\Controllers\Api\MerchandiseController;
use App\Http\Controllers\Api\DetailActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('admin', AdminController::class);
Route::post('admin/{admin_id}', [AdminController::class, 'update']);

Route::resource('member', MemberController::class);
Route::get('allmember', 'App\Http\Controllers\Api\MemberController@AllMember');
Route::post('member/{member_id}', [MemberController::class, 'update']);
Route::get('showmember/{member_id}', 'App\Http\Controllers\Api\MemberController@show');

Route::resource('user', UserController::class);
Route::post('user/{user_id}', [UserController::class, 'update']);

Route::resource('article', ArticleController::class);
Route::post('article/{article_id}', [ArticleController::class, 'update']);

Route::resource('articleuser', ArticleUserController::class);
Route::post('articleuser/{articleuser_id}', [ArticleUserController::class, 'update']);

Route::resource('gallery', GalleryController::class);

Route::resource('video', VideoGalleryController::class);
Route::post('video/{video_id}', [VideoGalleryController::class, 'update']);

Route::resource('activity', ActivityController::class);
Route::get('allactivity', 'App\Http\Controllers\Api\ActivityController@AllActivity');
Route::post('activity/{activity_id}', [ActivityController::class, 'update']);

Route::resource('merchandisectg', MerchandiseCategoryController::class);
Route::post('merchandisectg/{merchandisectg_id}', [MerchandiseCategoryController::class, 'update']);

Route::resource('merchandise', MerchandiseController::class);
Route::post('merchandise/{merchandise_id}', [MerchandiseController::class, 'update']);

Route::post('loginadmin','App\\Http\\Controllers\\Api\LoginAdminController@login');

Route::resource('detailactivity', DetailActivityController::class);
Route::get('detailactivity', 'App\Http\Controllers\Api\DetailActivityController@AllDetailActivity');
Route::post('detailactivity/{detailactivity_id}', [DetailActivityController::class, 'update']);
Route::get('detailactivityjkt48/{activity_id}', 'App\Http\Controllers\Api\DetailActivityController@ShowDetailActivity');
