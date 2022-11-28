<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Group;
use App\Models\Group_File;
use App\Models\Group_User;
use App\Models\User;
use App\Models\User_File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1;$i < 21 ; $i++){
            $name = Str::random(5);
            User::create([
                "name" => $name,
                "email" => "$name@$name",
                "password" => password_hash("12345678",PASSWORD_DEFAULT),
            ]);
        }
        for ($i = 1;$i <= 5 ; $i++){
            $name = Str::random(10);
            File::create([
                "id_user" => $i,
                "name" => $name,
                "path" => "Uploads/files/test.txt"
            ]);
        }
        Group::create([
            "id_user" => 1,
            "name" => "public",
            "type" => "public"
        ]);
        for ($i = 1;$i <= 5 ; $i++){
            $name = Str::random(10);
            Group::create([
                "id_user" => $i,
                "name" => $name
            ]);
        }
        for ($i = 1;$i < 21 ; $i++){
            Group_User::create([
                "id_user" => $i,
                "id_group" => random_int(1,6)
            ]);
        }
        for ($i = 1;$i < 6 ; $i++){
            Group_File::create([
                "id_file" => $i,
                "id_group" => random_int(1,6)
            ]);
        }
        for ($i = 1;$i < 6 ; $i++){
            User_File::create([
                "id_file" => $i,
                "id_user" => $i
            ]);
        }
    }
}
