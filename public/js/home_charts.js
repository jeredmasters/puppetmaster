$(document).ready(function (){


  $.post("/analyses/", {}, function (response) {
    for(analysis of response.analyses){
      renderResult(analysis)
    }
  });  

  ctx = document.getElementById("bellcurve").getContext('2d');

    window.bellcurve = new Chart(ctx, {
        
        type: 'bar',
        data: {
          labels: [],
          datasets: [{ 
            data: [],
            label: "Number of results",
            borderColor: "#3e95cd",
            backgroundColor: "#339",
            fill: false
          }],
        },
        options: {
          title: {
            display: true,
            text: "Sample Fitness Distribution",
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
          }
        }
    });

  renderBallcurve(40);

  $('#getballcurve').click(function (){
    renderBallcurve(parseInt($('#buckets').val()));
  });
});


function renderBallcurve(buckets){
  $.post('/bellcurve', {buckets: buckets}, function (response) {
    var data = response.data.map(function (d) {return d.y});
    var labels = response.data.map(function (d) {return d.x1 + " - " + d.x2});
    window.bellcurve.data.labels = labels;
    window.bellcurve.data.datasets[0].data = data;
    window.bellcurve.update();
  });
}