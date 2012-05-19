<html>
  <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
          $.get("<?=url()?>realestates/getclicks",function(response){
        	  var graph = [['Data','Cliques']];

        	  var data = new google.visualization.DataTable();
        	  data.addColumn('date', 'Data');
        	  data.addColumn('number', 'Cliques');
        	  
        	  for(i=0;i<response.length;i++){
            	  var date = response[i].date.split("-");
        		  data.addRow([new Date(response[i].date), parseInt(response[i].clicks)]);
        	  }
  			  var options = {	title: 'Cliques'	};

       	      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
       	      chart.draw(data, options);
      	  });
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>