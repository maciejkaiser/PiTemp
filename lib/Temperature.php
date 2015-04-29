<?php
class Temperature{
	
	/**
	 * Variable to get database connection
	 */
	private $db;
	
	function __construct(DB $db){
		$this->db = $db;
	}
	
    /**
     * Do measure - connect to the Raspberry Pi via SSH and run bash script
     * 
     * @param string $server
     * @param string $serverLogin
     * @param string $serverPassword
     * @return array
     */
	public function doMeasure($server, $serverLogin, $serverPassword){
		if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
			if(!($con = ssh2_connect($server, 22))){
			    echo "fail: unable to establish connection\n";
			} else {
			    // try to authenticate with username root, password secretpassword
			    if(!ssh2_auth_password($con, $serverLogin, $serverPassword)) {
			        echo "fail: unable to authenticate\n";
			    } else {
			        // allright, we're in!
			        echo "okay: logged in...\n";
			        // execute a command
			        if (!($stream = ssh2_exec($con, "sudo ./dht11" ))) {
			            echo "fail: unable to execute command\n";
			        } else {
			            // collect returning data from command
			            stream_set_blocking($stream, true);
			            $data = "";
			            while ($buf = fread($stream,4096)) {
			                $data .= $buf;
			            }
			            fclose($stream);
			            
			            $tab = array();
			            $tab = explode(",", $data);
			            $temp =  sprintf("%01.2f", $tab[1]);
			            $hum = sprintf("%01.2f", $tab[0]);
			            $date = date("Y-m-d H:i:s");
			            
			            if(!empty($temp) && !empty($hum))
			            {
			            	$rpiTemp = array(
			            			'temperature' => $temp ,
			            			'humidity' => $hum,
			            			'date' => $date);
			            
			            	return $rpiTemp;
			            		
			            }else{
			            	//error
			            }
			        }
			    }
			}
	
	}
	/**
	 * Inserts measure to database
	 *
	 *@param DateTime $date - date of measure
	 *@param float $temperature - value of temperature 
	 *@param float $humidity - value of humidity
	 *
	 */
	public function insertMeasure($date, $temperature, $humidity){
		
		if (!empty($date) && (!empty($temperature) || $temperature !== "00.00") && !empty($humidity)) {
			$this->date = $date;
			$this->temperature = $temperature;
			$this->humidity = $humidity;
			
			$dataArray = array(
				'date' => $this->date,
				'temperature' => $this->temperature ,
				'humidity' => $this->humidity
			);
			
			$this->db->insertRows("pomiar_tab", $dataArray);
		}
	}
	
	/**
	 * Gets all data (date, temperature, humidity from database
	 *
	 *@param string $type - type of data returns - as object, table, string.
	 *@param string $class - to set class of table e.g bootstrap table table-striped
	 *@param array $options - to add options for display data
	 */
	public function getAll($type, $class = null, $options = array()){
		if(!empty($type)){
			switch ($type){
				case "table":
						echo "<table";if($class){echo " class=\"$class\"";} echo ">";
						echo "<tr>";
							echo "<th>ID</th>";
							echo "<th>Date</th>";
							echo "<th>Temperature</th>";
							echo "<th>Humidity</th>";
						echo "</tr>";
						foreach($this->db->getRows("measure_tab") as $key=>$value){
							echo "<tr>";
							echo "<td>".$key."</td>";
							echo "<td>".$value->measure_date."</td>";
							echo "<td>".$value->measure_temp."</td>";
							echo "<td>".$value->measure_hum."</td>";
							echo "</tr>";
						}
						echo "</table>";
					break;
			
				case "string":
						foreach($this->db->getRows("measure_tab") as $key=>$value){
							echo "ID: ".$key;
							echo "Date: ".$value->measure_date;
							echo "Temperature: ".$value->measure_temp;
							echo "Humidity: ".$value->measure_hum;
						}
					break;
			
				case "object":
					return $this->db->getRows("measure_tab");
					break;
			
				default:
					return $this->db->getRows("measure_tab");
					break;
			}
		}else{
			//error
		}
	}
	
    /**
     * Gets date of measure with specyfic type (string or object)
     * 
     * @param string $type
     * @return array
     */
	public function getDate($type){
		if(!empty($type)){
			switch ($type){
				case "string":
					foreach($this->db->getRows("measure_tab", "measure_date") as $key=>$value){
						echo "'".$value->measure_date."',";
					}
					break;
	
				case "object":
					return $this->db->getRows("measure_tab", "measure_date");
					break;
	
				default:
					return $this->db->getRows("measure_tab", "measure_date");
					break;
			}
		}else{
			//error
		}
	}
	
	/**
	 * Gets only temperature parameter from database
	 *
     * @param string $type
     * @return array
	 */
	public function getTemerature($type){
		if(!empty($type)){
			switch ($type){
				case "string":
					foreach($this->db->getRows("measure_tab", "measure_temp") as $key=>$value){
						echo "'".$value->measure_temp."',";
					}
					break;
						
				case "object":
					return $this->db->getRows("measure_tab", "measure_temp");
					break;
						
				default:
					return $this->db->getRows("measure_tab", "measure_temp");
					break;
			}
		}else{
			//error
		}
	}
	
	/**
	 * Gets only humidity parameter from database
	 *
     * @param string $type
     * @return array 
	 */
	public function getHumidity($type){
		if(!empty($type)){
			switch ($type){
				case "string":
					foreach($this->db->getRows("measure_tab", "measure_hum") as $key=>$value){
						echo "'".$value->measure_hum."',";
					}
					break;
		
				case "object":
					return $this->db->getRows("measure_tab", "measure_hum");
					break;
		
				default:
					return $this->db->getRows("measure_tab", "measure_hum");
					break;
			}
		}else{
			//error
		}
		
	}
}
?>