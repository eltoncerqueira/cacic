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
require_once('../include/library.php');

// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

//foreach($HTTP_POST_VARS as $i => $v) 
//	{
//	echo 'i: '.$i.' v: '.$v.'<br>';
//	}

// Preciso remover os "Enters" dados nos campos texto do formul�rio, pois a rotina de envio de emails
// estava dando erro quando encontrava esse tipo de caractere especial.				
$te_notificar_mudanca_hardware = str_replace("\r\n", " ", $_POST['te_notificar_mudanca_hardware']);

$query = "UPDATE	configuracoes_locais set 
					te_notificar_mudanca_hardware	= '" . $te_notificar_mudanca_hardware . "', 
					te_serv_cacic_padrao 			= '" . $_POST['frm_te_serv_cacic_padrao'] . "', 			  
					te_serv_updates_padrao 			= '" . $_POST['frm_te_serv_updates_padrao'] . "'
		   WHERE	id_local = ".$_POST['frm_id_local'];			  

$result = mysql_query($query) or die('1-Ocorreu um erro durante a atualiza��o da tabela configuracoes ou sua sess�o expirou!'); 
GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		

// Aqui pego todas os hardwares selecionados para notifica��o e atualizo a tabela descricao_hardware .
$hardwares_selecionados = "'" . $_POST['list2'][0] . "'";
for( $i = 1; $i < count($_POST['list2']); $i++ ) 
	{
	$hardwares_selecionados = $hardwares_selecionados . ",'" . $_POST['list2'][$i] . "'";
	}
$hardwares_selecionados = ' nm_campo_tab_hardware IN ('. $hardwares_selecionados .')';

// Processo todas as descri��es de hardware e retiro as refer�ncias ao local atual
$querySELECT = "SELECT 	*
		  		FROM		descricao_hardware";
$resultSELECT = mysql_query($querySELECT) or die('2-Ocorreu um erro durante a consulta � tabela descricao_hardware ou sua sess�o expirou!'); 

$strPesquisa = ','.$_POST['frm_id_local'].',';
while ($row = mysql_fetch_array($resultSELECT))
	{
	$strRow = ','.str_replace(' ','',$row['te_locais_notificacao_ativada']).',';
	$intPos = strpos($strRow,$strPesquisa);
	if ($intPos > -1) // Achei o id_local dentro do campo te_locais_notificacao_ativada
		{
		$strRow = ','.str_replace($strPesquisa,'',$strRow).',';		

		// Retiro as v�rgulas duplicadas...
		while (strpos($strRow,',,')>-1)
			$strRow = str_replace(',,',',',$strRow);				

		$queryUPDATE = "UPDATE 	descricao_hardware set 
							te_locais_notificacao_ativada = '".$strRow."'
				  WHERE 	nm_campo_tab_hardware = '".$row['nm_campo_tab_hardware']."'";
		$resultUPDATE = mysql_query($queryUPDATE) or die('3-Ocorreu um erro durante a atualiza��o da tabela descricao_hardware ou sua sess�o expirou!'); 		
		}		
	}

$querySELECT = "SELECT 	*
		  		FROM		descricao_hardware
		  		WHERE 	".$hardwares_selecionados;
$resultSELECT = mysql_query($querySELECT) or die('4-Ocorreu um erro durante a consulta � tabela descricao_hardware ou sua sess�o expirou!'); 
while ($row = mysql_fetch_array($resultSELECT))
	{
	$queryUPDATE = "UPDATE 	descricao_hardware 
					SET		te_locais_notificacao_ativada = CONCAT(te_locais_notificacao_ativada,',','".$_POST['frm_id_local']."',',')
		  		    WHERE 	nm_campo_tab_hardware = '".$row['nm_campo_tab_hardware']."'";
	$resultUPDATE = mysql_query($queryUPDATE) or die('5-Ocorreu um erro durante a atualiza��o da tabela descricao_hardware ou sua sess�o expirou!'); 		
	}		

// Aqui pego todos os gr�ficos selecionados para serem exibidos e atualizo a tabela configuracoes_locais.
$te_exibe_graficos = $_POST['list4'][0];
for( $i = 1; $i < count($_POST['list4']); $i++ ) 
	{
	$te_exibe_graficos .= $_POST['list4'][$i];
	}

$queryUPDATE = "UPDATE 	configuracoes_locais set 
						te_exibe_graficos = '".$te_exibe_graficos."'
		  		WHERE   id_local=".$_POST['frm_id_local'];
$resultUPDATE = mysql_query($queryUPDATE) or die('6-Ocorreu um erro durante a atualiza��o da tabela configuracoes_locais ou sua sess�o expirou!'); 

header ("Location: ../include/operacao_ok.php?chamador=../admin/config_gerais.php&tempo=1");		
?>
