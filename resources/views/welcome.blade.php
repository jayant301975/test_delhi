<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
			<!-- DataTables CSS -->
			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

			<!-- jQuery JS -->
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

			<!-- DataTables JS -->
			<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

			<!-- Bootstrap JS -->
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	</head>
    <body>
        <div class="container">
		  <table class="table mytable">
			<thead>
			 <tr>
			   <td>Filter </td>
			   <td> 
					<select class="sexfilter form-control" name="">
					 <option value="0">Please Select</option>
					 <option value="male">Male</option>
					 <option value="female">Female</option>
					</select>
			   
			   
			   </td>
			   <td><a href="{{route('export')}}" class="btn btn-success">Export</a></td>
			</tr>
			<tr>
			  <th>Id</th>
			  <th>Name</th>
			  <th>Email</th>
              <th>Gender</th>			
		      <th>Nationality</th>
			</tr>  
			 </thead>
			 <tbody>
			
			 </tbody>
			 </table>
		</div>
    
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

     <script>
	 $(document).ready(function(){
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    $(".mytable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/') }}",
            type: "GET", 
            dataType: "json",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        columns: [
            { data: 'DT_RowIndex', name: 'id' },
            { data: 'name', name: 'name' },
			 { data: 'email', name: 'email' },
            { data: 'gender', name: 'gender' },
             { data: 'nationality', name: 'nationality' },
           
        ],
        lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
        pageLength: 10, // Set the default number of rows per page
        error: function(xhr, error, thrown) {
            console.log("DataTables error:", error, thrown);
        }
    });
});
 
	 </script>




</body>
</html>
