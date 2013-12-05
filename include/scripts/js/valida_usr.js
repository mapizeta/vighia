// JavaScript Document
function comparar(p1,p2){
	if(p1.value!=p2.value)
		return false
	else
		return true
	}
function valida(f){
	/*
	if (f.nombre.value==''){
		alert('Debe ingresar nombre');
		f.nombre.focus()
		return false
	}
	
	if (f.email.value==''){
	alert('Debe ingresar E-Mail');
		f.email.focus()
		return false
		}
		
	if (f.user.value==''){
	alert('Debe ingresar datos');
		f.user.focus()
		return false
		} */
	if (f.pass.value==''){
	alert('Debe ingresar una contraseña');
		f.pass.focus()
		return false
			}
	if(comparar(f.pass,f.pass2))
		return true
	else{
	alert('las contraseñas deben coincidir');
		f.pass2.focus()
		return false
	}		
}
