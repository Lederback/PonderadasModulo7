<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Add a student</h1>
<?php

  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  VerifyStudentTable($connection, DB_DATABASE);

  $student_name = htmlentities($_POST['NAME']);
  $student_age = htmlentities($_POST['AGE']);
  $student_status_approved = htmlentities($_POST['STATUS_APPROVED']);
  $student_scholarship = htmlentities($_POST['SCHOLARSHIP']);

  if (strlen($student_name) && strlen((string)$student_age) && strlen($student_scholarship)) {
    AddStudent($connection, $student_name, $student_age, $student_status_approved, $student_scholarship);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>AGE</td>
      <td>STATUS_APPROVED</td>
      <td>SCHOLARSHIP</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="number" name="AGE" />
      </td>
      <td>
        <input type="radio" name="STATUS_APPROVED" value="true" id="TRUE">
          <label for="TRUE"> True</label><br>
        <input type="radio" name="STATUS_APPROVED" value="false" id="FALSE">
          <label for="FALSE"> False</label><br>
      </td>
      <td>
        <input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="SCHOLARSHIP" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>AGE</td>
    <td>STATUS_APPROVED</td>
    <td>SCHOLARSHIP</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM ALUNOS");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

function AddStudent($connection, $name, $age, $status_approved, $scholarship) {
   $n = mysqli_real_escape_string($connection, $name);
   $e = mysqli_real_escape_string($connection, $age);
   $h = ($status_approved === 'true') ? 1 : 0;
   $s = mysqli_real_escape_string($connection, $scholarship);

   $query = "INSERT INTO ALUNOS (NAME, AGE, STATUS_APPROVED, SCHOLARSHIP) VALUES ('$n', '$e', '$h', '$s');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding student data.</p>");
}

function VerifyStudentTable($connection, $dbName) {
  if(!TableExists("ALUNOS", $connection, $dbName))
  {
     $query = "CREATE TABLE ALUNOS (
        ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        NAME VARCHAR(45),
        AGE int,
        STATUS_APPROVED BINARY,
        SCHOLARSHIP float)";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>