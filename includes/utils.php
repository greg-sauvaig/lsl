	<?php


function getCo(){

$VALEUR_hote = "localhost";
$VALEUR_user = "root";
$VALEUR_nom_bd = "lsl";
$VALEUR_mot_de_passe = "";

    $pdo = new PDO('mysql:host='.$VALEUR_hote.';dbname='.$VALEUR_nom_bd, $VALEUR_user, $VALEUR_mot_de_passe);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function get_sport(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT `nom` FROM `sport`");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function update_pass($mail){
	try {
		$code = gen_code();
		$pdo = getCo();
		$req = $pdo->prepare("UPDATE `user` set `pass` = '$code' where `login` = '$mail' ");
		$req->execute();
		$res =  $req->rowCount();
		if($res = 1){
			$m = smtpMailer($mail,$code,$mail);
	        if ($m) {
	            $a = "Un email récapitulatif vous a été adressé, il contient votre nouveau mot de passe, vous pouvez vous connecter dès à présent !";
	            return True;
	        }
	        else{
	            $a = "un probleme indiqué.";
	            return False;
	        }
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

function get_account(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * FROM `identite` join `user_has_id` on `user_has_id`.`idi_fk` = `identite`.`id`  join `user` on `user`.`id` = `user_has_id`.`idu_fk` WHERE `session` = :sess ");
		$req->bindValue(":sess", $_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetch(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function Check(){
	try {
		if(isset($_COOKIE["lsl"])){
			$t = time();
			$pdo = getCo();
			$req = $pdo->prepare("SELECT `id` FROM `user` WHERE `session` = :sess and `time` > '$t' ");
			$req->bindValue(":sess", $_COOKIE["lsl"]);
			$req->execute();
			$res = $req->fetch();
			if($res[0]){
				return True;
			}
			else{
				return False;
			}
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		#todo log error
		return False;
	}
}

function getRight(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT `droit` from user WHERE `session` = :sess ");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetch();
		if($res){
			return $res[0];
		}
		else{
			return "VISITOR";
		}	
	} catch (Exception $e) {
		#todo log error
		return "VISITOR";
	}
}


function gen_session(){
	$dic = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$code = "";
	for ($i=0; $i < 20; $i++) { 
		$code .= $dic[rand(0,35)];
	}
	return $code;
}

function gen_code(){
	$dic = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$code = "";
	for ($i=0; $i < 8; $i++) { 
		$code .= $dic[rand(0,35)];
	}
	return $code;
}

function trouver_coordonner($dlocation){
    // Get lat and long by address         
    $address = $dlocation; // Google HQ
    $prepAddr = str_replace(' ','+',$address);
    $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
    $output= json_decode($geocode);
    $latitude = $output->results[0]->geometry->location->lat;
    $longitude = $output->results[0]->geometry->location->lng;

    return array("lat"=>$latitude,"long"=>$longitude);
}

function get_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` join `match_has_user` on `match`.`id` = `match_has_user`.`idm_fk` where `match_has_user`.`idu_fk` = (SELECT `id` from `user` WHERE `session` = :sess) ");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}


function get_sport_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` join `match_has_user` on `match`.`id` = `match_has_user`.`idm_fk` where `match_has_user`.`idu_fk` = (SELECT `id` from `user` WHERE `session` = :sess) ORDER BY `sport` ASC");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function get_lieu_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` join `match_has_user` on `match`.`id` = `match_has_user`.`idm_fk` where `match_has_user`.`idu_fk` = (SELECT `id` from `user` WHERE `session` = :sess) ORDER BY `lieu` ASC");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function get_date_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` join `match_has_user` on `match`.`id` = `match_has_user`.`idm_fk` where `match_has_user`.`idu_fk` = (SELECT `id` from `user` WHERE `session` = :sess) ORDER BY `debut` DESC");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function get_all_sport_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` ORDER BY `sport` ASC");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function get_all_date_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` ORDER BY `debut` DESC");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function get_all_lieu_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` ORDER BY `lieu` DESC");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function get_all_event(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `match` ORDER BY `id` DESC");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} catch (Exception $e) {
		return False;
	}
}

function is_user_ranked($sportid){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT `id` from `user_has_rank` where `idu_fk` = (SELECT `id` from `user` where `session` = :sess) and `ids_fk` = '$sportid' ");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetch(PDO::FETCH_ASSOC);
		if($res){
			return $res;
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

function get_sport_id($sport){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT `id` from `sport` where `nom` = '$sport' ");
		$req->execute();
		$res = $req->fetch();
		if($res){
			return $res[0];
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

function set_user_ranked($sportid){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("INSERT INTO `user_has_rank` (`ids_fk`,`idu_fk`,`rank`) VALUES($sportid, (SELECT `id` from `user` where `session` = :sess),1800)");
		$req->bindValue(":sess",$_COOKIE["lsl"]);
		$req->execute();
		$res = $pdo->lastInsertId();
		if($res){
			return True;
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

function get_user_by_rank(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `identite` join `user_has_id` on `identite`.`id` = `user_has_id`.`idi_fk` join `user` on `user`.`id` = `user_has_id`.`idu_fk` join `user_has_rank` on `user_has_rank`.`idu_fk` = `user`.`id` join `sport` on `sport`.`id` = `user_has_rank`.`ids_fk` ORDER BY `rank` DESC");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		if($res){
			return $res;
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

function get_all_sport(){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `sport`");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		if($res){
			return $res;
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

function get_all_sport_rank($sport){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT * from `identite` join `user_has_id` on `identite`.`id` = `user_has_id`.`idi_fk` join `user` on `user`.`id` = `user_has_id`.`idu_fk` join `user_has_rank` on `user_has_rank`.`idu_fk` = `user`.`id` join `sport` on `sport`.`id` = `user_has_rank`.`ids_fk` WHERE `sport`.`nom` = '$sport' ORDER BY `rank` DESC");
		$req->execute();
		$res = $req->fetchAll(PDO::FETCH_ASSOC);
		if($res){
			return $res;
		}
		else{
			return False;
		}
	} catch (Exception $e) {
		return False;
	}
}

?>