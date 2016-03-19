<?php
	//snmp_read_mib('C:\xampp\php\extras\mibs\AGENTX-MIB.txt');
	//snmprealwalk('localhost','public','AGENTX-MIB::" "');
	
	$ip=$_POST['ip'];
	$oid=$_POST['id'];
	$a = snmpwalkoid("$ip","public","$oid");
	$result = array();
	echo "<table align=center widht=800 cellspacing=2 border=2>";
    echo "<tr><td bgColor=#FFFFFF widht=50>Host: $ip</td>";
	//echo "<table  align=center widht=5 cellspacing=2 border=2>";
	echo "<tr><td bgColor=#FFFFFF widht=20>Parametros</td><td bgColor=#FFFFFF widht=30>Nome</td></tr>";
	$i=0;
		foreach($a as $i => $n){
			$result[$n] = $a[$i];
			echo "<tr><td bgColor=#FFFFFF widht=30>$i</td><td bgColor=#FFFFFF widht=60>$n</td></tr>";			
		}
		//print_r($result);
	?>