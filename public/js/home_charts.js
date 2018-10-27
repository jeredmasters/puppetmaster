$(document).ready(function (){


  // $.post("/analyses/", {}, function (response) {
  //   for(analysis of response.analyses){
  //     renderResult(analysis)
  //   }
  // });  

  renderBallcurve(40);

  $('#getballcurve').click(function (){
    renderBallcurve(parseInt($('#buckets').val()));
  });
});


function renderBallcurve(buckets){
  $.post('/bellcurve', {buckets: buckets}, function (response) {
    ctx = document.getElementById("bellcurve").getContext('2d');
    var data = response.data.map(function (d) {return d.y});
    var labels = response.data.map(function (d) {return d.x});
    var myLineChart = new Chart(ctx, {
        
        type: 'line',
        data: {
          labels: labels,
          datasets: [{ 
            data: data,
            label: "Number of results",
            borderColor: "#3e95cd",
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
  });
}