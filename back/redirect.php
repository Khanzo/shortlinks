<?php
if (isset($_GET['z'])) {
    Include_once ('tools.php');
    Include_once ('handler.class.php');
    $z = 'z'.trim($_GET['z']);
    $z = clear($z);
    $r = [];
    $shortHandler = new ShortHandler(0);
    $r = $shortHandler->existsShort($z);
    if (count($r) == 0) {
        unset($shortHandler);
        header("Location: ../404.html");
        return;
    }
    $r = $r[0];
    $id = $r['id'];
    $longLink = $r['longurl'];
    $shortHandler->counter($id);
    unset($shortHandler);
    header("Location: ".$longLink);
}
else{
    header("Location: ../");
}
?>