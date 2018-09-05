<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<table class="table">
  <thead>
    <tr>
      <th scope="col">Item#</th>
      <th scope="col">X revenue Percentage</th>
    </tr>
  </thead>
  <tbody>
<?php
$date1= $_POST['picker'];
echo 'Date:'.$date1;
$unix = strtotime($date1);
$time = $unix/86400+61730;
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

    $tsql = "SELECT invt_log.UNIT_PRS,dbo.invt_log.ORDER_QTY,dbo.invt_log.LOG_DT,dbo.invt_log.INVS_NUM,dbo.inv.PROD_CD,dbo.invt_log.LOG_DT from dbo.inv LEFT JOIN dbo.invt_log ON dbo.inv.PROD_CD = dbo.invt_log.PROD_CD WHERE LOG_DT = '".$time."' ORDER BY dbo.invt_log.INVS_NUM" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $unixtime = ($row['LOG_DT']-61730)*86400;
            $date= date('Y-m-d',$unixtime);

                
                if($x==0){
                    $lastord = $row['INVS_NUM'];
                }
                if($row['INVS_NUM']==$lastord){
                    
                if(substr(trim($row['PROD_CD']),-1)=='X'){
                     $prodx18=$prodx18+$row['UNIT_PRS']*$row['ORDER_QTY'];
                     $count18=$count18+$row['UNIT_PRS']*$row['ORDER_QTY'];
                }else{
                    $count18=$count18+$row['UNIT_PRS']*$row['ORDER_QTY'];
                }
            }else{
                
                $percent = $prodx18/$count18;
                if($percent>$target){
                    $sum++;
                }
               
                echo '<tr><th>'.$lastord.'</th>';
                $ptgs=$percent*100;
                echo '<th>'.$ptgs.'</th></tr>';
              
                $prodx18=0;
                $count18=0;
                $lastord = $row['INVS_NUM'];
                if(substr(trim($row['PROD_CD']),-1)=='X'){
                    $prodx18++;
                    $count18++;
               }else{
                   $count18++;
               }
            }
                $x++;
            }
            
        
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn); 
        }
            else{
                echo "connection error";
                die( print_r( sqlsrv_errors(), true)); 
            }

            ?>
            
              </tbody>
</table>