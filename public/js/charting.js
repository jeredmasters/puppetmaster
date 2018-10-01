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
function createGraph(title, params, colorFunc) {
  var $row = $("<div class='row'></div>");
  $("#chartarea").append($row);
  $.post("/results/", {parameters: params}, function (response) {
    console.log(response);
    var i = -1;
    labels = null;
    
    maxVal = 0;
    dataSets = response.map(function (set) {
      errorBars = {};
      i += 1;
      if (labels == null){
        labels = set.data.map(function (point) {return point.x;})
      }
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
        errorColor: 'rgba('+ colorFunc(i).join(', ') + ', 1)',
        fill: false,
        tension: 0.2,
        radius: 10,
        pointStyle: selectPointStyle(i),
        borderWidth:2,
        borderColor: [
            'rgba('+ colorFunc(i).join(', ') + ', 1)',
            
        ],
      }
    })

    var $canvas = $("<canvas width='400' height='350'></canvas>");
    var $canvas_col = $("<div class='offset-md-1 col-md-10'></div>").append($canvas);

    var $table_div = $("<div></div>");
    var $table_col = $("<div class='offset-md-2 col-md-8'></div>").append($table_div);

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
        layout: {
          padding:  {
            left: 0,
            right: 0,
            top: 200,
            bottom: 0
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
    $table_div.append(
      createTable(title, labels, dataSets)
    );

    $row.append($canvas_col).append($table_col);
    
  });
}

function createTable(title, labels, data){

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

  for (set of data) {
    $tbody.append('<tr />').children('tr:last')
    .append(
      '<td rowspan="2" class="row-header">'+set.label+'</td><td>mean</td>' +
      set.data.map(function (i){
        return '<td>' + parseInt(i) + '</td>';
      }).join()
    );
  
    $tbody.append('<tr />').children('tr:last')
    .append(    
      '<td>var</td>' +
      set.error.map(function (i){
        return '<td>' + parseInt(i) + '</td>';
      }).join()
    );
  }
  return $table;
}
