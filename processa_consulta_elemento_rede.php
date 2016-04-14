<?php
    date_default_timezone_set('America/Manaus');
?>
<html>
<meta charset="utf-8">
<head>
    <title>Resultados da consulta</title>
    </head>
<body>
    
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
        $ip = $_POST["ip"];
    $armazenamento = FALSE;
    $cpu_uso = FALSE;    
    $mensagem;
    $mensagem_armazenamento = 'Unidades com problema: ';
  	$mensagem_cpu_uso = "CPU com problemas:";
  
  /*nome do host*/
  $host_name = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.1.5.0"));
  /*ip da maquina*/
  $current_ip = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.4.20.1.1"));
  
  $mensagem = 'Host: '.$host_name[0].' Ip address:'.$current_ip[1].' - Ocorreram problemas no dispositivo. ';
 
  /*dispositivos*/
  $devices = treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.3"));
  /*tamanho da unidade*/
  $alloc_unity_size = convertArrayToInt(treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.4")));
  /*tamanho de armazenamento total*/
  $total_storage_size = convertArrayToInt(treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.5")));
  /*tamanho de armazenamento que foi usado*/
  $total_storage_used = convertArrayToInt(treatArray(snmpwalk("$ip", "public", "iso.3.6.1.2.1.25.2.3.1.6")));
  
  echo '<table border=1>';
      echo '<tr>';
          echo '<th colspan="4"><b>Gerenciador Skynet</b></th>';
      echo '</tr>';
      echo '<tr>';
          echo '<td><b><i>Host:</i></b></td>';
          echo '<td colspan="2">', "$host_name[0]", '</td>';
      echo '</tr>';
      echo '<tr>';
          echo '<td><b><i>IP:</i></b></td>';
          echo '<td colspan="2">', "$current_ip[1]", '</td>';
      echo '</tr>';
      echo '<tr>';
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
          /*substituir por 1024 se quiser em MB*/
          echo '<td align="center">', number_format(($total_storage_size[$i]*$alloc_unity_size[$i]/(1073741824)), 2, ',', '.'), '</td>';
          echo '<td align="center">', number_format(($total_storage_used[$i]*$alloc_unity_size[$i]/(1073741824)), 2, ',', '.'), '</td>';
          echo '</tr>';
          /*verifica se houve problema de memoria*/
          $total_memory = ($total_storage_size[$i]*$alloc_unity_size[$i])/(1073741824);
          $total_memory_used = ($total_storage_used[$i]*$alloc_unity_size[$i])/(1073741824);
          
          if(($total_memory_used >= ($total_memory*0.9)) && $total_memory!=0.0){
              $armazenamento = TRUE;
              $mensagem_armazenamento=$mensagem_armazenamento.' '.$devices[$i];
              
          }
 
  }
  /*Caso nenhuma unidade tenha problema, exibimos esta mensagem*/
  	if($armazenamento == FALSE){
  		$mensagem_armazenamento = 'Nenhuma unidade com problemas';
  	}  
      echo '<tr>';
          echo '<th colspan="3"><b>CPU</b></th>';
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
      /*verifica se alguma cpu tem problema*/
      if($cpu_usage[$i]>=90){
        $cpu_uso = TRUE;
        $mensagem_cpu_uso .= ' CPU:'.($i+1).' ';
      }
  }
  if($cpu_uso != TRUE){
    $mensagem_cpu_uso ="CPU sem problemas";
      }
  echo '</table>';
  echo '<BR>';
  
  
  
  
  /*Etapa de elaboraçãode mensagem*/
  
  $mensagem = date('H:i, jS F').' '.$mensagem.'Armazenamento: '.$mensagem_armazenamento.' Uso de CPU: '.$mensagem_cpu_uso;
  
  
  /*---------------------------------------------------------------------------------------------------*/
  /*Caso ocorra algum problema etapa de envio de mensagem para dispositivo android e email*/
 /*Etapa de envio de email*/
 if($armazenamento!=FALSE || $cpu_uso!=FALSE){
    $subject='Olá!'; // Assunto.
    $to= 'oliveiradavid007@gmail.com'; // Para.
    $body= $mensagem; // corpo do texto.
  
    if (mail($to,$subject,$body)){
        echo 'E-mail enviado com sucesso!<br/>';
    }else{
        echo 'E-mail não enviado!<br/>';
    }
    
     /*---------------------------------------------------------------------------------------------------*/
 /*Etapa de envio de mensagem via socket para disppositivo android*/
 
 /*criando o socket*/
$sock = socket_create(AF_INET, SOCK_STREAM, 0);
/*Verificando se o socket foi criado com sucesso*/
if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}else{
    echo "Socket created<br/>";        
}
/*Conectando a um servidor*/
if(!socket_connect($sock ,'172.30.15.181',8080))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not connect: [$errorcode] $errormsg \n");
}else{
    echo "Connection established<br/>";
}
/*Enviando uma mensagem para o servidor*/
$message = $mensagem;
 
if( ! socket_send ( $sock , $message , strlen($message) , 0))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not send data: [$errorcode] $errormsg \n");
}else{
    echo "Message send successfully \n";
}
 
 
 /*Fechando o socket*/
 socket_close($sock);
    
    
    
 }else{
     echo 'Nenhum problema!<br/>';
 }
 
 
 
  
 ?>
</body>
</html>

