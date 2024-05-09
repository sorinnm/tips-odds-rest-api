<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeds = [
            ['name' => "Spain",                     'sportId' => 1, 'categoryId' => 7],
            ['name' => "France",                    'sportId' => 1, 'categoryId' => 36],
            ['name' => "England",                   'sportId' => 1, 'categoryId' => 8],
            ['name' => "Italy",                     'sportId' => 1, 'categoryId' => 5],
            ['name' => "Germany",                   'sportId' => 1, 'categoryId' => 38],
            ['name' => "Brazil",                    'sportId' => 1, 'categoryId' => 26],
            ['name' => "Argentina",                 'sportId' => 1, 'categoryId' => 18],
            ['name' => "Portugal",                  'sportId' => 1, 'categoryId' => 60],
            ['name' => "Netherlands",               'sportId' => 1, 'categoryId' => 54],
            ['name' => "United States and Canada",  'sportId' => 1, 'categoryId' => 80],
            ['name' => "Russia",                    'sportId' => 1, 'categoryId' => 62],
            ['name' => "Turkey",                    'sportId' => 1, 'categoryId' => 76],
            ['name' => "Mexico",                    'sportId' => 1, 'categoryId' => 50],
            ['name' => "Belgium",                   'sportId' => 1, 'categoryId' => 24],
            ['name' => "Scotland",                  'sportId' => 1, 'categoryId' => 64],
            ['name' => "Greece",                    'sportId' => 1, 'categoryId' => 42],
            ['name' => "Ukraine",                   'sportId' => 1, 'categoryId' => 78],
            ['name' => "China",                     'sportId' => 1, 'categoryId' => 30],
            ['name' => "Japan",                     'sportId' => 1, 'categoryId' => 46],
            ['name' => "South Korea",               'sportId' => 1, 'categoryId' => 68],
            ['name' => "Australia",                 'sportId' => 1, 'categoryId' => 20],
            ['name' => "Switzerland",               'sportId' => 1, 'categoryId' => 72],
            ['name' => "Denmark",                   'sportId' => 1, 'categoryId' => 32],
            ['name' => "Sweden",                    'sportId' => 1, 'categoryId' => 70],
            ['name' => "Norway",                    'sportId' => 1, 'categoryId' => 58],
            ['name' => "Austria",                   'sportId' => 1, 'categoryId' => 22],
            ['name' => "Egypt",                     'sportId' => 1, 'categoryId' => 34],
            ['name' => "South Africa",              'sportId' => 1, 'categoryId' => 66],
            ['name' => "Morocco",                   'sportId' => 1, 'categoryId' => 52],
            ['name' => "Algeria",                   'sportId' => 1, 'categoryId' => 16],
            ['name' => "Tunisia",                   'sportId' => 1, 'categoryId' => 74],
            ['name' => "Nigeria",                   'sportId' => 1, 'categoryId' => 56],
            ['name' => "Ghana",                     'sportId' => 1, 'categoryId' => 40],
            ['name' => "Kenya",                     'sportId' => 1, 'categoryId' => 48],
            ['name' => "Cameroon",                  'sportId' => 1, 'categoryId' => 28],
            ['name' => "Ivory-Coast",               'sportId' => 1, 'categoryId' => 44]
        ];

        foreach ($seeds as $seed) {
            DB::table('countries')->insert([
                'name' => $seed['name'],
                'sport_id' => $seed['sportId'],
                'category_id' => $seed['categoryId'],
            ]);
        }
    }
}
