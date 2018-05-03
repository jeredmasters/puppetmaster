function createGraph(elemId, title, params, colorFunc) {
  $.post("/results/", {parameters: params}, function (response) {
    console.log(response);
    var i = -1;
    dataSets = response.map(function (set) {
      i += 1;
      return {
        label: set.label,
        data: set.data,
        backgroundColor: [
            'rgba('+ colorFunc(i).join(', ') + ', 0.2)',
        ],
        borderColor: [
            'rgba('+ colorFunc(i).join(', ') + ', 1)',
            
        ],
      }
    })
    ctx = document.getElementById(elemId).getContext('2d');
    window.myScatter = Chart.Scatter(ctx, {
      data: {
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