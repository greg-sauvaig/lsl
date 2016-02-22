$(document).ready(function(){
    $("#resultat").append('<div id="messages"></div><form method="POST" action="lsl.php">
    	pseudo : <input type="text" name="pseudo" id="pseudo" /><br />
    	Message : <textarea name="message" id="message"></textarea><br />
    	<input type="submit" name="submit" value="Envoyez votre message !" id="envoi" /></form>');
});