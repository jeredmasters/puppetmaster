$(document).ready(function (){


  $.post("/analyses/", {}, function (response) {
    for(analysis of response.analyses){
      renderResult(analysis)
    }
  });  

  ctx1 = document.getElementById("bellcurve1").getContext('2d');

    window.bellcurve1 = new Chart(ctx1, {
        
        type: 'bar',
        data: {
          labels: [],
          datasets: [{ 
            data: [],
            label: "Number of results",
            backgroundColor: "#339",
            fill: false
          }],
        },
        options: {
          title: {
            display: true,
            text: "Fitness Distribution without Steepest Descent",
            fontFamily: 'arial'
          },        
          plugins: {
            chartJsPluginSubtitle: {
              display:	true,
              fontSize:	12,
              fontFamily:	"Arial",
              fontColor: '#999',
              fontStyle: '',
              text:	"Population=100, Generations=200, MutationRate=5, MutationVariance=Linear, SteepestDescent=Off"
            }
          },
          scales: {
            xAxes: [{
              scaleLabel: {
                display: true,
                labelString: "Fitness Range"
              }
            }],
            yAxes: [{
              scaleLabel: {
                display: true,
                labelString: "Count"
              }
            }]
          },
        }
    });
    ctx2 = document.getElementById("bellcurve2").getContext('2d');
    window.bellcurve2 = new Chart(ctx2, {
        
      type: 'bar',
      data: {
        labels: [],
        datasets: [{ 
          data: [],
          label: "Number of results",
          backgroundColor: "#339",
          fill: false
        }],
      },
      options: {
        title: {
          display: true,
          text: "Fitness Distribution with Steepest Descent",
          fontFamily: 'arial'
        },        
        plugins: {
          chartJsPluginSubtitle: {
            display:	true,
            fontSize:	12,
            fontFamily:	"Arial",
            fontColor: '#999',
            fontStyle: '',
            text:	"Population=100, Generations=200, MutationRate=5, MutationVariance=Linear, SteepestDescent=On"
          }
        },
        scales: {
          xAxes: [{
            scaleLabel: {
              display: true,
              labelString: "Fitness Range"
            }
          }],
          yAxes: [{
            scaleLabel: {
              display: true,
              labelString: "Count"
            }
          }]
        },
      }
  });

  renderBallcurve(100);

  $('#getballcurve').click(function (){
    renderBallcurve(parseInt($('#buckets').val()));
  });
});


function renderBallcurve(buckets){
  $.post('/bellcurve', {buckets: buckets, steepest_descent: 'false'}, function (response) {
    var data = response.data.map(function (d) {return d.y});
    var labels = response.data.map(function (d) {return d.x1 + " - " + d.x2});
    window.bellcurve1.data.labels = labels;
    window.bellcurve1.data.datasets[0].data = data;
    window.bellcurve1.update();
  });
  $.post('/bellcurve', {buckets: buckets, steepest_descent: 'true'}, function (response) {
    var data = response.data.map(function (d) {return d.y});
    var labels = response.data.map(function (d) {return d.x1 + " - " + d.x2});
    window.bellcurve2.data.labels = labels;
    window.bellcurve2.data.datasets[0].data = data;
    window.bellcurve2.update();
  });
}