<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fixtures;
use Illuminate\Support\Facades\DB;

class FixtureController extends Controller
{
    public function index()
    {
        $fixtures = Fixtures::all();
        return response()->json($fixtures);
    }

    public function save(Request $request)
    {
        $saved = false;

        // Before creating a new fixture, try to find an existing one
        $fixtures = Fixtures::all();
        $fixture = $fixtures->firstWhere('fixture_id', $request->fixture_id);

        if (empty($fixture)) {
            $fixture = new Fixtures;
        }

        $columns = $request->keys();
        foreach ($columns as $column) {
            $fixture->$column = $request->$column;
        }

        $saved = $fixture->save();
        $message = 'Fixture has been saved';

        if (!$saved) {
            $message = 'Unable to save Fixture';
        }

        return response()->json([
            'message' => $message
        ]);
    }

    public function show($id)
    {
        $fixtures = Fixtures::all();
        $fixtures->where('fixture_id', $id)->delete();
        return response()->json($fixtures->where('fixture_id', $id));
    }

    public function delete($id)
    {
        DB::table('fixtures')->where('fixture_id', '=', $id)->delete();
        return response()->json(null, 204);
    }

    public function escape(Request $request)
    {
        $jsonData = $request->getContent();
        if (empty($jsonData) || !$request->isJson()) {
            $jsonData = ['error' => 'Unable to provide results'];
        }

        return response()->json(["data" => $jsonData]);
    }
}
