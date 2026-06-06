<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use Illuminate\Support\Facades\Route;

// Page d'accueil principale du blog (Bellevieshop)
Route::get('/', [PostController::class, 'index'])->name('home');
Route::post('/posts/{post}/like', [PostController::class,'toggleLike']);
// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profil Utilisateur
Route::get('/profile/{username}', [AuthController::class, 'profile'])->name('profile');
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit/settings', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/edit/settings', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Articles (Publications)
// Tout le monde (même non connecté) peut lire un article complet
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Seul l'administrateur connecté peut créer, modifier ou supprimer un article
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/posts/create/new', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// Commentaires
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Réponses aux commentaires
Route::middleware('auth')->group(function () {
    Route::post('/comments/{comment}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::put('/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
});

// Notifications
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/count', [NotificationController::class, 'count'])->name('count');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
    Route::get('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
});

// Diffusion en Live
Route::prefix('live')->name('live.')->group(function () {
    Route::get('/', [LiveStreamController::class, 'index'])->name('index');
    Route::get('/{liveStream}', [LiveStreamController::class, 'show'])->name('show');
    Route::middleware('auth')->group(function () {
        Route::get('/create/new', [LiveStreamController::class, 'create'])->name('create');
        Route::post('/', [LiveStreamController::class, 'store'])->name('store');
        Route::post('/{liveStream}/end', [LiveStreamController::class, 'end'])->name('end');
        Route::delete('/{liveStream}', [LiveStreamController::class, 'destroy'])->name('destroy');
    });
});

// Espace Administration (Dashboard)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::delete('/posts/{post}', [AdminController::class, 'destroyPost'])->name('posts.destroy');
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
    Route::delete('/comments/{comment}', [AdminController::class, 'destroyComment'])->name('comments.destroy');
});
