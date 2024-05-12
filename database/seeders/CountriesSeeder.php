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
            ['name' => "Spain",                     'sportId' => 1, 'categoryId' => 7,  'author_id' => 1325],
            ['name' => "France",                    'sportId' => 1, 'categoryId' => 36, 'author_id' => 1326],
            ['name' => "England",                   'sportId' => 1, 'categoryId' => 8,  'author_id' => 1327],
            ['name' => "Italy",                     'sportId' => 1, 'categoryId' => 5,  'author_id' => 1328],
            ['name' => "Germany",                   'sportId' => 1, 'categoryId' => 38, 'author_id' => 1329],
            ['name' => "Brazil",                    'sportId' => 1, 'categoryId' => 26, 'author_id' => 1330],
            ['name' => "Argentina",                 'sportId' => 1, 'categoryId' => 18, 'author_id' => 1331],
            ['name' => "Portugal",                  'sportId' => 1, 'categoryId' => 60, 'author_id' => 1365],
            ['name' => "Netherlands",               'sportId' => 1, 'categoryId' => 54, 'author_id' => 1333],
            ['name' => "United States and Canada",  'sportId' => 1, 'categoryId' => 80, 'author_id' => 1334],
            ['name' => "Russia",                    'sportId' => 1, 'categoryId' => 62, 'author_id' => 1335],
            ['name' => "Turkey",                    'sportId' => 1, 'categoryId' => 76, 'author_id' => 1336],
            ['name' => "Mexico",                    'sportId' => 1, 'categoryId' => 50, 'author_id' => 1362],
            ['name' => "Belgium",                   'sportId' => 1, 'categoryId' => 24, 'author_id' => 1337],
            ['name' => "Scotland",                  'sportId' => 1, 'categoryId' => 64, 'author_id' => 1338],
            ['name' => "Greece",                    'sportId' => 1, 'categoryId' => 42, 'author_id' => 1339],
            ['name' => "Ukraine",                   'sportId' => 1, 'categoryId' => 78, 'author_id' => 1341],
            ['name' => "China",                     'sportId' => 1, 'categoryId' => 30, 'author_id' => 1342],
            ['name' => "Japan",                     'sportId' => 1, 'categoryId' => 46, 'author_id' => 1343],
            ['name' => "South Korea",               'sportId' => 1, 'categoryId' => 68, 'author_id' => 1344],
            ['name' => "Australia",                 'sportId' => 1, 'categoryId' => 20, 'author_id' => 1345],
            ['name' => "Switzerland",               'sportId' => 1, 'categoryId' => 72, 'author_id' => 1346],
            ['name' => "Denmark",                   'sportId' => 1, 'categoryId' => 32, 'author_id' => 1347],
            ['name' => "Sweden",                    'sportId' => 1, 'categoryId' => 70, 'author_id' => 1348],
            ['name' => "Norway",                    'sportId' => 1, 'categoryId' => 58, 'author_id' => 1349],
            ['name' => "Austria",                   'sportId' => 1, 'categoryId' => 22, 'author_id' => 1350],
            ['name' => "Egypt",                     'sportId' => 1, 'categoryId' => 34, 'author_id' => 1351],
            ['name' => "South Africa",              'sportId' => 1, 'categoryId' => 66, 'author_id' => 1352],
            ['name' => "Morocco",                   'sportId' => 1, 'categoryId' => 52, 'author_id' => 1353],
            ['name' => "Algeria",                   'sportId' => 1, 'categoryId' => 16, 'author_id' => 1354],
            ['name' => "Tunisia",                   'sportId' => 1, 'categoryId' => 74, 'author_id' => 1355],
            ['name' => "Nigeria",                   'sportId' => 1, 'categoryId' => 56, 'author_id' => 1356],
            ['name' => "Ghana",                     'sportId' => 1, 'categoryId' => 40, 'author_id' => 1357],
            ['name' => "Kenya",                     'sportId' => 1, 'categoryId' => 48, 'author_id' => 1358],
            ['name' => "Cameroon",                  'sportId' => 1, 'categoryId' => 28, 'author_id' => 1359],
            ['name' => "Ivory-Coast",               'sportId' => 1, 'categoryId' => 44, 'author_id' => 1360],
            ['name' => "Romania",                   'sportId' => 1, 'categoryId' => 86, 'author_id' => 1361],
            ['name' => "World Competitons",         'sportId' => 1, 'categoryId' => 93, 'author_id' => 1367],
            ['name' => "European Competitions",     'sportId' => 1, 'categoryId' => 94, 'author_id' => 1366],
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
