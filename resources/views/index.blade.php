<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="/js/chartjs.js"></script>
        <script src="/js/errorbars-plugin.js"></script>
        <script src="/js/subtitle-plugin.js"></script>
        <script src="/js/charting.js"></script>
        <script src="/js/home_charts.js"></script>

        <link href="/css/main.css" rel="stylesheet"/>

        <!-- Styles -->
       
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
    <div class="container">
      <div class="row top-margin-md"> 
        <div class="col-md-10 offset-md-1">
          <h1 class="text-center">GA Distributed Testing</h1>
          <br/>
          <p>Genetic Algorithms were introduced in 1975 by John Holland in his book “Adaptation in Natural and Artificial Systems” [10], as a mechanism for searching an extremely large, high-dimensional search space by mimicking natural evolutionary processes. In their paper “Developing a Generic Genetic Algorithm” [6], Neville and Sibley discuss the ability of species to evolve towards a complex and high functioning systems without the need of a supervisor, and as such this progress is automatic. Neville and Sibley lay out some steps for developing a genetic algorithm.</p>
          <p>
          The core tenants of a genetic algorithm are mutation, crossover, evaluation and selection:
          <table class="table table-sm">
            <tr>
              <th>Mutation</th>
              <td>The process of randomly altering tiny parts of a chromosome.</td>
            </tr>
            <tr>
              <th>Crossover</th>
              <td>The process of mixing (similar to breeding) two chromosomes together to create a new chromosome (child).</td>
            </tr>
            <tr>
              <th>Evaluation</th>
              <td>The process of setting the chromosome against the task being solved and giving it a score to measure how successful it is.</td>
            </tr>
            <tr>
              <th>Selection</th>
              <td>The process of ranking a selecting the chromosomes to crossover to create the next generation.</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row top-margin-sm"> 
        <div class="col-md-6 offset-md-3">

          <table class="table table-bordered">
            <tr>
              <th>Tests</th><td>{{ $tests }}</td>
            </tr>
            <tr>
              <th>Results</th><td>{{ $results }}</td>
            </tr>
            <tr>
              <th>Ratio</th><td>{{ number_format($ratio, 2, '.', '') }}</td>
            </tr>
            <tr>
              <th>Hosts</th><td>{{ $hosts }}</td>
            </tr>
            <tr>
              <th>Rate</th><td>{{ number_format($rate, 2, '.', '') }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row top-margin-lg">
        <div class="col-md-6">
          <input type="text" class="form-control" id="buckets"/>
          <button type="button" class="btn btn-default" id="getballcurve">Get</button>
        </div>
      </div>
      <div class="row">
        <div class="offset-md-2 col-md-8">
          <canvas id="bellcurve1" width='400' height='250'/>    
        </div>    
      </div>
      <div class="row">
        <div class="offset-md-2 col-md-8">
          <canvas id="bellcurve2" width='400' height='250'/>    
        </div>    
      </div>
    </div>
    <div class="container" id="chartarea">

    </div>


  </body>
</html>
