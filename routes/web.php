<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Helpers

Route::get('/', function () {
    $day = 1;
    collect([
        // Day 1
        fn() => str(
               File::get(resource_path('aoc_input/day1/input.txt'))
           )->explode(PHP_EOL.PHP_EOL)->map(
               fn($i) => str($i)->explode(PHP_EOL)->map(fn($i)=>(int)$i)
           )->map->sum()->sort()->tap(function($caloriesByElf) {
               dump('Day 1, Part 1: '.$caloriesByElf->last().' Calories');
               dump('Day 1, Part 2: '.$caloriesByElf->splice(-3)->sum().' Calories');
        }),
        // Day 2
        fn() => str(
               File::get(resource_path('aoc_input/day1/input.txt'))
           )->tap(function($caloriesByElf) {
               dump('Day 2, Part 1: ');
               dump('Day 2, Part 2: ');
        }),
        // Day 2
//        fn() => str(
//            File::get(resource_path('aoc_input/day1/input.txt'))
//        )->tap(function($caloriesByElf) {
//            dump('Day 2, Part 1: ');
//            dump('Day 2, Part 2: ');
//        }),
    ])[$day-1]();
});
