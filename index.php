<html>
    <head>
        <meta charset="utf-8">
        <title>Gerenciador de redes</title>
    </head>
    <body>
       <table width=580 border=0 cellspacing=0 cellpadding=0 bgcolor="#ffffff" align=center>
        <tr><td><font face="Comic SansMSa"><font color="black"><h1 align=center>Gerenciador de rede Tabajara</h1></td></tr>
        <form action="processa_consulta_elemento_rede.php" method="post">
            <table width=680 border=0 cellspacing=2 cellpadding=0 bgColor="#ffffff" align=center>
                <br>
         <td><font face="Comic SansMSa"><font color="black"><h3>Ip do elemento de rede:</h3>
         <td width="546"><input id="ip" maxlength="30" name="ip" size="60" type="text"></td>
         <br>
         <br>
         <tr>
             <td><font face="Comic SansMSa"><font color="black"><h3>Object ID: (Informação gerenciada)</h3></td>
             <td width="546"><input id="id" maxlength="30" name="id" size="60" type="text"></td>
             </tr>
        <tr><td align=center colspan=3>
            <input id="pesquisar" type=submit value="Pesquisar">
            <input type=reset value="Limpar"></td></tr>
        <tr><td align=center colspan=3>
            </tbody></table>
            </form>
            </html>