﻿<?php
/* WhispyForum CMS-forum portálrendszer
   http://code.google.com/p/whispyforum/
*/

/* viewtopics.php
   témák listázása egy adott fórumon belül
*/
 
 include('includes/common.php');
 Inicialize('viewtopics.php');
 
 if ( $_GET['id'] == $NULL )
	die(Hibauzenet("ERROR","A megadott azonosítójú fórum nem létezik"));
 
 print("<table class='forum'>
 <tr>
	<th class='forumheader'>Témák</th>
	<th class='forumheader'>Válaszok</th>
	<th class='forumheader'>Megtekintések</th>
	<th class='forumheader'>Utolsó hozzászólás</th>
 </tr>"); // Fejléc
 
 global $cfg, $sql;
 $adat = $sql->Lekerdezes("SELECT * FROM " .$cfg['tbprf']."topics WHERE fId='" .$_GET['id']. "'"); // Témák betöltése az adott fórumból
 
 while ($sor = mysql_fetch_array($adat, MYSQL_ASSOC)) { // Témák listázása
	// Felhasználók nevének betöltése
	$adat2 = mysql_fetch_array($sql->Lekerdezes("SELECT username FROM " .$cfg['tbprf']. "user WHERE id='" .$sor['startuser']. "'"), MYSQL_ASSOC);
	$adat3 = mysql_fetch_array($sql->Lekerdezes("SELECT username FROM " .$cfg['tbprf']. "user WHERE id='" .$sor['lastuser']. "'"), MYSQL_ASSOC);
		
	print("<tr>
		<td class='forumlist'><p><a href='viewtopic.php?id=" .$sor['id']. "'>" .$sor['name']. "</a><br>Szerző: " .$adat3['username']. " » " .Datum("normal","m","d","H","i","s",$sor['startdate']). "</p></td>
		<td class='forumlist'>" .$sor['replies']. "</td>
		<td class='forumlist'>" .$sor['opens']. "</td>
		<td class='forumlist'><p>" .Datum("normal","m","d","H","i","s",$sor['lastpostdate']). "<br>" .$adat2['username']. "<a href='viewtopic.php?id=" .$sor['id']. "#pid" .$sor['lpId']. "'><img src='themes/" .THEME_NAME. "/lastpost.gif' border='0'></a></p></td>
		</tr>"); // Téma sor
	}
	
 DoFooter();
?>