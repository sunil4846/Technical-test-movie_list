<?php
include "db.php";
// Read values
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];
if(isset($_POST['type']) && isset($_POST['value'])){
    $categoryType = trim($_POST['type']);
    $categoryValue = trim($_POST['value']);
}else{
    $categoryType = '';
    $categoryValue = '';
}

$sort_by = $_POST['sort_by'];

$searchQuery = "";

// search

if ($searchValue != '') {
    $searchQuery .= " and (title like '%".$searchValue."%' or description like '%".$searchValue."%' or movie_length like '%".$searchValue."%' or movie_r_date like '%".$searchValue."%')";
}

if(!empty($categoryType) && !empty($categoryValue)){
    $searchQuery .= " and (category.type = '$categoryType' and category.value = '$categoryValue')";
}

// total number of records  without filtering
$sel = $db->query("select count(*) as allcount from movies");
$records= $sel->fetch_assoc();
$totalRecords = $records['allcount'];

// total numbe of records with filtering
$sel = $db->query("select count(*) as allcount from movies inner join relationship on movies.id = relationship.movie_id
inner join category on category.id = relationship.category_id where 1 ".$searchQuery);
$records = $sel->fetch_assoc();
$totalRecordsWithFilter = $records['allcount'];

// sort and fetch records
if($sort_by != ''){
    $movieQuery = "select movies.*,category.type,category.value
from movies 
inner join relationship on movies.id = relationship.movie_id
inner join category on category.id = relationship.category_id where 1".$searchQuery." order by ".$sort_by." ".$columnSortOrder." limit ".$row.",".$rowperpage;
    // $movieQuery = "select * from movies where 1".$searchQuery." order by ".$sort_by." ".$columnSortOrder." limit ".$row.",".$rowperpage;
}else{
    $movieQuery = "select movies.*,category.type,category.value
    from movies 
    inner join relationship on movies.id = relationship.movie_id
    inner join category on category.id = relationship.category_id where 1".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
    // $movieQuery = "select * from movies where 1".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
}



$movieRecords = $db->query($movieQuery);

$data = array();

while ($row = $movieRecords->fetch_assoc()) {
    $data[] = array(
        "title" => $row['title'],
        "description" => $row['description'],
        "movie_length" => $row['movie_length'],
        "movie_r_date" => $row['movie_r_date'], 
        "type" => $row['type'],
        "value" => $row['value']
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecordsWithFilter,
    "iTotalDisplayRecords" => $totalRecords,
    "aaData" => $data
);

echo json_encode($response);

?>