<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\LoginController;


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/refresh', [LoginController::class, 'refresh']);
Route::get('/user-profile', [LoginController::class, 'userProfile']);
Route::get('/user-type-id', [LoginController::class, 'userTypeID']);

Route::apiResources([
    'posts' => PostController::class,
    'users' => UserController::class,
    'votes' => VoteController::class,
    'comments' => CommentController::class,
    'notifications' => NotificationController::class
]);

Route::get('/get-users-without-access', [UserController::class, 'getUsersWithoutAccess']);
Route::get('/change-access/{id}', [UserController::class, 'changeAccess']);
Route::get('/change-all-new-student-access', [UserController::class, 'changeAllNewStudentAccess']);

Route::get('/my-posts', [PostController::class, 'showUserPosts']);
Route::get('/page-notifications', [PostController::class, 'pageNotifications']);
Route::get('/search/{value}', [PostController::class, 'search']);
// Route::get('/votes/count-post-votes/{id}', [VoteController::class, 'countPostVotes']);
// Route::get('/has-voted-post/{id}',[VoteController::class, 'hasVotedPost']);