function createGraph(elemId, title, params, colorFunc) {
  $.post("/results/", {parameters: params}, function (response) {
    console.log(response);
    var i = -1;
    labels = null;
    dataSets = response.map(function (set) {
      i += 1;
      if (labels == null){
        labels = set.data.map(function (i) {return i.x;})
      }
      return {
        label: set.label,
        data: set.data.map(function (i) {return i.y;}),
        error: set.data.map(function (i) {return i.stdDev;}),
        errorColor: 'rgba('+ colorFunc(i).join(', ') + ', 1)',
        backgroundColor: [
            'rgba('+ colorFunc(i).join(', ') + ', 0.2)',
        ],
        borderColor: [
            'rgba('+ colorFunc(i).join(', ') + ', 1)',
            
        ],
      }
    })
    ctx = document.getElementById(elemId).getContext('2d');
    window.myScatter = new Chart(ctx, {
      type: 'lineError',
      data: {
        labels: labels,
        datasets: dataSets
      },
      options: {
        title: {
          display: true,
          text: title
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
            }
          }]
        }
      }
    });
    createTable(elemId+"_table", title, labels, dataSets);
  });
}

function createTable(tableId, title, labels, data){

  var $table = $('<table>');
  $table.addClass("table table-sm");

  .append('<thead>').children('thead')
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

  $table.appendTo('#' + tableId);
}
