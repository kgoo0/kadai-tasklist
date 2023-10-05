<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ログインしていない場合(こちらを上に書かないとログインしている場合もこちらが実行される)
// welcomesignupページへ（dashboard）
Route::get('/', function () {
    return view('dashboard');
});
// ログインしている場合
Route::middleware('auth')->group(function () {
    // getメソッドで「/」にアクセスした場合、TasksControllerクラスのindexメソッドを実行(タスク一覧ページへ)
    Route::get('/', [TasksController::class, 'index']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

// ログイン時、タスク一覧ページ表示
Route::get('/dashboard', function () {
    return view('tasks.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

//  未ログイン状態ではタスクの作成、編集、削除、表示ができないように
Route::group(['middleware' => ['auth']], function () {                                    
    Route::resource('tasks', TasksController::class);
});   
