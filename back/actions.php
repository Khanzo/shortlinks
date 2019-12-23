<?php

Include_once ('config.php');
Include_once ('handler.class.php');
$offset = OFFSET;

if (!isset($_POST['act'])) {
    $arr = array('error' => 1);
    echo json_encode($arr);
    return;
}

$act = $_POST['act'];

if ((count($act) > 2) || (count($act) == 0)) {
    $arr = array('error' => 1);
    echo json_encode($arr);
    return;
}

if ($act == 1) {
    
    //load linka offset count
    if (isset($_POST['p'])) {
        $p = intval($_POST['p']);
        $shortHandler = new ShortHandler($offset);
        $all = $shortHandler->allCount();
        $r = $shortHandler->table($p);
        unset($shortHandler);
        $arr = array('table' => $r, 'error' => 0, 'all' => $all, 'offset' => $offset);
        echo json_encode($arr);
        
    } else {
        $arr = array('error' => 1);
        echo json_encode($arr);
    }
    
    return;
}

if ($act == 2) {
    
    //generate and save
    if ((isset($_POST['u'])) && (trim($_POST['u']) != '')) {

        $url = rawurlencode(strtolower(trim(base64_decode($_POST['u']))));
        $cashe = hash("sha256", $url);
        $shortHandler = new ShortHandler($offset);
        $r = $shortHandler->exists($cashe);
        
        if ($r === 'false') {
            $g = $shortHandler->generate($url, $cashe);
            $arr = array('shortcode' => $g, 'error' => 0);
            echo json_encode($arr);
        } else {
            //такой есть
            $arr = array('shortcode' => $r, 'error' => 2);
            echo json_encode($arr);
        }

        unset($shortHandler);
    } else {
        $arr = array('error' => 1);
        echo json_encode($arr);
    }
    
    return;
}

if ($act == 3) {
    
    //delete
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $shortHandler = new ShortHandler($offset);
        $shortHandler->delete($id);
        unset($shortHandler);
    }
    
    return;
}

if ($act == 4) {
    
    //search
    if ((isset($_POST['s'])) && (trim($_POST['s']) != '')) {

        $url = trim(base64_decode($_POST['s']));
        $shortHandler = new ShortHandler($offset);
        $r = $shortHandler->existsShort($url);
        
        if ($r == []) {
            $arr = array('error' => 1);
            echo json_encode($arr);
        } else {
            //такой есть
            $arr = array('table' => $r, 'error' => 0);
            echo json_encode($arr);
        }
        
        unset($shortHandler);
    } else {
        $arr = array('error' => 1);
        echo json_encode($arr);
    }
    
    return;
}
?>