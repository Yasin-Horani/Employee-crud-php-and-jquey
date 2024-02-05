<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="description" content="Free Web tutorials">
	<meta name="keywords" content="php, jQuery, Ajax, mysql">
	<meta name="author" content="Yasin Horni">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Employees Systeem</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
	<style>
		body {
			background-image: url(https://pngfile.net/download/JSIbCvYdxr9AC7dGIlgr5gnP00DQa0YA1KNbKBPQo6rJ4VEI288d7RKKl9367a0B6ocRwBHXA6tgDnOLZNSJC6tWbpMOKl7bakfotel4ZOxGrARxUe7jOAmvPkjLio52XuGzupxFjVmnCtdIE994TxeuzidGARDtMtLDfteQbFZmNAtOrqhkrrFnQYyyvNF95z4GEbZ3/large);
			background-size: cover;
			background-repeat: no-repeat;
			background-attachment: fixed;
		}

		.list-email {
			font-style: italic;
		}

		.list-address {
			margin-top: -14px;
			margin-bottom: 0px;
			font-size: 12px;
		}

		.btnDelete {
			float: right;
		}

		.btnEdit {
			margin-right: 1em;
			float: right;
		}

		h1 {
			color: crimson;
		}

		h3 {
			color: orange;
		}

		.list-group-item {
			cursor: grab;
		}

		#email,
		#first_name,
		#last_name,
		#address {
			background-color: #ffffff82;
			resize: none;
		}

		.items {
			display: none;
		}

		#btnUpdate {
			display: none;
		}

		.dateGroup {
			border: black 1px solid;
		}

		#time,
		#date {
			float: right;
			padding: 20px;
			color: gray;
		}

		#search {
			float: right;
			margin-right: 10px;
		}

		.search {
			float: right;
			margin-right: 10px;
		}
	</style>
	<div class="dateGroup">
		<h3 id="time" class="">Time: </h3>
		<h3 id="date" class="">date: </h3>
	</div>
	<div class="container">
		<br><br>
		<h1>Employees Info</h1>


		<br><br>

		<div class="row">
			<div class="col-md-4">
				<h3>Add New Employee</h3>
				<form action="./process/process.php" id="employeeForm" method="POST" name="id">
					<div class="form-group">
						<label for="email">Email</label>
						<input class="form-control edit" type="email" name="email" id="email" require>
					</div>
					<div class="form-group">
						<label for="first_name">Voornaam</label>
						<input class="form-control edit" type="text" name="first_name" id="first_name" require>
					</div>
					<div class="form-group">
						<label for="last_name">Achternaam</label>
						<input class="form-control edit" type="text" name="last_name" id="last_name" require>
					</div>
					<div class="form-group">
						<label for="address">Adres</label>
						<textarea class="form-control edit" name="address" id="address" rows="3" require></textarea>
					</div>
					<div class="form-group">
						<label for="gender">gender:</label>
						<input type="radio" id="male" name="gender" value="male" require>
						<label for="male">male</label>
						<input type="radio" id="female" name="gender" value="female" require>
						<label for="female">female</label><br>
					</div>
					<input type="hidden" id="edit_id" name="edit_id" value="">
					<button type="button" class="btn btn-primary" id="btnSubmit">Opslaan</button>
					<button type="button" class="btn btn-primary" id="btnUpdate">Update</button>
				</form>

			</div>
			<div class="col-md-8">

				<button id="refresh" class="btn btn-info float-right">Refresh</button>
				<input id="myInput" type="text" placeholder="Search from Front-End..">

				<input type="text" id="search" placeholder="Search from database.." />
				<button type="button" class="btn btn-primary search" id="btnSearch">Search</button>
				<h3>List of Employees: <span id="id"></span> </h3>
				<div id="employees-list"></div>
			</div>
		</div>



	</div>

	<script>
		jQuery(function() {
			function save() {
				var $this = $("#btnSubmit");
				var form = "#form";

				$this.on("click", function() {
					var $caption = $this.html();

					// Validate the required fields
					var email = $("#email").val();
					var firstName = $("#first_name").val();
					var lastName = $("#last_name").val();
					var gender = $("input[name='gender']:checked").val();
					if (email.trim() === "" || firstName.trim() === "" || lastName.trim() === "" || !gender) {
						$("#btnSubmit").notify("Please fill in all required fields.", "error");
						return;
					}
					var formData = $(form).serialize();
					$.ajax({
						type: "POST",
						url: './process/process.php',
						dataType: 'json',
						data: {
							act: 'save',
							email: email,
							first_name: firstName,
							last_name: lastName,
							address: $("#address").val(),
							gender: $("input[name='gender']:checked").val()
						},
						beforeSend: function() {
							$this.attr('disabled', true).html("Processing...");
						},
						success: function(data) {
							console.log(data);
							$this.attr('disabled', false).html($caption);
							if (data['status'] === 'success') {
								all();
								$.notify(
									"Employee has been created successfully.", {
										position: "top center",
										className: "success"
									});
								resetForm();
							} else {
								alert("Error: " + data['message']);
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							alert("AJAX Error: " + errorThrown);
						}
					});
				});
			}

			function resetForm() {
				$('#form')[0].reset();
			}
			// Delete Item
			function deleteItem() {
				$(document).on("click", ".btnDelete", function() {
					var id = $(this).attr('itemID');
					if (confirm('Are you sure you want to delete this employee?')) {
						$.ajax({
							type: "POST",
							url: './process/process.php',
							dataType: 'json',
							data: {
								act: 'delete',
								id: id
							},
							success: function(data) {
								if (data.status === 'success') {
									$.notify(
										"Employee has been deleted successfully.", {
											position: "top center",
											className: "info"
										});
									$("#id").text('');
									all();
								} else {
									alert("Error: " + data.message);
								}
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								// Handle errors if any
								console.error(errorThrown);
							}
						});
					}
				});
			}

			function getData() {
				$(document).on("click", ".btnEdit", function() {
					$("#btnSubmit").css({
						display: "none"
					});
					$("#btnUpdate").css({
						display: "block"
					});

					var id = $(this).attr('itemID');
					var email = $(this).attr('itemEmail');
					var first_name = $(this).attr('itemFirstname');
					var last_name = $(this).attr('itemLastname');
					var address = $(this).attr('itemAddress');
					var gender = $(this).attr('gender')

					$('#btnUpdate').attr({
						'itemID': id
					});
					$("#id").text(id);
					$("#email").val(email);
					$("#first_name").val(first_name);
					$("#last_name").val(last_name);
					$("#address").val(address);
					$("input[name='gender'][value='" + gender + "']").prop("checked", true);
				});
			}

			function update() {
				$(document).on("click", "#btnUpdate", function() {
					var id = $(this).attr('itemID');

					var $this = $("#btnUpdate");
					var form = "#form";
					var $caption = $this.html();
					var formData = $(form).serialize(); // Serialize the entire form
					var gender = $("input[name='gender']:checked").val();

					// Add validation checks for empty fields
					var email = $("#email").val();
					var first_name = $("#first_name").val();
					var last_name = $("#last_name").val();
					var address = $("#address").val();

					if (!email || !first_name || !last_name || !address || !gender) {
						$("#btnUpdate").notify("Please fill in all required fields.", "error");
						return;
					}
					$.ajax({
						type: "POST",
						url: './process/process.php',
						dataType: 'json',
						data: {
							act: 'update',
							id: id,
							email: email,
							first_name: first_name,
							last_name: last_name,
							address: address,
							gender: gender,
						},
						beforeSend: function() {
							$this.attr('disabled', true).html("Processing...");
						},

						success: function(data) {
							//console.log(data);
							$this.attr('disabled', false).html($caption);
							if (data['status'] === 'success') {
								$("#btnSubmit").css({
									display: "block"
								});
								$("#btnUpdate").css({
									display: "none"
								});
								all();
								$.notify(
									"Employee has been updated successfully.", {
										position: "top center",
										className: "success"
									});
								// Reset form
								resetForm();
							} else {
								// Handle errors
								alert("Error: " + data['message']);
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							// Handle AJAX errors here
							alert("AJAX Error: " + errorThrown);
						}
					});
				});
			}

			function refreshPage() {
				$(document).on("click", "#refresh", function() {
					$("#btnSubmit").css({
						display: "block"
					});
					$("#btnUpdate").css({
						display: "none"
					});

					$('#employeeForm input').val("");
					$('#employeeForm textarea').val("");
					$("input[name='gender']").prop("checked", false);
					/*
					$("#id").text('');
					$("#email").val("");
					$("#first_name").val("");
					$("#last_name").val("");
					$("#address").val("");
					$("input[name='gender']").prop("checked", false);
					*/
					$("#refresh").notify(
						"Page has been refreshed", {
							position: "right",
							className: "success"
						}
					);

					$("#myInput").val("");
					$("#search").val("");
					all();
				});
			}

			function time() {
				var dt = new Date();
				var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
				var date = dt.getFullYear() + "/" + (dt.getMonth() + 1) + "/" + dt.getDate();
				$("#time").text(time);
				$("#date").text(date);
			}

			function search() {
				$("#myInput").on("keyup", function() {
					var value = $(this).val().toLowerCase();
					$(".list-group-item").filter(function() {
						$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
			}

			function all(search) {

				$.ajax({
					method: 'POST',
					url: './process/process.php',
					dataType: 'JSON',
					data: {
						act: 'all',
						search: search
					},
					success: function(data) {

						var rows = data['row'];
						var html = "";
						if (rows.length) {
							html += '<div class="list-group">';
							$.each(rows, function(key, value) {
								html += '<a href="#" class="list-group-item list-group-item-action" itemID=' + value.id + ' >';
								html += "<p>" + value.first_name + ' ' + value.last_name + " <span class='list-email'>(" + value.email + "), (" + value.gender + ")";
								html += "<button type='button' class='btn btn-danger btnDelete' itemID='" + value.id + "'>Verwijderen</button>";
								html += "<button type='button' class='btn btn-primary btnEdit' itemAddress='" + value.address + "'  itemLastname='" + value.last_name + "'   itemFirstname='" + value.first_name + "'  itemEmail='" + value.email + "'   itemID='" + value.id + "' gender='" + value.gender + "'>Wijzigen</button>";
								html += "</span>" + "</p>";
								html += "<p class='list-address'>" + value.address + " </p>";
								html += "";
								html += '</a>';
							});
							html += '</div>';
						} else {
							html += '<div class="alert alert-warning">';
							html += 'No matching records found!';
							html += '</div>';
						}
						$("#employees-list").html(html);
					}
				});
			}

			//function searchSQL() {
			$(document).on("keyup", "#search", function() {
				var search = $("#search").val();
				if (search.length >= 3) {
					all(search);
				} else {
					all();
				}
			});
			/*
			function openYouTubeAfterDelay() {
				setTimeout(function() {
					window.open('https://www.linkedin.com/in/yasin-horani-01a029228', 'xx'); // Open YouTube in a new tab
				}, 5000); // 60000 milliseconds = 1 minute
			}
			*/
			$(document).ready(function() {
				all();
				search();
				save();
				deleteItem();
				getData();
				update();
				refreshPage();
				time();
				setInterval(time, 1000);
				//searchSQL();
				
				search();
				//window.onload = openYouTubeAfterDelay;
			});

		});
	</script>
	<script src="./script/notify.js"></script>
	<script src="./script/notify.min.js"></script>
</body>

</html>