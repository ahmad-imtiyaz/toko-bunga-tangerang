<?php
// ============================================================
// Generic CRUD helper used by categories.php, locations.php etc.
// ============================================================

function handleCrud(string $table, array $fields, string $redirect): ?array {
    $action = $_GET['action'] ?? '';
    $id     = (int)($_GET['id'] ?? 0);
    $msg    = null;

    // DELETE
    if ($action === 'delete' && $id) {
        db()->prepare("DELETE FROM {$table} WHERE id=?")->execute([$id]);
        $msg = ['type'=>'success','text'=>'Data berhasil dihapus.'];
    }

    // SAVE
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pid = (int)($_POST['id'] ?? 0);
        $values = [];
        $cols   = [];

        foreach ($fields as $f) {
            $val = trim($_POST[$f] ?? '');

            // Handle image upload
            if ($f === 'image' && !empty($_FILES[$f]['name'])) {
                $ext   = strtolower(pathinfo($_FILES[$f]['name'], PATHINFO_EXTENSION));
                $fname = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES[$f]['tmp_name'], UPLOAD_DIR . $fname)) {
                    $val = $fname;
                } else {
                    $val = $_POST['old_' . $f] ?? '';
                }
            } elseif ($f === 'image') {
                $val = $_POST['old_image'] ?? '';
            }

            $cols[]   = $f;
            $values[] = $val;
        }

        if ($pid) {
            $sets = implode('=?,', $cols) . '=?';
            db()->prepare("UPDATE {$table} SET {$sets},updated_at=NOW() WHERE id=?")->execute([...$values, $pid]);
            $msg = ['type'=>'success','text'=>'Data berhasil diperbarui.'];
        } else {
            $placeholders = implode(',', array_fill(0, count($cols), '?'));
            $colStr = implode(',', $cols);
            db()->prepare("INSERT INTO {$table} ({$colStr}) VALUES ({$placeholders})")->execute($values);
            $msg = ['type'=>'success','text'=>'Data berhasil ditambahkan.'];
        }
    }

    return $msg;
}
