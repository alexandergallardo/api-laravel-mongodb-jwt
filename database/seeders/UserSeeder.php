<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    protected $connection = 'mongodb';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::collection('users')->delete();

        for($i = 1; $i <= 6; $i++)
        {
            DB::collection('users')->insert(["username" => "tester".$i,
                                            "password" => bcrypt("tester".$i),
                                            "role" => ($i % 2) ? "manager" : "agent",
                                            "is_active" => TRUE,
                                            "last_login" => null,
                                            "_id" => $i]);
        }

    }
}
