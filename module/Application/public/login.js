$(document).ready(function() {
	//form validation rules
    $("#auth-form").validate({
        rules: {
            username: "required",
            password: {
                required: true,
                minlength: 5
            },
        },
        messages: {
            username: "Por favor, ingrese su nombre de usuario",
            password: {
                required: "Por favor, ingrese su contraseña",
                minlength: "Su contraseña debe tener al menos 5 caracteres"
            },
        },
        submitHandler: function(form) {
        	user=$("#username").val();
        	pass=$("#password").val();

            $.post('/auth/process',{username:user, password:pass}, 
            function(data) {
            	if(!data.resultado){
            		alert(data.mensaje);
            	}else{
                        alert("Bienvenido " + data.username);
            		window.location.replace("/admin");
            	}
            },"json");
        }
    });
});
