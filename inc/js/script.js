$(function() {

	$('a.add').click(function(){
		$("div.form").show();
		$("input#action").val("add");
		return false;
	});

	$('a.update').click(function(){
		$("div.form").show();
		$("input#action").val("upd");
		return false;
	});

	$('a.delete.user').click(function(){
		$("input#action").val("del");
		$("input[name='uid']").val($(this).data("uid"));
		

		$.ajax({
		        url: '/administration/gestion_des_utilisateurs',
		        type: 'post',
		        dataType: 'json',
		        data: $('form#form').serialize(),
		        success: function(data) {
		                   alert("submitted");
		                 }
		    });
	});

	$('a.delete.profil').click(function(){
		$("input#action").val("del");
		$("input[name='pid']").val($(this).data("pid"));
		

		$.ajax({
		        url: '/administration/gestion_des_profils',
		        type: 'post',
		        dataType: 'json',
		        data: $('form#form').serialize(),
		        success: function(data) {
		                   alert("submitted");
		                 }
		    });
	});

	$("span.close").click(function(){
		$("div.form").hide();
	});


	$("#form").submit(function(){

		$value = false;

		if( $("input[name='action']").val() != 'del'){

			if( $("input[name='nom']").val() == '') $value = true;

			if( $("input[name='prenom']").val() == '') $value = true;

			if( $("input[name='email']").val() == '') $value = true;

			if( $("input[name='username']").val() == '') $value = true;

			if( $("input[name='password']").val() == '') $value = true;

			if( $("input[name='profil']").val() == '-1') $value = true;

		}

		if ($value == true) {

			alert("Merci de remplir tous les champs");
			return false;

		}
	});
});