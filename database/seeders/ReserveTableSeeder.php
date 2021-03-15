<?php

namespace Database\Seeders;

use App\Models\Reserve;
use Illuminate\Database\Seeder;

class ReserveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       if(Reserve::count() < 1){
        $alpha = ['empty','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
          $val = [2,9,2,2,3,15,2,2,2,8,1,1,5,3,6,6,2,1,6,6,6,6,2,1,1,1,1];
          for($i=0; $i < 27; $i++){
              Reserve::create([
                'lettre'=>$alpha[$i],
                'quantite'=>$val[$i]
              ]);
          }
       }
    }
}
