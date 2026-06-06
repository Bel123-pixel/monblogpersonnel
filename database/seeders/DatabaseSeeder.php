<?php
namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Admin Blog',
            'username' => 'admin',
            'email'    => 'admin@monblog.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'bio'      => 'Administrateur de MonBlog.',
        ]);

        $users = collect();
        foreach ([
            ['name' => 'Jean Kokou',   'username' => 'jeankokou',   'email' => 'jean@exemple.com'],
            ['name' => 'Marie Adjovi', 'username' => 'marieadjovi', 'email' => 'marie@exemple.com'],
            ['name' => 'Paul Dossou',  'username' => 'pauldossou',  'email' => 'paul@exemple.com'],
        ] as $u) {
            $users->push(User::create([
                'name'     => $u['name'],
                'username' => $u['username'],
                'email'    => $u['email'],
                'password' => Hash::make('password'),
                'bio'      => 'Passionné de technologie.',
            ]));
        }

        $allAuthors = $users->prepend($admin);

        $samplePosts = [
            ['title' => 'Débuter avec Laravel 12 : Guide complet',
             'content' => "Laravel 12 est sorti avec de nombreuses améliorations.\n\nDans cet article nous allons explorer les nouvelles fonctionnalités et comment démarrer un projet.\n\nInstallez PHP 8.2+ et Composer, puis :\ncomposer create-project laravel/laravel mon-projet"],
            ['title' => 'Tailwind CSS vs Bootstrap 5 : Lequel choisir ?',
             'content' => "Le débat continue en 2025.\n\nTailwind est utilitaire et donne un contrôle total. Bootstrap propose des composants prêts.\n\nPour débutant : Bootstrap. Pour projet sur mesure : Tailwind."],
            ['title' => 'Sécuriser une API Laravel : Bonnes pratiques',
             'content' => "La sécurité est primordiale.\n\n1. Utiliser Sanctum pour l'auth\n2. Valider toutes les entrées\n3. Implémenter le rate limiting\n4. Toujours HTTPS en production"],
        ];

        foreach ($samplePosts as $i => $p) {
            $post = Post::create([
                'user_id' => $allAuthors[$i % $allAuthors->count()]->id,
                'title'   => $p['title'],
                'content' => $p['content'],
                'status'  => 'published',
                'views'   => rand(10, 200),
            ]);

            $comment = Comment::create([
                'user_id' => $users->random()->id,
                'post_id' => $post->id,
                'body'    => 'Super article, très instructif !',
            ]);

            Reply::create([
                'user_id'    => $allAuthors->random()->id,
                'comment_id' => $comment->id,
                'body'       => 'Merci, content que ça aide !',
            ]);
        }

        $this->command->info('✅ Seeder OK — admin@monblog.com / password');
    }
}
