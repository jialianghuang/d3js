<!DOCTYPE html>
<?php
$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$epoch = intval(time()/86400);
$currentd = $epoch + 61723;
if(isset($_POST['percentage'])){
$target=$_POST['percentage'];
//echo "percentage:".$target*100;
}
else{
$target = 0.5;}
$count18=0;
$count17=0;
$count16=0;
$prodx18=0;
$prodx17=0;
$prodx16=0;

$sum18=0;
$sum17=0;
$sum16=0;
$percent18=0;
$percent17=0;
$percent16=0;
$lastord = NULL;
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {

    $tsql = "SELECT dbo.invt_log.INVS_NUM,dbo.inv.PROD_CD,dbo.invt_log.LOG_DT from dbo.inv LEFT JOIN dbo.invt_log ON dbo.inv.PROD_CD = dbo.invt_log.PROD_CD ORDER BY dbo.invt_log.INVS_NUM" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                 if($x==0){
                   $lastord=$row['INVS_NUM'];
                 }
                 if($row['INVS_NUM']==$lastord){
            if(substr(trim($row['PROD_CD']),-1)=='X'){
            
            $unixtime = ($row['LOG_DT']-61730)*86400;
            $date= date('Y',$unixtime);
            if($date=='2018'){
              $prodx18++;
              $count18++;
              
            }else if($date=='2017'){
              $prodx17++;
              $count17++;
              
            }else if($date=='2016'){
              $prodx16++;
              $count16++;
              
            }

            }else{
              
            
              $unixtime = ($row['LOG_DT']-61730)*86400;
              $date= date('Y',$unixtime);
              if($date=='2018'){
                $count18++;
                
              }else if($date=='2017'){
                $count17++;
                
              }else if($date=='2016'){
                $count16++;
                
              }
            }
          }else{
             if($date == '2018'){
            $percent18= $prodx18/$count18;
            if($percent18>$target)
            {$sum18++;}
             }else if($date=='2017'){
            $percent17= $prodx17/$count17;
            if($percent17>$target)
            {$sum17++;}
             }else if($date=='2016'){
            $percent16=$prodx16/$count16;
            if($percent16>$target)
            {$sum16++;}
             }
             $lastord=$row['INVS_NUM'];
$count18=0;
$count17=0;
$count16=0;
$prodx18=0;
$prodx17=0;
$prodx16=0;
$percent18=0;
$percent17=0;
$percent16=0;
             if(substr(trim($row['PROD_CD']),-1)=='X'){
            
            
              $unixtime = ($row['LOG_DT']-61730)*86400;
              $date= date('Y',$unixtime);
              if($date=='2018'){
                $prodx18++;
                $count18++;
                
              }else if($date=='2017'){
                $prodx17++;
                $count17++;
                
              }else if($date=='2016'){
                $prodx16++;
                $count16++;
                
              }
  
              }else{
                
              
                $unixtime = ($row['LOG_DT']-61730)*86400;
                $date= date('Y',$unixtime);
                if($date=='2018'){
                  $count18++;
                  
                }else if($date=='2017'){
                  $count17++;
                  
                }else if($date=='2016'){
                  $count16++;
                  
                }
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
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>chart</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
  <script>
  $( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
      " - " + $( "#slider-range" ).slider( "values", 1 ) );
  } );
  </script>
  <script>
  $( function() {
    $( "#slider-range-max" ).slider({
      range: "max",
      min: 1,
      max: 10,
      value: 2,
      slide: function( event, ui ) {
        $( "#amount1" ).val( ui.value*10 +"%");
      }
    });
    $( "#amount1" ).val( $( "#slider-range-max" ).slider( "value" )*10 + "%");
  } );
  </script>
<style>

.bar {
  fill: #72a6f9;
}

.bar:hover {
  fill: #2c7af7;
}

.axis--x path {
  display: none;
}
#ui-datepicker-div{
  z-index:9999 !important;
}
</style>
<svg width="700" height="500"></svg>
<script src="https://d3js.org/d3.v5.min.js"></script>
<script>
var data = [
  {letter: "2016",    frequency:  <?php echo $sum16; ?>},
  {letter: "2017",    frequency:  <?php echo $sum17; ?>},
  {letter: "2018",     frequency: <?php echo $sum18; ?>},

];
var svg = d3.select("svg"),
    margin = {top: 20, right: 20, bottom: 30, left: 40},
    width = +svg.attr("width") - margin.left - margin.right,
    height = +svg.attr("height") - margin.top - margin.bottom;

var x = d3.scaleBand().rangeRound([0, width]).padding(0.1),
    y = d3.scaleLinear().rangeRound([height, 0]);

var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  x.domain(data.map(function(d) { return d.letter; }));
  y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

  g.append("g")
      .attr("class", "axis axis--x")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x));

  g.append("g")
      .attr("class", "axis axis--y")
      .call(d3.axisLeft(y))
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", "0.71em")
      .attr("text-anchor", "end")
      .text("Frequency");

  g.selectAll(".bar")
    .data(data)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.letter); })
      .attr("y", function(d) { return y(d.frequency); })
      .attr("width", x.bandwidth())
      .attr("height", function(d) { return height - y(d.frequency); });
      
g.selectAll(".text")  		
	  .data(data)
	  .enter()
	  .append("text")
	  .attr("class","label")
    .attr("x", function(d) { return x(d.letter); })
      .attr("y", function(d) { return y(d.frequency); })
	  .attr("dy", ".75em")
	  .text(function(d) { return d.frequency; });


</script>
<p>
<span>Number of years comparesion</span>
<input type="text" value="">
</p>
<p>
  <label for="amount">Range:</label>
  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
 
<div id="slider-range"></div>

<p>
  <label for="amount1">Percentage:</label>
  <input type="text" id="amount1" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
<div id="slider-range-max"></div>
<br>
<form action= "chart.php" method = "POST"> 
Percentage:<input type = "text" name="percentage">
<input type="submit" name="submit">
</form>

<form action="test.php" method="POST">
<p>Select Date: <input type="text" id="datepicker" name="picker"></p> 
<input type="submit" name="Percentage">
</form>
