<?php 

class db {

	public $con;

	public function __construct() {

		try {

			$this->con = new PDO("mysql:host=" . HOST . ";dbname=" . DB . ';charset=UTF8', USER, PASSWORD);
			
// 			echo "Yay, Database connected!";

		} catch(PDOException $e) {

			echo "Connection Error: " . $e->getMessage();

		}

	}

}

?>