<?php
$_ERR = False;
if(isset($_POST['deco'])){
	$pdo = getCo();
	$req = $pdo->prepare("UPDATE `user` set `time` = 0 where `session` = :sess");
	$req->bindValue(":sess", $_COOKIE["lsl"]);
	$req->execute();
	header("location: index.php");
}

if(isset($_POST["update-account"],$_POST["nom"],$_POST["prenom"],$_POST["age"],$_POST["sexe"],$_POST["pays"],$_POST["ville"],$_POST["zip"],$_POST["rue"])){
	try {
		$pdo = getCo();
		$req = $pdo->prepare("SELECT `idi_fk` from `user_has_id` join `user` on `user_has_id`.`idu_fk` = `user`.`id` where `session` = :sess");
		$req->bindValue(":sess", $_COOKIE["lsl"]);
		$req->execute();
		$res = $req->fetch(PDO::FETCH_ASSOC);
		$id = $res["idi_fk"];
		$pdo = getCo();
		$req = $pdo->prepare("UPDATE `identite` set `Nom` = :nom, `prenom` = :prenom, `age` = :age, `sexe` = :sexe, `pays` = :pays, `ville` = :ville, `zip` = :zip, `rue` = :rue where `id` = '$id' ");
		$req->execute(array(
			":nom" => $_POST['nom'],
			":prenom" =>$_POST['prenom'],
			":sexe" =>$_POST['sexe'],
			":age" =>$_POST['age'],
			":pays" =>$_POST['pays'],
			":ville" =>$_POST['ville'],
			":zip" =>$_POST['zip'],
			":rue" =>$_POST['rue']
			));
		$res = $req->rowCount();
		if($res == 1){
			$_ERR = "votre compte a bien été mit à jours.";
		}
		else{
			$_ERR = "Une erreur est survenue.";
		}
	} catch (Exception $e) {
		#todo log error && create custom exept.
	}
}

if(isset($_POST["creer"],$_POST["lieu"],$_POST["sport"],$_POST["debut"],$_POST["fin"],$_POST["jour"])){
	$a = trouver_coordonner($_POST["lieu"]);
	$timestart = strtotime($_POST["jour"]." ".$_POST["debut"]);
	$timend = strtotime($_POST["jour"]." ".$_POST["fin"]);
	try {
		$pdo = getCo();
		$req = $pdo->prepare("INSERT INTO `match` (`lieu`,`latitude`,`longitude`,`sport`,`debut`,`fin`,`participant`,`status`) VALUES(:lieu,:lat,:long,:sport,:start,:end,1,'active') ");
		$req->bindValue(":lieu", $_POST["lieu"]);
		$req->bindValue(":lat", $a["lat"]);
		$req->bindValue(":long", $a["long"]);
		$req->bindValue(":start", $timestart);
		$req->bindValue(":end", $timend);
		$req->bindValue(":sport", $_POST["sport"]);
		$req->execute();
		$res = $req->rowCount();
		$id = $pdo->lastInsertId();
		if($res = 1){
			try {
				$pdo = getCo();
				$req = $pdo->prepare("SELECT `id` from `user` where `session` = :sess");
				$req->bindValue(":sess", $_COOKIE["lsl"]);
				$req->execute();
				$res = $req->fetch();
				$res = $res[0];
				$req = $pdo->prepare("INSERT INTO `match_has_user` (`idm_fk`,`idu_fk`) VALUES($id, $res)");
				$req->execute();
				$res = $req->rowCount();
				if($res = 1){
					$sportid = get_sport_id($_POST['sport']);
					if($sportid){
						$is = is_user_ranked($sportid);
						if($is){
							$_ERR = "Votre match a été crée avec succes, votre recherche est dorenavant active !";
						}
						else{
							$in = set_user_ranked($sportid);
							if($in){
								$_ERR = "Votre match a été crée avec succes, votre recherche est dorenavant active !";
							}
						}
					}
					else{
						$_ERR = False;
					}
				}
				else{
					$_ERR = False;
				}
			} catch (Exception $e) {
				
			}
		}
		else{
			$_ERR = False;
		}
	} catch (Exception $e) {
		#todo log error && create custom exept.
	}
}
$_TYPE_FILTER = False;
if(isset($_POST["filtrer"],$_POST["filter"])){
	$_TYPE_FILTER = $_POST["filter"];
}

?>
<body style="min-width:780px;" >
<div id="mail"><?php 
if($_ERR){
	echo("<h5 id='err'>$_ERR</h5>");
}

?>

</div>

<?php

if($_TYPE_FILTER){
	echo('<script type="text/javascript">$(document).ready(function(){$("#rdv-content").show();});</script>');
} 
if($_ERR){
	echo('<script type="text/javascript">$(document).ready(function(){$("#mail").slideDown(4000).slideUp(4000);setTimeout(redirect_delay, 8100);});</script>');
}

$_TYPE_FILTER_EVENT = False;
if(isset($_POST["filtrer-event"],$_POST["filter-event"])){
	$_TYPE_FILTER_EVENT = $_POST["filter-event"];
}
if($_TYPE_FILTER_EVENT){
	echo('<script type="text/javascript">$(document).ready(function(){$("#event-content").show();});</script>');
} 

$_TYPE_FILTER_RANK = False;
if(isset($_POST["filtrer-rank"],$_POST["filter-rank"])){
	$_TYPE_FILTER_RANK = $_POST["filter-rank"];
}
if($_TYPE_FILTER_RANK){
	echo('<script type="text/javascript">$(document).ready(function(){$("#rank-content").show();});</script>');
} 
?>
<script>
function redirect_delay() {
    window.location = "index.php";
}
</script>
	<div id="mainpage-container">
		<div id="navbar">
			<img src="./images/logo.png" style="width:80px;height:80px;padding:0px !important;">
			<div class="navmenu navred" id="partenaire" style="background-color:#D24726"><div class="menu-name">Trouver des partenaires</div></div>
			<div class="navmenu naviolet" id="rdv" style="background-color:#80397B"><div class="menu-name">Mes Rendez-vous</div></div>
			<div class="navmenu navgreen" id="event" style="background-color:rgb(5, 132, 24)"><div class="menu-name">Tout les évènements</div></div>
			<div class="navmenu navyellow" id="rank" style="background-color:#e6b700"><div class="menu-name">Classement par sport</div></div>
			<div class="navmenu navblue" id="account" style="float:right;margin-right:2px;"><div class="menu-name">Mon compte</div></div>
			<form method="POST" action="" id="deco"><input type="submit" name="deco" id="deconnect" value="deconnection" ></form>
			<div class="navmenu navblue" id="friend" style=""><div class="menu-name">Amis</div></div>
			<div class="navmenu navblue" id="notif" style=""><div class="menu-name">notifications</div></div>
		</div>
		<div id="mainpage-content">
			<div id="partenaire-content">
				<div id="partenaire-container">
					<div id="partenaire-title">Organisez une rencontre sportive ici !</div>
					<div style="margin-left:2%;">
						<form method="POST" action="" id="create-inv" name="create-inv">
							<div id="partenaire-mod">
								<div id="googleMap" style="float:right;width:300px;height:300px;margin-right:2%;"></div>
								<h5>Choisissez un sport</h5>
								<select id="sport" name="sport" required>
									<option value="" selected style="color:#ccc;">choisissez un sport!</option>
									<?php 
										$s = get_sport();
										if($s){
											foreach ($s as $key => $value) {
												foreach ($s[$key] as $k => $v) {
													echo("<option value='".$v."'>".$v."</option>");
												}
											}
										}
									?>
								</select>
							</div>
							<div id="partenaire-mod">
								<h5>Choisissez un lieu</h5>
								<input type="text" id="adresse" name="lieu" style="width:300px;height:30px;" onchange="TrouverAdresse();" required>
							</div>
							<div id="partenaire-mod">
								<h5>Choisissez un horaire</h5>
								<p id="datepairExample">
								    <input type="text" name="jour" class="date-start" style="width:80px;height:30px;padding:5px;font-size:12px" placeholder="jours de debut" required/>
								    <input type="text" name="debut" class="time-start" style="width:80px;height:30px;padding:5px;font-size:12px"placeholder="heure de debut" required/> 
									<input type="text" name="fin" class="time-end" style="width:80px;height:30px;padding:5px;font-size:12px"placeholder="heure de fin" required/> 
								</p>
							</div>
							<div><input type="submit" name="creer" value="Créer un match !" id="match-create"></div>
						</form>
					</div>
				</div>
			</div>
			<div id="rdv-content">
				<div id="rdv-container">
					<div id="rdv-control-container">
						<div id="rdv-control">
							<div id="control-title">Triez par critères</div>
							<form method="POST" action="" name="filter" id="filter-form">
								<select id="filter" name="filter" required>	
									<option value="date">date</option>
									<option value="sport">sport</option>
									<option value="lieu">lieu</option>
								</select>
								<input type="submit" name="filtrer" value="filtrer" id="filter-btn">
							</form>
						</div>
					</div>
					<div id="rdv-min">
						<?php

							$e = get_event();
							if($_TYPE_FILTER){
								switch ($_TYPE_FILTER) {
									case 'sport':
										$e = get_sport_event();
										break;
									case 'date':
										$e = get_date_event();
										break;
									case 'lieu':
										$e = get_lieu_event();
										break;
									default:
										# code...
										break;
								}
							}
							if($e){
								foreach ($e as $key => $value) {

									echo("<div class='event'>");
										switch ($e[$key]["sport"]) {
											case 'football':
												echo "<img class='min-ico' src='./images/football.png'>";
												break;
											case 'ping-pong':
												echo "<img class='min-ico' src='./images/ping-pong.png'>";
												break;
											case 'rugby':
												echo "<img class='min-ico' src='./images/rugby.png'>";
												break;
											default:
												echo "<img class='min-ico' src='./images/logo.png'>";
												break;
										}
										echo "<h4 id='min-title'>".$e[$key]["sport"]."</h4>";
										echo "<div>lieu : ".$e[$key]["lieu"]."</div>";
										echo "<div>date de debut : ".date("D j M  G:i",$e[$key]["debut"])."</div>";
										echo "<div>date de fin : ".date("D j M  G:i",$e[$key]["fin"])."</div>";
									echo("</div>");
								}
							}
						?>
					</div>
				</div>
			</div>
			<div id="event-content">
				<div id="event-container">
					<div id="event-filtre">
						<div id="rdv-control-container">
							<div id="rdv-control">
								<div id="control-title">Triez par critères</div>
								<form method="POST" action="" name="filter-event" id="filter-form">
									<select id="filter" name="filter-event" required>	
										<option value="date">date</option>
										<option value="sport">sport</option>
										<option value="lieu">lieu</option>
									</select>
									<input type="submit" name="filtrer-event" value="filtrer" id="filter-btn">
								</form>
							</div>
						</div>
					</div>
					<div id="event-min">
						<?php
							$p = get_all_event();
							if($_TYPE_FILTER_EVENT){
								switch ($_TYPE_FILTER_EVENT) {
									case 'sport':
										$p = get_all_sport_event();
										break;
									case 'date':
										$p = get_all_date_event();
										break;
									case 'lieu':
										$p = get_all_lieu_event();
										break;
									default:
										# code...
										break;
								}
							}
							$p_size = count($p);
							$messagesParPage = 7; 
							$nombreDePages = ceil ($p_size/$messagesParPage);
							$a = 0;
							$c = 0;
							for ($i=0; $i < $nombreDePages ; ++$i) { 
								echo(" <script type='text/javascript'>$(document).ready(function(){");echo" $('#btn".$i."').click(function(){";
									for ($e=0; $e < $nombreDePages; $e++) { 
										echo("$('#page".$e."').hide();");
										echo("$('#btn".$e."').css('background','#bbb');");
									}
									echo("$('#page".$i."').show();");
									echo("$('#btn".$i."').css('background','#eee');");

								echo("});});</script>");
							}
							echo('<script type="text/javascript">$(document).ready(function(){$("#btn0").click();});</script>'); 
							for ($i=0; $i < $nombreDePages ; ++$i) {
								echo("<div class='btn-page' id='btn$i' style='display:inline;width:20px;height:20px;margin-left:1px;float:right;'>".($i+1)."</div>");
							}
							
							echo("<div class='page' id='page$c' style='background:#eee;'>");
							for ($b = 0; $b < $p_size ;$b++) {
								if($b % $messagesParPage == 0 && $b != 0){$c++;echo("</div>");echo("<div class='page' id='page$c' style='background:#eee;display:none;'>");}
								echo("<div class='event-min'><div style='font-weight:bold;font-size:12px;text-align:right;width:50%;'>".$p[$b]['sport']."</div><div class='event-date'>date de debut : ".date("D j M  G:i",$e[$key]["debut"]).
									"<div>".$p[$b]['lieu']."</div>"."<div> status : ".$p[$b]['status']."</div></div>");
									switch ($p[$b]["sport"]) {
										case 'football':
											echo "<img class='min-ico' src='./images/football.png'>";
											break;
										case 'ping-pong':
											echo "<img class='min-ico' src='./images/ping-pong.png'>";
											break;
										case 'rugby':
											echo "<img class='min-ico' src='./images/rugby.png'>";
											break;
										default:
											echo "<img class='min-ico' src='./images/logo.png'>";
											break;
									}
									if($p[$b]['status'] == "active"){
										echo("<div class='btn-join' data-target=".$p[$b]['id']." id='btn-join-".$p[$b]['id']."'>Rejoindre</div>");
										echo("<script>$(document).ready(function(){ $('#btn-join-".$p[$b]['id']."').click(function(){alert($(this).data('target')); });});</script>");

									}
								echo("</div>");
							}	
							echo("</div>");
						?>
					</div>
				</div>
			</div>
			<div id="rank-content">
				<div id="rank-container">
					<div id="event-container">
					<div id="event-filtre">
						<div id="rdv-control-container">
							<div id="rdv-control">
								<div id="control-title">Triez par sports</div>
								<form method="POST" action="" name="filter-rank" id="filter-form">
									<select id="filter" name="filter-rank" required>	
										<?php 
											$tab = get_all_sport();
											foreach ($tab as $key => $value) {
												echo("<option value='".$tab[$key]['nom']."'>".$tab[$key]['nom']."</option>");
											}
										?>
									</select>
									<input type="submit" name="filtrer-rank" value="filtrer" id="filter-btn">
								</form>
							</div>
						</div>
					</div>
					<div id="event-min">
						<?php
							$p = get_user_by_rank();
							if($_TYPE_FILTER_RANK){
								switch ($_TYPE_FILTER_RANK) {
									case 'football':
										$p = get_all_sport_rank($_TYPE_FILTER_RANK);
										break;
									case 'ping-pong':
										$p = get_all_sport_rank($_TYPE_FILTER_RANK);
										break;
									case 'rugby':
										$p = get_all_sport_rank($_TYPE_FILTER_RANK);
										break;
									default:
										# code...
										break;
								}
							}

							$p_size = count($p);
							$messagesParPage = 5; 
							$nombreDePages = ceil ($p_size/$messagesParPage);
							$a = 0;
							$c = 0;
						if($p){
							for ($i=0; $i < $nombreDePages ; ++$i) { 
								echo(" <script type='text/javascript'>$(document).ready(function(){");echo" $('#btn".$i."').click(function(){";
									for ($e=0; $e < $nombreDePages; $e++) { 
										echo("$('#page".$e."').hide();");
									}
								echo("$('#page".$i."').show();");
								echo("});});</script>");
							}
							for ($i=0; $i < $nombreDePages ; ++$i) { 
								echo("<div class='btn-page' id='btn$i' style='display:inline;width:20px;height:20px;margin-left:1px;float:right;'>".($i+1)."</div>");
							}
							
							echo("<div class='page' id='page$c' style='background:#eee;'>");
							for ($b = 0; $b < $p_size ;$b++) {
								if($b % $messagesParPage == 0 && $b != 0){$c++;echo("</div>");echo("<div class='page' id='page$c' style='background:#eee;display:none;'>");}
								echo("<div class='rank-min' ><div style='font-weight:bold;font-size:18px;text-align:right;width:50%;'>".$p[$b]['nom']."</div><div class='event-date'>Rang : ".$p[$b]['rank']."</div>"."<div class='event-date' style='font-weight:bold;'>".$p[$b]['Nom']." ".$p[$b]['prenom']."</div>");
									switch ($p[$b]["nom"]) {
										case 'football':
											echo "<img class='min-ico' src='./images/football.png'>";
											break;
										case 'ping-pong':
											echo "<img class='min-ico' src='./images/ping-pong.png'>";
											break;
										case 'rugby':
											echo "<img class='min-ico' src='./images/rugby.png'>";
											break;
										default:
											echo "<img class='min-ico' src='./images/logo.png'>";
											break;
									}
								echo("</div>");
							}	
							echo("</div>");
						}
						?>
					</div>
				</div>
				</div>
			</div>
			<div id="account-content">
				<div id="account-container">
					<div id="display-account">
						<?php
							$tab = get_account();
						?>
						<div id="account-data">
							<div class="account-data">Nom : <div class="data"><?php echo $tab["Nom"];?></div></div>
							<div class="account-data">Prenom : <div class="data"><?php echo $tab["prenom"];?></div></div>
							<div class="account-data">Age : <div class="data"><?php echo $tab["age"];?></div></div>
							<div class="account-data">Sexe : <div class="data"><?php echo $tab["sexe"];?></div></div>
							<div class="account-data">Pays : <div class="data"><?php echo $tab["pays"];?></div></div>
							<div class="account-data">Ville : <div class="data"><?php echo $tab["ville"];?></div></div>
							<div class="account-data">Zip : <div class="data"><?php echo $tab["zip"];?></div></div>
							<div class="account-data">Rue : <div class="data"><?php echo $tab["rue"];?></div></div>
						</div>
					</div>
					<div id="update-account">
						<form action="" method="POST" >
						<div class="update-data">
							<input class="account-update" type="text" name="nom" placeholder=<?php echo $tab["Nom"];?> value=<?php echo $tab["Nom"];?> required>
							<input class="account-update" type="text" name="prenom" placeholder=<?php echo $tab["prenom"];?> value=<?php echo $tab["prenom"];?> required>
							<input class="account-update" type="text" name="age" placeholder=<?php echo $tab["age"];?> value=<?php echo $tab["age"];?> required>
							<input class="account-update" type="text" name="sexe" placeholder=<?php echo $tab["sexe"];?> value=<?php echo $tab["sexe"];?> required>
							<input class="account-update" type="text" name="pays" placeholder=<?php echo $tab["pays"];?> value=<?php echo $tab["pays"];?> required>
							<input class="account-update" type="text" name="ville" placeholder=<?php echo $tab["ville"];?> value=<?php echo $tab["ville"];?> required>
							<input class="account-update" type="text" name="zip" placeholder=<?php echo $tab["zip"];?> value=<?php echo $tab["zip"];?> required>
							<input class="account-update" type="text" name="rue" placeholder=<?php echo $tab["rue"];?> value=<?php echo $tab["rue"];?> required>
							<input class="account-update" type="submit" name="update-account" value="mettre à jours" style="height:40px;">
						</div>
						</form>
					</div>
				</div>
			</div>
			<div id="notifications">
				<div id="notif-container">
					
				</div>
			</div>
			<div id="amis">
				<div id="amis-container">
						<div id="amis-filtre">
							<div id="rdv-control-container">
								<div id="rdv-control">
									<div id="control-title">Recherchez des Amis</div>
									<form method="POST" action="" name="filter-event" id="filter-form">
										<input type="text" name="search-friend" id="searched-friend" style="width:80%;margin-left:5%;">
										<input type="submit" name="filtrer-event" value="filtrer" id="filter-btn" style="background-color:blue;">
									</form>
								</div>
							</div>
						</div>
				</div>
		</div>
	</div>

	<script>
			$(function(){
				$( "#searched-friend" ).autocomplete({
    				source: function( request, response ){
    					data = $("#searched-friend").val();
        				$.ajax({
           						dataType: "json",
            					type : 'POST',
            					data : 'searched='+data,
            					url: './pages/searched-member.php',
            					success: function(data) {
                						response($.map(data.items, function(item) {
                    						return {
                        						desc: item.desc,
                        						label: item.label
                    						};
                    					}));
            						},
            					error: function(data) {
                						  
            						}
        				});
    			},
    				focus: function (event, ui) {
            $("#searched-friend").val(ui.item.label+ui.item.desc);
            $("#searched-friend").val(ui.item.desc);
            return false;
        },
        select: function (event, ui) {
            $("#searched-friend").val(ui.item.label+ui.item.desc);
            $("#searched-friend").val(ui.item.desc);

            return false;
        }
    })
        .data("uiAutocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .data("item.autocomplete", item)
            .append("<a>" + item.label + "</a> ")
            .append("<a>" + item.desc + "</a>")
            .appendTo(ul);
    };
			});

	$(function() {

		$( ".date-start" ).datepicker(
			{
			  firstDay: 1,
			  altField: "#datepicker",
			  closeText: 'Fermer',
			  prevText: 'Précédent',
			  nextText: 'Suivant',
			  currentText: 'Aujourd\'hui',
			  monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			  monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
			  dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			  dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
			  dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			  weekHeader: 'Sem.',
			  dateFormat: 'yy-mm-dd'
			});

	        $.timepicker.regional['fr'] = {
	                timeOnlyTitle: 'Choisir une heure',
	                timeText: 'Heure',
	                hourText: 'Heures',
	                minuteText: 'Minutes',
	                secondText: 'Secondes',
	                millisecText: 'Millisecondes',
	                timezoneText: 'Fuseau horaire',
	                currentText: 'Maintenant',
	                closeText: 'Terminé',
	                timeFormat: 'hh:mm',
	                amNames: ['AM', 'A'],
	                pmNames: ['PM', 'P'],
	                ampm: false
	        };
	        $.timepicker.setDefaults($.timepicker.regional['fr']);


		$('.time-start').timepicker();
		$('.time-end').timepicker();
	});

	</script>
	<script type="text/javascript">
	
		var geocoder;
		var map;
		// initialisation de la carte Google Map de départ
		function initialiserCarte() {
		  geocoder = new google.maps.Geocoder();
		  // Ici j'ai mis la latitude et longitude de Strasbourg pour centrer la carte de départ
		  var latlng = new google.maps.LatLng(48.60,7.620850);		  
		  var mapOptions = {
		    zoom      : 10,
		    center    : latlng,
		    mapTypeId : google.maps.MapTypeId.ROADMAP
		  }
		  map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
		}
		google.maps.event.addDomListener(window, 'load', initialiserCarte());
		
		//initialiserCarte(); 
		function TrouverAdresse() {
		  // Récupération de l'adresse tapée dans le formulaire
		  var adresse = document.getElementById('adresse').value;
		  geocoder.geocode( { 'address': adresse}, function(results, status) {
		    if (status == google.maps.GeocoderStatus.OK) {
		      map.setCenter(results[0].geometry.location);
		      // Récupération des coordonnées GPS du lieu tapé dans le formulaire
		      var strposition = results[0].geometry.location+"";
		      strposition=strposition.replace('(', '');
		      strposition=strposition.replace(')', '');

		      // Création du marqueur du lieu (épingle)
		      var marker = new google.maps.Marker({
		          map: map,
		          position: results[0].geometry.location
		      });
		    } else {
		      alert('Adresse introuvable: ' + status);
		    }
		  });
		}
		// Lancement de la construction de la carte google map


	</script>

	<script type="text/javascript">
	$(document).ready(function(){
		//initialiserCarte();
		/* nav */
		$("#account").click(function(){
			$("#account-content").show();
			$("#rdv-content").hide();
			$("#partenaire-content").hide();
			$("#rank-content").hide();
			$("#event-content").hide();
			$("#notifications").hide();
			$("$amis").hide();
		});

		$("#rank").click(function(){
			$("#account-content").hide();
			$("#rdv-content").hide();
			$("#partenaire-content").hide();
			$("#rank-content").show();
			$("#event-content").hide();
			$("#notifications").hide();
			$("#amis").hide();
		});

		$("#event").click(function(){
			$("#account-content").hide();
			$("#rdv-content").hide();
			$("#partenaire-content").hide();
			$("#rank-content").hide();
			$("#event-content").show();
			$("#notifications").hide();
			$("#amis").hide();
		});

		$("#rdv").click(function(){
			$("#account-content").hide();
			$("#rdv-content").show();
			$("#partenaire-content").hide();
			$("#rank-content").hide();
			$("#event-content").hide();
			$("#notifications").hide();
			$("#amis").hide();
		});
		$("#partenaire").click(function(){
			$("#account-content").hide();
			$("#rdv-content").hide();
			$("#partenaire-content").show();
			initialiserCarte();
			$("#rank-content").hide();
			$("#event-content").hide();
			$("#notifications").hide();
			$("#amis").hide();
		});

		$("#notif").click(function(){
			$("#account-content").hide();
			$("#rdv-content").hide();
			$("#partenaire-content").hide();
			$("#notifications").show();
			$("#rank-content").hide();
			$("#event-content").hide();
			$("#amis").hide();
		});

		$("#friend").click(function(){
			$("#account-content").hide();
			$("#rdv-content").hide();
			$("#partenaire-content").hide();
			$("#notifications").hide();
			$("#rank-content").hide();
			$("#event-content").hide();
			$("#amis").show();
		});

	});
	</script>
</body>
</html>
