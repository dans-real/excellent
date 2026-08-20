<?php
// includes/functions.php — Helper functions

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function uploadImage(array $file, string $folder = 'gallery', array $allowedTypes = ['image/jpeg','image/png','image/webp','image/gif']): array {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error: ' . $file['error']];
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'File terlalu besar (max 5MB)'];
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowedTypes)) {
        return ['success' => false, 'error' => 'Format file tidak didukung'];
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid($folder . '_') . '.' . strtolower($ext);
    $dest = UPLOAD_PATH . $folder . '/' . $filename;
    if (!is_dir(UPLOAD_PATH . $folder)) mkdir(UPLOAD_PATH . $folder, 0755, true);
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => false, 'error' => 'Gagal menyimpan file'];
    }
    return ['success' => true, 'path' => $folder . '/' . $filename, 'url' => UPLOAD_URL . $folder . '/' . $filename];
}

function deleteImage(string $path): void {
    $full = UPLOAD_PATH . $path;
    if (file_exists($full)) unlink($full);
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function timeAgo(string $datetime): string {
    $now  = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);
    if ($diff->y > 0) return $diff->y . ' tahun lalu';
    if ($diff->m > 0) return $diff->m . ' bulan lalu';
    if ($diff->d > 0) return $diff->d . ' hari lalu';
    if ($diff->h > 0) return $diff->h . ' jam lalu';
    if ($diff->i > 0) return $diff->i . ' menit lalu';
    return 'Baru saja';
}

function paginate(int $total, int $perPage, int $current): array {
    $totalPages = (int)ceil($total / $perPage);
    return [
        'total'       => $total,
        'per_page'    => $perPage,
        'current'     => $current,
        'total_pages' => $totalPages,
        'offset'      => ($current - 1) * $perPage,
        'has_prev'    => $current > 1,
        'has_next'    => $current < $totalPages,
    ];
}
