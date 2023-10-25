<?php

use App\Models\Alternative;
use App\Models\Criteria;
use App\Services\ElectreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function (ElectreService $service) {
    $matrix = DB::table('electre_evaluations')->orderBy('alternative_id')->get();
    $matrixArray2d = $service->toArray($matrix);
    $criterias = Criteria::orderBy('id')->get();
    $m = Alternative::count();
    $n = Criteria::count();

    $nMatrix = $service->normalizedMatrix($matrixArray2d);
    $matrixV = $service->weightingNormalizedMatrix($nMatrix, $criterias);

    $cdIndex = $service->findConcordanceDiscordanceIndex($matrixV, $m, $n);
    $C = $service->findConcordanceMatrix($cdIndex['concordance'], $criterias, $m);
    $D = $service->findDiscordanceMatrix($matrixV, $cdIndex['discordance'], $m, $n);
    $threshold_c = $service->findThresholdC($C, $m);
    $threshold_d = $service->findThresholdD($D, $m);
    $F = $service->findConcordanceDominance($C, $threshold_c);
    $G = $service->findDiscordanceDominance($D, $threshold_d);

    return $service->findAggregateDominance($F, $G);
});
