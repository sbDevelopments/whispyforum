﻿<?php
/* WhispyForum CMS-forum portálrendszer
   http://code.google.com/p/whispyforum/
*/

/* install/database.php
   adatbázis tábla létrehozó rendszer
*/
 file_put_contents('logs/install.log', "Táblák létrehozása megkezdve: " .Datum("normal","nagybetu","dL","H","i","s"). " ( " .time(). " )", FILE_APPEND); // Naplófájl létrehozása
 function WOut( $tipus, $tabla, $sor = '' )
 {
	global $cfg;
	// Sikeresség kiírása
	print("<div class='messagebox'>"); // Üzenetdoboz
	
	/* Típus alapján megfelelő üzenet kiírása */
	switch ($tipus)
	{
		case "tabla":
			print("<small>A tábla</small> <b>`" .$cfg['dbname']. "`.`" .$cfg['tbprf'].$tabla."`</b> <small>sikeresen létrehozva.</small>"); // Kiírás
			file_put_contents('logs/install.log', "\r\nA tábla `" .$cfg['dbname']. "`.`" .$cfg['tbprf'].$tabla."` sikeresen létrehozva.", FILE_APPEND); // Napló
			break;
		case "sor":
			print("<small>Új sor hozzáadva a táblához:</small> <b>`" .$cfg['dbname']. "`.`" .$cfg['tbprf'].$tabla."`</b><br><small>A sor adatai:</small> " .$sor); // Kiírás
			file_put_contents('logs/install.log', "\r\nÚj sor hozzáadva a táblához: `" .$cfg['dbname']. "`.`" .$cfg['tbprf'].$tabla."`\r\nA sor adatai: " .$sor, FILE_APPEND); // Napló
			break;
	}
	print("</div>"); // Dobozzárás, újsor
 }
 
 /* Adattáblák létrehozása */
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."user (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pwd` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `realName` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `regsessid` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `regip` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `lastip` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `lastsessid` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `curip` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `cursessid` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `regdate` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `loggedin` tinyint(1) NOT NULL DEFAULT '0',
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activatedate` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userLevel` tinyint(1) NOT NULL DEFAULT '0',
  `activateToken` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `postCount` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
 ", 'INSTALL'); // Felhasználók
 WOut('tabla', 'user');
 
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."modules (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `side` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci", 'INSTALL'); // Modulok
 WOut('tabla', 'modules');
 
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."menuitems (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menuId` int(10) NOT NULL,
  `text` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci", 'INSTALL'); // Menüelemek
 WOut('tabla', 'menuitems');
 
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."forum (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `topics` int(10) NOT NULL DEFAULT '0',
  `posts` int(10) NOT NULL DEFAULT '0',
  `lastpostdate` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `lastuser` int(10) NOT NULL DEFAULT '0',
  `lpTopic` int(10) NOT NULL DEFAULT '0',
  `lpId` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci", 'INSTALL'); // Fórumok
 WOut('tabla', 'forum');
 
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."topics (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `fId` INT(10) NOT NULL,
  `name` VARCHAR(128) COLLATE utf8_unicode_ci NOT NULL,
  `type` TINYINT(1) NOT NULL DEFAULT '1',
  `startuser` INT(10) NOT NULL,
  `startdate` VARCHAR(24) COLLATE utf8_unicode_ci NOT NULL,
  `lastuser` INT(10) NOT NULL,
  `lastpostdate` VARCHAR(24) COLLATE utf8_unicode_ci NOT NULL,
  `replies` INT(10) NOT NULL,
  `opens` INT(10) NOT NULL,
  `locked` TINYINT(1) NOT NULL DEFAULT '0',
  `lpId` INT(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci", 'INSTALL'); // Fórumtopicok
 WOut('tabla', 'topics');
 
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."posts (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tId` int(10) NOT NULL,
  `uId` int(10) NOT NULL,
  `pTitle` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `pText` text COLLATE utf8_unicode_ci NOT NULL,
  `pDate` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci", 'INSTALL'); // Postok
 WOut('tabla', 'posts');
 
 $sql->Lekerdezes("CREATE TABLE " .$cfg['tbprf']."version (
  `RELEASE_TYPE` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `VERSION` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `RELEASE_DATE` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci", 'INSTALL'); // Verzióadatok
 WOut('tabla', 'version');
 
 /* Kezdeti adatok */
 /* Modulok */
 $sql->Lekerdezes("INSERT INTO " .$cfg['tbprf']."modules(name,type,side) VALUES('Főmenü','menu','1')", 'INSTALL'); // Főmenü
 WOut('sor', 'modules', 'Főmenü');
 
 /* Menüelemek */
 $sql->Lekerdezes("INSERT INTO " .$cfg['tbprf']."menuitems(menuId, text, href) VALUES
	('1','Kezdőlap','index.php'),
	('1','Fórum','viewforum.php')", 'INSTALL'); // Főmenü elemei
 WOut('sor', 'menuitems', 'Főmenü/Kezdőlap');
 WOut('sor', 'menuitems', 'Főmenü/Fórum');
 
 /* Fórumok */
 $sql->Lekerdezes("INSERT INTO " .$cfg['tbprf']."forum(name, description, topics, posts, lastpostdate, lastuser, lpTopic, lpId) VALUES
	('Első fórumod', 'Ez az első fórumod', '1', '1', '" .time(). "', '1', '1', '1')", 'INSTALL'); // Első fórumod
 WOut('sor', 'forum', 'Első fórumod');
 
 /* Témák */
 $sql->Lekerdezes("INSERT INTO " .$cfg['tbprf']."topics(fId, name, type, startuser, startdate, lastuser, lastpostdate, replies, opens, locked)
	VALUES ('1', 'Első témád, használd örömmel', '1', '1', '" .time(). "', '1', '" .time(). "', '1', '0', '0')", 'INSTALL'); // Első témád
 WOut('sor', 'topics', 'Első fórumod/Első témád');
 
 /* Postok */
 $sql->Lekerdezes("INSERT INTO " .$cfg['tbprf']."posts(tId, uId, pTitle, pText, pDate) VALUES
	('1', '1', 'Próbahozzászólás', 'Üdvözlünk az új fórumodban!\nEz egy próbahozzászólás, mely [b]bemutatja[/b] a BB-kódokat, és a többi ügyes dolgot, amit a fejlesztők létrehoztak\n:) ;) :wned: :spam: :banhammer: :wonder:\n\nJó szórakozást!', '" .time(). "')", 'INSTALL'); // Próbahozzászólás
 WOut('sor', 'posts', 'Első fórumod/Első témád/Próbahozzászólás');
 
 /* Verzióadatok */
 $sql->Lekerdezes("INSERT INTO " .$cfg['tbprf']."version (RELEASE_TYPE, VERSION, RELEASE_DATE) VALUES ('" .RELEASE_TYPE. "', '" .VERSION. "', '" .RELEASE_DATE. "')", 'INSTALL'); // Verzióadatok
 WOut('sor', 'version', 'Verzióadatok: ' .RELEASE_TYPE. " " .VERSION. " (" .RELEASE_DATE. ")");
 
 file_put_contents('logs/install.log', "\r\nTáblák létrehozása befejezve: " .Datum("normal", "nagybetu", "dL", "H", "i", "s"). " ( " .time(). " )", FILE_APPEND); // Napló zárása
?>