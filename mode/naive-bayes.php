<?php
function naiveBayes($luas_lahan, $nama_pupuk) {
    // Dummy probabilitas manual untuk setiap jenis pupuk
    // Ini bisa kamu ganti dengan hasil pelatihan dari dataset nanti
    $probabilitas = [
        'Urea' => ['kurang' => 0.1, 'cukup' => 0.7, 'lebih' => 0.2],
        'NPK' => ['kurang' => 0.2, 'cukup' => 0.6, 'lebih' => 0.2],
        'ZA' =>  ['kurang' => 0.3, 'cukup' => 0.6, 'lebih' => 0.1],
        'Organik' => ['kurang' => 0.2, 'cukup' => 0.5, 'lebih' => 0.3],
    ];

    // Ambil probabilitas berdasarkan pupuk
    $data = $probabilitas[$nama_pupuk] ?? ['cukup' => 1.0];
    
    // Simulasi logika klasifikasi (bisa diperluas)
    arsort($data); // ambil nilai tertinggi
    $kelas = key($data); 
    $nilai = current($data);

    return ['kelas' => $kelas, 'nilai' => $nilai];
}
?>
