<?php
	function __autoload($class) {
	  require "lib/{$class}.php";
	}
	$db = new DB("mysql","pi_temp","localhost","root","password");
	$measure = new Temperature($db);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>piTemp</title>
		<script src="js/chart.min.js"></script>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
    <hr>
        <div class="container">
        <p class="well">
	    	<span id="temperature">
		            <span id="value" class="badge">
		              <?php echo "ºC / %";?>
		            </span>
		    </span>
	    </p>
	    <canvas id="canvas" height="450" width="500"></canvas>
	    
	    <script>
		var lineChartData = {
			labels : [<?php $measure->getDate("string");?>],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					data : [<?php $measure->getHumidity("string");?>]
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					data : [<?php $measure->getTemerature("string");?>]
				}
			]
		}


		var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData);
	
	
		</script>
            <?php 
            $measure->getAll("table", "table table-striped"); 
            ?>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.min.js"></script>
        <!-- compiled and minified Bootstrap JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
	    $(document).ready(function(){
	      setInterval("piRefresh();",5000); 
	    });
	   
	    function piRefresh(){
	      $('#temperature').load(location.href + ' #value');
	    }
    </script>
    </body>
</html>
