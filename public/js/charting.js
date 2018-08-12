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
        error: set.error,
        errorColor: 'rgba('+ colorFunc(i).join(', ') + ', 1)',
        backgroundColor: [
            'rgba('+ colorFunc(i).join(', ') + ', 0.1)',
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
  });
}