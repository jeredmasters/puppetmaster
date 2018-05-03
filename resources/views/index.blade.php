<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="/js/charting.js"></script>
        <script src="/js/home_charts.js"></script>

        <!-- Styles -->
       
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
    <h1>GA Distributed Testing</h1>
    <h2>Test Case Coverage</h2>
    <p><strong>{{ $results }} / {{ $tests }} = {{ $ratio }}</strong></p>
    <h2>General Results</h2>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <canvas id="chart1" width="400" height="400"></canvas>
        </div>
        <div class="col-md-6">
          <canvas id="chart2" width="400" height="400"></canvas>
        </div>
        <div class="col-md-6">
          <canvas id="chart3" width="400" height="400"></canvas>
        </div>
        <div class="col-md-6">
          <canvas id="chart4" width="400" height="400"></canvas>
        </div>
        <div class="col-md-6">
          <canvas id="chart5" width="400" height="400"></canvas>
        </div>
        <div class="col-md-6">
          <canvas id="chart6" width="400" height="400"></canvas>
        </div>
      </div>
    </div>
  </body>
</html>
