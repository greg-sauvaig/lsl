function popin1(){
	if (document.getElementById('form-connect').style.display == (false||"none"))
	{
		document.getElementById('form-inscrit').style.display = 'none';
		document.getElementById('form-connect').style.display = 'block';
	}
	else
		document.getElementById('form-connect').style.display = 'none';

}

function popin(){
	if (document.getElementById('form-inscrit').style.display == (false||"none"))
	{
		document.getElementById('form-connect').style.display = 'none';
		document.getElementById('form-inscrit').style.display = 'block';
	}
	else
		document.getElementById('form-inscrit').style.display = 'none';

}