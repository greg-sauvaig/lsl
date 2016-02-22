<?php

$a = false;

if(isset($_POST["retrieve"],$_POST["login"])){
    if(update_pass($_POST["login"])){
    }
    else{
        $a = "un probleme indiqué.";
    }
}

if(isset($_POST["connection"]) && isset($_POST["login"]) && isset($_POST["pass"]) && $_POST["pass"] != null && $_POST["login"] != null){
    if(connect_User($_POST["login"], $_POST["pass"])){
        if(insert_session($_POST["login"], $_POST["pass"])){
            header("location: ./index.php");
        }
    }
}

if(isset($_POST["inscrit"]) && isset($_POST["mail1"]) && isset($_POST["pass"]) && $_POST["pass"] != null && $_POST["mail1"] != null && $_POST["mail2"] != null && isset($_POST["mail2"]) && $_POST["mail1"] == $_POST["mail2"]){
    if(!connect_User($_POST["mail1"], $_POST["pass"])){
        if(create_user($_POST["mail1"], $_POST["pass"])){
            $a = "Bienvenue sur LSL, un email récapitulatif vous a été adressé, vous pouvez vous connecter dès à présent !";
        }
    }
}

function smtpMailer($to,$pass,$login) {
    $from = "greg.sauvaigo@gmail.com";
    $from_name = "LSL Registration";
    $subject = "LSL Registration - Bienvenu sur LSL";
    $body = '<div style="width:100%;height:100%">'.
                '<img src="cid:logo" style="width:100px;height:100px;float:right;"/>'.
                '<h2 style"font-size:40px;padding:20px;">Bienvenue sur le site de la ligue des sports de lorainne.</h2></br></br>'.
                '<div style="width:100%;background:#ddd;padding:20px;">'.
                    '<div style="width:100%;padding:20px;">Vous pouvez des à présent vous connecter sur le site  à l\'adresse suivante http://localhost/lsl/ </div></br></br>'.
                    '<div style="width:100%;padding:20px;background:#eee;text-align:center;">voici votre login: <div style="background:#fff;">'.$login.'</div></div></br></br>'.
                    '<div style="width:100%;padding:20px;padding:20px;background:#eee;text-align:center;">voici mot de passe: <div style="background:#fff;">'.$pass.'</div></div></br></br>'.
                    '<div style="width:100%;padding:20px;">A bientot sur le site http://localhost/lsl/ </div></br></br>'.
                    '<div style="width:100%;padding:20px;margin-top 5%;font-style:italic;"></br>'.
                        'ce mail est généré automatiquement, ne repondez pas à cette adresse , en cas de problème contactez le support à l\'adresse suivante : greg.sauvaigo@gmail.com'.
                    '</div></br></br>'.
                '</div></br></br>'.
            '</div>';
    require_once('./includes/PHPMailer-master/PHPMailerAutoload.php');
    $mail = new PHPMailer();  // Cree un nouvel objet PHPMailer
    $mail->IsSMTP(); // active SMTP
    $mail->SMTPDebug = 0;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
    $mail->SMTPAuth = true;  // Authentification SMTP active
    $mail->SMTPSecure = 'ssl'; // Gmail REQUIERT Le transfert securise
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = "greg.sauvaigo@gmail.com";
    $mail->Password = "Th3rapt0r";
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->AddEmbeddedImage('./images/logo.png', 'logo', 'lsl.png'); 
    $mail->Body = $body;
    $mail->AddAddress($to);
    if(!$mail->Send()) {
        $a = "le mail n'est pas partit!";
        return False;
    } else {
        $a = "le mail n'est partit!";
        return true;
    }
}

function create_user($login, $pass){
    $login =  htmlspecialchars($login, ENT_QUOTES);
    $pass =  htmlspecialchars($pass, ENT_QUOTES);
    try {
        $pdo = getCo();
        $req = $pdo->prepare("INSERT INTO `identite`(`id`) values('auto')");
        $req->execute();
        $r = $pdo->lastInsertId();
        $pdo = getCo();
        $req = $pdo->prepare("INSERT INTO `user`(`login`,`pass`,`droit`) values( '$login', '$pass','user')");
        $req->execute();
        $res = $pdo->lastInsertId();
        if($res != null){
            $a = $res;
            $pdo = getCo();
            $req = $pdo->prepare("INSERT INTO `user_has_id`(`idu_fk`,`idi_fk`) values( '$res', '$r')");
            $req->execute();
            $res = $pdo->lastInsertId();
            if($res != null){
                $m = smtpMailer($login,$pass,$login);
                if ($m) {
                    $a = "Bienvenue sur LSL, un email récapitulatif vous a été adressé, vous pouvez vous connecter dès à présent !";
                    return True;
                }
                else{
                    $a = "le mail n'est pas le bon!";
                    return False;
                }
            }
            else{
                $a = "res is null";
                return False;
            }
        }
        else{
            $a = "res is null";
            return False;
        }
    } catch (Exception $e) {
        $a = "res is null";
        return False;
    }
}

function connect_User($login, $pass){
    $login =  htmlspecialchars($login, ENT_QUOTES);
    $pass =  htmlspecialchars($pass, ENT_QUOTES);
    try {
        $pdo = getCo();
        $req = $pdo->prepare("SELECT `id` from `user` where `login` = '$login' and `pass` = '$pass'");
        $req->execute();
        $res = $req->fetch(PDO::FETCH_ASSOC);
        if($res["id"] != null){
            return True;
        }
        else{
            return False;
        }
    } catch (Exception $e) {
        return False;
    }
}

function insert_session($login,$pass){
    $login =  htmlspecialchars($login, ENT_QUOTES);
    $pass =  htmlspecialchars($pass, ENT_QUOTES);
    $code = gen_session();
    try {
        $t = time() + (1*24*60*60*1000);
        $pdo = getCo();
        $req = $pdo->prepare("UPDATE `user` set `session` = '$code', `time` = '$t' where `login` = '$login' and `pass` = '$pass'");
        $req->execute();
        $cookie_name = "lsl";
        $cookie_value = $code;
        setcookie($cookie_name, $cookie_value, time() + (1*24*60*60*10), "/"); // 86400 = 1 day
        return True;
    } catch (Exception $e) {
        $a = "session error";
        return False;
    }
}

if($a)
    echo('<script type="text/javascript">$(document).ready(function(){$("#mail").slideDown(4000).slideUp(4000);});</script>');
?>

<body>
<div id="connect-container">
<div id="mail">
<?php

if($a){
    echo($a);
}
?>
</div>
<div id="cookie">
    <div style="padding:10px;">
        <button style="top:0px;float:right" id="cookie-btn">X</button>
        <h3 style="color:orange">Ce site utilise un cookie pour améliorer la navigation, en clicquant sur cette fentre vous acceptez leur stockage sur votre machine.</h3>
    </div>
</div>
<div class="main-container">
    <div id="retrieve">
        <div id="retrieve-content">
            <div id="retrieve-container">
                <div id="connect" class="form" align="center">
                    <button id="retrieve-btn" style="top:0px;float:right">X</button>
                    <h3>Mot de passe oublié ?</h3>
                    <div class="hr"></div>
                    <form action="" method='post'>
                        <input type="text" name="login" maxlength="255" required placeholder="Entrez votre émail">
                        <input type="submit" name="retrieve" value="Envoyer" class="btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="logo"></div>
    <div class="login-container">
        <div id="connect" class="form" align="center">
            <h3>Déjà membre ?</h3>
            <div class="hr"></div>
            <form action="" method='post'>
                <input type="text" name="login" maxlength="255" required placeholder="Login ou Email">
                <input type="password"name="pass" maxlength="25" placeholder="Mot de passe" required>
                <input type="submit" value="connection" name="connection" class="btn">
            </form>
            <div id="forgotmdp"><h6 style="margin:0px;">Mot de passe oublié ? cliquez ici !</h6></div>
        </div>
        <div id="inscript" class="form" align="center" style="margin-top:60px;">
            <h3>Pas encore membre ? inscrivez vous!</h3>
            <div class="hr"></div>
            <form action="" method='post'>
                <input type="mail" name="mail1" maxlength="255" required placeholder="Email">
                <input type="mail" name="mail2" maxlength="255" required placeholder="Email">
                <input type="password"name="pass" maxlength="25" required placeholder="Mot de passe">
                <input type="submit" value="envoyer" name="inscrit" class="btn">
            </form>
        </div>
    </div>
    <script>
$(document).ready(function(){
    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*60*60*24*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    } 


    if(readCookie("LSL-C")=="ok"){
        $("#cookie").hide();
    }
    $("#cookie-btn").click(function(){
        setCookie("LSL-C","ok" , 1);
        $("#cookie").hide();
    });
    $("#retrieve").click(function(){
        $("#retrieve").hide();
        $("#retrieve-container").animate({'top': '-500px'}, 1);
    });
    $("#forgotmdp").click(function(){
        $("#retrieve").show();
        $("#retrieve-container").animate({'top': '0px'}, 500);
    });
    $("#retrieve-container").click(function(e){
        e.stopPropagation();
    });
    $("#retrieve-btn").click(function(){
        $("#retrieve").hide();
        $("#retrieve-container").animate({'top': '-500px'}, 1);
    });

});
</script>
</div>
</div>  
</body>
</html>