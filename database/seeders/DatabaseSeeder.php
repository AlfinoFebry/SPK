<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Alternative;
use App\Models\Criteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            AlternativeSeeder::class,
            CriteriaSeeder::class,
        ]);

        $datas = [];
        $alternatives = Alternative::all('id');
        $criterias = Criteria::all('id');

        foreach ($alternatives as $alternative) {
            foreach ($criterias as $criteria) {
                $data = [
                    'alternative_id' => $alternative->id,
                    'criteria_id' => $criteria->id,
                    'value' => rand(1, 5),
                ];
                array_push($datas, $data);
            }
        }


        DB::table('electre_evaluations')->insert($datas);
    }
}
