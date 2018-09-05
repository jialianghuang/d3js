<!DOCTYPE html>
<?php
$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$epoch = intval(time()/86400);
$currentd = $epoch + 61723;

$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {

    $tsql = "SELECT dbo.inv.PC_CASE,dbo.inv_data.ORDER_QTY,dbo.inv.CAT_NUM,dbo.inv.PROD_CD,dbo.inv.DESCRIP,dbo.inv.CLASS_CD,dbo.inv.DEPT_NUM,dbo.inv_data.IN_STOCK from dbo.inv LEFT JOIN dbo.inv_data ON dbo.inv.PROD_CD = dbo.inv_data.PROD_CD ORDER BY dbo.inv.CLASS_CD" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

             $object[$x]['PROD_CD'] = $row['PROD_CD'];
             $object[$x]['DESCRIP'] = $row['DESCRIP'];
             $object[$x]['CLASS_CD'] = $row['CLASS_CD'];
             $object[$x]['DEPT_NUM'] = $row['DEPT_NUM'];
             $object[$x]['IN_STOCK'] = $row['IN_STOCK'];
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
<style>

.chart rect {
  fill: steelblue;
}

.chart text {
  fill: white;
  font: 10px sans-serif;
  text-anchor: end;
}

</style>
<svg class="chart"></svg>
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script>

var data = [4, 8, 15, 16, 23, 42];

var width = 420,
    barHeight = 20;

var x = d3.scale.linear()
    .domain([0, d3.max(data)])
    .range([0, width]);

var chart = d3.select(".chart")
    .attr("width", width)
    .attr("height", barHeight * data.length);

var bar = chart.selectAll("g")
    .data(data)
  .enter().append("g")
    .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

bar.append("rect")
    .attr("width", x)
    .attr("height", barHeight - 1);

bar.append("text")
    .attr("x", function(d) { return x(d) - 3; })
    .attr("y", barHeight / 2)
    .attr("dy", ".35em")
    .text(function(d) { return d; });
    x.domain(data.map(function(d) { return d.letter; }));
  y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

  g.append("g")
      .attr("class", "axis axis--x")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x));

  g.append("g")
      .attr("class", "axis axis--y")
      .call(d3.axisLeft(y).ticks(10, "%"))
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
</script>