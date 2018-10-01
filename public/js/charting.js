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
function renderResult(title, params, colorFunc) {

  var $row = $("<div class='row top-margin'></div>");
  $("#chartarea").append($row);
  $.post("/results/", {parameters: params}, function (response) {
    labels = response.sets[0].data.map(function (point) {return point.x;})
    var $canvas = $("<canvas width='400' height='250'></canvas>");

    $row
      .append(createHeader(title, response.meta))
      .append($("<div class='offset-md-1 col-md-10'></div>").append($canvas))
      .append(createTable(title, labels, response.sets));

    // must pass in a bound canvas object for chartjs to work
    createGraph($canvas, title, params, colorFunc, labels, response.sets);
  });

}


function createHeader(title, meta){
  return $("<div class='offset-md-1 col-md-10 text-center'></div>")
    .append("<h2>" + title + "</h2>")
    .append("<p>Points: " + meta.points + ", Total Samples: " + meta.total_samples + ", Average Samples: " + meta.average_samples + ", Least Samples: " + meta.lowest_samples + "</p>");
}


function createGraph($canvas, title, params, colorFunc, labels, sets) {
  
  var color_index = -1;
  
  maxVal = 0;
  
  dataSets = sets.map(function (set) {
    color_index += 1;
    errorBars = {};
    set.data.forEach(function (point){
      lowerError = point.y;
      if (point.y > point.stdDev){
        lowerError = point.stdDev;
      }
      errorBars[point.x] = {plus: point.stdDev, minus: lowerError}
      if (point.y+point.stdDev > maxVal){
        maxVal = point.y+point.stdDev;
      }
      
    })
    return {
      label: set.label,
      data: set.data.map(function (point) {return point.y;}),
      error: set.data.map(function (point) {return point.stdDev;}),
      errorBars: errorBars,
      errorColor: 'rgba('+ colorFunc(color_index).join(', ') + ', 1)',
      fill: false,
      tension: 0.2,
      radius: 10,
      pointStyle: selectPointStyle(color_index),
      borderWidth:2,
      borderColor: [
          'rgba('+ colorFunc(color_index).join(', ') + ', 1)',
          
      ],
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
        text: title
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
            labelString: params.x.label
          }
        }],
        yAxes: [{
          scaleLabel: {
            display: true,
            labelString: params.y.label
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: maxVal
          }
        }]
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
