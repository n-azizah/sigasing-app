<?php
if (isset($_POST['button_create'])) {
    //ini untuk membuat objek dari class database
    $database = new Database();

    //ini untuk menjalankan method getConnection yang berisi kode membuka koneksi
    $db = $database->getConnection();

    //untuk validasi mencari NIK yang sama
    $validateSql = "SELECT * FROM karyawan WHERE nik = ?";

    //untuk menyiapkan kueri
    $stmt = $db->prepare($validateSql);

    //binding parameter
    $stmt->bindParam(1, $_POST['nik']);

    //menjalankan kueri
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
?>
<div class="alert alert-danger alert-dismissible">
   <button class="close" data-dismiss="alert" aria-hidden="true">x</button>
   <h5><i class="icon fas fa-ban"></i> Gagal</h5>
   NIK sama sudah ada
</div>
<?php
    } else {
        $validateSql = "SELECT * FROM pengguna WHERE username = ?";
        $stmt = $db->prepare($validateSql);
        $stmt->bindParam(1, $_POST['username']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
        ?>
<div class="alert alert-danger alert-dismissible">
   <button class="close" data-dismiss="alert" aria-hidden="true">x</button>
   <h5><i class="icon fas fa-ban"></i> Gagal</h5>
   Username sama sudah ada
</div>
<?php
        } else {
            if ($_POST['password'] != $_POST['password_ulangi']) {
            ?>
<div class="alert alert-danger alert-dismissible">
   <button class="close" data-dismiss="alert" aria-hidden="true">x</button>
   <h5><i class="icon fas fa-ban"></i> Gagal</h5>
   Password tidak sama
</div>
<?php
            } else {
                $md5Password = md5($_POST['password']);

                $insertSql = "INSERT INTO pengguna VALUES (NULL, ?, ?, ?, NULL)";
                $stmt = $db->prepare($insertSql);
                $stmt->bindParam(1, $_POST['username']);
                $stmt->bindParam(2, $md5Password);
                $stmt->bindParam(3, $_POST['peran']);

                if ($stmt->execute()) {

                    $pengguna_id = $db->lastInsertId();

                    $insertKaryawanSql = "INSERT INTO karyawan VALUES (NULL, ?, ?, ?, ?, ?, ?)";
                    $stmtKaryawan = $db->prepare($insertKaryawanSql);
                    $stmtKaryawan->bindParam(1, $_POST['nik']);
                    $stmtKaryawan->bindParam(2, $_POST['nama_lengkap']);
                    $stmtKaryawan->bindParam(3, $_POST['handphone']);
                    $stmtKaryawan->bindParam(4, $_POST['email']);
                    $stmtKaryawan->bindParam(5, $_POST['tanggal_masuk']);
                    $stmtKaryawan->bindParam(6, $pengguna_id);

                    if ($stmtKaryawan->execute()) {
                        $_SESSION['hasil'] = true;
                        $_SESSION['pesan'] = "Berhasil simpan data";
                    } else {
                        $_SESSION['hasil'] = false;
                        $_SESSION['pesan'] = "Gagal simpan data";
                    }
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo '<meta http-equiv="refresh" content="0; url=?page=karyawanread">';
            }
        }
    }
}
?>

<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Tambah Data Karyawan</h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="?page=home"> Home</li></a>
               <li class="breadcrumb-item"><a href="?page=karyawanread"> Karyawan</li></a>
               <li class="breadcrumb-item">Tambah Data</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title">Tambah Karyawan</h3>
      </div>
      <div class="card-body">
         <form method="post">
            <div class="form-group">
               <label for="nik">Nomor Induk Karyawan</label>
               <input type="text" name="nik" class="form-control">
            </div>
            <div class="form-group">
               <label for="nama_lengkap">Nama Lengkap</label>
               <input type="text" name="nama_lengkap" class="form-control">
            </div>
            <div class="form-group">
               <label for="handphone">Handphone</label>
               <input type="text" name="handphone" class="form-control">
            </div>
            <div class="form-group">
               <label for="email">Email</label>
               <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
               <label for="tanggal_masuk">Tanggal Masuk</label>
               <input type="date" name="tanggal_masuk" class="form-control">
            </div>
            <div class="form-group">
               <label for="username">Username</label>
               <input type="text" name="username" class="form-control">
            </div>
            <div class="form-group">
               <label for="password">Password</label>
               <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
               <label for="password_ulangi">Password (Ulangi)</label>
               <input type="password" name="password_ulangi" class="form-control">
            </div>
            <div class="form-group">
               <label for="peran">Peran</label>
               <select name="peran" class="form-control">
                  <option value="">-- Pilih Peran --</option>
                  <option value="ADMIN">ADMIN</option>
                  <option value="USER">USER</option>
               </select>
            </div>
            <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right ml-2">
               <i class="fa fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-success btn-sm float-right" name="button_create">
               <i class="fa fa-save"></i> Simpan
            </button>
         </form>
      </div>
   </div>
</section>

<?php include_once "partials/scripts.php" ?>