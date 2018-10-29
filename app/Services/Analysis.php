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
      $analysis = $analyses[$a];
      $total_least = 999999;
      $samples = 0;
      $total_points = 0;
      $total_limit = 0;
      $total_max = 0;      
      $total_min = 999999;

      for($g = 0; $g < count($analysis['graphs']); $g++){
        $analysis = $analyses[$a];
        $graph_least = 999999;
        $graph_samples = 0;
        $graph_points = 0;
        $graph_limit = 0;
        $graph_max = 0;        
        $graph_min = 999999;


        $graph = $analysis['graphs'][$g];
        for($s = 0; $s < count($graph['sets']); $s++){
          $set = $graph['sets'][$s];
          $results = $this->results($set, $analysis['x'], $analysis['y'], $analysis['static']);

          $analyses[$a]['graphs'][$g]['sets'][$s]['data'] = $results['data'];
          $analyses[$a]['graphs'][$g]['sets'][$s]['meta'] = $results['meta'];
          $graph_points += $results['meta']['points'];
          $graph_samples += $results['meta']['samples'];
         
          if ($results['meta']['least'] < $graph_least){
            $graph_least = $results['meta']['least'];
          }            
          if ($results['meta']['limit'] > $graph_limit){
            $graph_limit = $results['meta']['limit'];
          } 
          if ($results['meta']['max'] > $graph_max){
            $graph_max = $results['meta']['max'];
          }
          if ($results['meta']['min'] < $graph_min){
            $graph_min = $results['meta']['min'];
          }       
        }
        $analyses[$a]['graphs'][$g]['meta'] =  [
          'points' => $graph_points,
          'samples' => $graph_samples,
          'least' => $graph_least,
          'max' => $graph_max,
          'min' => $graph_min
        ];
        if ($graph_least < $total_least){
          $total_least = $graph_least;
        }
        if ($graph_limit > $total_limit){
          $total_limit = $graph_limit;
        }
        if ($graph_max > $total_max){
          $total_max = $graph_max;
        }
        if ($graph_min < $total_min){
          $total_min = $graph_min;
        }
        $total_points += $graph_points;
        $samples += $graph_samples;
      }
      $analyses[$a]['meta'] =  [
        'points' => $total_points,
        'samples' => $samples,
        'least' => $total_least,
        'limit' => $total_limit,
        'max' => $total_max,
        'min' => $total_min
      ];
    }

    return $analyses;
  }

  
  public static function results($setParams, $xParams, $yParams, $static){
    $least = 999999;
    $total_samples = 0;
    $points = 0;
    $max = 0;
    $limit = 0;
    $min = 999999;
    
    $results = [];

    $y_col = 'results.'.$yParams['column'];
  
    foreach($xParams['values'] as $x){
      $x_col = 'tests.'.$xParams['column'];
      $q = DB::table('results')
        ->join('tests', 'tests.id', '=', 'results.test_id')
        ->where($x_col, '=', $x)
        ->where('results.status', 'complete')
      //  ->where('fitness', '!=', -1)
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
      $stdDev = static::stdDev($q->pluck($y_col)->toArray());
      if ($samples < $least){
        $least = $samples;
      }
      if ($y > $max){
        $max = $y;
      }
      if ($y + $stdDev > $limit){
        $limit = $y + $stdDev;
      }
      if ($y < $min){
        $min = $y;
      }


      $data[] = [
        "x" => $x,
        "y" => floatval($y),
        "samples" => $samples,
        "stdDev" => $stdDev
      ];
      $points += 1;
    }    

    return [
      'data' => $data,
      'meta' => [
        'points' => $points,
        'samples' => $total_samples,
        'least' => $least,
        'max' => $max,
        'min' => $min,
        'limit' => $limit
      ]
    ];
  }

  private static function stdDev($a){
    $n = count($a);
    if ($n === 0) {        
        return 0;
    }
    $mean = array_sum($a) / $n;
    $sum = 0.0;
    foreach ($a as $val) {
        $d = ((double) $val) - $mean;
        $sum += $d * $d;
    };
    return sqrt($sum / $n);
  }

  private static function mrSet($mr, $sd){
    if ($sd){
      return ['label' => "MutationRate=$mr", 'filter' => ['mutation_rate' => $mr, 'steepest_descent' => 1], 'style' => 'dashed'];
    }
    else{
      return ['label' => "MutationRate=$mr", 'filter' => ['mutation_rate' => $mr, 'steepest_descent' => 0]];
    }
  }

  private static function crSet($cr, $sd){
    if ($sd){
      return ['label' => "CrossoverSlicing=$cr", 'filter' => ['crossover_rate' => $cr, 'steepest_descent' => 1], 'style' => 'dashed'];
    }
    else{
      return ['label' => "CrossoverSlicing=$cr", 'filter' => ['crossover_rate' => $cr, 'steepest_descent' => 0]];
    }
  }

  public static function Analyses(){
    
    $mutationGraphs = [
      [
        'id' => 'q4h56t',
        'subtitle' => "Population=200, Generations=200, SteepestDescent=Off",
        'sets' => [static::mrSet(0, false), static::mrSet(1, false), static::mrSet(2, false)]
      ],
      [
        'id' => '3m7wvw',
        'subtitle' => "Population=200, Generations=200, SteepestDescent=On",
        'sets' => [static::mrSet(0, true), static::mrSet(1, true), static::mrSet(2, true)]
      ],
      [
        'id' => 'm758ei',
        'subtitle' => "Population=200, Generations=200, SteepestDescent=Off",
        'sets' => [static::mrSet(5, false), static::mrSet(10, false), static::mrSet(20, false)]
      ],
      [
        'id' => 'z58654',
        'subtitle' => "Population=200, Generations=200, SteepestDescent=On",
        'sets' => [static::mrSet(5, true), static::mrSet(10, true), static::mrSet(20, true)]
      ],
    ];

    $crossoverGraphs = [
      [
        'id' => '45w7nn',
        'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=Off",
        'sets' => [static::crSet(0, false), static::crSet(1, false), static::crSet(2, false)]
      ],
      [
        'id' => '9ps5ej',
        'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=On",
        'sets' => [static::crSet(0, true), static::crSet(1, true), static::crSet(2, true)]
      ],
      [
        'id' => 'ghm68o',
        'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=Off",
        'sets' => [static::crSet(5, false), static::crSet(10, false), static::crSet(20, false)]
      ],
      [
        'id' => 'aw59hj',
        'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=On",
        'sets' => [static::crSet(5, true), static::crSet(10, true), static::crSet(20, true)]
      ],
    ];

    $durationGraphs = [
      [
        'id' => 'wn457',
        'subtitle' => "SteepestDescent=Off",
        'sets' => [
          ['label' => 'DurationVariance=Off', 'filter' => ['duration_variance' => 0, 'steepest_descent' => 0]],
          ['label' => 'DurationVariance=On', 'filter' => ['duration_variance' => 1, 'steepest_descent' => 0]],
        ]
      ],
      [
        'id' => 'srj66',
        'subtitle' => "SteepestDescent=On",
        'sets' => [
          ['label' => 'DurationVariance=Off', 'filter' => ['duration_variance' => 0, 'steepest_descent' => 1], 'style' => 'dashed'],
          ['label' => 'DurationVariance=On', 'filter' => ['duration_variance' => 1, 'steepest_descent' => 1], 'style' => 'dashed'],
        ]
      ]
    ];

    $durationStatic = [
      'population' => 100,
      'selection_pressure' => 2,
      'duration' => 100 * 150,
      'crossover_rate' => 6,
      'mutation_rate' => 4,
      'mutation_variance' => 0
    ];

    return [      
      [
        'id' => 'popgen',
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
        'graphs' => [
          [
            'id' => '45wb7',
            'sets' => [
              ['label' => "Population=20", 'filter' => ['population' => 20]],
              ['label' => "Population=100", 'filter' => ['population' => 100]],
              ['label' => "Population=200", 'filter' => ['population' => 200]]
            ],
          ]
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
        'id' => 'nanvar',
        'title' => 'No Mutation Variance',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_variance' => 0,
          'duration_variance' => 0,
          'crossover_rate' => 6
        ],
        'graphs' => $mutationGraphs,
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
        'id' => 'linvar',
        'title' => 'Linear Mutation Variance',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_variance' => 1,
          'duration_variance' => 0,
          'crossover_rate' => 6
        ],
        'graphs' => $mutationGraphs,
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
        'id' => 'bitvar',
        'title' => 'Bitwise Mutation Variance',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_variance' => 2,
          'duration_variance' => 0,
          'crossover_rate' => 6
        ],
        'graphs' => $mutationGraphs,
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
          'duration_variance' => 0
        ],
        'graphs' => [
          [
            'id' => '45wb7',
            'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=Off",
            'sets' => [
              ['label' => 'SelectionPressure=1', 'filter' => ['selection_pressure' => 1, 'steepest_descent' => 0]],
              ['label' => 'SelectionPressure=2', 'filter' => ['selection_pressure' => 2, 'steepest_descent' => 0]],
            ]
            ],
          [
            'id' => 'n584e',
            'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=On",
            'sets' => [
              ['label' => 'SelectionPressure=1', 'filter' => ['selection_pressure' => 1, 'steepest_descent' => 1], 'style' => 'dashed'],
              ['label' => 'SelectionPressure=2', 'filter' => ['selection_pressure' => 2, 'steepest_descent' => 1], 'style' => 'dashed'],
            ]
            ],
          [
            'id' => 'e56ui',
            'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=Off",
            'sets' => [
              ['label' => 'SelectionPressure=3', 'filter' => ['selection_pressure' => 3, 'steepest_descent' => 0]],
              ['label' => 'SelectionPressure=4', 'filter' => ['selection_pressure' => 4, 'steepest_descent' => 0]],
            ]
            ],
          [
            'id' => 'fly42',
            'subtitle' => "Population=200, Generations=200, MutationRate=4, MutationVariance=None, SteepestDescent=On",
            'sets' => [
              ['label' => 'SelectionPressure=3', 'filter' => ['selection_pressure' => 3, 'steepest_descent' => 1], 'style' => 'dashed'],
              ['label' => 'SelectionPressure=4', 'filter' => ['selection_pressure' => 4, 'steepest_descent' => 1], 'style' => 'dashed'],
            ]
          ]
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
        'id' => 'crsovr',
        'title' => 'Crossover Slicing',
        'static' => [
          'selection_pressure' => 2,
          'population' => 100,
          'duration' => 100 * 150,
          'mutation_rate' => 4,
          'mutation_variance' => 0,
          'duration_variance' => 0
        ],
        'graphs' => $crossoverGraphs,
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
        'id' => 'durfit',
        'title' => 'Duration Variance VS Fitness',
        'static' => $durationStatic,
        'graphs' => $durationGraphs,
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
        'id' => 'durrun',
        'title' => 'Duration Variance VS Runtime',
        'static' => $durationStatic,
        'graphs' => $durationGraphs,
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
        ],
        'y' => [
          'label' => 'Duration',
          'column' => 'scaled_millis'
        ]    
      ],
      [
        'id' => 'dursft',
        'title' => 'Duration Variance VS Scaled Fitness',
        'static' => $durationStatic,
        'graphs' => $durationGraphs,
        'x' => [
          'label' => 'Generations',
          'column' => 'generations',
          'values' => [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
        ],
        'y' => [
          'label' => 'Fitness',
          'column' => 'scaled_fitness'
        ]    
      ]
    ];
  }
}

