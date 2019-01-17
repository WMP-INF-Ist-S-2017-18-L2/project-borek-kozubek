<?php
set_time_limit(720);                   #script time work in seconds
$connectsql = require_once 'connectsql.php';    #Database connection

#----- preparing SQL Queries -----#
$stmt = $pdo->prepare("INSERT INTO $table ($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8)VALUES(:itemid,:datatrackid,:city,:cena,:powierzchnia,:rooms,:url,now())");
$stmtu = $pdo->prepare("UPDATE $table SET $col3 = :city, $col4 = :cena, $col5 = :powierzchnia, $col6 = :rooms, $col7 = :url WHERE $col1 = :itemid AND $col2 = :datatrackid");
$data = $pdo->prepare("INSERT INTO $datatable ($datcol1, $datcol2, $datcol3)VALUES(:id, :vendor,:date)");
$datau = $pdo->prepare("UPDATE $datatable SET $datcol2 = :vendor, $datcol3 = :date WHERE $datcol1 = :id");
$deldat = $pdo->prepare("DELETE FROM $table WHERE $col8 <=  NOW() - INTERVAL '1 MONTH'");
#-------------------------#

#----- open File for save -----#
$fp = fopen("daneolx.txt","w");

#----- Deleting old records -----#
try {
    $deldat->execute();
}catch(Exception $e){
    #echo "Date delete ERROR: ".$e->getMessage();
    error_log('<br />Date delete PROBLEM: ' . $e->getMessage());
}

#----- Strings for matching data from page -----#
$data_item_id='[\s]ad_id(.*?)"';
$data_trackin_id='[\s]*data-id="(.*?)"';
$web_link='a[\s]*href=\"https://www.(.*)?html';
$web_cena='[\s]class="price">[\s]*<strong>(.*?)z';
$web_field='';
$web_rooms='';
$web_pages="page-link-last\">[\s]*<span>(.*?)<";
$web_city='location-filled(.*?)\"';
#$web_district='';
#$web_province='';

#----- Initializing cURL extension & defining options -----#
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://www.olx.pl/nieruchomosci/mieszkania/wynajem/?search%5Border%5D=created_at%3Adesc");

$result = curl_exec($ch);                                               //string of web page
preg_match_all("!$web_pages!", $result, $match0);       //matching string for number of web pages
$webpages = array_values($match0[1]);                                   //assign to variable

#echo "$webpages[0]";                      // say numer of pages

#----- calling function for first page -----#
pars($result);

#----- loop for evry page with announcements/advertisments -----#
for($pagenumber = 2; $pagenumber < $webpages[0]; $pagenumber++){
    #----- setting page & getting them -----#
    try {
        curl_setopt($ch, CURLOPT_URL, "https://www.olx.pl/nieruchomosci/mieszkania/wynajem/?search%5Border%5D=created_at%3Adesc&page=$pagenumber");
        $result = curl_exec($ch);
    }catch(Exception $e){
        #echo '<br />Url PROBLEM: '.$e->getMessage();
        error_log('<br />Url PROBLEM: '.$e->getMessage());
    }
    #----- calling function -----#
    pars($result);
}

#----- Function for matching, trimmign strings, binding for query, -----#
function pars($wynik){
    #----- getting needed variables -----#
    global $data_item_id,$data_trackin_id,$web_link,$web_city,$web_cena,$web_field,$web_rooms,$pdo,$stmt,$stmtu,$pagenumber,$fp;

    #----- Matching strings -----#
    try {
        preg_match_all("!$data_item_id!", $wynik, $match1);
        preg_match_all("!$data_trackin_id!", $wynik, $match2);
        preg_match_all("!$web_link!", $wynik, $match3);
        preg_match_all("!$web_city!", $wynik, $match4);
        preg_match_all("!$web_cena!", $wynik, $match5);
        preg_match_all("!$web_field!", $wynik, $match6);
        preg_match_all("!$web_rooms!", $wynik, $match7);
    }catch (Exception $e){
        #echo '<br />Search PROBLEM: '.$e->getMessage();
        error_log('<br />Search PROBLEM: '.$e->getMessage());
    }

    #----- assign matches, unique prevents repetitions -----#
    try {
        $dataids = array_values(array_unique($match1[1]));
        $datasids = array_values(array_unique($match2[0]));
        $linkeds = array_values(array_unique($match3[0]));
        $citisies = array_values($match4[1]);
        $cenowka = array_values($match5[1]);
        $fielded = array_values($match6[1]);
        $roomsed = array_values($match7[1]);
    }catch(Exception $e){
        #echo '<br />Matching PROBLEM: '.$e->getMessage();
        error_log('<br />Matching PROBLEM: '.$e->getMessage());
    }

    for ($i=0; $i < count ($datasids); $i++) {
        #----- checking for adv freom this service -----#
        if(substr($linkeds[$i], 20, 3) !== "olx" ){
            #echo "to jest i: ".$i."<br />";
        }else{

            #----- triming matches=> preparing for insertion to DB -----#
            try {
                $linkeds[$i] = substr($linkeds[$i], 8);
                $datasids[$i] = substr($datasids[$i], 10, -1);
                #$dataids[$i] = substr($dataids[$i], 0, 0);
                $cenowka[$i] = preg_replace('/[^0-9]/', '', $cenowka[$i]);
                $fielded[$i] = preg_replace('/[,]/', ".", preg_replace('/[^0-9,]/', '', $fielded[$i]));
                #$roomsed[$i] = preg_replace('/[^0-9]/', '', $roomsed[$i]);
            } catch (Exception $e) {
                #echo '<br />Trim PROBLEM: ' . $e->getMessage();
                error_log('<br />Trim PROBLEM: ' . $e->getMessage());
            }

            #----- binding -----#
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
            } catch (Exception $e) {
                #echo '<br />Data PROBLEM: ' . $e->getMessage();
                error_log('<br />Data PROBLEM: ' . $e->getMessage());
            }

            #----- executing query with values, withdrawal of existing records -----#
            $pdo->beginTransaction();
            try{
                $stmt->execute($datas);
                $pdo->commit();
            }catch(PDOException $err){
                try {
                    $pdo->rollBack();
                    $stmtu->execute($datas);
                }catch (Exception $error){
                    #echo '<br />Update record PROBLEM: '.$error->getMessage();
                    error_log('<br />Update record PROBLEM: '.$error->getMessage());
                }
                error_log('<br />PDO PROBLEM: '.$err->getMessage());
            }catch (Exception $e) {
                #echo '<br />Insert Into DB PROBLEM: '.$e->getMessage();
                error_log('<br />Insert Into DB PROBLEM: '.$e->getMessage());
            }

            #----- save to file -----#
            try {
                #echo "<pre>$pagenumber &#09 $i &#09 $dataids[$i] &#09 $datasids[$i] &#09 $citisies[$i] &#09 $cenowka[$i] &#09 $fielded[$i] &#09 $roomsed[$i] &#09 $linkeds[$i]</pre>";
                $dane_do_pliku = "$pagenumber \t $i \t $dataids[$i] \t $datasids[$i] \t $citisies[$i] \t $cenowka[$i] \t $fielded[$i] \t $roomsed[$i] \t $linkeds[$i]\n";
                fputs($fp, $dane_do_pliku);
            } catch (Exception $e) {
                #echo '<br />echo or sth PROBLEM: ' . $e->getMessage();
                error_log('<br />echo or sth PROBLEM: ' . $e->getMessage());
            }
        }
    }
}

#----- Binding and executing Query for data table in DB -----#
$datas = [
    ':id' => '2',
    ':vendor' => 'olx',
    ':date' => date('d-m-Y H:i:s'),
];
try{
    $data->execute($datas);
}catch (PDOException $e){
    $datau->execute($datas);
}catch (Exception $err){
    #echo '<br />Data Insertion PROBLEM: '.$e->getMessage().'or Update PROBLEM: '.$err->getMessage();
    error_log('<br />Data Insertion PROBLEM: '.$e->getMessage().'or Update PROBLEM: '.$err->getMessage());
}
#-------------------------#

echo 'done in '.date('d-m-Y H:i:s');

#----- closing file and cURL ext -----#
fclose($fp);
curl_close($ch);

#----- cleaning and closing/terminating connection to the Data-base -----#
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