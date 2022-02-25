<?php
if (isset($_GET['id']) && $_GET['id'] <> '') {
    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $deleteSql = "DELETE FROM lokasi WHERE id = ?";
    $stmt = $db->prepare($deleteSql);
    $stmt->bindParam(1, $id);
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil hapus data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal hapus data";
    }
}
echo '<meta http-equiv="refresh" content="0; url=?page=lokasiread">';