<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Rounds extends Model
{
    use HasFactory;

    protected $table = 'rounds';
    protected $fillable = ['name', 'season_id', 'start_date', 'created_at', 'updated_at'];

    const API_ENDPOINT_ROUNDS = '/fixtures/rounds';

    /**
     * @return mixed|string|void
     */
    public function getCurrentRound()
    {
        try {
            $currentRound = '';
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_ROUNDS, [
                'league' => '',
                'season' => '',
                'current' => true
            ]);

            Log::debug("FIXTURE_LEAGUE: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $currentRound = $data['response'];
                }
            }

            return $currentRound;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
