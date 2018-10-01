$(document).ready(function (){
  population();
  gradientDescent();
  mutationTypeNormal();
  mutationTypeSteepest();
  mutationRateNormal();
  mutationRateSteepest();
  crossoverRate();  
  selectionPressureNormal();
  selectionPressureSteepest();
  durationVariance();
  durationVarianceScaled();
  durationVarianceMillis();
  
});

function population(){
  var params = {
    static: {  
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      duration_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: 20, filter: {population:20}},
      {label: 40, filter: {population:40}},
      {label: 60, filter: {population:60}},
      {label: 80, filter: {population:80}},
      {label: 100, filter: {population:100}},
      {label: 120, filter: {population:120}},
      {label: 140, filter: {population:140}},
      {label: 160, filter: {population:160}},
      {label: 180, filter: {population:180}},
      {label: 200, filter: {population:200}}
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [255 - c, 0, c]
  }
  createGraph("Populations and Generations", params, color)
}

function mutationTypeNormal(){
  var params = {
    static: {
      population: 100,   
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 3,
      duration_variance: 0,
      steepest_descent: 0
    },
    sets: [
      {label: "None", filter: {mutation_variance:0}},
      {label: "Linear", filter: {mutation_variance:1}},
      {label: "Bitwise", filter: {mutation_variance:2}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [100, 255 - c, c]
  }
  createGraph("Mutation Variance", params, color)
}
function mutationTypeSteepest(){
  var params = {
    static: {
      population: 100,   
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 3,
      duration_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: "None", filter: {mutation_variance:0}},
      {label: "Linear", filter: {mutation_variance:1}},
      {label: "Bitwise", filter: {mutation_variance:2}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [100, 255 - c, c]
  }
  createGraph("Mutation Variance with Steepest Descent", params, color)
}


function gradientDescent(){
  var params = {
    static: {
      population: 100,    
      selection_pressure: 2,
      duration: 100 * 150,      
      mutation_variance: 0,
      duration_variance: 0
    },
    sets: [
      {label: "Off MR=4", filter: {steepest_descent:0, mutation_rate: 4, crossover_rate: 6}},
      {label: "On  MR=4", filter: {steepest_descent:1, mutation_rate: 4, crossover_rate: 6}},
      {label: "Off MR=1", filter: {steepest_descent:0, mutation_rate: 1, crossover_rate: 0}},
      {label: "On  MR=1", filter: {steepest_descent:1, mutation_rate: 1, crossover_rate: 0}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [c, c, 50]
  }
  createGraph("Steepest Descent", params, color)
}

function selectionPressureNormal(){
  var params = {
    static: {
      population: 100,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      duration_variance: 0,
      steepest_descent: 0
    },
    sets: [
      {label: "1", filter: {selection_pressure:1}},
      {label: "2", filter: {selection_pressure:2}},
      {label: "3", filter: {selection_pressure:3}},
      {label: "4", filter: {selection_pressure:4}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [c, Math.floor((255-c) / 2), 255-c]
  }
  createGraph("Selection Pressure", params, color)
}
function selectionPressureSteepest(){
  var params = {
    static: {
      population: 100,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      duration_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: "1", filter: {selection_pressure:1}},
      {label: "2", filter: {selection_pressure:2}},
      {label: "3", filter: {selection_pressure:3}},
      {label: "4", filter: {selection_pressure:4}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [c, Math.floor((255-c) / 2), 255-c]
  }
  createGraph("Selection Pressure with Steepest Descent", params, color)
}

function crossoverRate(){
  var params = {
    static: {
      selection_pressure: 2,
      population: 100,
      duration: 100 * 150,
      mutation_rate: 4,
      mutation_variance: 0,
      duration_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: "0", filter: {crossover_rate:0}},
      {label: "1", filter: {crossover_rate:1}},
      {label: "2", filter: {crossover_rate:2}},
      {label: "3", filter: {crossover_rate:3}},
      {label: "4", filter: {crossover_rate:4}},
      {label: "5", filter: {crossover_rate:5}},
      {label: "6", filter: {crossover_rate:6}}
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [c, 0, Math.floor(c / 2)]
  }
  createGraph("Crossover Rate", params, color)
}
function mutationRateNormal(){
  var params = {
    static: {
      selection_pressure: 2,
      population: 100,
      duration: 100 * 150,
      mutation_variance: 0,
      duration_variance: 0,
      steepest_descent: 0,
      crossover_rate: 6
    },
    sets: [
      {label: "0", filter: {mutation_rate:0}},
      {label: "1", filter: {mutation_rate:1}},
      {label: "2", filter: {mutation_rate:2}},
      {label: "3", filter: {mutation_rate:3}},
      {label: "4", filter: {mutation_rate:4}},
      {label: "5", filter: {mutation_rate:5}},
      {label: "6", filter: {mutation_rate:6}}
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [c, 0, Math.floor(c / 2)]
  }
  createGraph("Mutation Rate", params, color)
}
function mutationRateSteepest(){
  var params = {
    static: {
      selection_pressure: 2,
      population: 100,
      duration: 100 * 150,
      mutation_variance: 0,
      duration_variance: 0,
      steepest_descent: 1,
      crossover_rate: 6
    },
    sets: [
      {label: "0", filter: {mutation_rate:0}},
      {label: "1", filter: {mutation_rate:1}},
      {label: "2", filter: {mutation_rate:2}},
      {label: "3", filter: {mutation_rate:3}},
      {label: "4", filter: {mutation_rate:4}},
      {label: "5", filter: {mutation_rate:5}},
      {label: "6", filter: {mutation_rate:6}}
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [c, 0, Math.floor(c / 2)]
  }
  createGraph("Mutation Rate with Steepest Descent", params, color)
}
function durationVariance(){
  var params = {
    static: {
      population: 100,
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: "Off", filter: {duration_variance:0}},
      {label: "On", filter: {duration_variance:1}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
    },
    y: {
      label: "Fitness",
      column: 'fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [150, Math.floor((255-c) / 2), 255-c]
  }
  createGraph("Duration Variance VS Fitness", params, color)
}
function durationVarianceScaled(){
  var params = {
    static: {
      population: 100,
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: "Off", filter: {duration_variance:0}},
      {label: "On", filter: {duration_variance:1}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
    },
    y: {
      label: "Fitness",
      column: 'scaled_fitness'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [150, Math.floor((255-c) / 2), 255-c]
  }
  createGraph("Duration Variance VS Scaled Fitness", params, color)
}


function durationVarianceMillis(){
  var params = {
    static: {
      population: 100, 
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      steepest_descent: 1
    },
    sets: [
      {label: "Off", filter: {duration_variance: 0}},
      {label: "On", filter: {duration_variance: 1}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300]
    },
    y: {
      label: "Duration",
      column: 'scaled_millis'
    }    
  };
  var color = function (i) {
    c = i * Math.floor(255 / params.sets.length)
    return [255-c, c, Math.floor((255-c) / 3) + 100]
  }
  createGraph("Duration Variance VS Runtime", params, color)
}


