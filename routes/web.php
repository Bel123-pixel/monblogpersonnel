<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

// Page d'accueil : affiche toutes les publications
Route::get('/', [PostController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/posts/{post}/like', [PostController::class, 'submitLike'])->name('posts.like');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/order', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'sellerIndex'])->name('orders.my');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Autocomplétion @mention
Route::get('/mention-search', [CommentController::class, 'mentionSearch'])->name('mention.search');
// --- SYSTÈME D'AUTHENTIFICATION MANUEL POUR LA DÉMO ---

// Formulaires (Vues)
Route::get('/login', function() { return view('auth.login'); })->name('login');
Route::get('/register', function() { return view('auth.register'); })->name('register');

// Traitement de la connexion
Route::post('/login', function(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->route('home');
    }
    return back()->withErrors(['email' => 'Identifiants incorrectes.']);
});

// Traitement de l'inscription (Crée un utilisateur client)
Route::post('/register', function(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:4|confirmed',
    ]);
    $user = \App\Models\User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'is_admin' => false, // C'est un client par défaut !
    ]);
    Auth::login($user);
    return redirect()->route('home');
});

// Déconnexion
Route::post('/logout', function(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ── Mot de passe oublié ──────────────────────────────────
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    try {
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Un lien de réinitialisation a été envoyé à votre email.')
            : back()->withErrors(['email' => __($status)]);
    } catch (\Exception $e) {
        return back()->withErrors(['email' => 'Impossible d\'envoyer l\'email. Vérifiez votre adresse ou réessayez plus tard.']);
    }
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])
                 ->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Mot de passe réinitialisé avec succès !')
        : back()->withErrors(['email' => __($status)]);
})->name('password.update');

// Profil utilisateur — edit AVANT {username} pour éviter le conflit
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [App\Http\Controllers\AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');
});
Route::get('/profile/{username}', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-admin', [App\Http\Controllers\AdminController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/posts', [App\Http\Controllers\AdminController::class, 'posts'])->name('posts');
    Route::delete('/posts/{post}', [App\Http\Controllers\AdminController::class, 'destroyPost'])->name('posts.destroy');
    Route::get('/comments', [App\Http\Controllers\AdminController::class, 'comments'])->name('comments');
    Route::delete('/comments/{comment}', [App\Http\Controllers\AdminController::class, 'destroyComment'])->name('comments.destroy');
    Route::get('/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/confirm', [App\Http\Controllers\AdminOrderController::class, 'confirm'])->name('orders.confirm');
    Route::delete('/orders/{order}', [App\Http\Controllers\AdminOrderController::class, 'destroy'])->name('orders.destroy');
});