<HTML>
<META CHARSET="UTF-8">
<HEAD>
    <TITLE>Resultados da consulta</TITLE>
    </HEAD>
<BODY>
<?php

function treatArray($array) {
    for($i = 0; $i < sizeof($array); $i++) {
        $pos = strpos($array[$i]," ");
        $array[$i] = substr($array[$i],$pos+1);
    }
    return $array;
}

function convertArrayToInt($array) {
    for($i = 0; $i < sizeof($array); $i++) {
        $array[$i] = intval($array[$i]);
    }
    return $array;
}

function printAllInfo($ip) {
    $all = snmpwalkoid("$ip", "public", "");
    for(reset($all); $i = key($all); next($all)) {
        echo "$i: $all[$i]<BR>";
    }
    echo "<BR>";
}
//--------------------------------------------------------------------------
  $ip = $_POST["ip"];

  $host_name = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.1.5.0"));
  $devices = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.3"));
  $alloc_unity_size = convertArrayToInt(treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.4")));
  $total_storage_size = convertArrayToInt(treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.5")));
  $total_storage_used = convertArrayToInt(treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.6")));
  $current_ip = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.4.20.1.1"));
  echo '<table class="infoTable" border=1>';
      echo '<tr class="tableTitle">';
          echo '<th colspan="4"><b>Informa&ccedil;&atilde;o de gerenciamento</b></th>';
      echo '</tr>';
      echo '<tr>';
          echo '<td><b><i>Host:</i></b></td>';
          echo '<td colspan="2" class="machineInfo">', "$host_name[0]", '</td>';
      echo '</tr>';
      echo '<tr>';
          echo '<td><b><i>IP:</i></b></td>';
          echo '<td colspan="2" class="machineInfo">', "$current_ip[1]", '</td>';
      echo '</tr>';
      echo '<tr class="titleSection">';
          echo '<th colspan="3"><b>Armazenamento</b></th>';
      echo '</tr>';
      echo '<tr>';
         echo '<th><i>Dispositivo</i></th>';
         echo '<th><i>Total</i> (GB)</th>';
         echo '<th><i>Utilizado</i> (GB)</th>';
      echo '</tr>';
  for ($i = 0; $i < sizeof($devices); $i++){
      echo '<tr>';
          echo '<td>', "$devices[$i]", '</td>';
          echo '<td align="center">', ($total_storage_size[$i]*$alloc_unity_size[$i]/(1024*3)), '</td>';
          echo '<td align="center">', ($total_storage_used[$i]*$alloc_unity_size[$i]/(1024*3)), '</td>';
      echo '</tr>';
  }
      echo '<tr>';
          echo '<th colspan="3" class="titleSection"><b>CPU</b></th>';
      echo '</tr>';
      echo '<tr>';
          echo '<th ><i>Unidade</i></th>';
          echo '<th colspan="2"><i>Uso</i> (%)</th>';
      echo '</tr>';
  $cpu_usage = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.3.3.1.2"));
  for($i = 0; $i < sizeof($cpu_usage); $i++) {
      echo '<tr>';
          echo '<td align="center">', ($i+1), '</td>';
          echo '<td align="center" colspan="2">', $cpu_usage[$i], '</td>';
      echo '</tr>';
  }
  echo '</table>';
  echo '<BR>';
?>
</BODY>
</HTML>