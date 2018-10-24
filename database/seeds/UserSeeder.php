<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class,300)->create();
        $user = \App\User::query()->find(1);
        $user -> name="gaoxiaonao";
        $user -> email = 'shelizi2011@163.com';
        $user -> password = bcrypt('123456');
        $user -> save();
    }
}
