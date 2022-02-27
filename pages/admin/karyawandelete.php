<?php
if (isset($_GET['id']) && $_GET['id'] <> '') {
    $database = new Database();
    $db = $database->getConnection();

    //variabel untuk id karyawan
    $id = $_GET['id'];
    $findSql = "SELECT * FROM karyawan WHERE id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch();

    //variabel untuk id pengguna
    $pengguna_id = $row['pengguna_id'];

    $deleteSql = "DELETE FROM karyawan WHERE id = ?";
    $stmt = $db->prepare($deleteSql);
    $stmt->bindParam(1, $id);

    $deletePenggunaSql = "DELETE FROM pengguna WHERE id = ?";
    $stmtDeletePenggunaSql = $db->prepare($deletePenggunaSql);
    $stmtDeletePenggunaSql->bindParam(1, $pengguna_id);

    if ($stmt->execute() && $stmtDeletePenggunaSql->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil hapus data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal hapus data";
    }
}
echo '<meta http-equiv="refresh" content="0; url=?page=karyawanread">';