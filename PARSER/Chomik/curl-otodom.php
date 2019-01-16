<?php
set_time_limit(600);
$connectsql = require_once 'connectsql.php';
$stmt = $pdo->prepare("INSERT INTO $table ($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8)VALUES(:itemid,:datatrackid,:city,:cena,:powierzchnia,:rooms,:url,now())");
$stmtu = $pdo->prepare("UPDATE $table SET $col3 = :city, $col4 = :cena, $col5 = :powierzchnia, $col6 = :rooms, $col7 = :url WHERE $col1 = :itemid AND $col2 = :datatrackid");
$data = $pdo->prepare("INSERT INTO $datatable ($datcol1, $datcol2, $datcol3)VALUES(:id, :vendor,:date)");
$datau = $pdo->prepare("UPDATE $datatable SET $datcol2 = :vendor, $datcol3 = :date WHERE $datcol1 = :id");
$deldat = $pdo->prepare("DELETE FROM $table WHERE $col8 <=  NOW() - INTERVAL 1 MONTH");
$fp = fopen("daneoto.txt","w");

$data_item_id='article class=\"offer-item ad_id(.*?)\s*\"';
$data_trackin_id='data-tracking-id=\"[^\s]*?\"';
$web_link='data-url=\"https://www.otodom.pl/oferta/[^\s]*?html';
$web_cena='li class=\"offer-item-price\">\s*(.*?)\s*zł';
$web_field='strong class=\"visible-xs-block\">(.*?) m';
$web_rooms='li class="offer-item-rooms hidden-xs\">(.*?) pok';
$web_pages='strong class=\"current\">(.*?)<';
$web_city='p class=\"text-nowrap hidden-xs\">Mieszkanie na wynajem: (.*?),';
#$web_district='';
#$web_province='';

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://www.otodom.pl/wynajem/mieszkanie/?nrAdsPerPage=999&search%5Border%5D=created_at_first%3Adesc");
$result = curl_exec($ch);
preg_match_all("!$web_pages!", $result, $match0);
$webpages = array_values($match0[1]);

echo "$webpages[0]";

pars($result);

for($pagenumber = 2; $pagenumber < $webpages[0]; $pagenumber++){
    try {
        curl_setopt($ch, CURLOPT_URL, "https://www.otodom.pl/wynajem/mieszkanie/?nrAdsPerPage=999&search%5Border%5D=created_at_first%3Adesc&page=$pagenumber");
        $result = curl_exec($ch);
    }catch(Exception $e){
        echo '<br />Url PROBLEM: '.$e->getMessage();
        error_log('<br />Url PROBLEM: '.$e->getMessage());
    }

    pars($result);
}

function pars($wynik){
    global $data_item_id,$data_trackin_id,$web_link,$web_city,$web_cena,$web_field,$web_rooms,$pdo,$stmt,$stmtu,$pagenumber,$fp;
    try {
        preg_match_all("!$data_item_id!", $wynik, $match1);
        preg_match_all("!$data_trackin_id!", $wynik, $match2);
        preg_match_all("!$web_link!", $wynik, $match3);
        preg_match_all("!$web_city!", $wynik, $match4);
        preg_match_all("!$web_cena!", $wynik, $match5);
        preg_match_all("!$web_field!", $wynik, $match6);
        preg_match_all("!$web_rooms!", $wynik, $match7);
    }catch (Exception $e){
        echo '<br />Search PROBLEM: '.$e->getMessage();
        error_log('<br />Search PROBLEM: '.$e->getMessage());
    }

    try {
        $dataids = array_values(array_unique($match1[1]));
        $datasids = array_values(array_unique($match2[0]));
        $linkeds = array_values(array_unique($match3[0]));
        $citisies = array_values($match4[1]);
        $cenowka = array_values($match5[1]);
        $fielded = array_values($match6[1]);
        $roomsed = array_values($match7[1]);
    }catch(Exception $e){
        echo '<br />Matching PROBLEM: '.$e->getMessage();
        error_log('<br />Matching PROBLEM: '.$e->getMessage());
    }

    for ($i=0; $i < count ($linkeds); $i++){
        try {
            $linkeds[$i] = substr($linkeds[$i], 10);
            $datasids[$i] = substr($datasids[$i], 18, -1);
            $dataids[$i] = substr($dataids[$i], 0, 5);
            $cenowka[$i] = preg_replace('/[^0-9]/', '', $cenowka[$i]);
            $fielded[$i] = preg_replace('/[,]/', ".", preg_replace('/[^0-9,]/', '', $fielded[$i]));
            #$roomsed[$i] = preg_replace('/[^0-9]/', '', $roomsed[$i]);
        }catch (Exception $e){
            echo '<br />Trim PROBLEM: '.$e->getMessage();
            error_log('<br />Trim PROBLEM: '.$e->getMessage());
        }

        try {
            $datas = [
                ':itemid' => $dataids[$i],
                ':datatrackid' => $datasids[$i],
                ':city' => $citisies[$i],
                ':cena' => $cenowka[$i],
                ':powierzchnia' => $fielded[$i],
                ':rooms' => $roomsed[$i],
                ':url' => $linkeds[$i],
            ];
        }catch (Exception $e){
            echo '<br />Data PROBLEM: '.$e->getMessage();
            error_log('<br />Data PROBLEM: '.$e->getMessage());
        }

        $pdo->beginTransaction();
        try{
            $stmt->execute($datas);
            $pdo->commit();
        }catch(PDOException $err){
            try {
                $pdo->rollBack();
                $stmtu->execute($datas);
            }catch (Exception $error){
                echo '<br />Update record PROBLEM: '.$error->getMessage();
                error_log('<br />Update record PROBLEM: '.$error->getMessage());
            }
            error_log('<br />PDO PROBLEM: '.$err->getMessage());
        }catch (Exception $e) {
            echo '<br />Insert Into DB PROBLEM: '.$e->getMessage();
            error_log('<br />Insert Into DB PROBLEM: '.$e->getMessage());
        }

        try{
            echo "<pre>$pagenumber &#09 $i &#09 $dataids[$i] &#09 $datasids[$i] &#09 $citisies[$i] &#09 $cenowka[$i] &#09 $fielded[$i] &#09 $roomsed[$i] &#09 &#39$linkeds[$i]&#39</pre>";
            $dane_do_pliku = "$pagenumber \t $i \t $dataids[$i] \t $datasids[$i] \t $citisies[$i] \t $cenowka[$i] \t $fielded[$i] \t $roomsed[$i] \t $linkeds[$i]\n";
            fputs($fp, $dane_do_pliku);
        }catch(Exception $e){
            echo '<br />echo or sth PROBLEM: '.$e->getMessage();
            error_log('<br />echo or sth PROBLEM: '.$e->getMessage());
        }
    }
}

$datas = [
    ':id' => '1',
    ':vendor' => 'otodom',
    ':date' => date('d-m-Y H:i:s'),
];

try{
    $data->execute($datas);
}catch (PDOException $e){
    $datau->execute($datas);
}catch (Exception $err){
    echo '<br />Data Insertion PROBLEM: '.$e->getMessage().'or Update PROBLEM: '.$err->getMessage();
    error_log('<br />Data Insertion PROBLEM: '.$e->getMessage().'or Update PROBLEM: '.$err->getMessage());
}

$deldat->execute();

echo 'done in '.date('d-m-Y H:i:s');

fclose($fp);
curl_close($ch);
$stmt->closeCursor();
$stmtu->closeCursor();
$data->closeCursor();
$datau->closeCursor();
$stmt = null;
$stmtu = null;
$data = null;
$datau = null;
$pdo->query('SELECT pg_terminate_backend(pg_backend_pid());');
$pdo = null;
?>
