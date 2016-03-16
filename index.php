<html>
    <head>
        <meta charset="utf-8">
        <title>Gerenciador de redes</title>
    </head>
    <body>
        <h1>Gerenciador de rede Tabajara</h1>
        <form action="processa_consulta_elemento_rede.php" method="post">
        <table width=580 border=0 cellspadding=0 cellpading=0 bgcolor="#FFFFFF" align=center>
        
         <tr>
             <td>
                 <font face="Arial" color="black">
                     <h3>Ip do elemento de rede:</h3>
                     <td width="546"><input type="text" name="ip" size="60" maxlength="30"></td>
                     <br>
                     <br>
         </tr>
        <tr>
            <td> 
            <font face="Arial" color="black">
                     <h3>Object ID: (Info. gerenciada)</h3>
                     <td width="546"><input type="text" name="id" size="60" maxlength="30"></td></td>
        </tr>
        <tr>
            <td align=center colspan=3>
                <input id="pesquisar" type="submit" value="Pesquisar">
                <input type="reset" value="Limpar">
                
            </td>
        </tr>
        <tr>
            <td align=center colspan=3>
                
            </tbody>
        </tr>
        </table>
        </form>
    </body>
</html>