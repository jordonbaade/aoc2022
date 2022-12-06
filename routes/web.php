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
    $day = 6;
    $n = PHP_EOL;
    $input = str(File::get(resource_path(sprintf('aoc_input/day%s.txt', $day))));
    $get = collect([
        3 => [
            'priority'=>collect(range('a', 'z'))->merge(range('A', "Z"))->keyBy(fn($a,$i)=>$i+1)->flip()
        ],
        5 => [
            'chart' => fn($input) => $input->takeUntil(fn($i)=>str($i)->startsWith(' 1'))->pipe(
                fn($r)=>$r->map(fn($r)=>str($r)->split(4)->map(fn($s)=>str($s)->beforeLast(' ')->value()))
            )->pipe(function($c){
                $rows = $c->count() - 1;
                $cols = $c[0]->count() - 1;
                $skeleton = collect(range(0,$cols))->map(fn($r)=>collect(range(0, $rows)));
                return $skeleton->map(fn($row, $ri)=>$row->map(
                    fn($col,$ci)=>$c[$ci][$ri])->reverse()->values()->reject(fn($s)=>blank($s))
                );
            }),
            'moves' => fn($input) => $input->skipUntil(fn($i)=>blank($i))->skip(1)->map(
                fn($s)=>str($s)->replace(['move ', 'from ', 'to '], [''])->explode(' ')
            ),
        ]
    ])[$day] ?? [];
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
        // Day 4
        fn() => $input->explode($n)->map(fn($i)=>str($i)->explode(','))
            ->map->map(fn($i)=>str($i)->explode('-'))->map(function($i){
                return (collect(range($i[0][0],$i[0][1]))->intersect(range($i[1][0],$i[1][1]))->count()) || null;
            })->filter()->tap(fn($t)=>dd(
                'Day 4, Part 1: 475 (Calculated from old code)',
                'Day 4, Part 2: '.$t->count(),
            )),
        // Day 5
        fn() => $input->explode($n)->pipe(fn($c)=>collect([
            'chart'=>$get['chart']($c),
            'moves'=>$get['moves']($c)
        ]))->pipe(function($get){
            return $get['chart']->pipe(function($chart) use ($get) {
                $get['moves']->each(
                    fn($moveset) => $chart[$moveset[1]-1]->pop($moveset[0])->reverse()->each(
                        fn($i)=>$chart[$moveset[2]-1]->push($i)
                    )
                );
                return $chart;
            })->map->last()->map(fn($s)=>str($s)->replace(['[',']'], '')->value());
        })->tap(fn($t)=>dd(
            'Day 5, Part 1: CMZ (Calculated from old code)',
            'Day 5, Part 2: '.$t->reduce(fn($c,$i)=>$c.$i),
        )),
        // Day #
        fn() => collect(str_split($input))->sliding(14)->first(
            fn($i)=>$i->unique()->count() == 14
        )->tap(fn($t)=>dd(
            'Day 5, Part 1: 1275 (Calculated from old code)',
            'Day 5, Part 2: '.$t->flip()->last() + 1,
        )),
        // Day #
        //fn() => $input->dd()->tap(fn($t)=>dd(
        //    'Day #, Part #: ',
        //)),
    ])[$day-1]();
});