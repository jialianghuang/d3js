<!DOCTYPE html>
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
<h3>CLASS Revenue Percentage</h3>
<?php
$countt = 0;
if(isset($_POST['min'])){
$min = $_POST['min'];
if($min == 'Jan')
{$min = 1;}else if($min == 'Feb')
{$min = 2;}else if($min == 'Mar')
{$min = 3;}else if($min == 'Apr')
{$min = 4;}else if($min == 'May')
{$min = 5;}else if($min == 'Jun')
{$min = 6;}else if($min == 'Jul')
{$min = 7;}else if($min == 'Aug')
{$min = 8;}else if($min == 'Sep')
{$min = 9;}else if($min == 'Oct')
{$min = 10;}else if($min == 'Nov')
{$min = 11;}else if($min == 'Dec')
{$min = 12;}
}else{
  $min = 1;
}
if(isset($_POST['max'])){
$max = $_POST['max'];
if($max == 'Jan')
{$max = 1;}else if($max == 'Feb')
{$max = 2;}else if($max == 'Mar')
{$max = 3;}else if($max == 'Apr')
{$max = 4;}else if($max == 'May')
{$max = 5;}else if($max == 'Jun')
{$max = 6;}else if($max == 'Jul')
{$max = 7;}else if($max == 'Aug')
{$max = 8;}else if($max == 'Sep')
{$max = 9;}else if($max == 'Oct')
{$max = 10;}else if($max == 'Nov')
{$max = 11;}else if($max == 'Dec')
{$max = 12;}
}else{
  $max = 3;
}

$month18=strtotime('2018-'.$min)/86400+61730;
$month17=strtotime('2017-'.$min)/86400+61730;
$month16=strtotime('2016-'.$min)/86400+61730;
$month15=strtotime('2015-'.$min)/86400+61730;
$month181=strtotime('2018-'.$max)/86400+61729;
$month171=strtotime('2017-'.$max)/86400+61729;
$month161=strtotime('2016-'.$max)/86400+61729;
$month151=strtotime('2015-'.$max)/86400+61729;


$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$epoch = intval(time()/86400);
$currentd = $epoch + 61723;
if(isset($_POST['percentage'])){
$target=$_POST['percentage']/10;
}
else{
$target = 0.5;}
$testper=0;
$count18=0;
$count17=0;
$count16=0;
$count15=0;
$prodx18=0;
$prodx17=0;
$prodx16=0;
$prodx15=0;
$sum18=0;
$sum17=0;
$sum16=0;
$sum15=0;
$sum18a=0;
$sum17a=0;
$sum16a=0;
$sum15a=0;
$percent18=0;
$percent17=0;
$percent16=0;
$percent15=0;
$lastord = NULL;

$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {

    $tsql = "SELECT dbo.invt_log.DISCOUNT,dbo.invt_log.PROD_QTY,dbo.inv.CLASS_CD,dbo.invt_log.UNIT_PRS,dbo.invt_log.ORDER_QTY,dbo.invt_log.LOG_DT,dbo.invt_log.INVS_NUM,dbo.inv.PROD_CD,dbo.invt_log.LOG_DT FROM dbo.invt_log LEFT JOIN dbo.inv ON dbo.inv.PROD_CD = dbo.invt_log.PROD_CD WHERE LOG_DT > $month18 AND LOG_DT < $month181 ORDER BY INVS_NUM" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                for($i=0;$i<$y;$i++){
                  if(trim($row['CLASS_CD']) == $class[$i]){
                    if($row['DISCOUNT']>0)
                    $classcount[$i] = $classcount[$i]+ $row['UNIT_PRS']*(1-$row['DISCOUNT']/100)*$row['ORDER_QTY'];
                    else
                    $classcount[$i] = $classcount[$i]+ $row['UNIT_PRS']*$row['ORDER_QTY'];
                      
                     
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
          $classcount[$i] = round($classcount[$i],2);
         }
        // echo $testper.'/'.$min.'/'.$max.'/'.$target.'/'.$sum15.'/'.$sum16.'/'.$sum17.'/'.$sum18;
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
    $( "#slider-range-max" ).slider({
      range: "max",
      min: 1,
      max: 10,
      value: <?php echo $target*10; ?>,
      slide: function( event, ui ) {
        $( "#amount1" ).val( ui.value*10 +"%");
        
        document.getElementById('percentbar').value = ui.value;
      }
    });
    $( "#amount1" ).val( $( "#slider-range-max" ).slider( "value" )*10 + "%");
  } );
  </script>
<style>

.bar {
  fill: #72a6f9;
}
.bar2 {
  fill: orange;
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
<svg width="900" height="500"></svg>
<script src="https://d3js.org/d3.v5.min.js"></script>
<script>
var data = [
<?php 
for($i=0;$i<$y;$i++){
  if($classcount[$i] > 0){
   
  echo '{letter: "'.$class[$i].'",    frequency:  '.$classcount[$i].',},';
  }
}

?>
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


      

  //g.append("g")
  //    .attr("class", "axis axis--y")
  //    .call(d3.axisLeft(y))
  //  .append("text")
  //    .attr("transform", "rotate(-90)")
  //    .attr("y", 6)
  //    .attr("dy", "0.71em")
  //    .attr("text-anchor", "end")
  //    .text("Invoice");

      g.append("g")
      .attr("class", "axis")
      .call(d3.axisLeft(y).ticks(null, "s"))
    .append("text")
      .attr("x", 2)
      .attr("y", y(y.ticks().pop()) + 0.5)
      .attr("dy", "0.32em")
      .attr("fill", "#000")
      .attr("font-weight", "bold")
      .attr("text-anchor", "start")
      .text("Invoices");

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

var legend = svg.selectAll(".legend")
  .data(colors)
  .enter().append("g")
  .attr("class", "legend")
  .attr("transform", function(d, i) { return "translate(30," + i * 19 + ")"; });
 
legend.append("rect")
  .attr("x", width - 18)
  .attr("width", 18)
  .attr("height", 18)
  .style("fill", function(d, i) {return colors.slice().reverse()[i];});
 
legend.append("text")
  .attr("x", width + 5)
  .attr("y", 9)
  .attr("dy", ".35em")
  .style("text-anchor", "start")
  .text(function(d, i) { 
    switch (i) {
      case 0: return "frequency";
   
      
    }
  });
</script>

<!-- <p>
  <label for="amount">Range:</label>
  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>-->
 
<!--<div id="slider-range"></div>-->
<div class="sliderContainer"><div id="dateSlider"></div></div>
<!--
<p>
  <label for="amount1">Percentage:</label>
  <input type="text" id="amount1" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>

<div id="slider-range-max"></div>
-->
<br>
<form action= "class.php" method = "POST"> 

<input type ="hidden" id="minstr" value="<?php if(isset($_POST['min'])){echo $_POST['min'];}else{echo 'Jan';} ?>" name="min">
<input type ="hidden" id="maxstr" value="<?php if(isset($_POST['min'])){echo $_POST['max'];}else{echo 'Mar';} ?>" name="max">
<input type ="hidden" id="percentbar" value="<?php echo $target; ?>" name="percentage">
<input class="btn btn-primary" type="submit" name="submit">
</form>
<br>
<form action="testa.php" method="POST">

<p>Select Date: <input type="text" id="datepicker" name="picker"></p> 
<input class="btn btn-primary" type="submit" name="Percentage">
</form>

  <script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/style.css" rel="stylesheet" media="screen">
	<link href="css/demo.css" rel="stylesheet" media="screen">
  <link href="stable/css/iThing.css" rel="stylesheet" media="screen">

  
  <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
  <script src="stable/jQRangeSliderMouseTouch.js"></script>
  <script src="stable/jQRangeSliderDraggable.js"></script>
  <script src="stable/jQRangeSliderHandle.js"></script>
  <script src="stable/jQRangeSliderBar.js"></script>
  <script src="stable/jQRangeSliderLabel.js"></script>
  <script src="stable/jQRangeSlider.js"></script>

  <script src="stable/jQDateRangeSliderHandle.js"></script>
  <script src="stable/jQDateRangeSlider.js"></script>
  
  <script src="stable/jQEditRangeSliderLabel.js"></script>
  <script src="stable/jQEditRangeSlider.js"></script>

  <script src="stable/jQRuler.js"></script>

<script>
//<!--
(function($){
  var Months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

  $(document).ready(function(){
    
    $("#dateSlider").dateRangeSlider({
      bounds: {min: new Date( 2018,0, 1), max: new Date( 2018,12, 1, 12, 59, 59)},
      defaultValues: {min: new Date(2018, <?php $min1=$min-1; echo $min1;?>, 1), max: new Date(2018, <?php $max1=$max-1;echo $max1;?>, 1)},
      valueLabels:"hide",
      step:{
    months: 1
  },
      scales: [{
        next: function(val){
          var next = new Date(val);
          return new Date(next.setMonth(next.getMonth() + 1));
        },
        label: function(val){
          return Months[val.getMonth()];
        }
      }]
    });
    $("#dateSlider").bind("valuesChanged", function(e, data){
  
      var str1 = data.values.min.toString().substring(4,7);
      var str2 = data.values.max.toString().substring(4,7);
 
  document.getElementById('minstr').value = str1;
  document.getElementById("maxstr").value = str2;
});
  });
})(jQuery);

//-->
</script>
