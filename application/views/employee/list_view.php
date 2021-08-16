<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- CSS -->
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/datatable/dataTables.bootstrap4.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/styles.css">
		
		<!-- JavaScript -->
		<script src="<?= base_url() ?>assets/js/jquery-3.4.1.min.js"></script>
		<script src="<?= base_url() ?>assets/js/popper.min.js"></script>
		<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
		<script src="<?= base_url() ?>assets/js/jquery.validate.js"></script>
		<script src="<?= base_url() ?>assets/js/datatable/jquery.dataTables.min.js"></script>
		<script src="<?= base_url() ?>assets/js/datatable/dataTables.bootstrap4.min.js"></script>
		<script src="<?= base_url() ?>assets/js/datatable/dataTables.buttons.min.js"></script>

		<title>Employee Database</title>
	</head>
	<body>
		<header class="py-3 mb-4 border-bottom">
			<div class="container d-flex flex-wrap justify-content-center">
				<h5 style="letter-spacing: 1px;">EMPLOYEE DATABASE</h5>
			</div>
		</header>
		
		<div class="container mt-5 mb-5">
			<div class="row">	
				<div class="col-md-12">	
					<div class="float-right mb-3">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Upload Data</button>
					</div>
				</div>
			</div>
				
			<table class="table table-bordered" id="employee_table">
				<thead>
					<tr>
						<th width="40">#</th>
						<th>Employee Code</th>
						<th>Employee Name</th>
						<th>Department</th>
						<th>Age</th>
						<th>Experience in the organization</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if (!empty($employees)) {
					$i=1;
					foreach ($employees as $emp) :
				?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo (!empty($emp->emp_code))? $emp->emp_code : ''; ?></td>
						<td><?php echo (!empty($emp->name))? $emp->name : ''; ?></td>
						<td><?php echo (!empty($emp->department))? $emp->department : ''; ?></td>
						<td><?php echo (!empty($emp->dob))? date_diff(date_create($emp->dob), date_create('today'))->y : ''; ?></td>
						<td><?php echo (!empty($emp->joining_date))? date_diff(date_create($emp->joining_date), date_create('today'))->y : ''; ?></td>
					</tr>
				<?php  $i++;	
					endforeach;
				}
				?>
				</tbody>
			</table>
		</div>	
		
		
		<!-- modal -->
		<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="loadermodal">Please Wait...</div>
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Upload Employee Data</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					
					<form name="employee_form" id="employee_form" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="resultmsg"></div>
							</div>
						</div>
						<h5>Define columns</h5><br/>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="column1">Column 1 <span class="field-mandatory">*</span></label>
									<select class="form-control" id="column1" name="column1">
										<option value="" hidden>Select</option>
										<option value="name">Name</option>
										<option value="employee code">Employee code</option>
										<option value="department">Department</option>
										<option value="date of birth">Date of Birth</option>
										<option value="joining date">Joining Date</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label for="column1">Column 2 <span class="field-mandatory">*</span></label>
									<select class="form-control" id="column2" name="column2">
										<option value="" hidden>Select</option>
										<option value="name">Name</option>
										<option value="employee code">Employee code</option>
										<option value="department">Department</option>
										<option value="date of birth">Date of Birth</option>
										<option value="joining date">Joining Date</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label for="column1">Column 3 <span class="field-mandatory">*</span></label>
									<select class="form-control" id="column3" name="column3">
										<option value="" hidden>Select</option>
										<option value="name">Name</option>
										<option value="employee code">Employee code</option>
										<option value="department">Department</option>
										<option value="date of birth">Date of Birth</option>
										<option value="joining date">Joining Date</option>
									</select>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="column1">Column 4 <span class="field-mandatory">*</span></label>
									<select class="form-control" id="column4" name="column4">
										<option value="" hidden>Select</option>
										<option value="name">Name</option>
										<option value="employee code">Employee code</option>
										<option value="department">Department</option>
										<option value="date of birth">Date of Birth</option>
										<option value="joining date">Joining Date</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label for="column1">Column 5 <span class="field-mandatory">*</span></label>
									<select class="form-control" id="column5" name="column5">
										<option value="" hidden>Select</option>
										<option value="name">Name</option>
										<option value="employee code">Employee code</option>
										<option value="department">Department</option>
										<option value="date of birth">Date of Birth</option>
										<option value="joining date">Joining Date</option>
									</select>
								</div>
							</div>
							
							<hr>
							<h5>Upload CSV File</h5><br/>
							<div class="form-row">
								<div class="form-group col-md-6">
								  <input type="file" class="form-control-file" id="csvfile" name="emp_data">
								</div>
							</div>
					</div>
					
					<div class="modal-footer">
						<a href="<?php echo base_url() ?>uploads/sample_data.csv" class="btn btn-info">Download Sample CSV File</a>
						<button type="submit" class="btn btn-primary" id="emp_save">Save</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		<!-- modal -->
		
		
		<script>
			$(document).ready( function () {
				$('#employee_table').DataTable();
				
				jQuery.validator.addMethod("notEqual", function(value, element, param) {
					var notEqual = true;
					for (i = 0; i < param.length; i++) {
						if (value == $(param[i]).val()) { notEqual = false; }
					}
					return this.optional(element) || notEqual;
				}, "Please specify a different value");
				
				jQuery.validator.addMethod("extension", function(value, element, param) {
					param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
					return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
				}, "Extension is not valid.");
				
				$('#employee_form').validate({
					ignore: [],
					rules: {
						  column1: {
							required: true,
							notEqual: ['#column2', '#column3', '#column4', '#column5']
						  },
						  column2: {
							required: true,
							notEqual: ['#column1', '#column3', '#column4', '#column5']
						  },
						  column3: {
							required: true,
							notEqual: ['#column1', '#column2', '#column4', '#column5']
						  },
						  column4: {
							required: true,
							notEqual: ['#column1', '#column2', '#column3', '#column5']
						  },
						  column5: {
							required: true,
							notEqual: ['#column1', '#column2', '#column3', '#column4']
						  },
						  emp_data: {
							required: true,
							extension: "csv"
						  }
					},
					messages: {
							
					},
					submitHandler: function(form) {	
						$('.loadermodal').show();
						$('#emp_save').attr('disabled',true);
						var formData = new FormData(form);
						$.ajax({
							type: "POST",
							dataType: "json",
							url:  "<?php echo base_url(); ?>" + "employee/uploadEmployeeData",
							data: formData,
							contentType: false,
							processData: false,
							cache:false,
							async:false,
							success: function (result) {
								$('.loadermodal').hide();
								$('#emp_save').attr('disabled',false);
								if(!result.status){
									$(".resultmsg").html("<div class='alert alert-danger loading wow fadeIn animated'>"+result.message+"</div>");
								}else{
									$(".resultmsg").html("<div class='alert alert-success login wow fadeIn animated'>"+result.message+"</div>");

									setTimeout(function(){ 
									   location.reload();
									},2000);
								}
							}
						});	
						return false;
					},	
				});
				
			});
		
		</script>
		
	</body>
</html>