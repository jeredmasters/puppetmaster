$(document).ready(function (){
  plotCharts();
  plotMutationType();
  gradientDescent();
  selectionPressure();
  durationVariance();
  durationVarianceMillis();
});

function plotCharts(){
  var params = {
    static: {  
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      //mutation_rate: 4,
      //mutation_variance: 0,
      duration_variance: 0,
      //gradient_decent: 0
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
  createGraph('chart1', "Populations and Generations", params, color)
}

function plotMutationType(){
  var params = {
    static: {
      population: 100,   
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      duration_variance: 0,
      gradient_decent: 0
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
  createGraph('chart2', "Mutation Variance", params, color)
}


function gradientDescent(){
  var params = {
    static: {
      population: 100,    
      selection_pressure: 2,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      duration_variance: 0
    },
    sets: [
      {label: "Off", filter: {gradient_decent:0}},
      {label: "On", filter: {gradient_decent:1}},
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
  createGraph('chart3', "Steepest Descent", params, color)
}

function selectionPressure(){
  var params = {
    static: {
      population: 100,
      duration: 100 * 150,
      crossover_rate: 6,
      mutation_rate: 4,
      mutation_variance: 0,
      duration_variance: 0,
      gradient_decent: 0
    },
    sets: [
      {label: "2", filter: {selection_pressure:2}},
      {label: "2", filter: {selection_pressure:3}},
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
    return [150, Math.floor((255-c) / 2), 255-c]
  }
  createGraph('chart4', "Selection Pressure", params, color)
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
      gradient_decent: 0
    },
    sets: [
      {label: "Off", filter: {duration_variance:0}},
      {label: "On", filter: {duration_variance:1}},
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
    return [150, Math.floor((255-c) / 2), 255-c]
  }
  createGraph('chart5', "Duration Variance VS Fitness", params, color)
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
      gradient_decent: 0
    },
    sets: [
      {label: "Off", filter: {duration_variance: 0}},
      {label: "On", filter: {duration_variance: 1}},
    ],
    x: {
      label: "Generations",
      column: 'generations',
      values: [20,40,60,80,100,120,140,160,180,200]
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
  createGraph('chart6', "Duration Variance VS Runtime", params, color)
}