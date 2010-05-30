﻿<?php
/* WhispyForum CMS-forum portálrendszer
   http://code.google.com/p/whispyforum/
*/

/* newpost.php
   új hozzászólás hozzáadása
*/

 include('includes/common.php'); // Betöltjük a portálrendszer alapscriptjeit (common.php elvégzi)
 Inicialize('newpost.php');
 
 if ($_SESSION['loggedin'] != 1) { // Ha a felhasználó nincs bejelentkezve, nem szólhat hozzá
	Hibauzenet("ERROR", "Amíg nem jelentkezel be, nem küldhetsz hozzászólást");
  } else {
	
	/* Téma ID beállítása a bejövő adatok alapján */
	if ( $_GET['id'] != $NULL)
		$tId = $_GET['id'];
	if ( $_POST['id'] != $NULL)
		$tId = $_POST['id'];
	
	$sor2 = mysql_fetch_array($sql->Lekerdezes("SELECT * FROM " .$cfg['tbprf']."topics WHERE id='" .$tId. "'"), MYSQL_ASSOC); // Tábla adatai
	if ( $sor2 == FALSE )
	{
		Hibauzenet("ERROR", "A megadott azonosítójú téma nem létezik");
	} else {
		
		if ( ($_POST['title'] != $NULL) && ($_POST['post'] != $NULL) )
		{
			// Ha van bejövő adat a hozzászólásról
			// Megnézzük, megtekintés van-e
			if ( $_POST['submit'] != $NULL )
			{
				// Fórum hozzászólásszámok bekérése
				$sor3 = mysql_fetch_array($sql->Lekerdezes("SELECT * FROM " .$cfg['tbprf']."forum WHERE id='" .$sor2['fId']. "'"), MYSQL_ASSOC); // Fórum sor
				// Hozzászólás beküldése
				$sql->Lekerdezes("INSERT INTO posts(tId, uId, pTime, pTitle, pText, pDate) VALUES ( " .$_POST["id"]. ", '" .$_SESSION["userID"]. "', '" .time(). "', '" .$_POST["title"]. "', '" .$_POST['post']. "', '" .time(). "')"); // Beküldés az adatbázisba
				
				$sql->Lekerdezes("UPDATE " .$cfg['tbprf']."topics SET replies='" .($sor2['replies']+1). "' WHERE id='" .$_POST['id']. "'"); // Hozzászólásszám növelése a témán
				$sql->Lekerdezes("UPDATE " .$cfg['tbprf']. "forum SET posts='" .($sor3['posts']+1). "' WHERE id='" .$sor2['fId']. "'"); // Hozzászólásszám növelése a fórumon
				
				print("<div class='messagebox'>Hozzászólásod elküldve!<br><a href='viewtopic.php?id=" .$_POST['id']. "'>Vissza a témához</a></div>"); // Visszatérési link
				die(); // A többi kód nem fut le!
			}
			
		}
		
		print("<a href='viewtopic.php?id=" .$tId. "'><< Vissza a témához</a><form action='" .$_SERVER['PHP_SELF']. "' method='POST'>
			<span class='formHeader'>Új hozzászólás beküldése: " .$sor2['name']. "</span>
			<p class='formText'>Cím: <input type='text' name='title' size='70' value='" .$_POST['title']. "'></p>
			<div class='postbox'><p class='formText'>Hozzászólás:<br></a>
			<textarea rows='20' name='post' cols='70'>" .$_POST['post']. "</textarea></div>
			<div class='postright'>"); // Bal oldali rész
			print("<a href='/themes/" .THEME_NAME. "/images.php#emoticons' target='_blank'>Hangulatjelek</a>"); // Emoticon ablak
			print("</div>
			</p>
			<input type='hidden' name='id' value='" .$tId. "'>
			<fieldset class='submit-buttons'>
				<input type='submit' name='submit' value='Hozzászólás elküldése'>
				<input type='submit' name='preview' value='Előnézet'>
			</fieldset>
			</form><br>"); // Hozzászólás beküldési űrlap
			
			if ( $_POST['preview'] != $NULL )
			{	// Vagy elküldés
				/* Hózzászólás formázása */
				$postBody = $_POST['post']; // Nyers
				$postBody = EmoticonParse($postBody); // Hangulatjelek hozzáadása BB-kódként
				$postBody = HTMLDestroy($postBody); // HTML kódok nélkül 
				$postBody = BBDecode($postBody); // BB kódok átalakítása HTML-kóddá (hangulatjeleket képpé)
				
				// Megtekintés
				print("<h2 class='header'><p class='header'>A hozzászólásod így fog kinézni:</p></h2><div class='post'>"); // Fejléc
				print("<div class='postbody'><h3 class='postheader'><p class='header'>" .$_POST['title']. "</p></h3>"); // Hozzászólás fejléc
				print("<div class='content'>" .$postBody. "</div></div>"); // Hozzászólás
				print("<dl class='postprofile'><dt>" .$_SESSION['username']. "</dt><br><dd>Rang: " .$_SESSION['usrLevelTXT']. "</dd><dd>Hozzászólások: " .($_SESSION['postCount']+1). "</dd>"); // Hozzászólás adatai (hozzászóló, stb.)
				print("<dd>Csatlakozott: " .Datum("normal","m","d","H","i","", $_SESSION['regdate']). "</dd></dl>"); 
				print("</div>"); // Hozzászólás vége
			}
	}
 }
 
 DoFooter();
?>