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
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Classe', 'TotPaniniClasse'],
          <?php
            $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "SELECT classe, sum(tot) as tot FROM panini_n_csv where classe <> 'S' group by classe ";
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
            if (isset($_GET['classe'])){
              $queryAdd = $_GET['classe'];
              $strTmp = "where classe <> 'S' and classe = '";
              $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
              $query = "SELECT `data`, sum(tot) as tot FROM panini_n_csv ".$strTmp.$queryAdd."' group by `data`";
              $result = $connection->query($query);
              if ($result->num_rows != 0) {
                while ($row = $result->fetch_array()) {
                    echo "['$row[data]',$row[tot]],";
                }
              }
            }
            else{
              $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
              $query = "SELECT `data`, sum(tot) as tot FROM panini_n_csv group by `data`";
              $result = $connection->query($query);
              if ($result->num_rows != 0) {
                while ($row = $result->fetch_array()) {
                    echo "['$row[data]',$row[tot]],";
                }
              }
            }
          ?>
        ]);

        var options = {
          <?php
          if (isset($_GET['classe'])){
            echo "title: 'Totale ordinazioni per Classe ".$_GET['classe']."',";
          }
          else{
            echo "title: 'Totale ordinazioni per ogni data',";
          }
          ?>
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
    <div class="box">
      <h3>Visualizza grafico temporale per classi:</h3>
      <br>
    <?php
      $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $query = "SELECT DISTINCT classe FROM panini_n_csv where classe <> 'S';";
      $result = $connection->query($query);
      if ($result->num_rows != 0) {
        while ($row = $result->fetch_array()) {
            echo "<a href='./index.php?classe=$row[classe]' class='btn btn-outline-primary'>Classe $row[classe]</a>";
        }
      }
    ?>
    <a href='./index.php' class='btn btn-outline-primary'>Visualizza totale</a>
    </div>
</body>
</html>