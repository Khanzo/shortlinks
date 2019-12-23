<?php

Include_once ('mybase.class.php');

class ShortHandler {

    private $offset;
    protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";

    function __construct($o = 10) {
        $this->$offset = $o;
    }

    function __destruct() {
        
    }

    //проверка на существования url
    public function exists($cashe) {
        $MyBaseReg = new MyBase();
        $r = $MyBaseReg->exists($cashe);
        unset($MyBaseReg);
        return $r;
    }

    //проверка на существование shortcode
    public function existsShort($uniq) {
        $MyBaseReg = new MyBase();
        $ex = $MyBaseReg->existsShortInfo($uniq);
        unset($MyBaseReg);
        return $ex;
    }

    //получаем данные по линку для таблицы
    public function table($p) {
        $MyBaseReg = new MyBase();
        $r = $MyBaseReg->selectLinks($p, $this->$offset);
        unset($MyBaseReg);
        return $r;
    }

    //получаем общее количество
    public function allCount() {
        $MyBaseReg = new MyBase();
        $r = $MyBaseReg->allCount();
        unset($MyBaseReg);
        return $r;
    }

    //удаление по id
    public function delete($id) {
        $MyBaseReg = new MyBase();
        $MyBaseReg->delete($id);
        unset($MyBaseReg);
    }

    //счетчик по id
    public function counter($id) {
        $MyBaseReg = new MyBase();
        $MyBaseReg->counter($id);
        unset($MyBaseReg);
    }

    //генерация shortcode из строки символов
    function genLink($length = 5) {
        $sets = explode('|', self::$chars);
        $all = '';
        $randString = '';
        
        foreach ($sets as $set) {
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        
        $all = str_split($all);
        
        for ($i = 0; $i < $length - count($sets); $i++) {
            $randString .= $all[array_rand($all)];
        }
        
        $randString = str_shuffle($randString);
        $uniq = 'z' . $randString;
        return $uniq;
    }

    //генерируем и проверяем есть ли такой
    public function generate($url, $cashe) {
        $length = 4;
        $step = 0;
        $uniq = $this->genLink($length);
        $MyBaseReg = new MyBase();
        $ex = $MyBaseReg->existsShort($uniq);
        
        while ($ex !== false) {
            $step++;
            $uniq = $this->genLink($length);
            $ex = $MyBaseReg->existsShort($uniq);
            
            if ($step > 20) {
                $length++;
                $step = 0;
            }
        }
        
        $MyBaseReg->saveLink($url, $cashe, $uniq);
        unset($MyBaseReg);
        return $uniq;
    }

}

?>
