<?php

use App\Http\Controllers\{AuthController,
    CompanyRequestController,
    ExhibitionController,
    FavoriteController,
    InviteController,
    ProductController,
    ProductLikeController,
    RegisterRequestController};

use Illuminate\Support\Facades\Route;

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
Route::get('/image/{file}',[AuthController::class,'image']);
Route::post('/search',[InviteController::class,'search']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('show/managers/{id}', [ProductController::class, 'show_managers']);

    Route::prefix('signup')->group(function() {
        Route::post('/admin', [AuthController::class, 'admin']);
        Route::post('/user', [AuthController::class, 'user']);
        Route::post('/company', [AuthController::class, 'company']);
    });

    Route::prefix('visitor/show')->group(function() {
        Route::get('/exhibitions', [ExhibitionController::class, 'visitor_exh']);
        Route::get('/products/{id}', [ProductController::class, 'visitor_show']);
        Route::get('/user/pavilions/{id}', [ExhibitionController::class, 'visitor_show_user_pav']);
    });


    Route::middleware(['auth:api,admin-api,company-api'])->group(function() {

        Route::prefix('show')->group(function() {
            Route::get('/exhibitions', [ExhibitionController::class, 'show_exh']);
            Route::get('/pavilions/{id}', [ExhibitionController::class, 'show_pav']);
            Route::get('/user/pavilions/{id}', [ExhibitionController::class, 'show_user_pav']);
            Route::get('/products/{id}', [ProductController::class, 'show']);
            Route::get('/request', [CompanyRequestController::class, 'show_request']);
            Route::get('/register', [RegisterRequestController::class, 'show_register']);
            Route::get('/my_exhibitions', [ExhibitionController::class, 'show_my_exh']);
        });
        Route::prefix('invite')->group(function() {
            Route::get('/send/{table_id}&{user_id}', [InviteController::class, 'invite']);
            Route::get('/accept/{id}', [InviteController::class, 'accept_invite']);
            Route::delete('/reject/{id}', [InviteController::class, 'reject_invite']);
            Route::get('/show', [InviteController::class, 'show_invites']);
        });

        Route::prefix('edit')->group(function (){
            Route::post('/admin', [AuthController::class, 'edit_admin']);
            Route::post('/user', [AuthController::class, 'edit_user']);
            Route::post('/company', [AuthController::class, 'edit_company']);
        });
        Route::prefix('table')->group(function (){
            Route::get('/register/{id}', [RegisterRequestController::class, 'register_table']);
            Route::get('/accept/{id}', [RegisterRequestController::class, 'accept_table']);
            Route::delete('/reject/{id}', [RegisterRequestController::class, 'reject_table']);
        });

        Route::prefix('exhibition')->group(function (){
            Route::post('/add', [ExhibitionController::class, 'add_exh']);
            Route::post('/pavilion/add/{id}', [ExhibitionController::class, 'add_pavilion']);
            Route::post('/edit/{id}', [ExhibitionController::class, 'update_exh']);
            Route::delete('/delete/{id}', [ExhibitionController::class, 'delete_exh']);
        });

        Route::prefix('company')->group(function() {
            Route::get('/accept/{id}', [CompanyRequestController::class, 'accept']);
            Route::get('/reject/{id}', [CompanyRequestController::class, 'reject']);
            Route::delete('/delete/{id}', [CompanyRequestController::class, 'delete']);
        });
        Route::prefix('favorite')->group(function() {
            Route::get('exhibition/{id}', [FavoriteController::class, 'favorite_exh']);
            Route::get('table/{id}', [FavoriteController::class, 'favorite_tab']);
            Route::get('show/exhibition', [FavoriteController::class, 'show_favorite_exh']);
            Route::get('show/table', [FavoriteController::class, 'show_favorite_tab']);
        });

        Route::get('/like/{id}', [ProductLikeController::class, 'like']);

        Route::get('/logout', [AuthController::class, 'logout']);

        Route::prefix('product')->group(function() {
            Route::post('/add/{id}', [ProductController::class, 'add_product']);
            Route::post('/edit/{id}', [ProductController::class, 'edit_product']);
            Route::delete('/delete/{id}', [ProductController::class, 'delete_product']);



        });
    });




