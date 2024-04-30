<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fixtures;

class FixtureController extends Controller
{
    public function index()
    {
        $fixtures = Fixtures::all();
        return response()->json($fixtures);
    }

    public function save(Request $request)
    {
        // $fixture = new Fixtures;
        // 'fixtures', 'standings', 'home_team_squad', 'away_team_squad', 'injuries', 'predictions', 'head_to_head', 'bets', 'status', 'created_at', 'updated_at'
//        $fixture->fixture_id($request->fixture_id);
//        $fixture->standings($request->standings);
//        $fixture->save();

        die(var_dump($request->all()));

        return response()->json([
            'message' => 'Fixture created'
        ]);
    }
}
