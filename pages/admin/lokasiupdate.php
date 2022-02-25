<?php
if (isset($_GET['id']) && $_GET['id'] <> '') {
    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $findSql = "SELECT * FROM lokasi WHERE id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch();
    if (isset($row['id'])) {
?>
<!-- di sini koding untuk formnya -->
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Ubah Data Lokasi</h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="?page=home"> Home</li></a>
               <li class="breadcrumb-item"><a href="?page=lokasiread"> Lokasi</li></a>
               <li class="breadcrumb-item">Ubah Data</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title">Ubah Lokasi</h3>
      </div>
      <div class="card-body">
         <form method="post">
            <div class="form-group">
               <label for="nama_lokasi">Nama Lokasi</label>
               <input type="hidden" name="id" class="form-control" value="<?= $row['id']; ?>">
               <input type="text" name="nama_lokasi" class="form-control" value="<?= $row['nama_lokasi']; ?>">
            </div>
            <a href="?page=lokasiread" class="btn btn-danger btn-sm float-right ml-2">
               <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-success btn-sm float-right" name="button_update">
               <i class="fa fa-save"></i> Simpan
            </button>
         </form>
      </div>
   </div>
</section>

<?php include_once "partials/scripts.php" ?>
<?php
    } else {
        echo '<meta http-equiv="refresh" content="0; url=?page=lokasiread">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0; url=?page=lokasiread">';
}