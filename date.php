

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
  


  <script src="stable/jQRuler.js"></script>



  <div class="sliderContainer"><div id="dateSlider"></div></div>


  <script>
  //<!--
  (function($){
    var Months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    $(document).ready(function(){

      $("#dateSlider").dateRangeSlider({
        bounds: {min: new Date(2018, 0, 1), max: new Date(2018, 11, 31, 12, 59, 59)},
        defaultValues: {min: new Date(2018, 1, 10), max: new Date(2018, 4, 22)},
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

    });
  })(jQuery);


  //-->

$("#slider").on("valuesChanging", function(e, data){
  console.log("Something moved. min: " + data.values.min + " max: " + data.values.max);
});

  </script>
