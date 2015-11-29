<html>
<body>

The item you want to drop has id 

<?php
	
	$drop_id = intval($_GET["drop"]);
	print($drop_id."<br>");
	$myJsonFile = "./MyTasks.json";
        $json = json_decode(file_get_contents($myJsonFile), true);
	foreach ($json as &$item) {
		if (intval($item["id"]) == $drop_id){
			print("will drop :".$item["name"]."<br>");
			$item["dropped"]=true;
			print("---".$item["dropped"]."---");
		}
	}
	$json_file_content = json_encode($json);
	$res = file_put_contents($myJsonFile,$json_file_content);
	var_dump($res);
	print("written in ".$myJsonFile);
?>


<ul>
<li>myTargetDeadline = new Date("November 29, 2015 19:00:00");
<li>myTasks {...}
<ul>
<li>task X update (remove)
<li>add task
</ul>
</ul>

<script>
setTimeout(function() { 
    window.location.href = "./index.php"; 
 }, 100000);
</script>
Redirection in 100 seconds.

</body>
</html> 
