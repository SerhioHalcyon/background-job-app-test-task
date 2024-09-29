<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            'Cherkasy Oblast',
            'Chernihiv Oblast',
            'Chernivtsi Oblast',
            'Dnipropetrovsk Oblast',
            'Donetsk Oblast',
            'Ivano-Frankivsk Oblast',
            'Kharkiv Oblast',
            'Kherson Oblast',
            'Khmelnytskyi Oblast',
            'Kirovohrad Oblast',
            'Kyiv Oblast',
            'Luhansk Oblast',
            'Lviv Oblast',
            'Mykolaiv Oblast',
            'Odessa Oblast',
            'Poltava Oblast',
            'Rivne Oblast',
            'Sumy Oblast',
            'Ternopil Oblast',
            'Vinnytsia Oblast',
            'Volyn Oblast',
            'Zakarpattia Oblast',
            'Zaporizhzhia Oblast',
            'Zhytomyr Oblast',
            'Autonomous Republic of Crimea',
        ];

        foreach ($states as $state) {
            State::updateOrCreate(
                ['name' => $state],
            );
        }
    }
}
