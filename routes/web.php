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

Route::get('/', function () {
    // Helpers
    $day = 3;
    $n = PHP_EOL;
    $input = str(File::get(resource_path(sprintf('aoc_input/day%s.txt', $day))));
    $get = collect([
        3 => [
            'priority'=>collect(range('a', 'z'))->merge(range('A', "Z"))->keyBy(fn($a,$i)=>$i+1)->flip()
        ]
    ])[$day];
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
                ['A', 'B', 'C', 'X', 'Y', 'Z'], [1, 2, 3, 0, 3, 6]
            )->explode(' ')
        )->map(fn($p) => $p[1] + (
               $p[1] == 3
            ?  $p[0]
            : ($p[1] == 6
            ? ($p[0] == 3 ? 1 : $p[0]+1)
            : ($p[0] == 1 ? 3 : $p[0]-1))
        ))->tap(fn($scoresPerMatch) => dd(
           'Day 2, Part 1: 15422 (Calculated from old code)',
           'Day 2, Part 2: '.$scoresPerMatch->sum()
        )),
        // Day 3
        fn() => $input->explode($n)->map(
            fn($i)=>collect(str_split($i))
        )->chunk(3)->map->values()->map(
            fn($i)=>$i[0]->intersect($i[1])->intersect($i[2])->unique()->first()
        )->map(fn($c) => $get['priority'][$c]
        )->tap(fn($t)=>dd(
            'Day 3, Part 1: 8252 (Calculated from old code)',
            'Day 3, Part 2: '.$t->sum()
        )),
        // Day #
        // fn() => $input->dd('dostuffhere')->tap(fn($t)=>dd(
        //    'Day #, Part #: ',
        // )),
    ])[$day-1]();
});