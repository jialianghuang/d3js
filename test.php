
<?php

$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$epoch = intval(time()/86400);
$currentd = $epoch + 61723;
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$prodx18=0;
$count18=0;
$percent=0;
$sum=0;
$target=0.2;
$lastord= null;
if( $conn ) {

    $tsql = "SELECT DISTINCT INVS_NUM from dbo.invt_log WHERE LOG_DT >=79382 AND LOG_DT <=79412  ORDER BY INVS_NUM" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  /* if($x == 0)
                   {$last = $row['INVS_NUM'];}
                   if($last != $row['INVS_NUM']){

                 

                       
                        if($row['LOG_DT']>=79382&&$row['LOG_DT']<=79412)
               {$sum++;}

               $last = $row['INVS_NUM'];
                       
                    } */
              
            
            /* $unixtime = ($row['LOG_DT']-61730)*86400;
            $date= date('Y',$unixtime);
            $month = date('m',$unixtime);
            if($date=='2018'){
                if($month>=5&&$month<=6){
    $sum++;}
                }
                */
                
                /* if($row['INVS_NUM']<=9999999){
                if($row['LOG_DT']>=79382&&$row['LOG_DT']<=79412)
                $sum++; 
                } */
            $x++;
            }
            
        
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn); 
        }
            else{
                echo "connection error";
                die( print_r( sqlsrv_errors(), true)); 
            }
       echo $sum.'/'.$x;


      
            ?>
            
