function selectPointStyle(index){
  var styles = [
    'circle',
    'star',
    'triangle',
    'rect',
    'rectRot'
  ]
  return styles[index % 5];
}
function renderResult(analysis) {

  
  var $row = $("<div class='row top-margin-lg'></div>");
  $("#chartarea").append($row);

  labels = analysis.graphs[0].sets[0].data.map(function (point) {return point.x;})  

  $row.append(createHeader(analysis.title, analysis.meta));

  var canvasClass = "";
  switch(analysis.graphs.length){
    case 1:  canvasClass = "offset-md-1 col-md-10"; break;
    case 2:  canvasClass = "col-md-6"; break;
    case 3:  canvasClass = "col-md-4"; break;
    default: canvasClass = "col-md-6"; break;
  }

  var allSets = [];
  for(var graph of analysis.graphs){
    var $canvas = $("<canvas width='400' height='300'></canvas>");
    $row.append($("<div class='"+canvasClass+"'></div>").append($canvas));
    createGraph($canvas, analysis, labels, graph);

    allSets = allSets.concat(graph.sets)
  }
  console.log(allSets);    
  $row.append(createTable(analysis.title, labels, allSets));
}


function createHeader(title, meta){
  return $("<div class='offset-md-1 col-md-10 text-center'></div>")
    .append("<h2>" + title + "</h2>")
    .append("<p>Total Points: " + meta.points + ", Total Samples: " + meta.samples + ", Average Samples: " + meta.samples / meta.points + ", Least Samples: " + meta.least + "</p>");
}


function createGraph($canvas, analysis, labels, graph) {
  var colorFunc = function (i) {
    var c = i * Math.floor(255 / graph.sets.length)


    var r = 255 - c * 2;
    var g = 255-Math.abs(c - 127) * 2;
    var b = (c - 127) * 2;

    if (r < 0){
      r = 0;
    }
    if (g < 0){
      g = 0;
    }
    if (b < 0){
      b = 0;
    }

    
    return [r,b, g];
  }

  var color_index = -1;
    
  dataSets = graph.sets.map(function (set) {
    color_index += 1;
    errorBars = {};
    set.data.forEach(function (point){
      lowerError = point.y;
      if (point.y > point.stdDev){
        lowerError = point.stdDev;
      }
      errorBars[point.x] = {plus: point.stdDev, minus: lowerError}     
    });
    return {
      label: set.label,
      data: set.data.map(function (point) {return point.y;}),
      error: set.data.map(function (point) {return point.stdDev;}),
      errorBars: errorBars,
      errorColor: 'rgba('+ colorFunc(color_index).join(', ') + ', 1)',
      fill: false,
      tension: 0.2,
      radius: 8,
      pointStyle: selectPointStyle(color_index),
      borderWidth:2,
      borderColor: [
          'rgba('+ colorFunc(color_index).join(', ') + ', 1)',
          
      ],
      borderDash: (set.style === 'dashed' ? [5, 5] : [])
    }
  })



  ctx = $canvas[0].getContext('2d');
  myScatter = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: dataSets
    },
    options: {
      title: {
        display: true,
        text: analysis.title,
        fontFamily: 'arial'
      },
      legend: {
        display: true,       
        labels: {
          usePointStyle: true
        }
      }, 
      scales: {
        xAxes: [{
          scaleLabel: {
            display: true,
            labelString: analysis.x.label
          }
        }],
        yAxes: [{
          scaleLabel: {
            display: true,
            labelString: analysis.y.label
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: parseFloat(analysis.meta.limit)
          }
        }]
      },
      plugins: {
        chartJsPluginSubtitle: {
          display:	graph.subtitle !== undefined,
          fontSize:	12,
          fontFamily:	"Arial",
          fontColor: '#999',
          fontStyle: '',
          text:	graph.subtitle
        },
        chartJsPluginErrorBars: {
          lineWidth: 0.5
        }
      }     
    }
  });
}

function createTable(title, labels, sets){

  var $table = $('<table>');
  $table.addClass("table table-sm");

  $table.append('<thead>').children('thead')
  .append('<tr />').children('tr').append(
    '<th>-</th><th>-</th>' +
    labels.map(function (label){
      return '<th>' + label + '</th>';
    }).join()
  );

  var $tbody = $table.append('<tbody />').children('tbody');

  for (set of sets) {
    $tbody.append('<tr />').children('tr:last')
    .append(
      '<td rowspan="2" class="row-header">'+set.label+'</td><td>mean</td>' +
      set.data.map(function (point){
        return '<td>' + parseInt(point.y) + '</td>';
      }).join()
    );
  
    $tbody.append('<tr />').children('tr:last')
    .append(    
      '<td>var</td>' +
      set.data.map(function (point){
        return '<td>' + parseInt(point.stdDev) + '</td>';
      }).join()
    );
  }

  return $("<div class='offset-md-2 col-md-8'></div>").append($table);
}
