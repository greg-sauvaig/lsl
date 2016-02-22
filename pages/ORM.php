<?php

function getCO($user, $pswd){
	$pdo = new PDO('mysql:host=localhost;dbname=lsl', $user, $pswd);
	return $pdo;
}

class MyORM{

	public function __construct($params){
		foreach ($params as $key => $value) {
			$file = fopen("$value.php", "w+");
			$text = "<?php \rrequire 'getCo.php'; \r\rclass ".ucfirst($value)."{";
			$PDO = getCo("root", "");
			$query = $PDO->prepare("SELECT column_name FROM information_schema.columns WHERE table_name ='".$value."' ");
			$query->execute();
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach ($data as $key => $attr) {
				$text.= "\r\t".'private $_'.$attr['column_name'].";";
			}
			$text.="\r";
			foreach ($data as $key => $attr) {
				$text.= "\r\tpublic function get".ucfirst($attr['column_name'])."(){";
				$text.= "\r\t\t".'return $this->_'.$attr['column_name'].";\r\t}";
				$text.= "\r\r\t".'public function set'.ucfirst($attr['column_name']).'($val){';
				$text.= "\r\t\t".'$this->_'.$attr['column_name'].'= $val;';
				$text.= "\r\t\t".'try{'.
					"\r\t\t\t".'$PDO=getCo();'.
					"\r\t\t\t".'$query = $PDO->prepare("UPDATE $value SET \''.$attr['column_name'].'\' = $val");'.
					"\r\t\t\t".'$query->execute();'.
					"\r\t\t\t".'$cpt = $query->rowcount();'.
					"\r\t\t\t".'if ($cpt === 1){'.
						"\r\t\t\t\t".'return $cpt;'.
					"\r\t\t\t".'}'.
					"\r\t\t\t".'else{'.
						"\r\t\t\t\t".'return 0;'.
					"\r\t\t\t".'}'.

				"\r\t\t".'}catch (Exception $e){'.
					"\r\t\t\t".'//to do log error'.
				"\r\t\t".'}'.
			"\r\t".'}';
			$text .= "\r";
			}
			$text.="}";
			fputs($file, $text);
			fclose($file);
		}
	}
}

$orm = new MyORM(["Vehicules", "Utilitaire", "Commercial"]);
