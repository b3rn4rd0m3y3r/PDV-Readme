<?php
	function u2iso($tx){
		return iconv("UTF-8", "ISO-8859-1", $tx);
		}
	function i2utf($tx){
		return iconv("ISO-8859-1","UTF-8", $tx);
		}
	function dtSepar($sdate){
		if( $sdate == "" ){
			return "";
			}
		list($ano, $mes,$dia) = explode("-", $sdate);
		return $dia . "/" . $mes . "/" . $ano;
		}
	header('Content-type: text/html; charset=UTF-8');
	$arrTIPOS = ["C"=>"text", "D"=>"date", "I"=>"text", "N"=>"number"];
	// 1 - Leitura da configuração em JSON
		if( isset($_GET["DTD"]) ){
			$dtd = $_GET["DTD"];
			} else {
			$dtd = "";
			}
	// Texto em UTF-8
	$txt = i2utf(file_get_contents('./' . $dtd) );
	// Conversão em JSON
	$obj = json_decode($txt);
	// Extrai permissões
	$PERMISSOES = $obj->permissao;
	$TABELA = $obj->tabela;
	$TITULO = $obj->titulo;
	$SUBTITULO = $obj->subtitulo;
	$arr = $obj->campos;
	$arrNomes = [];
	$arrDec = json_decode(json_encode($arr), true);
	
	foreach ($arrDec as $chave => $valor) {
		$arrNomes[$arrDec[$chave]["nome"]] = $arrDec[$chave];
		}
	$IDFOUND = 0;
	if( isset($_GET["Id"]) ){
		$IDFOUND = 1;
		}
	$BACK_MAIN_SCREEN = "<p><span class=big>&#128072;</span><a class=msgBott href=\"?CodUsr=1&DTD=" . $dtd . "\">VOLTAR &Agrave; TELA INICIAL</a></p>";
?>