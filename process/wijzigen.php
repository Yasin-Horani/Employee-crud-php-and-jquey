<?php
require_once('db.php');
function febonacci($num)
{
    $x0 = 0;
    $x1 = 1;
    while ($x0 < $num) {
        echo $x0 . " ♠ ";
        $x2 = $x0 + $x1;
        $x0 = $x1;
        $x1 = $x2;
    }
}
$number = 3000;

febonacci($number);

