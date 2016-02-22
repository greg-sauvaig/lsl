$('#envoi').click(function(e){
    e.preventDefault();

    var pseudo = encodeURIComponent( $('#pseudo').val() );
    var message = encodeURIComponent( $('#message').val() );

    if(pseudo != "" && message != ""){ 
        $.ajax({
            url : "tchat.php", 
            type : "POST", 
            data : "pseudo=" + pseudo + "&message=" + message
        });
    $('#messages').append("<p>" + pseudo + " dit : " + message + "</p>");
    }
});

function charger(){
    setTimeout( function(){
    	var premierID = $('#messages p:first').attr('id');
        $.ajax({
            url : "charger.php?id=" + premierID,
            type : GET,
            success : function(html){
                $('#messages').prepend(html); 
            }
        });
    charger();
    }, 1000);
}
charger();
