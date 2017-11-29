<html>
	<head>
		<title>.. :: Hologa LLC :: ..</title>
		<link rel="icon" type="image/png" href="./assets/img/favicon.png" />
      <!--  Android 5 Chrome Color-->
      <meta name="theme-color" content="#f57c00">
			<!--  Importacion de bootstrap y dataTables-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<link href="css/index.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<style>
			body
			{
				margin:0;
				padding:0;
				background-color:#f1f1f1;
			}
			.box
			{
				width:1270px;
				padding:20px;
				background-color:#fff;
				border:1px solid #ccc;
				border-radius:5px;
				margin-top:25px;
			}
		</style>
	</head>
	<body>
		<div class="container box">
			<h1 align="center">Promesas de Pago</h1>
			<br />
			<div class="table-responsive">
				<br />
				<div align="right">
					<button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-primary btn-lg">Agregar</button>
					<button type="button" data-toggle="modal" class="btn btn-info btn-lg">12:00:00 P.M.</button>
				</div>
				<br /><br />
				<table id="user_data" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="10%">Imagen</th>
							<th width="20%">Nombre</th>
							<th width="20%">Cuenta</th>
							<th width="10%">Monto</th>
							<th width="15%">Fecha Promesa</th>
							<th width="15%">Fecha Pago</th>
							<th width="10%">Bucket</th>
							<th width="10%">Edit</th>
							<th width="10%">Delete</th>
						</tr>
					</thead>
				</table>

			</div>
		</div>
	</body>
</html>

<div id="userModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="user_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Agregar Promesa</h4>
				</div>
				<div class="modal-body">
					<label>Nombre</label>
					<input type="text" name="nombre" id="nombre" class="form-control" />
					<br />
					<label>Cuenta</label>
					<input type="text" name="cuenta" id="cuenta" class="form-control" />
					<br />
					<label>Monto</label>
					<input type="text" name="monto" id="monto" class="form-control" />
					<br />
					<label>Fecha Promesa</label>
					<input type="text" name="fecha_promesa" id="fecha_promesa" class="form-control" />
					<br />
					<label>Fecha Pago</label>
					<input type="text" name="fecha_pago" id="fecha_pago" class="form-control" />
					<br />
					<label>Bucket</label>
					<input type="text" name="bucket" id="bucket" class="form-control" />
					<br />
					<label>Selecciona Imagen</label>
					<input type="file" name="user_image" id="user_image" />
					<span id="user_uploaded_image"></span>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="user_id" id="user_id" />
					<input type="hidden" name="operation" id="operation" />
					<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
	$('#add_button').click(function(){
		$('#user_form')[0].reset();
		$('.modal-title').text("Add User");
		$('#action').val("Add");
		$('#operation').val("Add");
		$('#user_uploaded_image').html('');
	});

	var dataTable = $('#user_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],

	});

	$(document).on('submit', '#user_form', function(event){
		event.preventDefault();
		var hlgnombre = $('#nombre').val();
		var hlgcuenta = $('#cuenta').val();
		var hlgmonto = $('#monto').val();
		var hlgfecha_promesa = $('#fecha_promesa').val();
		var hlgfecha_pago = $('#fecha_pago').val();
		var hlgbucket = $('#bucket').val();
		var extension = $('#user_image').val().split('.').pop().toLowerCase();
		if(extension != '')
		{
			if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
			{
				alert("Invalid Image File");
				$('#user_image').val('');
				return false;
			}
		}
		if(hlgnombre != '' && hlgcuenta != '' && hlgmonto != '')
		{
			$.ajax({
				url:"insert.php",
				method:'POST',
				data:new FormData(this),
				contentType:false,
				processData:false,
				success:function(data)
				{
					alert(data);
					$('#user_form')[0].reset();
					$('#userModal').modal('hide');
					dataTable.ajax.reload();
				}
			});
		}
		else
		{
			alert("Both Fields are Required");
		}
	});

	$(document).on('click', '.update', function(){
		var user_id = $(this).attr("id");
		$.ajax({
			url:"fetch_single.php",
			method:"POST",
			data:{user_id:user_id},
			dataType:"json",
			success:function(data)
			{
				$('#userModal').modal('show');
				$('#nombre').val(data.nombre);
				$('#cuenta').val(data.cuenta);
				$('#monto').val(data.monto);
				$('#fecha_promesa').val(data.fecha_promesa);
				$('#fecha_pago').val(data.fecha_pago);
				$('#bucket').val(data.bucket);
				$('.modal-title').text("Edit User");
				$('#user_id').val(user_id);
				$('#user_uploaded_image').html(data.user_image);
				$('#action').val("Edit");
				$('#operation').val("Edit");
			}
		})
	});

	$(document).on('click', '.delete', function(){
		var user_id = $(this).attr("id");
		if(confirm("Estás seguro de que quieres eliminar esto?"))
		{
			$.ajax({
				url:"delete.php",
				method:"POST",
				data:{user_id:user_id},
				success:function(data)
				{
					alert(data);
					dataTable.ajax.reload();
				}
			});
		}
		else
		{
			return false;
		}
	});


});
</script>
