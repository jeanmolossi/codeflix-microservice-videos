<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CastMember extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CastMember::factory(100)->create();
    }
}
