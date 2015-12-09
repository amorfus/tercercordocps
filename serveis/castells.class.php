<?php

class CastellDiada{
	
	/* An array that stores the CastellDiada item data: */
	
	private $data;
	
	/* The constructor */
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	/*
		This is an in-build "magic" method that is automatically called 
		by PHP when we output the CastellDiada objects with echo. 
	*/
		
	public function __toString(){
		
		// The string we return is outputted by the echo statement
		
			return '
				<li id="todo-'.$this->data['resultat_id'].'" class="todo ' . $this->data['castell_id'] . '">
					<div class="prova">'.$this->data['castell_id'].'
					<span class="text">'.$this->data['resultat'].'</span></div>
					
					<div class="actions">
						<a href="#" class="edit no_print">Edit</a>
						<a href="#" class="delete no_print">Delete</a>
					</div>
					
				</li>';
	}
	
	/*
		The edit method takes the CastellDiada item id and the new text
		of the CastellDiada. Updates the database.
	*/
		
	public static function edit($id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Texte Erroni!");
		
		mysql_query("	UPDATE resultats
						SET resultat='".$text."'
						WHERE resultat_id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Impossible actualitzar resultat!");
	}
	
	/*
		The delete method. Takes the id of the CastellDiada item
		and deletes it from the database.
	*/
	
	public static function delete($id){
		
		mysql_query("DELETE FROM resultats WHERE resultat_id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("No es pot esborrar!");
	}
	
	/*
		The rearrange method is called when the ordering of
		the CastellDiadas is changed. Takes an array parameter, which
		contains the ids of the CastellDiadas in the new order.
	*/
	
	public static function rearrange($key_value, $var_resultat_id){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the CastellDiada positions en masse:
		mysql_query("	UPDATE resultats SET ronda = CASE resultat_id
						".join($strVals)."
						ELSE ronda
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error actualitzant rondes!");
	}
	
	/*
		The createNew method takes only the text of the CastellDiada,
		writes to the databse and outputs the new CastellDiada back to
		the AJAX front-end.
	*/
	
	public static function createNew($var_id_diada, $var_castell){
		
		$var_castell = self::esc($var_castell);
		$var_id_diada = self::esc($var_id_diada);
		if(!$var_castell) throw new Exception("Error en dades introdu&iuml;des!");
		
		$posResult = mysql_query("SELECT MAX(ronda)+1 FROM resultats WHERE diada_id=" . $var_id_diada);
		
		if (!posResult) $position = 1;
		else {
			if(mysql_num_rows($posResult))
				list($position) = mysql_fetch_array($posResult);

			if(!$position) $position = 1;
		}
		
		mysql_query("INSERT INTO resultats SET castell_id='".$var_castell."', ronda = ".$position.", diada_id = '" . $var_id_diada ."'");

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserint CASTELL!");
		
		// Creating a new CastellDiada and outputting it directly:
		
		echo (new CastellDiada(array(
			'resultat_id'	=> mysql_insert_id($GLOBALS['link']),
			'diada_id'		=> $var_id_diada,
			'castell_id'	=> $var_castell
		)));
		
		exit;
	}
	
	/*
		A helper method to sanitize a string:
	*/
	
	public static function esc($str){
		
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		
		return mysql_real_escape_string(strip_tags($str));
	}
	
} // closing the class definition

?>