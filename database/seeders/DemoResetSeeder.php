<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reply;

class DemoResetSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Reset des données de démonstration en cours...');

        // Désactiver contraintes FK pour nettoyer proprement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('likes')->truncate();
        DB::table('replies')->truncate();
        DB::table('comments')->truncate();
        DB::table('posts')->truncate();
        // Supprimer les utilisateurs non-admin
        User::where('is_admin', false)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Créer / s'assurer de l'existence de l'admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@monblog.com'],
            [
                'name'     => 'Admin Blog',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'bio'      => 'Administrateur',
            ]
        );

        // Nouveaux utilisateurs de démonstration
        $u1 = User::create([
            'name' => 'Aline Kossi',
            'username' => 'alinek',
            'email' => 'aline@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Amoureuse des routines naturelles.',
        ]);

        $u2 = User::create([
            'name' => 'Marc Togo',
            'username' => 'marct',
            'email' => 'marc@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Conseils bien-être.',
        ]);

        // Publications récentes
        $posts = [
            [
                'user_id' => $admin->id,
                'title' => 'Routine Matinale Bio : 5 étapes simples',
                'content' => "1. Nettoyage doux\n2. Tonique hydratant\n3. Sérum naturel\n4. Crème riche\n5. Protection solaire (minérale)",
                'status' => 'published',
            ],
            [
                'user_id' => $u1->id,
                'title' => 'Masque capillaire naturel pour cheveux secs',
                'content' => "Recette : avocat + huile de coco + miel. Appliquer 20 minutes, rincer.",
                'status' => 'published',
            ],
            [
                'user_id' => $u2->id,
                'title' => 'Les bienfaits de l’huile de jojoba',
                'content' => "L'huile de jojoba équilibre, hydrate et convient à tous les types de peau.",
                'status' => 'published',
            ],
        ];

        foreach ($posts as $p) {
            Post::create(array_merge($p, ['views' => rand(1, 120)]));
        }

        $this->command->info('✅ Réinitialisation terminée — comptes et publications recréés.');
        $this->command->info('Accès admin : admin@monblog.com / password');
    }
}
