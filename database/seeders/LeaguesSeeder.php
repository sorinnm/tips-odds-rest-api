<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeds = [
            ['name' => 'La Liga',                       'countryId' => 1, 'apiFootballId' => 140,   'categoryId' => 9],
            ['name' => 'Ligue 1',                       'countryId' => 2, 'apiFootballId' => 61,    'categoryId' => 37],
            ['name' => 'Premier League',                'countryId' => 3, 'apiFootballId' => 39,    'categoryId' => 10],
            ['name' => 'Serie A',                       'countryId' => 4, 'apiFootballId' => 135,   'categoryId' => 6],
            ['name' => 'Bundesliga',                    'countryId' => 5, 'apiFootballId' => 78,    'categoryId' => 39],
            ['name' => 'Serie A',                       'countryId' => 6, 'apiFootballId' => 71,    'categoryId' => 27],
            ['name' => 'Liga Profesional Argentina',    'countryId' => 7, 'apiFootballId' => 128,   'categoryId' => 19],
            ['name' => 'Primeira Liga',                 'countryId' => 8, 'apiFootballId' => 94,    'categoryId' => 61],
            ['name' => 'Eredivisie',                    'countryId' => 9, 'apiFootballId' => 88,    'categoryId' => 55],
            ['name' => 'MLS All-Star',                  'countryId' => 10, 'apiFootballId' => 866,  'categoryId' => 81],
            ['name' => 'Premier League',                'countryId' => 11, 'apiFootballId' => 235,  'categoryId' => 63],
            ['name' => 'Süper Lig',                     'countryId' => 12, 'apiFootballId' => 203,  'categoryId' => 77],
            ['name' => 'Liga MX',                       'countryId' => 13, 'apiFootballId' => 262,  'categoryId' => 51],
            ['name' => 'Jupiler Pro League',            'countryId' => 14, 'apiFootballId' => 144,  'categoryId' => 25],
            ['name' => 'Premiership',                   'countryId' => 15, 'apiFootballId' => 179,  'categoryId' => 65],
            ['name' => 'Super League 1',                'countryId' => 16, 'apiFootballId' => 197,  'categoryId' => 43],
            ['name' => 'Premier League',                'countryId' => 17, 'apiFootballId' => 333,  'categoryId' => 79],
            ['name' => 'Super League',                  'countryId' => 18, 'apiFootballId' => 169,  'categoryId' => 31],
            ['name' => 'J1 League',                     'countryId' => 19, 'apiFootballId' => 98,   'categoryId' => 47],
            ['name' => 'K League 1',                    'countryId' => 20, 'apiFootballId' => 292,  'categoryId' => 69],
            ['name' => 'A-League',                      'countryId' => 21, 'apiFootballId' => 188,  'categoryId' => 21],
            ['name' => 'Super League',                  'countryId' => 22, 'apiFootballId' => 207,  'categoryId' => 73],
            ['name' => 'Superliga',                     'countryId' => 23, 'apiFootballId' => 119,  'categoryId' => 33],
            ['name' => 'Allsvenskan',                   'countryId' => 24, 'apiFootballId' => 113,  'categoryId' => 71],
            ['name' => 'Eliteserien',                   'countryId' => 25, 'apiFootballId' => 103,  'categoryId' => 59],
            ['name' => 'Bundesliga',                    'countryId' => 26, 'apiFootballId' => 218,  'categoryId' => 23],
            ['name' => 'Premier League',                'countryId' => 27, 'apiFootballId' => 233,  'categoryId' => 35],
            ['name' => 'Premier Soccer League',         'countryId' => 28, 'apiFootballId' => 288,  'categoryId' => 67],
            ['name' => 'Botola Pro',                    'countryId' => 29, 'apiFootballId' => 200,  'categoryId' => 53],
            ['name' => 'Ligue 1',                       'countryId' => 30, 'apiFootballId' => 186,  'categoryId' => 17],
            ['name' => 'Ligue 1',                       'countryId' => 31, 'apiFootballId' => 202,  'categoryId' => 75],
            ['name' => 'NPFL',                          'countryId' => 32, 'apiFootballId' => 399,  'categoryId' => 57],
            ['name' => 'Premier League',                'countryId' => 33, 'apiFootballId' => 570,  'categoryId' => 41],
            ['name' => 'FKF Premier League',            'countryId' => 34, 'apiFootballId' => 276,  'categoryId' => 49],
            ['name' => 'Elite One',                     'countryId' => 35, 'apiFootballId' => 411,  'categoryId' => 29],
            ['name' => 'Ligue 1',                       'countryId' => 36, 'apiFootballId' => 386,  'categoryId' => 45]
        ];

        foreach ($seeds as $seed) {
            DB::table('leagues')->insert([
                'name' => $seed['name'],
                'country_id' => $seed['countryId'],
                'api_football_id' => $seed['apiFootballId'],
                'category_id' => $seed['categoryId']
            ]);
        }
    }
}
