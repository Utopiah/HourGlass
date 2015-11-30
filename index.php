<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
	  <div class="row-fluid">
		 <div class="col-md-4">
			 <div class="hero-unit">
			      <h1>HourGlass</h1>
			      <p>HourGlass, listing tasks objectively to focus on the one you <em>still</em> have the time to do!</p>
			 </div>

			<h2>My progresses</h2>
			<div id="myProgresses" class="progress"></div>

			<h2>My informations</h2>
			<ul id="myInformation"></ul>
		</div>
		<div class="col-md-8">
			<h2>My detailed progresses</h2>
			<table id="myDetailedProgresses" width="100%" class="table-hover">
				<tr>
					<th>Task completion</th>
					<th>Description</th>
					<th>Percentage done</th>
					<th>Total time required</th>
					<th>Mark as complete</th>
					<th>Dropped</th>
				</tr>
			</table>
		</div><!--end span9-->
	</div><!--end row fluid-->
	<div class="row-fluid">
		 <div class="col-md-4 col-md-offset-4">
			<div id="myWarnings" class=""></div>
		</div>
	</div><!--end row fluid-->
</div><!--end contained fluid-->

<script>
<?php
	$json = file_get_contents("MyTasks.json");
	print("myTasks=");
	print($json);
	print(";");
?>

var myTargetDeadline = new Date("November 29, 2015 19:00:00");
var myCurrentDate = new Date();
var myTimeLeft = Math.round((myTargetDeadline-myCurrentDate)/1000);
var myTotalTimeLeftForTasks = 0;
var myTotalTimeRequired = 0;

$( "#myInformation" ).append( "<li>My target deadline: "+myTargetDeadline+"</li>" );
$( "#myInformation" ).append( "<li>Time left: "+Math.round(myTimeLeft/3600)+"hrs</li>" );

myTasks.forEach( function(item, index, array){
	myTotalTimeLeftForTasks = myTotalTimeRequired + item.requiredtime - item.requiredtime * item.done / 100;
	myTotalTimeRequired = myTotalTimeRequired + item.requiredtime;
});
$( "#myInformation" ).append( "<li>Total time required to complete all tasks: "+Math.round(myTotalTimeRequired/60)+"min</li>" );
var myTimeLeftWithTasks = myTimeLeft - myTotalTimeLeftForTasks;
$( "#myInformation" ).append( "<li>Time left with tasks: "+Math.round(myTimeLeftWithTasks/3600)+"hrs</li>" );

myTasks.forEach( function(item, index, array){
	var barStatus = "";
	switch (true) {
		case(item.done < 30):
			barStatus = "progress-bar-danger";
			break;
		case(item.done > 30 && item.done < 70):
			barStatus = "progress-bar-warning";
			break;
		case(item.done > 70):
			barStatus = "progress-bar-success";
			break;
	}
	var markComplete = "<td></td>" ;
	if (item.done == 100) { markComplete = "<td><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></td>" };
	var markDropped = "<td></td>" ;
	if (item.dropped) { markDropped = "<td><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></td>" };
	$( "#myDetailedProgresses" ).append( "<tr>"
	+"<td width=\"20%\"><div class=\"progress\" ><div class=\"progress-bar "+barStatus+" \" style=\"width: "+item.done+"%\"></div></div></td>"
	+"<td>" + item.name + "</td>"
	+"<td>" +item.done + "%</td>"
	+"<td>" +item.requiredtime/60 + " min</td>"
	+markComplete
	+markDropped
	+"</tr>" );
	$( "#myProgresses" ).append( "<div class=\"progress-bar " +barStatus+" progress-bar-striped\" style=\"width: "+myTotalTimeRequired/item.requiredtime+"%\"><span class=\"sr-only\">"+item.name+"</span></div>" );
});
if (myTimeLeft = 0) {
		$( "#myWarnings" ).append( ' <div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> <span class="label label-warning">Alert</span>Your deadline has passed.</div>');
} else {
	if ( myTimeLeft < (myTotalTimeLeftForTasks + 60*60*24) ) {
		$( "#myWarnings" ).append( ' <div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> <span class="label label-warning">Warning</span> You have less than <em>1</em> day left, start to prioritize!</div>');
	}

	if ( myTimeLeft < myTotalTimeLeftForTasks ) {
		$( "#myWarnings" ).append( ' <div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Warning!</strong> You have less time left than the total amount of tasks, you must drop a task.</div>');
		$( "#myWarnings" ).append( ' <div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Warning!</strong> The tasks you can still drop are : <ul>');

		myTasks.forEach( function(item, index, array){
			if (item.done < 100 && !item.dropped) {
				$( "#myWarnings" ).append( '<li><a href="./backend.php?drop='+item.id+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></a> '+item.name+'</span></li>');
			}
		});
		$( "#myWarnings" ).append( '</ul></div>');
		$( "#myWarnings" ).append( '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Warning!</strong> The tasks dropped must have a sufficient cumulative length.</div>');
	}
}
</script>
</body>
</html>
