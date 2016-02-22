<?php
    include_once('../includes/utils.php');
    if(!empty($_POST["searched"])) {
        $q = $_POST["searched"];
    if (!$q) return;
    $pdo = getCo();
    $req = $pdo->prepare("SELECT nom, prenom FROM identite WHERE nom LIKE '$q%' or prenom  LIKE '$q%' LIMIT 10");
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);
    $t = array();
    $a = 0;
    foreach ($res as $key => $value) {
            $t[$a] = array("label" => $res[$key]["nom"],"desc" => $res[$key]["prenom"]);
        $a++;
    }

    echo json_encode(array("items" => $t));
}
?>