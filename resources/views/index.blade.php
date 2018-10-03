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
        <script src="/js/charting.js"></script>
        <script src="/js/home_charts.js"></script>

        <link href="/css/main.css" rel="stylesheet"/>

        <!-- Styles -->
       
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
    <h1>GA Distributed Testing</h1>
    <h2>Test Case Coverage</h2>
    <p><strong>{{ $results }} / {{ $tests }} = {{ $ratio }}</strong></p>
    <p>Hosts: {{ $hosts }} (last 10 min)</p>
    <p>Rate: {{ $rate }} (last 10 min)</p>
    <h2>General Results</h2>
    <div class="container" id="chartarea">

    </div>
  </body>
</html>
