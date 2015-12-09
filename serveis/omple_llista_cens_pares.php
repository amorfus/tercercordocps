<?php
	define('INCLUDE_CHECK',true);

	require 'connect.php';
	$con_pares = mysql_connect( $db_host, $db_user, $db_pass);
	if(!$con_pares)
	    die('Could not connect: ' . mysql_error() );
	mysql_select_db($db_database, $con_pares);
	$query_pares = "select * from cens_pares order by nom";
	$result = mysql_query($query_pares);
	
	echo "<form action='' method='get'>";
	echo "<div class='llista_noms_scroll'><ul id='cens_pares' data-role='listview' class='llista_cens touch' data-split-icon='delete' data-filter='true' data-filter-reveal='false' data-filter-placeholder='Cercar...' data-filter-theme='b' data-autodividers='true' data-inset='true'>";
	
    if(mysql_num_rows($result) > 0)
	{
		while($row = mysql_fetch_array($result))
		{
			$paternitat_query = "select nom,cognom_1 from cens where (canalla = 1 and (pare_id=" . $row['id'] . " or mare_id=" . $row['id'] . "))";
			$paternitat = mysql_query($paternitat_query);
			/*$email = $row['email'];
			$url_paparres="http://www.castellersdelpoblesec.cat/mailman/admin/paparres_castellersdelpoblesec.cat/members?findmember=" . $email . "&setmemberopts_btn&adminpw=cpspaparresd199740";
			$llista_paparres=file_get_contents("$url_paparres","FALSE");*/
		    echo "<li class='dades_camps'>
			<div class='nom_pare' name='" . $row['nom'] . "'>" . $row['nom'] . " " . $row['cognom_1'];
		    echo (strlen($row['cognom_2']) > 0? " i " . $row['cognom_2'] : "");
		    echo "<p class='ui-li-aside'><a href=\"#\" onclick=\"javascript:jQuery(this).find('i').toggleClass('fa-chevron-down');jQuery(this).find('i').toggleClass('fa-chevron-up');jQuery('#dades_cens_" . $row['id'] . "').fadeToggle(400);\" class=\"open\" width=\"64\" height=\"64\"><i class='lIcon fa fa-chevron-down'></i></a>";
		    echo "<a href=\"#\" onclick=\"javascript:afegeix_fill(" . $row['id'] . ",'" . $row['pare_mare'] . "');\" class=\"fills\" width=\"32\" height=\"32\">&nbsp;</a>";
		    echo "</p><br><span><input type=\"text\" maxlength=\"15\" size=\"8\" name=\"mobil\" id=\"mobil\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','mobil', this.value)\" value=\"" . $row['mobil'] . "\"";
		    echo "/><label for='mobil'>m&ograve;bil</label></span>";
		    echo "<span><input type=\"text\" maxlength=\"15\" size=\"8\" name=\"fixe\" id=\"fixe\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','fixe', this.value)\" value=\"" . $row['fixe'] . "\"";
		    echo "/><label for='fixe'>fixe</label></span>";
		    
		    if(mysql_num_rows($paternitat) > 0) {
			echo "<div class='canalla'>( ";
			while($filla = mysql_fetch_array($paternitat)) {
				echo $filla['nom'] . " ";
			}
			echo ")  </div>";
		    }
		    
		    echo "<div id=\"dades_cens_" . $row['id'] . "\" style=\"display:none;\" class=\"dades_cens\">";
		    
		    echo "<input type=\"text\" maxlength=\"25\" size=\"10\" name=\"nom\" id=\"nom\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','nom', this.value)\" value=\"" . $row['nom'] . "\"/>
			<input type=\"text\" maxlength=\"50\" size=\"10\" name=\"cognom_1\" id=\"cognom_1\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','cognom_1', this.value)\" value=\"" . $row['cognom_1'] . "\"/>
			<input type=\"text\" maxlength=\"50\" size=\"10\" name=\"cognom_2\" id=\"cognom_2\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','cognom_2', this.value)\" value=\"" . $row['cognom_2'] . "\"/>";
		    
		    echo "<br><label for='email'>Email</label>
			<input type=\"text\" maxlength=\"45\" size=\"25\" name=\"email\" id=\"email\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','email', this.value)\" value=\"" . $row['email'] . "\"";
		    echo "/>";
		    echo "<br><label for='rebre_mails'>Rebre mails?</label>
			<select id=\"rebre_mails\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','rebre_mails', this.value)\" value=\"" . $row['rebre_mails'] . "\">";
			if ($row['rebre_mails'] == 1) { echo "<option value=1 selected>Si</option>"; }
			else { echo "<option value=1>Si</option>"; }
			if ($row['rebre_mails'] == 0) { echo "<option value=0 selected>No</option>"; }
			else { echo "<option value=0>No</option>"; }
		    echo "</select>";
		    echo "<label for='pare_mare'>Pare / Mare</label>
			<select id=\"pare_mare\" onchange=\"actualitza_dades_cens_pares('" . $row['id'] . "','pare_mare', this.value)\" value=\"" . $row['pare_mare'] . "\">";
			if ($row['pare_mare'] == 'P') { echo "<option value='P' selected>Pare</option>"; }
			else { echo "<option value='P'>Pare</option>"; }
			if ($row['pare_mare'] == 'M') { echo "<option value='M' selected>Mare</option>"; }
			else { echo "<option value='M'>Mare</option>"; }
		    echo "</select>";
		    
		    echo "<div id=\"boto_esborrar\">
			<a href='#' onclick=\"javascript:actualitza_dades_cens_pares('" . $row['id'] . "','borrar', '0'); return false;\"><img src=\"images/icones/trash_can.png\" alt=\"Esborrar\"></a>
			<a href='http://www.castellersdelpoblesec.cat/mailman/admin/paparres_castellersdelpoblesec.cat/members/remove?adminpw=cpspaparresd199740&send_unsub_notifications_to_list_owner=0&send_unsub_ack_to_this_batch=0&unsubscribees=" . $row['email'] ."' target='_blank'><img src=\"images/icones/delete_page.png\" alt=\"Desubscriure de paparres\"></a></div>";
		    echo "</div></div>";
		  /*  if ($row['email'] !='')
			if ( preg_match("/$email/", $llista_paparres) )  echo "<img class=\"si_llista\" src=\"./pinyes_imatges/icones/llista_1999.png\">";
		    else echo "<img  class=\"no_llista\" src=\"./pinyes_imatges/icones/llista_1999.png\">";*/
		    echo "</li>";
		} // while
	}
else
	echo "Cens buit! <br />";
	echo "</ul></div>";
?>
</form>