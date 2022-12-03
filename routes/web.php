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
    $day = 2;
    $n = PHP_EOL;
    $input = str(File::get(resource_path(sprintf('aoc_input/day%s.txt', $day))));
    collect([
        // Day 1
        fn() => $input->explode($n.$n)->map(
               fn($i) => str($i)->explode($n)->map(fn($i)=>(int)$i)
            )->map->sum()->sort()->tap(fn($caloriesByElf) => dd(
               'Day 1, Part 1: '.$caloriesByElf->last().' Calories',
                'Day 1, Part 2: '.$caloriesByElf->splice(-3)->sum().' Calories'
            )
        ),
        // Day 2
        fn() => $input->explode($n)->map(
            fn($match) => str($match)->replace(
                ['A', 'B', 'C', 'X', 'Y', 'Z'], [1, 2, 3, 1, 2, 3]
            )->explode(' ')
        )->map(fn($point) =>
            (($result = $point[0]-$point[1]) === 0
                ? 3 : ($result === -1 || $result === 2 ? 6 : 0)
            ) + $point[1]
        )->tap(function($scoresPerMatch) {
                   dd(
                   'Day 2, Part 1: '.$scoresPerMatch->sum(),
                   'Day 2, Part 2: '
                   );
        }),
        // Day 3
//        fn() => $input->dd('dostuffhere')->tap(function($caloriesByElf) {
//            dump('Day 2, Part 1: ');
//            dump('Day 2, Part 2: ');
//        }),
    ])[$day-1]();
});
