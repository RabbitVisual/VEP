<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Ministry\Database\Seeders;

use Illuminate\Database\Seeder;
use VertexSolutions\Ministry\Models\Ministry;

class MinistryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ministries = [
            ['name' => 'Louvor', 'icon' => 'fa-music', 'color' => 'amber-500', 'description' => 'Ministério de música e louvor.'],
            ['name' => 'Infantil', 'icon' => 'fa-child-reaching', 'color' => 'blue-500', 'description' => 'Ministério com crianças.'],
            ['name' => 'Missões', 'icon' => 'fa-earth-americas', 'color' => 'emerald-500', 'description' => 'Missões locais e transculturais.'],
            ['name' => 'Jovens', 'icon' => 'fa-users', 'color' => 'violet-500', 'description' => 'Ministério com jovens.'],
        ];

        $leaderId = \App\Models\User::first()?->id;

        foreach ($ministries as $data) {
            Ministry::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['leader_id' => $leaderId])
            );
        }
    }
}
