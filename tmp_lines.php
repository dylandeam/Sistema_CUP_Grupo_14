<?php
$lines = file('app/Http/Controllers/PostulanteController.php');
foreach ($lines as $i => $line) {
    $num = $i + 1;
    if ($num >= 449 && $num <= 520) {
        echo sprintf('%4d: %s', $num, $line);
    }
}
