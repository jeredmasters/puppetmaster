<?php

namespace App;

use App\Test;
use App\Result;
use App\Benchmark;
use App\Chromosome;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class Analysis
{

  protected $analyses;

  public function __construct(){
    $this->analyses = static::Analyses();
  }

  public function getAnalysesWithResults(){
    $analyses = Analysis::analyses();

    for($a = 0; $a < count($analyses); $a ++){
      $lowest_samples = 999999;
      $total_samples = 0;
      $total_points = 0;
      for($s = 0; $s < count($analyses[$a]['sets']); $s++){
        $results = $this->results($analyses[$a]['sets'][$s], $analyses[$a]['x'], $analyses[$a]['y'], $analyses[$a]['static']);

        $analyses[$a]['sets'][$s]['data'] = $results['data'];
        $analyses[$a]['sets'][$s]['meta'] = $results['meta'];
        $total_points += $results['meta']['points'];
        $total_samples += $results['meta']['total_samples'];
        if ($results['meta']['lowest_samples'] < $lowest_samples){
          $lowest_samples = $results['meta']['lowest_samples'];
        }
      }
      $analyses[$a]['meta'] =  [
        'total_points' => $total_points,
        'average_samples' => $total_samples / $total_points,
        'total_samples' => $total_samples,
        'lowest_samples' => $lowest_samples 
      ];
    }

    return $analyses;
  }

  
  public static function results($setParams, $xParams, $yParams, $static){
    $lowest_samples = 999999;
    $total_samples = 0;
    $points = 0;
    
    $results = [];

    $y_col = 'results.'.$yParams['column'];
  
    foreach($xParams['values'] as $x){
      $x_col = 'tests.'.$xParams['column'];
      $q = DB::table('results')
        ->join('tests', 'tests.id', '=', 'results.test_id')
        ->where($x_col, '=', $x)
        ->where('fitness', '!=', -1)
        ->where('tests.active', true);
      foreach($static as $key => $value){
        $q->where($key, $value);
      }

      foreach($setParams['filter'] as $col => $val){
        $q = $q->where('tests.'.$col, $val);
      }        

      

      $y = $q->avg($y_col);
      $samples = $q->count($y_col);
      $total_samples += $samples;
      if ($samples < $lowest_samples){
        $lowest_samples = $samples;
      }


      $data[] = [
        "x" => $x,
        "y" => floatval($y),
        "samples" => $samples,
        "stdDev" => static::stdDev($q->pluck($y_col)->toArray())
      ];
      $points += 1;
    }    

    return [
      'data' => $data,
      'meta' => [
        'points' => $points,
        'average_samples' => $total_samples / $points,
        'total_samples' => $total_samples,
        'lowest_samples' => $lowest_samples 
      ]
    ];
  }

  private static function stdDev($a){
    $n = count($a);
    if ($n === 0) {        
        return 0;
    }
    $mean = array_sum($a) / $n;
    $carry = 0.0;
    foreach ($a as $val) {
        $d = ((double) $val) - $mean;
        $carry += $d * $d;
    };
    return sqrt($carry / $n);
  }

  public static function Analyses(){
    return [
      [
        'title' => 'Populations and Generations',
        'static' => [  
          'selection_pressure' => 2,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'duration_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => 20, 'filter' => ['population' => 20]],
          ['label' => 40, 'filter' => ['population' => 40]],
          ['label' => 60, 'filter' => ['population' => 60]],
          ['label' => 80, 'filter' => ['population' => 80]],
          ['label' => 100, 'filter' => ['population' => 100]],
          ['label' => 120, 'filter' => ['population' => 120]],
          ['label' => 140, 'filter' => ['population' => 140]],
          ['label' => 160, 'filter' => ['population' => 160]],
          ['label' => 180, 'filter' => ['population' => 180]],
          ['label' => 200, 'filter' => ['population' => 200]]
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]  
      ],
      [
        'title' => 'No Mutation Variance',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_variance' => 0,
          'duration_variance' => 0,
          'crossover_rate' => 6
        ],
        'sets' => [
          ['label' => '0', 'filter' => ['mutation_rate' => 0, 'steepest_descent' => 0]],
          ['label' => '1', 'filter' => ['mutation_rate' => 1, 'steepest_descent' => 0]],
          ['label' => '2', 'filter' => ['mutation_rate' => 2, 'steepest_descent' => 0]],
          ['label' => '3', 'filter' => ['mutation_rate' => 3, 'steepest_descent' => 0]],
          ['label' => '4', 'filter' => ['mutation_rate' => 4, 'steepest_descent' => 0]],
          ['label' => '5', 'filter' => ['mutation_rate' => 5, 'steepest_descent' => 0]],
          ['label' => '6', 'filter' => ['mutation_rate' => 6, 'steepest_descent' => 0]],
          ['label' => '0 SD', 'filter' => ['mutation_rate' => 0, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '1 SD', 'filter' => ['mutation_rate' => 1, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '2 SD', 'filter' => ['mutation_rate' => 2, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '3 SD', 'filter' => ['mutation_rate' => 3, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '4 SD', 'filter' => ['mutation_rate' => 4, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '5 SD', 'filter' => ['mutation_rate' => 5, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '6 SD', 'filter' => ['mutation_rate' => 6, 'steepest_descent' => 1], 'style' => 'dashed']
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Linear Mutation Variance',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_variance' => 1,
          'duration_variance' => 0,
          'crossover_rate' => 6
        ],
        'sets' => [
          ['label' => '0', 'filter' => ['mutation_rate' => 0, 'steepest_descent' => 0]],
          ['label' => '1', 'filter' => ['mutation_rate' => 1, 'steepest_descent' => 0]],
          ['label' => '2', 'filter' => ['mutation_rate' => 2, 'steepest_descent' => 0]],
          ['label' => '3', 'filter' => ['mutation_rate' => 3, 'steepest_descent' => 0]],
          ['label' => '4', 'filter' => ['mutation_rate' => 4, 'steepest_descent' => 0]],
          ['label' => '5', 'filter' => ['mutation_rate' => 5, 'steepest_descent' => 0]],
          ['label' => '6', 'filter' => ['mutation_rate' => 6, 'steepest_descent' => 0]],
          ['label' => '0 SD', 'filter' => ['mutation_rate' => 0, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '1 SD', 'filter' => ['mutation_rate' => 1, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '2 SD', 'filter' => ['mutation_rate' => 2, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '3 SD', 'filter' => ['mutation_rate' => 3, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '4 SD', 'filter' => ['mutation_rate' => 4, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '5 SD', 'filter' => ['mutation_rate' => 5, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '6 SD', 'filter' => ['mutation_rate' => 6, 'steepest_descent' => 1], 'style' => 'dashed']
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Bitwise Mutation Variance',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_variance' => 2,
          'duration_variance' => 0,
          'crossover_rate' => 6
        ],
        'sets' => [
          ['label' => '0', 'filter' => ['mutation_rate' => 0, 'steepest_descent' => 0]],
          ['label' => '1', 'filter' => ['mutation_rate' => 1, 'steepest_descent' => 0]],
          ['label' => '2', 'filter' => ['mutation_rate' => 2, 'steepest_descent' => 0]],
          ['label' => '3', 'filter' => ['mutation_rate' => 3, 'steepest_descent' => 0]],
          ['label' => '4', 'filter' => ['mutation_rate' => 4, 'steepest_descent' => 0]],
          ['label' => '5', 'filter' => ['mutation_rate' => 5, 'steepest_descent' => 0]],
          ['label' => '6', 'filter' => ['mutation_rate' => 6, 'steepest_descent' => 0]],
          ['label' => '0 SD', 'filter' => ['mutation_rate' => 0, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '1 SD', 'filter' => ['mutation_rate' => 1, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '2 SD', 'filter' => ['mutation_rate' => 2, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '3 SD', 'filter' => ['mutation_rate' => 3, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '4 SD', 'filter' => ['mutation_rate' => 4, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '5 SD', 'filter' => ['mutation_rate' => 5, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => '6 SD', 'filter' => ['mutation_rate' => 6, 'steepest_descent' => 1], 'style' => 'dashed']
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Mutation Variance',
        'static' => [
          'population' => 100,   
          'selection_pressure' => 2,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 3,
          'duration_variance' => 0,
          'steepest_descent' => 0
        ],
        'sets' => [
          ['label' => 'None', 'filter' => ['mutation_variance' => 0]],
          ['label' => 'Linear', 'filter' => ['mutation_variance' => 1]],
          ['label' => 'Bitwise', 'filter' => ['mutation_variance' => 2]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Mutation Variance with Steepest Descent',
        'static' => [
          'population' => 100,   
          'selection_pressure' => 2,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 3,
          'duration_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => 'None', 'filter' => ['mutation_variance' => 0]],
          ['label' => 'Linear', 'filter' => ['mutation_variance' => 1]],
          ['label' => 'Bitwise', 'filter' => ['mutation_variance' => 2]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      
      [
        'title' => 'Steepest Descent',
        'static' => [
          'population' => 100,    
          'selection_pressure' => 2,
          'duration' => 100 * 150,      
          'mutation_variance' => 0,
          'duration_variance' => 0
        ],
        'sets' => [
          ['label' => 'Off MR=4', 'filter' => ['steepest_descent' => 0, 'mutation_rate' => 4, 'crossover_rate' => 6]],
          ['label' => 'On  MR=4', 'filter' => ['steepest_descent' => 1, 'mutation_rate' => 4, 'crossover_rate' => 6]],
          ['label' => 'Off MR=1', 'filter' => ['steepest_descent' => 0, 'mutation_rate' => 1, 'crossover_rate' => 0]],
          ['label' => 'On  MR=1', 'filter' => ['steepest_descent' => 1, 'mutation_rate' => 1, 'crossover_rate' => 0]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Selection Pressure',
        'static' => [
          'population' => 100,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'duration_variance' => 0,
          'steepest_descent' => 0
        ],
        'sets' => [
          ['label' => '1', 'filter' => ['selection_pressure' => 1]],
          ['label' => '2', 'filter' => ['selection_pressure' => 2]],
          ['label' => '3', 'filter' => ['selection_pressure' => 3]],
          ['label' => '4', 'filter' => ['selection_pressure' => 4]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Selection Pressure with Steepest Descent',
        'static' => [
          'population' => 100,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'duration_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => '1', 'filter' => ['selection_pressure' => 1]],
          ['label' => '2', 'filter' => ['selection_pressure' => 2]],
          ['label' => '3', 'filter' => ['selection_pressure' => 3]],
          ['label' => '4', 'filter' => ['selection_pressure' => 4]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Crossover Rate',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'duration_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => '0', 'filter' => ['crossover_rate' => 0]],
          ['label' => '1', 'filter' => ['crossover_rate' => 1]],
          ['label' => '2', 'filter' => ['crossover_rate' => 2]],
          ['label' => '3', 'filter' => ['crossover_rate' => 3]],
          ['label' => '4', 'filter' => ['crossover_rate' => 4]],
          ['label' => '5', 'filter' => ['crossover_rate' => 5]],
          ['label' => '6', 'filter' => ['crossover_rate' => 6]]
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],      
      [
        'title' => 'Duration Variance VS Fitness',
        'static' => [
          'population' => 100,
          'selection_pressure' => 2,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => 'Off', 'filter' => ['duration_variance' => 0]],
          ['label' => 'On', 'filter' => ['duration_variance' => 1]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'fitness'
        ]    
      ],
      [
        'title' => 'Duration Variance VS Scaled Fitness',
        'static' => [
          'population' => 100,
          'selection_pressure' => 2,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => 'Off', 'filter' => ['duration_variance' => 0]],
          ['label' => 'On', 'filter' => ['duration_variance' => 1]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'scaled_fitness'
        ]    
      ],
      [
        'title' => 'Duration Variance VS Runtime',
        'static' => [
          'population' => 100, 
          'selection_pressure' => 2,
          'duration' => 100 * 150,
          'crossover_rate' => 6,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'steepest_descent' => 1
        ],
        'sets' => [
          ['label' => 'Off', 'filter' => ['duration_variance' => 0]],
          ['label' => 'On', 'filter' => ['duration_variance' => 1]],
        ],
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
        ],
        'y' => [
          'label' => 'Duration',
          'column' => 'scaled_millis'
        ]    
      ]
    ];
  }
}