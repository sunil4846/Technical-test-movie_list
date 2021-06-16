<?php
include 'db.php';

$sql = "select * from movies";
$result = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Test</title>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">    
    <!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
</head>
<body>
  <div class="container">
    
    <!-- Filter start -->
    <table border="0" cellspacing="5" cellpadding="5">
        <tbody>
        <tr>
          <td>
            <label for="">Filter By:</label><br>
            <span>Type:</span>
            <select id='type_lg' class="category_type">
              <option value=''>-- Filter By--</option>

              <?php $result= $db->query('SELECT DISTINCT type FROM category ORDER BY type'); 
              $type_lg = "";
              ?>
              <?php while($row= $result->fetch_assoc()) { ?>
                  <option <?php if ($row['type']==$type_lg) { ?>selected="selected"<?php } ?>>
                      <?php echo ($row['type']); ?>
                  </option>
              <?php } ?>

            </select>

            <span>Value:</span>
            <select id = "value_lg" class="category_value" >
              <option value=''>-- Filter By--</option>
                
            </select>
          </td>
        </tr>      
        </tbody>
    </table>
    <!-- filter end -->

    <!-- sort by start -->
    <table border="0" cellspacing="5" cellpadding="5">
      <tbody>
      <tr>
        <td>
          <label for="sort_by">Sort By:</label>
          <select id='sort_by'>
            <option value=''>-- select --</option>
            <option value='movie_length'>Length</option>
            <option value='movie_r_date'>Release Date</option>
          </select>
        </td>
      </tr>      
      </tbody>
    </table>
    <!-- sort by end -->
  
  <table id="techMovies" class="table table-striped table-bordered display dataTables">
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Movie Length(in minutes)</th>
        <th>Movie Release Date</th>
        <th>Type</th>
        <th>Value</th>
      </tr>
    </thead>   
  </table>

  <table id="techMovies1" class="table table-striped table-bordered display dataTables">
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Movie Length(in minutes)</th>
        <th>Movie Release Date</th>
        <th>Type</th>
        <th>Value</th>
      </tr>
    </thead>   
  </table>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>   
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
$('#techMovies1').hide();
// Initialize DataTables API object and configure table
var table = $('#techMovies').DataTable({
    
    "processing": true,
    "serverSide": true,
    "serverMethod": 'post',
    "ajax": {
       "url": "ajaxfile.php",
       "data": function ( d ) {
         return $.extend( {}, d, {
           "sort_by": $('#sort_by').val(),

         } );
       }
     },
     'columns':[
        {data: 'title'},
        {data: 'description'},
        {data: 'movie_length'},
        {data: 'movie_r_date'},
        {data: 'type'},
        {data: 'value'}
      ]
});

$(document).ready(function(){
    // Redraw the table
    table.draw();
    
    // Redraw the table based on the custom input
    $('#sort_by').bind("change", function(){
        table.draw();
    });


});

$(document).on('change','.category_type', function () {
    var category_type = $('.category_type').find(":selected").text();
        $.ajax({
        type: "POST",
        url: "categoryTypePHP.php",
        data: {type:category_type},
        success: function (response) {
          $('.category_value').html(response);

        }
    });
});

$(document).on('change','.category_value', function () {
  $('#techMovies1').show();
  $('#techMovies').hide();
  $('#techMovies_wrapper').css('display', 'none');
    var category_type = $('.category_type').find(":selected").text();
    var category_value = $('.category_value').find(":selected").text();
    
    var table = $('#techMovies1').DataTable({
    // "searching": false,
    "processing": true,
    "serverSide": true,
    "serverMethod": 'post',
    "ajax": {
       "url": "ajaxfile.php",
       "data": function ( d ) {
         return $.extend( {}, d, {
           "sort_by": $('#sort_by').val(),
           "type": category_type,
           "value": category_value,

         } );
       }
     },
     'columns':[
        {data: 'title'},
        {data: 'description'},
        {data: 'movie_length'},
        {data: 'movie_r_date'},
        {data: 'type'},
        {data: 'value'}
      ]
});
    
});

</script>

</body>
</html>

