<?php
// Разрешаем твоему сайту Fornex общаться с этим сервером
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $input = $_FILES['video']['tmp_name'];
    $output = sys_get_temp_dir() . '/vid_' . uniqid() . '.mp4';
    
    // Запускаем станок FFmpeg
    $cmd = "ffmpeg -y -i " . escapeshellarg($input) . " -c:v libx264 -preset veryfast -pix_fmt yuv420p -c:a aac -movflags +faststart " . escapeshellarg($output) . " 2>&1";
    exec($cmd, $log, $status);
    
    if ($status === 0 && file_exists($output) && filesize($output) > 0) {
        header('Content-Type: video/mp4');
        readfile($output);
        unlink($output);
        exit;
    } else {
        http_response_code(500); echo "Ошибка конвертации"; exit;
    }
}
echo "Кузница Railway работает и готова к приему металла!";
?>
