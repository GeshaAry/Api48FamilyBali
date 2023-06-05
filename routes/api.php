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
use App\Http\Controllers\Api\ArticlePictureController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TransactionEventController;
use App\Http\Controllers\Api\EventCommentController;
use App\Http\Controllers\Api\TransactionMerchandiseController;
use App\Http\Controllers\Api\MerchandiseCommentController;
use App\Http\Controllers\Api\ArticleCommentController;
use App\Http\Controllers\Api\ArticleUserCommentController;

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
Route::get('birthdaymember', 'App\Http\Controllers\Api\MemberController@BirthdayMember');
Route::post('member/{member_id}', [MemberController::class, 'update']);
Route::get('showmember/{member_id}', 'App\Http\Controllers\Api\MemberController@show');


Route::resource('user', UserController::class);
Route::post('user/{user_id}', [UserController::class, 'update']);
Route::post('pictureuser/{user_id}', [UserController::class, 'updateProfilePictureUser']);
Route::post('memberuser/{user_id}', [UserController::class, 'updateMemberUser']);
Route::post('changepassword/{user_id}', 'App\Http\Controllers\Api\UserController@updatePassword');

Route::resource('article', ArticleController::class);
Route::post('article/{article_id}', [ArticleController::class, 'update']);

Route::resource('articleuser', ArticleUserController::class);
Route::post('articleuser/{articleuser_id}', [ArticleUserController::class, 'update']);
Route::get('showarticleuser/{articleuser_id}', 'App\Http\Controllers\Api\ArticleUserController@showDetailArticle');
Route::get('articleuserpage', 'App\Http\Controllers\Api\ArticleUserController@showArticleUser');
Route::post('updatestatusarticle/{articleuser_id}', 'App\Http\Controllers\Api\ArticleUserController@updateStatus');

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
Route::post('login','App\\Http\\Controllers\\Api\UserController@login');

Route::resource('detailactivity', DetailActivityController::class);
Route::get('detailactivity', 'App\Http\Controllers\Api\DetailActivityController@AllDetailActivity');
Route::post('detailactivity/{detailactivity_id}', [DetailActivityController::class, 'update']);
Route::get('detailactivityjkt48/{activity_id}', 'App\Http\Controllers\Api\DetailActivityController@ShowDetailActivity');

Route::resource('articlepictures', ArticlePictureController::class);

Route::get('verifyemail/{token}', [UserController::class, 'emailVerify'])->name('verifyemailuser');

Route::get('checktoken/{email}/{token_reset_password}', [UserController::class, 'checkTokenResetPassword']);
Route::post('resetpassword/{email}/{token_reset_password}', [UserController::class, 'resetPassword']);
Route::post('sendforgotpassword', [UserController::class, 'forgotPassword']);

Route::resource('event', EventController::class);
Route::post('event/{event_id}', [EventController::class, 'update']);

Route::post('transactionevent', 'App\Http\Controllers\Api\TransactionEventController@store');
Route::get('transactionevent/user/{user_id}', 'App\Http\Controllers\Api\TransactionEventController@show');
Route::get('transactionevent', 'App\Http\Controllers\Api\TransactionEventController@index');
Route::delete('transactionevent/{transactionevent_id}', 'App\Http\Controllers\Api\TransactionEventController@destroy');
Route::post('uploadproofpayment/{transactionevent_id}', 'App\Http\Controllers\Api\TransactionEventController@updateProofPayment');
Route::post('updatestatusevent/{transactionevent_id}', 'App\Http\Controllers\Api\TransactionEventController@updateStatusTransactionEvent');
Route::get('downloadinvoice/{transactionevent_id}', 'App\Http\Controllers\Api\TransactionEventController@downloadEvent');
Route::get('reportevent', 'App\Http\Controllers\Api\TransactionEventController@reportEvent');

Route::get('event/{event_id}/comment', 'App\Http\Controllers\Api\EventCommentController@index');
Route::post('event/{event_id}/comment', 'App\Http\Controllers\Api\EventCommentController@store');
Route::put('event/{event_id}/comment/{eventcomment_id}', 'App\Http\Controllers\Api\EventCommentController@update');
Route::delete('event/{event_id}/comment/{eventcomment_id}', 'App\Http\Controllers\Api\EventCommentController@destroy');
Route::get('eventcomment', 'App\Http\Controllers\Api\DiskusiController@getEventComment');

Route::post('transactionmerchandise', 'App\Http\Controllers\Api\TransactionMerchandiseController@store');
Route::get('transactionmerchandise/user/{user_id}', 'App\Http\Controllers\Api\TransactionMerchandiseController@show');
Route::get('transactionmerchandise', 'App\Http\Controllers\Api\TransactionMerchandiseController@index');
Route::delete('transactionmerchandise/{merchandisetns_id}', 'App\Http\Controllers\Api\TransactionMerchandiseController@destroy');
Route::post('uploadproofpaymentmerchandise/{merchandisetns_id}', 'App\Http\Controllers\Api\TransactionMerchandiseController@updateProofPayment');
Route::post('updatestatusmerchandise/{merchandisetns_id}', 'App\Http\Controllers\Api\TransactionMerchandiseController@updateStatusTransactionMerchandise');
Route::get('downloadinvoicemerchandise/{merchandisetns_id}', 'App\Http\Controllers\Api\TransactionMerchandiseController@downloadMerchandise');
Route::get('reportmerchandise', 'App\Http\Controllers\Api\TransactionMerchandiseController@reportMerchandise');


Route::get('merchandise/{merchandise_id}/comment', 'App\Http\Controllers\Api\MerchandiseCommentController@index');
Route::post('merchandise/{merchandise_id}/comment', 'App\Http\Controllers\Api\MerchandiseCommentController@store');
Route::put('merchandise/{merchandise_id}/comment/{eventcomment_id}', 'App\Http\Controllers\Api\MerchandiseCommentController@update');
Route::delete('merchandise/{merchandise_id}/comment/{eventcomment_id}', 'App\Http\Controllers\Api\MerchandiseCommentController@destroy');
Route::get('merchandisecomment', 'App\Http\Controllers\Api\MerchandiseCommentController@getMerchandiseComment');

Route::get('article/{article_id}/comment', 'App\Http\Controllers\Api\ArticleCommentController@index');
Route::post('article/{article_id}/comment', 'App\Http\Controllers\Api\ArticleCommentController@store');
Route::put('article/{article_id}/comment/{articlecomment_id}', 'App\Http\Controllers\Api\ArticleCommentController@update');
Route::delete('article/{article_id}/comment/{articlecomment_id}', 'App\Http\Controllers\Api\ArticleCommentController@destroy');
Route::get('articlecomment', 'App\Http\Controllers\Api\MerchandiseCommentController@getArticleComment');

Route::get('articleuser/{articleuser_id}/comment', 'App\Http\Controllers\Api\ArticleUserCommentController@index');
Route::post('articleuser/{articleuser_id}/comment', 'App\Http\Controllers\Api\ArticleUserCommentController@store');
Route::put('articleuser/{articleuser_id}/comment/{articleusercomment_id}', 'App\Http\Controllers\Api\ArticleUserCommentController@update');
Route::delete('articleuser/{articleuser_id}/comment/{articleusercomment_id}', 'App\Http\Controllers\Api\ArticleUserCommentController@destroy');
Route::get('articleusercomment', 'App\Http\Controllers\Api\ArticleUserCommentController@getArticleUserComment');



