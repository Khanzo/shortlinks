<?php

Include_once ('config.php');

class MyBase {

    private $mMySqli;

    function __construct() {
        $this->mMySqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $this->mMySqli->query("SET names 'utf8mb4'");
        $this->mMySqli->query("SET collation_connection='utf8mb4_general_ci'");
        $this->mMySqli->query("SET collation_server='utf8mb4_general_ci'");
        $this->mMySqli->query("SET character_set_client='utf8mb4'");
        $this->mMySqli->query("SET character_set_connection='utf8mb4'");
        $this->mMySqli->query("SET character_set_results='utf8mb4'");
        $this->mMySqli->query("SET character_set_server='utf8mb4'");
    }

    function __destruct() {
        $this->mMySqli->close();
    }

    //удаление по id
    public function delete($id) {
        $query = "DELETE FROM `shortlinks` where `id`=?";
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    //счетчик
    public function counter($id) {
        $query = "UPDATE `shortlinks` SET counters = counters+1 where `id`=?";
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    //получаем shortcode по кэшу если есть
    public function exists($cashe) {
        $r = 'false';
        $query = "SELECT shorturl FROM shortlinks WHERE cashe = ? LIMIT 1";
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('s', $cashe);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $rows = $result->num_rows;
        if ($rows > 0) {
            $obj = $result->fetch_object();
            $r = $obj->shorturl;
        }
        return $r;
    }

    //проверяем по shorycode есть ли
    public function existsShort($uniq) {
        $r = false;
        $query = "SELECT id FROM shortlinks WHERE shorturl = ? LIMIT 1";
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('s', $uniq);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $rows = $result->num_rows;
        if ($rows > 0) {
            $r = true;
        }
        return $r;
    }

    //получаем данные по shorycode если есть
    public function existsShortInfo($uniq) {
        $r = [];
        $query = 'SELECT `id`,`longurl`,`shorturl`,`date_create`,`counters` FROM `shortlinks` WHERE shorturl = ? LIMIT 1';
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('s', $uniq);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $rows = $result->num_rows;
        if ($rows > 0) {
            while ($obj = $result->fetch_object()) {
                $item = array(
                    'id' => $obj->id,
                    'longurl' => rawurldecode($obj->longurl),
                    'shorturl' => $obj->shorturl,
                    'date_create' => $obj->date_create,
                    'counters' => $obj->counters,
                );
                array_push($r, $item);
            }
        }
        return $r;
    }

    //вставка новых записей
    public function saveLink($url, $cashe, $uniq) {
        $d = new DateTime("Now");
        $dc = $d->format('Y-m-d H:i:s');
        $query = "INSERT INTO `shortlinks` (`longurl`,`shorturl`,`cashe`,`date_create`,`counters`) values(?,?,?,?,0)";
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('ssss', $url, $uniq, $cashe, $dc);
        $stmt->execute();
        $stmt->close();
    }

    //получем количество записей
    public function allCount() {
        $count = 0;
        if ($result = $this->mMySqli->query('SELECT COUNT(*) AS Summ FROM `shortlinks`')) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $count = $row['Summ'];
            $result->Close();
        }
        return $count;
    }

    //получаем данные для таблицы со смещением
    public function selectLinks($p, $offset) {
        $r = [];
        $start = ($p - 1) * $offset;
        $query = 'SELECT `id`,`longurl`,`shorturl`,`date_create`,`counters` FROM `shortlinks` order by id asc limit ?,?';
        $stmt = $this->mMySqli->prepare($query);
        $stmt->bind_param('ii', $start, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $rows = $result->num_rows;
        if ($rows > 0) {
            while ($obj = $result->fetch_object()) {
                $item = array(
                    'id' => $obj->id,
                    'longurl' => rawurldecode($obj->longurl),
                    'shorturl' => $obj->shorturl,
                    'date_create' => $obj->date_create,
                    'counters' => $obj->counters,
                );
                array_push($r, $item);
            }
        } else {
            $r = [];
        }
        return $r;
    }

}

?>
