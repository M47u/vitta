<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FragranceNote;

class FragranceNoteSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [
            // Top notes
            ['name' => 'Bergamota', 'slug' => 'bergamota', 'type' => 'top', 'icon' => '🍋'],
            ['name' => 'Limón', 'slug' => 'limon', 'type' => 'top', 'icon' => '🍋'],
            ['name' => 'Azafrán', 'slug' => 'azafran', 'type' => 'top', 'icon' => '🌺'],
            ['name' => 'Rosa', 'slug' => 'rosa', 'type' => 'top', 'icon' => '🌹'],

            // Heart notes
            ['name' => 'Jazmín', 'slug' => 'jazmin', 'type' => 'heart', 'icon' => '🌸'],
            ['name' => 'Ámbar', 'slug' => 'ambar', 'type' => 'heart', 'icon' => '🟡'],
            ['name' => 'Pachulí', 'slug' => 'pachuli', 'type' => 'heart', 'icon' => '🍃'],
            ['name' => 'Almizcle', 'slug' => 'almizcle', 'type' => 'heart', 'icon' => '💫'],

            // Base notes
            ['name' => 'Oud', 'slug' => 'oud', 'type' => 'base', 'icon' => '🪵'],
            ['name' => 'Sándalo', 'slug' => 'sandalo', 'type' => 'base', 'icon' => '🌲'],
            ['name' => 'Vainilla', 'slug' => 'vainilla', 'type' => 'base', 'icon' => '🍦'],
            ['name' => 'Incienso', 'slug' => 'incienso', 'type' => 'base', 'icon' => '🕯️'],
        ];

        foreach ($notes as $note) {
            FragranceNote::create($note);
        }
    }
}