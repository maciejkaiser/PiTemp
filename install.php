<?php
$msg = null;
if($_POST){
	$core = $_POST['core'];
	$host = $_POST['host'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	
	$createDB = "CREATE DATABASE IF NOT EXISTS `pi_temp` ";
	$createTAB = "USE `pi_temp`; CREATE TABLE `measure_tab` (
		measure_id int auto_increment,
		measure_date datetime,
		measure_temp varchar(5),
		measure_hum varchar(5),
	    PRIMARY KEY (measure_id)
	);";
	
	if(!empty($core) && !empty($host) && !empty($user)){
		
		function __autoload($class) {
			require "lib/{$class}.php";
		}
		try {
			$db = new DB($core, null, $host, $user, $pass);
			$db->query($createDB);
			if($db->query($createTAB)){
				$msg = "Installation complete!";
			}else{
				$msg = $db->error;
			}
		} catch (PDOException $e) {
			$msg = $e.getMessage();
		}
		
	}else{
		$msg = "Wrong data! Are you sure you correctly entered all informations?";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>piTemp -> install</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
    <div class="well"></div>
        <div class="container">
        <div class="page-header">
		  <h1>piTemp <small>INSTALLATION FILE</small></h1>
		</div>
        <?php if($msg){?>
	    <div class="alert alert-info">
	    <?php echo $msg;?>
	    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	    </div>
	    <?php }?>
            <div class="row">
            	<div class="col-md-2"></div>
            	<div class="col-md-6">
            		<form method="post">
					  <div class="form-group">
					    <label>core</label>
					    <input type="text" name="core" class="form-control" placeholder="e.g mysql" required>
					  </div>
					  <div class="form-group">
					    <label>host</label>
					    <input type="text" name="host" class="form-control" placeholder="e.g localhost" required>
					  </div>
					  <div class="form-group">
					    <label>user</label>
					    <input type="text" name="user" class="form-control" placeholder="db user" required>
					  </div>
					  <div class="form-group">
					    <label>password</label>
					    <input type="text" name="pass" class="form-control" placeholder="password">
					  </div>
					  <button type="submit" class="btn btn-primary btn-block">Install</button>
					</form>
					<hr>
            	</div>
            	
            	<div class="col-md-4">
            		<div class="panel panel-default">
            			<div class="panel-heading">INFO</div>
            			 <div class="panel-body">
						    Basic panel example
						 </div>
            		</div>
            	</div>
            </div>
             <footer>
				<hr>
				<p>Copyright &copy; 2014</p>
			</footer>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.min.js"></script>
        <!-- compiled and minified Bootstrap JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>