<?php
$countt = 0;
$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {

    $tsql = "SELECT DISTINCT CLASS_CD from dbo.inv" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $y = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                   $class[$y] = trim($row['CLASS_CD']);
                   $y++;
               }
               sqlsrv_free_stmt($stmt);
               sqlsrv_close($conn); 
           }
               else{
                   echo "connection error";
                   die( print_r( sqlsrv_errors(), true)); 
               }

               for($j=0;$j<$y;$j++){
                   $classcount[$j] = 0;
                   $percent[$j] = 0;
               }
               ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<table class="table">
  <thead>
    <tr>
      <th scope="col">CLASS</th>
      <th scope="col">CLASS Net Sales</th>
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

$sum=0;
$target=0.2;
$lastord= null;
if( $conn ) {

    $tsql = "SELECT dbo.invt_log.DISCOUNT,dbo.invt_log.PROD_QTY,dbo.inv.CLASS_CD,dbo.invt_log.UNIT_PRS,dbo.invt_log.ORDER_QTY,dbo.invt_log.LOG_DT,dbo.invt_log.INVS_NUM,dbo.inv.PROD_CD,dbo.invt_log.LOG_DT FROM dbo.invt_log LEFT JOIN dbo.inv ON dbo.inv.PROD_CD = dbo.invt_log.PROD_CD WHERE LOG_DT = '".$time."' ORDER BY dbo.invt_log.INVS_NUM" ;  
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

                
               
                    for($i=0;$i<$y;$i++){
                        if(trim($row['CLASS_CD']) == $class[$i]){
                            if($row['DISCOUNT']>0)
                            $classcount[$i] = $classcount[$i]+ $row['UNIT_PRS']*(1-$row['DISCOUNT']/100)*$row['ORDER_QTY'];
                            else
                            $classcount[$i] = $classcount[$i]+ $row['UNIT_PRS']*$row['ORDER_QTY'];
                            
                            $countt = $countt + $row['UNIT_PRS']*$row['ORDER_QTY'];
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
            
            for($i=0;$i<$y;$i++){
             if($countt != 0 ) {  
            $percent[$i] = ($classcount[$i]/$countt)*100;}
            
            if($percent[$i] >0 && $class[$i] != '')
            echo '<tr><th>'.$class[$i].'</th><th>'.$classcount[$i].'</th></tr>';
            }
            ?>
            
              </tbody>
</table>