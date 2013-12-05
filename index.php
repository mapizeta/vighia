<!doctype html>
<!--[if IE 6]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if (gt IE 6)|!(IE)]><!-->
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>Control Vighia</title>
<meta name="author" content="ROWSIS especialista en aplicaciones multiplataforma">
<link rel="shortcut icon" href="http://vighiaprime.com//images/favicon.ico">
<link rel="stylesheet" type="text/css" href="http://vighiaprime.com//css/styles.css">
<script type="text/javascript" src="http://vighiaprime.com//include/scripts/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
$(function(){
	
	$("form").submit(function(){
		var form = $(this);
		$.ajax({
			url: form.attr('action'),
			type: 'POST',
			beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
			data: $(this).serialize(),
			success: function(data){
				var response = $.parseJSON(data);
				if(response.type == 'error'){
					alert(response.text);
					//$('input#usuario, input#pass').val('');
				}else{
					location='home/index.php';
				}
				form.find('button[type=submit]').removeAttr('disabled');
			}
		});
		return false;
	});
	
});	
</script>
</head>
<body>

<header>
</header>
<!--
<div class="lang">
	<label for="lang">Idioma: </label>
	<select name="lang" id="lang">
    	<option value="esp">Español</option>
        <option value="eng">Ingles</option>
    </select>
</div>-->

<div class="login">
	<img class="logo" src="images/logo.png" width="269" height="75" alt="Logo Vighia" />
    <h4>Ingreso Usuarios</h4>
	<form id="form" action="index.code.php">
    	<input type="text" name="usuario" id="usuario" placeholder="Usuario" />
        <input type="password" name="pass" id="pass" placeholder="Contraseña" />
        <input type="hidden" name="task" value="login" />
    </form>
    <button form="form" type="submit" form="form">Entrar</button>
    <a href="#">¿A olvidado su contraseña?</a>
</div>

<footer>
</footer>

</body>
</html>
