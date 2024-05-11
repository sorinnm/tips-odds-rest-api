<?php

namespace App\Http\Controllers;

use App\Services\ApiFootballService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Fixtures;
use Illuminate\Support\Facades\DB;

class FixtureController extends Controller
{
    /**
     * @param ApiFootballService $apiFootballService
     */
    public function __construct(
        protected ApiFootballService $apiFootballService
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->get('paginate');

        if (empty($paginate)) {
            return response()->json("Please use a paginate parameter", 400);
        }
        $fixtures = Fixtures::paginate($paginate);
        return response()->json($fixtures);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        $saved = false;

        // Before creating a new fixture, try to find an existing one
        $fixtures = Fixtures::all();
        $fixture = $fixtures->firstWhere('fixture_id', $request->get('fixture_id'));

        if (empty($fixture)) {
            $fixture = new Fixtures;
        }

        $columns = $request->keys();
        foreach ($columns as $column) {
            $fixture->$column = json_encode($request->$column);
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

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $fixtures = Fixtures::all();
        return response()->json($fixtures->where('fixture_id', $id));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        DB::table('fixtures')->where('fixture_id', '=', $id)->delete();
        return response()->json(null, 204);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function escape(Request $request): JsonResponse
    {
        $jsonData = $request->getContent();
        if (empty($jsonData) || !$request->isJson()) {
            $jsonData = ['error' => 'Unable to provide results'];
        }

        return response()->json(["data" => $jsonData]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function storeFixturesAllData(Request $request): JsonResponse
    {
        return response()->json($this->apiFootballService->importFixtures(
            $request->get('league'),
            $request->get('season')
        ));
    }
}
