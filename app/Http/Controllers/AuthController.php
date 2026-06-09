<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * ==========================================
     * PARTIE 1 : AUTHENTIFICATION (CONNEXION / INSCRIPTION)
     * ==========================================
     */

    // Afficher le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    // Afficher le formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traiter l'inscription
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar'   => 'default.png', // Photo par défaut
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Inscription réussie !');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    /**
     * ==========================================
     * PARTIE 2 : GESTION DU PROFIL & AVATAR
     * ==========================================
     */

    // Afficher le profil public d'un utilisateur
    public function profile(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $posts = $user->posts()->latest()->paginate(6);
        
        return view('auth.profile', compact('user', 'posts'));
    }

    // Afficher le formulaire de modification du profil
    public function editProfile()
    {
        return view('auth.edit-profile', ['user' => auth()->user()]);
    }

    // Traiter la mise à jour (Ta méthode corrigée)
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'bio'      => 'nullable|string|max:500',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Supprimer l'ancienne photo
            if ($user->avatar && $user->avatar !== 'default.png' && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            if (config('filesystems.default') === 'cloudinary') {
                $result = cloudinary()->upload($request->file('avatar')->getRealPath(), ['folder' => 'bellevieshop/avatars']);
                $data['avatar'] = $result->getSecurePath();
            } else {
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $request->file('avatar')->extension();
                Storage::disk('public')->putFileAs('avatars', $request->file('avatar'), $filename);
                $data['avatar'] = $filename;
            }
        }

        $user->update($data);

        return redirect()->route('profile', $user->username)->with('success', 'Profil mis à jour avec succès !');
    }
}
