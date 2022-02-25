<?php 
if (isset($_GET['id'])) {
   $database = new Database();
   $db = $database->getConnection();

   $id = $_GET['id'];
   $findSql = "SELECT * FROM lokasi WHERE id = ?";
   $stmt = $db->prepare($findsql);
   $stmt->bindParam(1, $_GET['id']);
   $stmt->execute();
   $row = $stmt->fetch();
   if (isset($row['id'])) {
 ?>

<?php  
}else {
   echo "<meta http-equive='refresh' content='0;url=?page=lokasiread'>";
}
} else {
   echo "<meta http-equive='refresh' content='0;url=?page=lokasiread'>";
}
   ?>