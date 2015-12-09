<?php
	define('INCLUDE_CHECK',true);

	require 'connect.php';
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);
	/*$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');*/
	
	$con = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con)) {
		echo("Error de conexio: ". mysqli_connect_error());
		exit();
	}
	$var_llista = $_POST['llista'];
	switch ($var_llista) {
		case "cens":  			$query_llista = "select id,nom,nom_pinya,cognom_1,cognom_2,mobil,fixe,email,estat from cens order by nom,nom_pinya,cognom_1";
				     			break;
		case "canalla": 		$query_llista = "select * from cens  where canalla = 1 order by estat,nom";
								break;
		case "musics": 			$query_llista = "select * from cens  where musics = 1 order by nom";
								break;
		case "gent_a_treballar_troncs": $query_llista = "select id,nom,nom_Pinya,cognom_1,cognom_2,troncs,email,estat from cens  where (canalla = 0 AND estat<>'B' AND estat<>'NA') order by nom,cognom_1 ASC";
								break;
		default: 				$query_llista = "select * from cens order by nom";
								break;
	}
	$result = mysqli_query($con,$query_llista);
	/*echo"<h2>Cens
		<div id=\"llista_close\">
			<a onclick=\"javascript:jQuery('#paper_esquerra').stop().animate({'left': '-800px'}, 'slow'); restaura_pantalla();return false;\" href=\"#\"><img src='./pinyes_imatges/icones/snow/Stop.png'></a>
		</div>
		<div id=\"boto_afegir\">
			<a href='#' onclick=\"javascript:pant_afegir_cens(); return false;\"><img src=\"pinyes_imatges/icones/snow/Add.png\" alt=\"afegir\"></a>
		</div>
		<div id=\"boto_export\">
			<a href='./exporta_csv.php?taula=cens' target='_new'><img src=\"pinyes_imatges/icones/snow/Export.png\" alt=\"Exportar cens\"></a>
		</div>
		</h2><br>";*/
	/*echo "<form action='autoritzat.php' method='get'>";
	echo "<div id='llista_ul-nav' class='listnav'></div><br>";
	echo "<div class='llista_noms_scroll'><ul id='llista_ul'>";*/
	
   /* if(mysqli_num_rows($result) > 0)
	{*/
		/*echo "<div id='sorter'>
            <ul data-role='listview'>
                <li><span>A</span></li>
                <li><span>B</span></li>
                <li><span>C</span></li>
                <li><span>D</span></li>
                <li><span>E</span></li>
                <li><span>F</span></li>
                <li><span>G</span></li>
                <li><span>H</span></li>
                <li><span>I</span></li>
                <li><span>J</span></li>
                <li><span>K</span></li>
                <li><span>L</span></li>
                <li><span>M</span></li>
                <li><span>N</span></li>
                <li><span>O</span></li>
                <li><span>P</span></li>
                <li><span>Q</span></li>
                <li><span>R</span></li>
                <li><span>S</span></li>
                <li><span>T</span></li>
                <li><span>U</span></li>
                <li><span>V</span></li>
                <li><span>W</span></li>
                <li><span>X</span></li>
                <li><span>Y</span></li>
                <li><span>Z</span></li>
            </ul>
        </div><!-- /sorter -->";*/
        
        echo "<ul id='cens_" . $var_llista . "' data-role='listview' class='llista_cens touch' data-split-icon='delete' data-filter='true' data-filter-reveal='false' data-filter-placeholder='Cercar...' data-autodividers='true'>";
		while($row = mysqli_fetch_array($result))
		{
		    echo "<li class='troncs_" . $row['troncs'] . "'><div class='pregunta'></div><div bandarra_id='" . $row['id'] . "' class='nom_pinya estat_" . $row['estat'] . "'>";
		    /*echo "<div name='" . $row['nom_pinya'] . "'> ";*/
		    
		    
            echo "<p class='nom topic'><a href='#' class='ui-btn ui-btn-raised edita_bandarra'><i class='zmdi zmdi-edit'></i></a>";
            echo "<a href='#bottomsheetlist_auxiliar' class='ui-btn ui-btn-raised consulta_llistes' onclick=\"javascript:comprova_llistes_correus('comprova_un','1999','" . $row['email'] . "');\"><i class='zmdi zmdi-email'></i></a>";
            echo htmlentities($row['nom']) . " " . htmlentities($row['cognom_1']);
		    echo (strlen($row['cognom_2']) > 0? " i " . htmlentities($row['cognom_2']) : "");
		    echo (strlen($row['nom_pinya']) > 0? "  (" . htmlentities($row['nom_pinya']) . ")" : "");
	        if (strlen($row['email']) > 0) echo "<span class='email'><br><img src='./images/icones/16px/email.png'> " . $row['email'] . "</span></p>";
		    echo "<p class='estat estat_" . $row['estat'] . "'>" . $row['estat'] . "</p>";
		    echo "<p class='mes_dades amagat'>";
		   /* echo "<a href=\"#\" onclick=\"javascript:jQuery(this).toggleClass('open');jQuery('#dades_cens_" . $row['id'] . "').fadeToggle(500);\" class=\"open\" width=\"64\" height=\"64\">&nbsp;</a>";*/
		    if ($var_llista != "gent_a_treballar_troncs") {
			    if (strlen($row['mobil']) > 0)  echo " <i class='zmdi zmdi-smartphone-iphone''></i> " . $row['mobil'] . " ";
			    elseif (strlen($row['fixe']) > 0) echo " <i class='zmdi zmdi-phone'></i> " . $row['fixe'] . " ";
		    }
		    else {
		   
                echo "<span><label for='novato'>Novato/a</label>
                <select id=\"novato\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','novato', this.value)\" value=\"" . $row['novato'] . "\">";
                if ($row['novato'] == 'Si') { echo "<option value='Si' selected>Si</option>"; }
                else { echo "<option value='Si'>Si</option>"; }
                if ($row['novato'] == 'No') { echo "<option value='No' selected>No</option>"; }
                else { echo "<option value='No'>No</option>"; }
                echo "</select></span>";

                echo "<div id=\"dades_cens_" . $row['id'] . "\" style=\"display:none;\" class=\"dades_cens\">";
                echo "<br>";

               echo "<input type=\"text\" maxlength=\"25\" size=\"10\" name=\"nom\" id=\"nom\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','nom', this.value)\" value=\"" . $row['nom'] . "\"/>";
               echo "<input type=\"text\" maxlength=\"50\" size=\"10\" name=\"cognom_2\" id=\"cognom_2\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','cognom_2', this.value)\" value=\"" . $row['cognom_2'] . "\"/>";
               echo "<input type=\"text\" maxlength=\"50\" size=\"10\" name=\"cognom_1\" id=\"cognom_1\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','cognom_1', this.value)\" value=\"" . $row['cognom_1'] . "\"/><br>";

                echo "<label for='nom_pinya'>Nom Pinya</label><input type=\"text\" maxlength=\"25\" size=\"10\" name=\"nom_pinya\" id=\"nom_pinya\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','nom_pinya', this.value)\" value=\"" . $row['nom_pinya'] . "\"";
                echo "/>";
                echo "<label for='email'>Email</label>
                <input type=\"text\" maxlength=\"45\" size=\"25\" name=\"email\" id=\"email\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','email', this.value)\" value=\"" . $row['email'] . "\"";
                echo "/>";
                /* novato anava aqui */

                echo "<label for='estat'>Estat</label>
                <select id=\"estat\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','estat', this.value)\" value=\"" . $row['estat'] . "\">";
                if ($row['estat'] == 'A') { echo "<option value='A' selected>A</option>"; }
                else { echo "<option value='A'>A</option>"; }
                if ($row['estat'] == 'MA') { echo "<option value='MA' selected>MA</option>"; }
                else { echo "<option value='MA'>MA</option>"; }
                if ($row['estat'] == 'O') { echo "<option value='O' selected>O</option>"; }
                else { echo "<option value='O'>O</option>"; }
                if ($row['estat'] == 'NA') { echo "<option value='NA' selected>NA</option>"; }
                else { echo "<option value='NA'>NA</option>"; }
                echo "</select>";



                 echo "<br>";
                 echo "<label for='adre'>Adre&ccedil;a</label>
                <input type=\"text\" maxlength=\"100\" size=\"25\" name=\"adre\" id=\"adre\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','adre', this.value)\" value=\"" . $row['adre'] . "\"";
                echo "/>";
                echo "<label for='cp'>CP</label>
                <input type=\"text\" maxlength=\"5\" size=\"5\" name=\"cp\" id=\"cp\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','cp', this.value)\" value=\"" . $row['cp'] . "\"";
                echo "/>";
                 echo "<label for='dni'>DNI</label>
                <input type=\"text\" maxlength=\"9\" size=\"10\" name=\"dni\" id=\"dni\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','dni', this.value)\" value=\"" . $row['dni'] . "\"";
                echo "/>";

                echo "<br><label for='poblacio'>Poblaci&oacute;</label>
                <input type=\"text\" maxlength=\"25\" size=\"25\" name=\"poblacio\" id=\"poblacio\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','poblacio', this.value)\" value=\"" . $row['poblacio'] . "\"";
                echo "/>";
                 echo "<label for='ocupacio'>Ocupaci&oacute;</label>
                <input type=\"text\" maxlength=\"45\" size=\"30\" name=\"ocupacio\" id=\"ocupacio\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','ocupacio', this.value)\" value=\"" . $row['ocupacio'] . "\"";
                echo "/>";

                 echo "<br><label for='alta'>alta (aaaa-mm-dd)</label>
                <input type=\"text\" maxlength=\"10\" size=\"8\" name=\"alta\" id=\"alta\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','data_ingres', this.value)\" value=\"" . $row['data_ingres'] . "\"";
                echo "/>";
                echo "<label for='baixa'>baixa (aaaa-mm-dd)</label>
                <input type=\"text\" maxlength=\"10\" size=\"8\" name=\"baixa\" id=\"baixa\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','data_baixa', this.value)\" value=\"" . $row['data_baixa'] . "\"";
                echo "/>";
                echo "<label for='canalla'>Canalla?</label>
                <select id=\"canalla\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','canalla', this.value)\" value=\"" . $row['canalla'] . "\">";
                if ($row['canalla'] == 1) { echo "<option value=1 selected>Si</option>"; }
                else { echo "<option value=1>Si</option>"; }
                if ($row['canalla'] == 0) { echo "<option value=0 selected>No</option>"; }
                else { echo "<option value=0>No</option>"; }
                echo "</select>";
                echo "<label for='musics'>M&uacute;sic?</label>
                <select id=\"musics\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','musics', this.value)\" value=\"" . $row['musics'] . "\">";
                if ($row['musics'] == 1) { echo "<option value=1 selected>Si</option>"; }
                else { echo "<option value=1>Si</option>"; }
                if ($row['musics'] == 0) { echo "<option value=0 selected>No</option>"; }
                else { echo "<option value=0>No</option>"; }
                echo "</select>";

                echo "<div id=\"boto_esborrar\">
                <a href='#' onclick=\"javascript:actualitza_dades_cens('" . $row['id'] . "','borrar', '0'); return false;\"><img src=\"pinyes_imatges/icones/trash_can.png\" alt=\"Esborrar\"></a></div>";

                echo "</div>";
            }
            echo "</p>";
            /*** acaba mes dades **/
		    echo "</div></li>";
		} // while
	/*}
else
	echo "Cens buit! <br />";*/
	echo "</ul>";
	/*echo "</div>";*/
	mysqli_close($con);
?>
</form>