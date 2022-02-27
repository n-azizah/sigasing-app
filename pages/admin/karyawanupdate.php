<?php
if (isset($_GET['id']) && $_GET['id'] <> '') {
    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $findSql = "SELECT * FROM karyawan INNER JOIN pengguna ON pengguna.id = karyawan.pengguna_id WHERE karyawan.id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch();
    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {

            $database = new Database();
            $db = $database->getConnection();

            $validateSql = "SELECT * FROM karyawan WHERE NIK = ? AND id != ?";
            $stmt = $db->prepare($validateSql);
            $stmt->bindParam(1, $_POST['nik']);
            $stmt->bindParam(2, $_POST['id']);
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
                $validateSql = "SELECT * FROM pengguna WHERE username = ? AND pengguna.id != ?";
                $stmt = $db->prepare($validateSql);
                $stmt->bindParam(1, $_POST['username']);
                $stmt->bindParam(2, $_POST['pengguna_id']);
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

                        if ($_POST['password'] <> '') {
                            $updateSql = "UPDATE pengguna SET username = ?, password = ?, peran = ? WHERE id = ?";
                            $stmt = $db->prepare($updateSql);
                            $stmt->bindParam(1, $_POST['username']);
                            $stmt->bindParam(2, $md5Password);
                            $stmt->bindParam(3, $_POST['peran']);
                            $stmt->bindParam(4, $_POST['pengguna_id']);
                        } else {
                            $updateSql = "UPDATE pengguna SET username = ?, peran = ? WHERE id = ?";
                            $stmt = $db->prepare($updateSql);
                            $stmt->bindParam(1, $_POST['username']);
                            $stmt->bindParam(2, $_POST['peran']);
                            $stmt->bindParam(3, $_POST['pengguna_id']);
                        }

                        if ($stmt->execute()) {
                            $updateKaryawanSql = "UPDATE karyawan SET nik=?, nama_lengkap=?, handphone=?, email=?, tanggal_masuk=? WHERE id=?";
                            $stmtKaryawan = $db->prepare($updateKaryawanSql);
                            $stmtKaryawan->bindParam(1, $_POST['nik']);
                            $stmtKaryawan->bindParam(2, $_POST['nama_lengkap']);
                            $stmtKaryawan->bindParam(3, $_POST['handphone']);
                            $stmtKaryawan->bindParam(4, $_POST['email']);
                            $stmtKaryawan->bindParam(5, $_POST['tanggal_masuk']);
                            $stmtKaryawan->bindParam(6, $_POST['id']);

                            if ($stmtKaryawan->execute()) {
                                $_SESSION['hasil'] = true;
                                $_SESSION['pesan'] = "Berhasil ubah data";
                            } else {
                                $_SESSION['hasil'] = false;
                                $_SESSION['pesan'] = "Gagal ubah data";
                            }
                        } else {
                            $_SESSION['hasil'] = false;
                            $_SESSION['pesan'] = "Gagal ubah data";
                        }
                        echo '<meta http-equiv="refresh" content="0; url=?page=karyawanread">';
                    }
                }
            }
        }
        ?>
<!-- di sini koding untuk formnya -->
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Ubah Data Karyawan</h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="?page=home"> Home</li></a>
               <li class="breadcrumb-item"><a href="?page=karyawanread"> Karyawan</li></a>
               <li class="breadcrumb-item">Ubah Data</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title">Ubah Karyawan</h3>
      </div>
      <div class="card-body">
         <form method="post">
            <div class="form-group">
               <input type="hidden" name="id" class="form-control" value="<?= $row['id']; ?>">
               <label for="nik">Nomor Induk Karyawan</label>
               <input type="text" name="nik" class="form-control" value="<?= $row['nik']; ?>">
            </div>
            <div class=" form-group">
               <label for="nama_lengkap">Nama Lengkap</label>
               <input type="text" name="nama_lengkap" class="form-control" value="<?= $row['nama_lengkap']; ?>">
            </div>
            <div class="form-group">
               <label for="handphone">Handphone</label>
               <input type="text" name="handphone" class="form-control" value="<?= $row['handphone']; ?>">
            </div>
            <div class="form-group">
               <label for="email">Email</label>
               <input type="email" name="email" class="form-control" value="<?= $row['email']; ?>">
            </div>
            <div class="form-group">
               <label for="tanggal_masuk">Tanggal Masuk</label>
               <input type="date" name="tanggal_masuk" class="form-control" min="1990-01-01" max="2050-01-01"
                  value="<?= $row['tanggal_masuk']; ?>">
            </div>
            <div class="form-group">
               <input type="hidden" name="pengguna_id" class="form-control" value="<?= $row['pengguna_id']; ?>">
               <label for="username">Username</label>
               <input type="text" name="username" class="form-control" value="<?= $row['username']; ?>">
            </div>
            <div class="form-group">
               <label for="password">Password</label>
               <input type="password" name="password" class="form-control"
                  placeholder="Kosongkan jika tidak ingin diganti">
            </div>
            <div class="form-group">
               <label for="password_ulangi">Password (Ulangi)</label>
               <input type="password" name="password_ulangi" class="form-control"
                  placeholder="Kosongkan jika tidak ingin diganti">
            </div>
            <div class="form-group">
               <label for="peran">Peran</label>
               <select name="peran" class="form-control">
                  <option value="">-- Pilih Peran --</option>
                  <option value="ADMIN" <?php if ($row['peran'] == 'ADMIN') echo 'selected'; ?>>ADMIN</option>
                  <option value="USER" <?php if ($row['peran'] == 'USER') echo 'selected'; ?>>USER</option>
               </select>
            </div>
            <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right ml-2">
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
        echo '<meta http-equiv="refresh" content="0; url=?page=karyawanread">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0; url=?page=karyawanread">';
}