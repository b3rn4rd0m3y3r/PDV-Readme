<html>
	<!-- Configurando o comportamento do parâmetro CodUsr em relação à edição dos campos -->
	<?php
		include "Funcoes.php";
		if( isset($_GET["action"]) ){
			$action = $_GET["action"];
			} else {
			$action = "";
			}
		if( isset($_GET["CodUsr"]) ){
			$User = $_GET["CodUsr"];
			} else {
			$User = "0";
			}
	?>
	<head>
		<!-- Coleta dos campos da VIEW -->
		<title>PDE023</title>
		<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
		<meta name="robots" content="noindex, nofollow" />
		<link rel="stylesheet" href="PDE01.css">
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function(e) {
			    var scs = document.querySelector("script");
			    scs.src = "";
			    scs.setAttribute("data-dtconfig","");
			    console.log(scs);
				// Tablesorter
				$("TABLE[Id=Resultset]").tablesorter();
			});
		</script>
		<script type="text/javascript" src="jquery-3.1.0.js"></script>
		<script type="text/javascript" src="jquery.tablesorter.js"></script>
	</head>
	<body>
		<center>
		<h1 class="streito"><?php echo u2iso($TITULO); ?></h1>
		<h2 class="brd"><?php echo u2iso($SUBTITULO); ?> [<?php echo $TABELA; ?>]</h2>
		</center>
		<?php
		switch ($action){
			// Ação de preparação de  inclusão de registros
			case "add":	
		?>
			<center>
			<form method="post" action="?action=addsave&CodUsr=<?php echo $User; ?>&DTD=<?php echo $dtd; ?>">
				<table>
					<?php
					foreach ($arr as $chave => $valor) {
						$NOME = $valor->nome;
						$LABEL = u2iso($valor->label);
						$TAM = $valor->tam;
						$TAMTOT = $valor->tamtot;
						$TIPO = $valor->tipo;
						$CODUSER = $valor->coduser;
						echo "<tr><td><label>" . $LABEL . "</label></td><td>";
						// Teste de igualdade do CodUsr do campo
						$ID = " id=\"" . $NOME . "\" name=\"" . $NOME . "\" ";
						if( $User != $CODUSER ){
							$ID .= "readonly ";
							}
						// Decidir se coloca INPUT ou Textarea ou SELECT
						// Janela do campo bem menor que o comprimento total
						if( $TAMTOT > 2*$TAM ){
							$linhas = floor(intval($TAMTOT)/intval($TAM));
							echo "<textarea " . $ID . " rows=\"" . strval($linhas) ."\" cols=\"" . strval($TAM) . "\"></textarea>";
							} else {
							// Tipo SELECT
							if( isset($valor->sele) != false ){
								$SELE = $valor->sele;
								echo "<select " . $ID . ">";
								foreach ($SELE as $chave1 => $valor1) {
									echo "<option value=\"" . $valor1 . "\">" . $valor1;
									}
								echo "</select>";
								} else {							
								// Não sendo Textarea e nem Select ...
								echo "<input data-user=" . $CODUSER . $ID . " type=\"" . $arrTIPOS[$TIPO] . "\" size=\"" . strval($TAM) . "\" maxlength=\"" . strval($TAMTOT) . "\">";
								}
							}
						echo "</td></tr>";
						}
					?>
					<tr><td colspan=2 align="center"><input type="submit" value="GRAVA"></td></tr>
				</table>
			</form>
			</center>
		<?php
				echo $BACK_MAIN_SCREEN;
				break;
			// Ação de inclusão de registros no banco de dados
			case "addsave":
				// Coleta dos campos do formulário
				echo "<center><table>";
				foreach($_POST as $key => $value) {
					echo "<tr><td>" . $key . "</td><td>" . $value . "</td><td>" . $arrNomes[$key]["tipo"] . "</td><td align=right>" . $arrNomes[$key]["tam"] . "</td></tr>";
					//echo "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
					}
				echo "</table></center>";
				echo "<br>";
				$strSQL = "INSERT INTO " . $TABELA . " (";
				// Especificação dos campos
				foreach($_POST as $key => $value) {
					$strSQL .= $key . ", ";
					}
				$strSQL = substr($strSQL,0,strlen($strSQL)-2);
				$strSQL .= ") ";
				$strSQL .= " VALUES ( ";
				$_POST["Id"] = "null";
				// Especificação dos valores
				foreach($_POST as $key => $value) {
					if( $arrNomes[$key]["tipo"] == "C" || $arrNomes[$key]["tipo"] == "D" ){
						$strSQL .= "'" . $value . "', ";
						} else {
						$strSQL .= "" . $value . ", ";
						}
					};
				$strSQL = substr($strSQL,0,strlen($strSQL)-2);
				$strSQL .= " )";
				echo $strSQL;
				include "connection.php";
				$conn->exec($strSQL);
				include "connection_close.php";
				echo $BACK_MAIN_SCREEN;
				break;
			// Ação de preparação de  edição de registros
			case "edit":
				if( $IDFOUND == 0 ){ // Teste de presença de um parâmetro Id
					exit("<span class=erro>PDE - Edição necessita do parâmetro Id preenchido.</span>");
					}
				$Id = $_GET["Id"];
				// Teste de preenchimento do parâmetro Id
				if( $Id == "" ){
					exit("<span class=erro>PDE - Parâmetro Id presente mas não preenchido.</span>");
					}				
				// Constrói o SQL para pesquisa do registro que tem este Id
				$strSQL = "SELECT * FROM " . $TABELA . " WHERE Id = " .  $Id;
				echo $strSQL . "<br>";
				// Abre Conexão
				include "connection.php";
				$comm = $conn->prepare($strSQL);
				$comm->execute();
				$row = $comm->fetch();
				$CAMPOS = $row;
				//print_r($CAMPOS);
				// Fecha Conexão
				include "connection_close.php";
			?>
			<center>
			<form method="post" action="?action=editsave&CodUsr=<?php echo $User; ?>&DTD=<?php echo $dtd; ?>">
				<table>
				<?php
				foreach( $arr as $key => $valor ){
					$NOME = $valor->nome;
					$LABEL = u2iso($valor->label);
					$TAM = $valor->tam;
					$TAMTOT = $valor->tamtot;
					$TIPO = $valor->tipo;
					$CODUSER = $valor->coduser;
					// Linha de um campo - INICIO
					echo "<tr><td><label>" . $LABEL . "</label></td><td>";
					// Teste de igualdade do CodUsr do campo
					$ID = " id=\"" . $NOME . "\" name=\"" . $NOME . "\" value=\"" . $CAMPOS[$NOME] . "\" ";
					if( $User != $CODUSER ){
						$ID .= "readonly ";
						}
					//$ID = "id=\"" . $NOME . "\" name=\"" . $NOME . "\" value=\"" . $CAMPOS[$NOME] . "\" ";
					// Decidir se coloca INPUT ou Textarea ou SELECT
					// Janela do campo bem menor que o comprimento total
					if( $TAMTOT > 2*$TAM ){
						$linhas = floor(intval($TAMTOT)/intval($TAM));
						echo "<textarea " . $ID . " rows=\"" . strval($linhas) ."\" cols=\"" . strval($TAM) . "\">" . $CAMPOS[$NOME] . "</textarea>";
						} else {
						// Tipo SELECT
						if( isset($valor->sele) != false ){
							$SELE = $valor->sele;
							echo "<select " . $ID . ">";
							echo "<option value=\"" . $CAMPOS[$NOME] . "\">" . $CAMPOS[$NOME] . "</option>";
							foreach ($SELE as $chave1 => $valor1) {
								echo "<option value=\"" . $valor1 . "\">" . $valor1 . "</option>";
								}
							echo "</select>";
							} else {							
							// Não sendo Textarea e nem Select ...
							echo "<input " . $ID . " type=\"" . $arrTIPOS[$TIPO] . "\" size=\"" . strval($TAM) . "\" maxlength=\"" . strval($TAMTOT) . "\">";
							}
						}
					echo "</td></tr>";
					// Linha de um campo - INICIO
					}
				?>
					<tr><td colspan=2 align="center"><input type="submit" value="GRAVA"></td></tr>
				</table>
			</form>
			</center>
			<?php					
				echo $BACK_MAIN_SCREEN;
				break;
			// Ação de persistência de  edição de registros
			case "editsave":
				// Coleta dos campos do formulário
				echo "<table>";
				foreach($_POST as $key => $value) {
					echo "<tr><td>" . $key . "</td><td>" . $value . "</td><td>" . $arrNomes[$key]["tipo"] . "</td><td align=right>" . $arrNomes[$key]["tam"] . "</td></tr>";
					}
				echo "</table>";
				echo "<br>";
				$strSQL = "UPDATE " . $TABELA . " SET ";
				// Especificação dos campos
				foreach($_POST as $key => $value) {
					if( $key != "Id" ){
						$strSQL .= $key . " = ";
						if( $arrNomes[$key]["tipo"] == "C" || $arrNomes[$key]["tipo"] == "D" ){
							$strSQL .= "'" . $value . "', ";
							} else {
							$strSQL .= "" . $value . ", ";
							}						
						}
					}
				$strSQL = substr($strSQL,0,strlen($strSQL)-2);
				$strSQL .= " WHERE Id = " . $_POST["Id"];
				echo $strSQL . "<br>";
				include "connection.php";
				$conn->exec($strSQL);
				include "connection_close.php";				
				echo $BACK_MAIN_SCREEN;
				break;
			// Ação de preparação de  deleção de registros
			case "dele":
				if( $IDFOUND == 0 ){ // Teste de presençaa de um parâmetro Id
					exit("<span class=erro>PDE - Edição necessita do parâmetro Id preenchido.</span>");
					}
				$Id = $_GET["Id"];
				// Teste de preenchimento do parâmetro Id
				if( $Id == "" ){
					exit("<span class=erro>PDE - Parâmetro Id presente mas não preenchido.</span>");
					}				
				// Constrói o SQL para pesquisa do registro que tem este Id
				$strSQL = "SELECT * FROM " . $TABELA . " WHERE Id = " .  $Id;
				echo $strSQL . "<br>";
				// Abre Conexão
				include "connection.php";
				$comm = $conn->prepare($strSQL);
				$comm->execute();
				$row = $comm->fetch();
				$CAMPOS = $row;
				//print_r($CAMPOS);
				// Fecha Conexão
				include "connection_close.php";
				?>
				<!-- Formulário -->
				<center>
				<table class=entry>
					<?php
					foreach ($arr as $chave => $valor) {
						$NOME = $valor->nome;
						$LABEL = u2iso($valor->label);
						$TAM = $valor->tam;
						$TAMTOT = $valor->tamtot;
						$TIPO = $valor->tipo;
						$CODUSER = $valor->coduser;
						echo "<tr><td><label>" . $LABEL . "</label></td><td>";
						$ID = "id=\"" . $NOME . "\" name=\"" . $NOME . "\" readonly ";
						echo "<input " . $ID . " type=\"" . $arrTIPOS[$TIPO] . "\" size=\"" . strval($TAM) . "\" maxlength=\"" . strval($TAMTOT) . "\" value=\"" . $CAMPOS[$NOME] . "\">";
						echo "</td>";
						echo "</tr>";
						}
					?>
				</table>
				<a href="?action=delesave&Id=<?php echo $Id ?>&CodUsr=<?php echo $User; ?>&DTD=<?php echo $dtd; ?>">CONFIRMA DELE��O DO REGISTRO <?php echo $Id ?></a>
				</center>
				<?php
				break;
			// Ação de persistência de  deleção de registros
			case "delesave":
				echo "Efetiva a deleção.";
				$strSQL = "DELETE FROM " . $TABELA;
				$strSQL .= " WHERE Id = " . $_GET["Id"];
				echo $strSQL . "<br>";
				include "connection.php";
				$conn->exec($strSQL);
				include "connection_close.php";				
				echo $BACK_MAIN_SCREEN;				
				break;
			/*
				Ação de pesquisa de acordo com um determinado par�metro
			*/
			case "list":
				$PsqFieldName = $_POST["Botao"];
				$PsqFieldCont = $_POST[$_POST["Botao"]];
				// Constrói o SQL para pesquisa do registro que tem este Id
				$strSQL = "SELECT * FROM " . $TABELA . " WHERE Id > 0 ";
				$strSQL .= " AND " . $PsqFieldName . " LIKE '%" . $PsqFieldCont . "%'";
				// Abre Conexão
				include "connection.php";
				$comm = $conn->prepare($strSQL);
				$comm->execute();
				// Delimita a TABLE que vai exibir os registros
				echo "<table id=Resultset cellspacing=0 cellpadding=4 border=1>";
				echo "<thead>";
				foreach( $arr as $key => $valor ){
					$NOME = $valor->nome;
					$LABEL = u2iso($valor->label);
					$TIPO = $valor->tipo;
					echo "<th>" . $LABEL . "</th>";
					}
				echo "<th>INCLUIR</th>";
				echo "<th>ALTERAR</th>";
				echo "<th>APAGAR</th>";
				echo "</thead>";
				echo "<tbody>";
				// Lista os campos de um registro
				while( $row = $comm->fetch() ){
					echo "<tr>";
					foreach( $arr as $key => $valor ){
						$NOME = $valor->nome;
						$TIPO = $valor->tipo;
						$ALIN = "right";
						if( $TIPO == "C" || $TIPO == "D" ){
							$ALIN = "left";
							}
						echo "<td align=" . $ALIN . ">";
						if ($TIPO == "D" ){
							echo dtSepar($row[$NOME]);
							} else {
							echo $row[$NOME];
							}
						
						echo "</td>";
						}
					// Campos de controle de acordo com as permissões
					if( $PERMISSOES->add == "S" ){
						echo "<td align=center><a class=blue href=\"?action=add&CodUsr=" . $User . "&DTD=" . $dtd . "\" target=\"NewReg\">&#10009;</a></td>";
						} else {
						echo "<td>&nbsp;</td>";
						}
					if( $PERMISSOES->edit == "S" ){
						echo "<td align=center><a href=\"?action=edit&Id=" . $row["Id"] . "&CodUsr=" . $User . "&DTD=" . $dtd . "\" target=\"OldReg\">&#9997;</a></td>";
						} else {
						echo "<td>&nbsp;</td>";
						}
					if( $PERMISSOES->dele == "S" ){
						echo "<td align=center><a href=\"?action=dele&Id=" . $row["Id"] . "&CodUsr=" . $User . "&DTD=" . $dtd . "\" target=\"DelReg\">&#10060;</a></td>";
						} else {
						echo "<td>&nbsp;</td>";
						}
					echo "</tr>";
					}
				echo "</tbody>";
				echo "<table>";
				$CAMPOS = $row;
				// Fecha Conexão
				include "connection_close.php";
				echo $BACK_MAIN_SCREEN;
				break;
			default:
		?>
			<center>
				<h2 class="subt">P E S Q U I S A S</h2>
				<form method="post" action="?action=list&CodUsr=<?php echo $User; ?>&DTD=<?php echo $dtd; ?>">
					<div id="container">
					<table class=entry>
						<?php
						foreach ($arr as $chave => $valor) {
							$NOME = $valor->nome;
							$LABEL = u2iso($valor->label);
							$TAM = $valor->tam;
							$TAMTOT = $valor->tamtot;
							$TIPO = $valor->tipo;
							$CODUSER = $valor->coduser;
							echo "<tr><td><label>" . $LABEL . "</label></td><td>";
							// Teste de igualdade do CodUsr do campo
							$ID = " id=\"" . $NOME . "\" name=\"" . $NOME . "\" ";
							if( $User != $CODUSER ){
								$ID .= "readonly ";
								}
							// Decidir se coloca INPUT ou SELECT
							// Tipo SELECT
							if( isset($valor->sele) != false ){
								$SELE = $valor->sele;
								echo "<select " . $ID . ">";
								foreach ($SELE as $chave1 => $valor1) {
									echo "<option value=\"" . $valor1 . "\">" . $valor1;
									}
								echo "</select>";
								} else {							
								// Não sendo Textarea e nem Select ...
								echo "<input " . $ID . " type=\"" . $arrTIPOS[$TIPO] . "\" size=\"" . strval($TAM) . "\" maxlength=\"" . strval($TAMTOT) . "\">";
								}
							echo "</td><td>";
							echo "<input class=qry type=submit name=Botao value=\"" . $NOME . "\">";
							echo "</td></tr>";
							}
						?>
					</table>
					</div>
				</form>
				<p>
					<button class="openform" onclick="window.location.href = '?action=add&CodUsr=<?php echo $User; ?>&DTD=<?php echo $dtd; ?>';"><span class=contorno>ABRIR O FORMUL&Aacute;RIO DE INCLUS&Atilde;O DE REGISTRO</span></button>
				</p>
			</center>
		<?php
			}
		?>
	</body>
</html>