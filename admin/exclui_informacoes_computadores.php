<?
 /*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start(); 
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../include/library.php'); 
// Comentado temporariamente - AntiSpy();
if ($_POST['submit_cond'])
	{
	$query_sele_exclui = '';
	$value_anterior = '';
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'') 
			{
			if (trim(strpos($key,'frm_condicao_'))<>'')
				{
				$query_sele_exclui .= str_replace('frm_condicao_','',$value);
				}
			else
				{
				if ($value_anterior) $query_sele_exclui .= ' and ';
				$query_sele_exclui = str_replace('frm_te_valor_condicao',$value,$query_sele_exclui);
				}
			
			$value_anterior = $value;
			}
		} 


	$query_sele_exclui = (substr($query_sele_exclui,-5)==' and '?substr($query_sele_exclui,0,strlen($query_sele_exclui)-5):$query_sele_exclui);
	$query_sele_exclui = str_replace('-MENOR-',' < ',$query_sele_exclui);
	$query_sele_exclui = str_replace('-MAIOR-',' > ',$query_sele_exclui);	

    $where 	= ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND c.id_local = '.$_SESSION['id_local']:'');		
	if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
		{
		// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta	
		$where = str_replace('c.id_local = ','(c.id_local = ',$where);
		$where .= ' OR c.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}
	
	$Query_Pesquisa = 'SELECT 	a.id_so,
								a.te_node_address,
								a.te_nome_computador, 
								a.te_ip, 
								a.te_versao_cacic, 
								a.te_versao_gercols, 
								a.dt_hr_ult_acesso,
								a.dt_hr_inclusao,								
								b.sg_so,
								d.sg_local						 
						FROM	computadores a,
								so b,
								redes c,
								locais d   
						WHERE   '.stripslashes($query_sele_exclui).' AND 
								a.id_so = b.id_so AND 
								a.id_ip_rede = c.id_ip_rede AND 
								c.id_local = d.id_local '.
								$where . ' 
						ORDER 	by a.te_nome_computador';
	conecta_bd_cacic();
	$result = mysql_query($Query_Pesquisa) or die('Erro no select ou sua sess�o expirou!');
	
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
	<title>Excluir Computadores</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">	
	<link href="../include/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	function Verifica_Check_Exclui()
		{
		var v_total_check;
		v_total_check = 0;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'checkbox' && window.document.forms[i].elements[j].checked == true)
					{
					v_total_check ++;
					}
				}
			}		

		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'submit' && window.document.forms[i].elements[j].name == 'submit_exc')
					{
					if (v_total_check==0) window.document.forms[i].elements[j].disabled = true
					else window.document.forms[i].elements[j].disabled = false;
					}
				}
			}					
		}
	</script>	
	</head>

	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<form name="form1" method="post">
	<table width="95%" border="0" align="center">
	<tr> 
	
	  <td class="cabecalho">Excluir Computadores</td>
	</tr>
	<tr> 
	
	  <td class="descricao">Esta op&ccedil;&atilde;o permite a sele&ccedil;&atilde;o 
        final para exclus&atilde;o dos computadores selecionados na pesquisa.</td>
	</tr>
	</table>
	<br><br>
  	<table width="90%" align="center"><tr>
    <td><div align="center"> 
   	<input name="submit_exc" type="submit" value="  Excluir Computadores Selecionados" onClick="return Confirma('Confirma EXCLUS�O?');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
   	&nbsp;&nbsp;
   	<input name="submit_nova" type="submit" value="  Nova Sele��o  ">	
	</div></td>		
   	</tr></table>
	<br><br>
	<table border="0" align="center" cellpadding="0" cellspacing="0">
  	<tr> 
    <td> <table border="0" cellpadding="0" cellspacing="0" align="center">
          <tr> 
            <td height="1" bgcolor="#333333" colspan="23"></td>
          </tr>
	
          <tr bgcolor="#E1E1E1"> 
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap><img src="../imgs/exclui_computador.gif" width="23" height="23"></td>
            <td nowrap><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left">Nome da M&aacute;quina</div></td>
            <td nowrap><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left">Local</div></td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>			
            <td nowrap class="cabecalho_tabela"><div align="center">IP</div></td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">Endere�o MAC</div></td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">S.O.</div></td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">Cacic2</div></td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela">GerCols</td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">&Uacute;lt. Acesso</div></td>
            <td nowrap ><img src="../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">Inclus�o</div></td>
            <td nowrap >&nbsp;</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="23"></td>
          </tr>
          <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
          <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><? echo $NumRegistro; ?></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><input type="checkbox" name="chk_<? echo $row['te_node_address'].'#'. $row['id_so']; ?>" value="1" checked onClick="Verifica_Check_Exclui();"></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['sg_local']; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_ip']; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_node_address'];?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['sg_so']; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_cacic']; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_gercols']; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="right"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="right"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/y H:i", strtotime( $row['dt_hr_inclusao'] ));   ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal">&nbsp;</td>
          </tr>
          <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}		
	if ($NumRegistro == 1)
		{
		?>
          <td colspan="20" align="center" class="label_vermelho">N�O FORAM ENCONTRADOS 
            REGISTROS!</TD>
          <script language="JavaScript">
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == 'submit_exc')
					{
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		</script>
          <?
		}
		?>
        </table></td>
  	</tr>
  	<tr> 
    <td height="1" bgcolor="#333333"></td>
  	</tr>
	</table>

  	<br><br>
  	<table width="90%" align="center"><tr>
    <td><div align="center"> 
   	<input name="submit_exc" type="submit" value="  Excluir Computadores Selecionados" <? if ($NumRegistro == 1) echo 'disabled'; ?> onClick="return Confirma('Confirma EXCLUS�O?');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>		  
   	&nbsp;&nbsp;
   	<input name="submit_nova" type="submit" value="  Nova Sele��o  ">	
	</div></td>		
   	</tr></table>
	<p><p><p>  
	</form>
	</html>	
	<?				
	} 
else	
	{
	if ($_POST['submit_exc'])
		{
		$v_cs_exclui = '';
		conecta_bd_cacic();			
		while(list($key, $value) = each($HTTP_POST_VARS))
			{
			if (strpos($key,'chk_')>-1)
				{
				if (!$result_tables) $result_tables	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do CACIC
				
				mysql_data_seek($result_tables,0);
				$v_arr_exclui = explode('#',$key);
				while ($row_exclui = mysql_fetch_row($result_tables)) //Percorre as tabelas comandando a exclus�o, conforme TE_NODE_ADDRESS e ID_SO				
					{
					$v_query_exclui = 'DELETE FROM '.$row_exclui[0] .' WHERE te_node_address = "'. str_replace('chk_','',$v_arr_exclui[0]) . '" and id_so="'.str_replace('chk_','',$v_arr_exclui[1]).'"';
					$exclui 		= @mysql_query($v_query_exclui);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela fun��o MYSQL_QUERY()
					$v_cs_exclui = '1';
					}			
				}
			if ($v_cs_exclui)
				GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'computadores');
			}
		}
	?>	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
	<title>Excluir Computadores</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
	<link href="../include/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	function Preenche_Condicao_VAZIO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<VAZIO>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}
	function Preenche_Condicao_NULO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<NULL>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}

	function Verifica_Condicoes_Seta_Campo(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo && window.document.forms[i].elements[j].value == '<VAZIO>')
					{
					window.document.forms[i].elements[j].value = '';
					window.document.forms[i].elements[j].disabled = false;										
					}
				}
			}		
		SetaCampo(p_campo);								
		}

	function Verifica_Selecao(p_campo,p_campo_selecao)
		{
		if (p_campo.value == '')
			{
			for (i=0;i<window.document.forms.length;i++)
				{
				for (j=0;j<window.document.forms[i].elements.length;j++)
					{
					if (window.document.forms[i].elements[j].name == p_campo_selecao)
						{
						window.document.forms[i].elements[j].value = '';
						}
					}
				}		
			}
		}

	
	function Valida_Form_Pesquisa(p_argumento)
		{
		var v_conteudo = '';
		var v_tamanho = 0;
		v_tamanho = p_argumento.length;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name.substring(0,v_tamanho) == p_argumento && 
				    window.document.forms[i].elements[j].value != '')
					{
					v_conteudo = v_conteudo + window.document.forms[i].elements[j].value;
					}
				}
			}

		if (v_conteudo == '')
			{
			alert('� necess�rio informar ao menos uma condi��o para pesquisa!');
			return false;
			}

		return true;
		}			
	</SCRIPT>
	</head>

	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<form name="form1" method="post">
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr> 
	
  	<td class="cabecalho">Excluir Computadores</td>
	</tr>
	<tr> 
	
  	<td class="descricao">Esta op&ccedil;&atilde;o permite a exclus&atilde;o 
	parametrizada das informa&ccedil;&otilde;es armazenadas na base sobre 
	as esta&ccedil;&otilde;es monitoradas. Deve-se tomar muito cuidado com 
	a abrang&ecirc;ncia da condi&ccedil;&atilde;o a ser formulada.</td>
	</tr>
	</table>
	<br><br>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
    <td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="  Selecionar Computadores para Exclus&atilde;o  " onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">
   	</div></td>
   	</tr></table>

	<br><br>	
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>	
	<tr bgcolor="#CCCCCC"> 
  	<td class="destaque">Campo</font></strong></td>
  	<td class="destaque">Condi&ccedil;&atilde;o</font></strong></td>
  	<td class="destaque">Valor para Pesquisa</font></strong></td>
	</tr>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	<?
	$cor = 0;
	require_once('../include/library.php');
	conecta_bd_cacic();	
	$res_fields = mysql_query("SHOW COLUMNS FROM computadores");
	$v_arr_nomes_campos = array();
	while ($row_fields = mysql_fetch_array($res_fields)) 
		{
		$query_desc = 'SELECT 	* 
					   FROM 	descricoes_colunas_computadores 
					   WHERE 	TRIM(nm_campo) = "'. $row_fields[0].'"';
		$res_desc 	= @mysql_query($query_desc);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela fun��o MYSQL_QUERY()
		if (!@$row_desc = mysql_fetch_array($res_desc))
			{
			$query_ins_desc = 'INSERT 
							   INTO 	descricoes_colunas_computadores 
							   SET 		nm_campo = "'. $row_fields[0].'", 
							   			te_descricao_campo="'.$row_fields[0].'", 
										nm_tipo_campo = "datetime"';
			$res_ins_desc 	= @mysql_query($query_ins_desc);
			//GravaLog('INS',$_SERVER['SCRIPT_NAME'],'descricoes_colunas_computadores');
			}
			
		if ($row_desc['cs_condicao_pesquisa']=='S')
			{
			array_push($v_arr_nomes_campos,$row_desc['te_descricao_campo'].'#'.$row_fields[0].'#'.$row_fields[1]);
			}
		}

	sort($v_arr_nomes_campos);
	for ($i=0;$i<count($v_arr_nomes_campos);$i++)
		{
		$v_arr_campo = explode('#',$v_arr_nomes_campos[$i]);
		?>
		<tr <? if ($cor) echo 'bgcolor="#E1E1E1"';?>> 
		<td nowrap><? echo $v_arr_campo[0];?></td>
		<td><select name="frm_condicao_<? echo $v_arr_campo[1]; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
		<option value=""></option>
		<?
		if ($v_arr_campo[2] == 'datetime') 
			{
			$v_operacao = "(TO_DAYS(NOW())-TO_DAYS(a.".$v_arr_campo[1].")";
			?>
			<option value="<? echo $v_operacao . ' =       frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">IGUAL A</option>					
			<option value="<? echo $v_operacao . ' -MAIOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MAIOR QUE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>					
			<option value="<? echo $v_operacao . ' -MENOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MENOR QUE</option>											
			<?
			}
		else
			{
			?>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." =       'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">IGUAL A</option>		
			<option value="<? echo 'a.'      .$v_arr_campo[1]." <>      'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">DIFERENTE DE</option>			
			<option value="<? echo 'a.'      .$v_arr_campo[1]." -MAIOR- 'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MAIOR QUE</option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." -MENOR- 'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MENOR QUE</option>						
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    '%frm_te_valor_condicao%'";?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">CONTENHA</option>
			<option value="<? echo "'%frm_te_valor_condicao%' not like  (a.".$v_arr_campo[1].")  ";?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">N�O CONTENHA</option>			
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    'frm_te_valor_condicao%'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">INICIE COM</option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    '%frm_te_valor_condicao'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">TERMINE COM</option>				
			<option value="<? echo 'TRIM(a.'.$v_arr_campo[1].") = '' and " 					      ;?>" onClick="Preenche_Condicao_VAZIO('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"		 >SEJA VAZIO</option>		
			<option value="<? echo 'a.'.$v_arr_campo[1]." IS NULL " 					          ;?>" onClick="Preenche_Condicao_NULO('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"		 >SEJA NULO</option>					
			<?
			}
			?>
		</select> </td>
		<td><input name="frm_te_valor_condicao_<? echo $v_arr_campo[1]; ?>" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);Verifica_Selecao(this,'<? echo "frm_condicao_". $v_arr_campo[1]; ?>');" size="60" maxlength="100"></td>
		</tr>
		<?			
		$cor=!$cor;
		}

	?>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	</table>
	<br><br>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
	<td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="  Selecionar Computadores para Exclus&atilde;o  " onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">	
	</div></td>
	</tr></table>

	<p><p><p>  
	</form>
	</html>	
	<?			
	}
	?>