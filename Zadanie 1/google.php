<!DOCTYPE HTML>
<html lang="sk">

<head>
  <title>Výsledky predmetu Webové Technológie 2</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" />
  <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawBar);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['Hodnotenie A',     20],
          ['Hodnotenie B',      11],
          ['Hodnotenie C',  13],
          ['Hodnotenie D', 7],
          ['Hodnotenie E', 5],
          ['Hodnotenie FX', 0],
          ['Hodnotenie FN', 1],
          ['Hodnotenie Ostatné', 5]
        ]);

        var options = {
          title: 'Webové technológie 2 šk. rok 2012/13'
        };

         var data2 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['Hodnotenie A',     20],
          ['Hodnotenie B',      19],
          ['Hodnotenie C',  6],
          ['Hodnotenie D', 3],
          ['Hodnotenie E', 1],
          ['Hodnotenie FX', 0],
          ['Hodnotenie FN', 0],
          ['Hodnotenie Ostatné', 4]
        ]);

        var options2 = {
          title: 'Webové technológie 2 šk. rok 2013/14'
        };
        var data3 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['Hodnotenie A',     9],
          ['Hodnotenie B',      19],
          ['Hodnotenie C',  22],
          ['Hodnotenie D', 0],
          ['Hodnotenie E', 0],
          ['Hodnotenie FX', 0],
          ['Hodnotenie FN', 3],
          ['Hodnotenie Ostatné', 0]
        ]);

        var options3 = {
          title: 'Webové technológie 2 šk. rok 2014/15'
        };

        var data4 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['Hodnotenie A',     10],
          ['Hodnotenie B',      33],
          ['Hodnotenie C',  35],
          ['Hodnotenie D', 20],
          ['Hodnotenie E', 14],
          ['Hodnotenie FX', 0],
          ['Hodnotenie FN', 0],
          ['Hodnotenie Ostatné', 0]
        ]);

        var options4 = {
          title: 'Webové technológie 2 šk. rok 2015/16'
        };

        var data5 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['Hodnotenie A',     5],
          ['Hodnotenie B',      18],
          ['Hodnotenie C',  31],
          ['Hodnotenie D', 26],
          ['Hodnotenie E', 27],
          ['Hodnotenie FX', 18],
          ['Hodnotenie FN', 3],
          ['Hodnotenie Ostatné', 0]
        ]);

        var options5 = {
          title: 'Webové technológie 2 šk. rok 2016/17'
        };



        var chart = new google.visualization.PieChart(document.getElementById('201213'));
        var chart2 = new google.visualization.PieChart(document.getElementById('201314'));
        var chart3 = new google.visualization.PieChart(document.getElementById('201415'));
        var chart4 = new google.visualization.PieChart(document.getElementById('201516'));
        var chart5 = new google.visualization.PieChart(document.getElementById('201617'));
//
        chart.draw(data, options);
        chart2.draw(data2, options2);
        chart3.draw(data3, options3);
        chart4.draw(data4, options4);
        chart5.draw(data5, options5);

        }

        function drawBar() {
        var data = new google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Počet študentov'],
          ['A',     20],
          ['B',      11],
          ['C',  13],
          ['D', 7],
          ['E', 5],
          ['FX', 0],
          ['FN', 1],
          ['Ostatné', 5]
        ]);

        var options = {
          title: 'Webové technológie 2 šk. rok 2012/13',
          width: 900,
          legend: { position: 'none' },
          chart: { title: 'Webové technológie 2 šk. rok 2012/13'},
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Počet študentov'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

         var data2 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['A',     20],
          ['B',      19],
          ['C',  6],
          ['D', 3],
          ['E', 1],
          ['FX', 0],
          ['FN', 0],
          ['Ostatné', 4]
        ]);

        var options2 = {
          title: 'Webové technológie 2 šk. rok 2013/14',
          width: 900,
          legend: { position: 'none' },
          chart: { title: 'Webové technológie 2 šk. rok 2013/14'},
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Počet študentov'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };
        var data3 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['A',     9],
          ['B',      19],
          ['C',  22],
          ['D', 0],
          ['E', 0],
          ['FX', 0],
          ['FN', 3],
          ['Ostatné', 0]
        ]);

        var options3 = {
          title: 'Webové technológie 2 šk. rok 2014/15',
          width: 900,
          legend: { position: 'none' },
          chart: { title: 'Webové technológie 2 šk. rok 2014/15'},
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Počet študentov'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var data4 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['A',     10],
          ['B',      33],
          ['C',  35],
          ['D', 20],
          ['E', 14],
          ['FX', 0],
          ['FN', 0],
          ['Ostatné', 0]
        ]);

        var options4 = {
          title: 'Webové technológie 2 šk. rok 2015/16',
          width: 900,
          legend: { position: 'none' },
          chart: { title: 'Webové technológie 2 šk. rok 2015/16'},
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Počet študentov'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var data5 = google.visualization.arrayToDataTable([
          ['Hodnotenie', 'Známka'],
          ['A',     5],
          ['B',      18],
          ['C',  31],
          ['D', 26],
          ['E', 27],
          ['FX', 18],
          ['FN', 3],
          ['Ostatné', 0]
        ]);

        var options5 = {
          title: 'Webové technológie 2 šk. rok 2016/17',
          width: 900,
          legend: { position: 'none' },
          chart: { title: 'Webové technológie 2 šk. rok 2016/17'},
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Počet študentov'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };


        var chart = new google.charts.Bar(document.getElementById('bar201213'));
        var chart2 = new google.charts.Bar(document.getElementById('bar201314'));
        var chart3 = new google.charts.Bar(document.getElementById('bar201415'));
        var chart4 = new google.charts.Bar(document.getElementById('bar201516'));
        var chart5 = new google.charts.Bar(document.getElementById('bar201617'));
//
        chart.draw(data, options);
        chart2.draw(data2, options2);
        chart3.draw(data3, options3);
        chart4.draw(data4, options4);
        chart5.draw(data5, options5);
      }
    </script>
</head>
<body>
  <div id="main">
    <div id="obsah_outer">
      <div id="header">
        <h1>Hodnotenia študentov predmetu Webte 2 pomocou Google Charts.</h1>
      </div>
      <section id="obsah">
        <h2>Každý graf predstavuje konkrétny rok popísaný v titulke grafu!</h2>

        <div id="bar201213" style="height: 500px;"></div>
        <div id="bar201314" style="height: 500px;"></div>
        <div id="bar201415" style="height: 500px;"></div>
        <div id="bar201516" style="height: 500px;"></div>
        <div id="bar201617" style="height: 500px;"></div>

        <div id="201213" style="height: 500px;"></div>
        <div id="201314" style="height: 500px;"></div>
        <div id="201415" style="height: 500px;"></div>
        <div id="201516" style="height: 500px;"></div>
        <div id="201617" style="height: 500px;"></div>
      </section>
    </div>
    <footer>
      <span>&copy; xchovanecv1 2018</span>
    </footer>
  </div>
</body>
</html>