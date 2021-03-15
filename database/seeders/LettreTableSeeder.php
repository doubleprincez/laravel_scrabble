<?php

namespace Database\Seeders;

use App\Models\Lettre;
use Illuminate\Database\Seeder;

class LettreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       if(Lettre::count() < 1){
           $alpha = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
           $val = [1,3,3,2,1,4,2,4,1,8,5,1,3,1,1,3,10,1,1,1,1,4,4,8,4,10];
      
           for($i = 0; $i < 26; $i++){
               Lettre::create([
                   'lettre'=>$alpha[$i],
                   'valeur'=>$val[$i]
               ]);
           }
        }
    }
}
