<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = [
            [
                'appid' => 'ry2f6yr96ngrdz3rkb',
                'secret' => 'vyb9xyoilg7h8hsbt3717mtdk8nanife',
                'name' => '若愚优选小程序',
                'desc' => '若愚优选小程序应用',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'appid' => 'ry4nw3gozrzot0k8q0',
                'secret' => '42qufxe75bwibzcvq5s0sx3faxyse6cq',
                'name' => '若愚精选小程序',
                'desc' => '若愚精选小程序应用',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        collect($defaults)->each(function ($default) {
            DB::table('clients')->insert($default);
        });
    }
}
