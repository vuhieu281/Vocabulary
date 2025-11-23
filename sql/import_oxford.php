<?php
$pdo = new PDO("mysql:host=localhost:3366;dbname=vocabulary_db;charset=utf8", "root", "");

$file = fopen("oxford_words.csv", "r");

fgetcsv($file);

while (($row = fgetcsv($file)) !== FALSE) {

    $oxford_url    = $row[1];
    $level         = $row[2];
    $word          = $row[3];
    $part_of_speech = $row[4];
    $ipa           = $row[5];
    $audio_link    = $row[6];
    $senses        = $row[7];


    $stmt = $pdo->prepare("
        INSERT IGNORE INTO local_words (word, part_of_speech, ipa, audio_link, senses, level, oxford_url)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url]);
}

fclose($file);

echo "🎉 Import thành công toàn bộ dữ liệu Oxford!";
?>
