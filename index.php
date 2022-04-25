<?php
    require_once "./sys&db/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ED.Alimentare</title>
    <link rel="stylesheet" href="/css/style.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Classe', 'TotPaniniClasse'],
          <?php
            $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "SELECT classe, sum(tot) as tot FROM panini_n_csv group by classe";
            $result = $connection->query($query);
            if ($result->num_rows != 0) {
              $ntmp = "";
              while ($row = $result->fetch_array()) {
                  echo "['Classe $row[classe]',$row[tot]],";
              }
            }
          ?>
        ]);

        var options = {
          title: 'Ordinazioni totali per classi',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Panini', 'Quantità Ordinata'],
          <?php
            $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "SELECT `data`, sum(tot) as tot FROM panini_n_csv group by `data`";
            $result = $connection->query($query);
            if ($result->num_rows != 0) {
              $ntmp = "";
              while ($row = $result->fetch_array()) {
                  echo "['$row[data]',$row[tot]],";
              }
            }
          ?>
        ]);

        var options = {
          title: 'Totale ordinazioni per ogni data',
          curveType: 'function',
          legend: { position: 'rigth' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
</head>
<body>
    <div id="piechart_3d"></div>
    <div id="curve_chart"></div>
</body>
</html>