<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::insert([
            'name' =>'Agent System',
            'parent' => '-1',
            'had_child' => '1'
        ]);
        Category::insert([
            'name' =>'Super Master',
            'parent' => '1',
            'had_child' => '-1'
        ]);
        Category::insert([
            'name' =>'Master',
            'parent' => '1',
            'had_child' => '-1'
        ]);
        Category::insert([
            'name' =>'Agent',
            'parent' => '1',
            'had_child' => '-1'
        ]);
        Category::insert([
            'name' =>'Api',
            'parent' => '-1',
            'had_child' => '-1'
        ]);
        Category::insert([
            'name' =>'White Label',
            'parent' => '-1',
            'had_child' => '-1'
        ]);
    }
}
