<?
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usu�rio
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Softwares Inventariados por M&aacute;quinas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<link href="../../include/cacic.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="/cacic2/imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de Invent&aacute;rio de Softwares</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y �\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';
?>
<table border="0" align="center" width="300" >
  <tr> 
    <td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><? echo stripslashes($_GET['nm_software_inventariado']); ?></strong></font></td>
  </tr>
</table>
<?
	 $query = "SELECT a.te_nome_computador, a.te_node_address, a.id_so,
   			a.te_ip, a.dt_hr_ult_acesso, b.nm_software_inventariado 
		FROM computadores a, softwares_inventariados b,
		     softwares_inventariados_estacoes c
		WHERE a.te_node_address = c.te_node_address  
	 	  AND b.id_software_inventariado = " 
			. $_GET['id_software_inventariado'] . " 
		  AND b.id_software_inventariado = 
			c.id_software_inventariado 
		  AND a.te_nome_computador like '%" 
			. $_GET['nm_maquina'] . "%' 
		ORDER BY a.te_nome_computador"; 
	$result = mysql_query($query) or die('Erro de acesso ao banco ou sua sess�o expirou!');
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nome
              da M&aacute;quina</font></strong></div></td>
          <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
	  <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&Uacute;ltima Coleta</a></font></strong></div></td>
	  <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="../../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? if ($row['te_nome_computador']) { echo $row['te_nome_computador']; } else { echo '<i>Em Branco</i>'; } ?></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['te_ip']; ?></font></td>
	  <td nowrap>&nbsp;</td>
	  <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></font></div></td>
	  <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	}
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Clique 
      sobre o nome da m&aacute;quina para ver os detalhes da mesma</font> </td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>	

</body>
</html>
