<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Services\ElectreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectreController extends Controller
{
    protected ElectreService $service;

    public function __construct(ElectreService $service) {
        $this->service = $service;
    }

    public function index()
    {
        $matriks = DB::table('electre_evaluations')
                ->select('*')
                ->orderBy('alternative_id')
                ->orderBy('criteria_id')
                ->get();
        
        $weight = DB::table('criterias')
                ->select('weight')
                ->orderBy('id')
                ->get();

        $array = $this->service->toArray($matriks);
        $normalized = $this->service->normalizedMatrix($array);
        $preferensi = $this->service->weightingNormalizedMatrix($normalized, $weight);

        $m = Alternative::count();
        $n = 5;
        $index = $this->service->findConcordanceDiscordanceIndex($preferensi, $m, $n);

        $concordancematrix = $this->service->findConcordanceMatrix($index['concordance'], $weight, $m);
        $disordancematrix = $this->service->findDiscordanceMatrix($preferensi, $index['discordance'], $m, $n);

        $concordanceThreshold = $this->service->findThresholdC($concordancematrix, $m);
        $discordanceThreshold = $this->service->findThresholdD($disordancematrix, $m);

        $concordanceDominance = $this->service->findConcordanceDominance($concordancematrix, $concordanceThreshold);
        $discordanceDominance = $this->service->findDiscordanceDominance($disordancematrix, $discordanceThreshold);
        $aggregateDominance = $this->service->findAggregateDominance($concordanceDominance, $discordanceDominance);  

        return view('electre', [
            'array'=> $array, 
            'normalized' => $normalized,
            'weight' => $weight,
            'preferensi' => $preferensi,
            'concordanceIndex' => $index['concordance'],
            'discordanceIndex' => $index['discordance'],
            'concordanceMatrix' => $concordancematrix,
            'discordanceMatrix' => $disordancematrix,
            'concordanceThreshold' => $concordanceThreshold,
            'discordanceThreshold' => $discordanceThreshold,
            'concordanceDominance' => $concordanceDominance,
            'discordanceDominance' => $discordanceDominance,
            'aggregateDominance' => $aggregateDominance,
        ]);
    }
}
