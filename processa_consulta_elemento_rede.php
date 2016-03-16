<?php
$ip=$_POST['ip'];
$oid=$_POST['id'];
    $a=snmpwalkoid("$ip","public","$oid");
$result = array();
echo '<table align=center width=800 cellspacing=2 border=2>';
echo '<tr><td bgcolor=#FFFFFF width=50>$ip</td></tr>';
echo '<tr><td bgcolor=#FFFFFF width=20>Parametro</td><td bgcolor=#FFFFFF width=30>Nome</td></tr>';

    $i=0;
    foreach($a as $i => $n)
{
    $result[$n] = $a[$i];
    echo '<tr><td bgcolor=#FFFFFF width=30>$i</td><td bgcolor=#FFFFFF width=60>$n</td></tr>';
}
?>