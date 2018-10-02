$(document).ready(function (){


  $.post("/analyses/", {}, function (response) {
    for(analysis of response.analyses){
      renderResult(analysis)
    }
  });  
});
