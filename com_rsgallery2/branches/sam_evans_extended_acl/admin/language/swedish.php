<?php

/**
* Swedish languagefile for RSGallery
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author Stefan Frick - stefan.frick@gmail.com, updated to version 1.13.0 by Christer Toivonen, christer.toivonen@gmail.com
* RSGallery is Free Software
**/
defined( '_JEXEC' ) or die( 'Restricted access' );
//Created by Christer 2007-05-20
//display.class.php
setlocale(LC_ALL,"sv_SE");
DEFINE("_RSGALLERY_SPECIAL_DISPLAY_Owner",	"�gare");
DEFINE("_RSGALLERY_SPECIAL_DISPLAY_Size",	"Antal");
DEFINE("_RSGALLERY_SPECIAL_DISPLAY_Created",	"Skapad");
DEFINE("_RSGALLERY_SPECIAL_DISPLAY_Dateform",	"%A den %e %b %Y");
//rsgallery2.html.php
//function showUserGallery
DEFINE("_RSGALLERY_MAKECAT_ALERT_NAME",		"Du m�ste ange ett albumnamn.");
DEFINE("_RSGALLERY_MAKECAT_ALERT_DESCR",	"Du m�ste ange en beskrivning.");
DEFINE("_RSGALLERY_CREATE_GALLERY",			"Skapa nytt album");
DEFINE("_RSGALLERY_SAVE",					"Spara");
DEFINE("_RSGALLERY_CANCEL",					"Avbryt");
DEFINE("_RSGALLERY_CATLEVEL",				"Toppalbum");
DEFINE("_RSGALLERY_USERCAT_NAME",			"Albumnamn");
DEFINE("_RSGALLERY_DESCR",					"Beskrivning");
DEFINE("_RSGALLERY_CATPUBLISHED",			"Publicerad");
//function edit_image
DEFINE("_RSGALLERY_EDIT_IMAGE",				"Redigera bild");
DEFINE("_RSGALLERY_CAT_NAME",				"Namn p� kategori");
DEFINE("_RSGALLERY_EDIT_FILENAME",			"Filnamn");
DEFINE("_RSGALLERY_EDIT_TITLE",			    "Rubrik");
DEFINE("_RSGALLERY_EDIT_DESCRIPTION",		"Beskrivning");
//function showFrontUpload
DEFINE("_RSGALLERY_UPLOAD_ALERT_CAT",		"Du m�ste v�lja ett album.");
DEFINE("_RSGALLERY_UPLOAD_ALERT_FILE",		"Du m�ste ange en fil att ladda upp.");
DEFINE("_RSGALLERY_ADD_IMAGE",				"L�gg till bild");
DEFINE("_RSGALLERY_USERUPLOAD_TITLE",		"Anv�ndaruppladdning");
DEFINE("_RSGALLERY_USERUPLOAD_CATEGORY",	"Album");
DEFINE("_RSGALLERY_FILENAME",				"Filenamn");
DEFINE("_RSGALLERY_UPLOAD_FORM_TITLE",		"Rubrik");
DEFINE("_RSGALLERY_UPLOAD_THUMB",			"Miniatyr:");
//function RSGalleryInline
DEFINE("_RSGALLERY_COMMENT_DELETE",			"�r du s�ker p� att du vill ta bort den h�r kommentaren?");
DEFINE("_RSGALLERY_NOIMG",					"Inga bilder i albumet");
DEFINE("_RSGALLERY_SLIDESHOW",		        "Bildspel");
DEFINE("_RSGALLERY_CATHITS",				"Tr�ffar");
DEFINE("_RSGALLERY_NODESCR",				"Ingen beskrivning");
DEFINE("_RSGALLERY_VOTING",					"Omd�me");
DEFINE("_RSGALLERY_VOTES_NR",				"R�ster");
DEFINE("_RSGALLERY_VOTES_AVG",				"Medelbetyg");
DEFINE("_RSGALLERY_NO_RATINGS",				"Inget omd�me �n!");
DEFINE("_RSGALLERY_VOTE",			   		"R�sta");
DEFINE("_RSGALLERY_VERYGOOD",				"&nbsp;Mycket bra&nbsp;");
DEFINE("_RSGALLERY_GOOD",					"&nbsp;Bra&nbsp;");
DEFINE("_RSGALLERY_OK",					    "&nbsp;Ok&nbsp;");
DEFINE("_RSGALLERY_BAD",					"&nbsp;D�lig&nbsp;");
DEFINE("_RSGALLERY_VERYBAD",				"&nbsp;Mycket d�lig&nbsp;");
DEFINE("_RSGALLERY_COMMENTS",			   	"Kommentarer");
DEFINE("_RSGALLERY_NO_COMMENTS",			"Inga kommentarer �n!");
DEFINE("_RSGALLERY_COMMENT_DATE",   		"Datum");
DEFINE("_RSGALLERY_COMMENT_BY",     		"Av");
DEFINE("_RSGALLERY_COMMENT_TEXT",   		"Kommentar");
DEFINE("_RSGALLERY_DELETE_COMMENT",			"Ta bort kommentar");
DEFINE("_RSGALLERY_COMMENT_ADD",			"L�gg till kommentar");
DEFINE("_RSGALLERY_COMMENT_NAME",   		"Ditt namn");
DEFINE("_RSGALLERY_COMMENT_ADD_TEXT",   	"Din kommentar");
DEFINE("_RSGALLERY_EXIF",					"EXIF");
//function RSGalleryTitleblock
DEFINE("_RSGALLERY_MAIN_GALLERY_PAGE",		"Huvudsida");
DEFINE("_RSGALLERY_MY_GALLERIES",			"Mina album");
DEFINE("_RSGALLERY_SLIDESHOW_EXIT",			"Avsluta bildspel");
DEFINE("_RSGALLERY_COMPONENT_TITLE",		"Album");
//function subGalleryList
DEFINE("_RSGALLERY_IMAGES",					" Bilder");
DEFINE("_RSGALLERY_SUB_GALLERIES",			"Underalbum:");
//function RSGalleryList
//function RSShowPictures
DEFINE("_RSGALLERY_DELIMAGE_TEXT"			,"�r du s�ker p� att du vill ta bort den h�r bilden?");
//function showMyGalleries
DEFINE("_RSGALLERY_USER_MY_GAL",			"Mina album");
DEFINE("_RSGALLERY_MY_IMAGES_CATEGORY",		"Album");
DEFINE("_RSGALLERY_MY_IMAGES_PUBLISHED",	"Publicerad");
DEFINE("_RSGALLERY_MY_IMAGES_DELETE",		"Radera");
DEFINE("_RSGALLERY_MY_IMAGES_EDIT",			"Redigera");
DEFINE("_RSGALLERY_MY_IMAGES_PERMISSIONS",	"R�ttigheter");
DEFINE("_RSGALLERY_NO_USER_GAL",			"Inga anv�ndaralbum skapade");
DEFINE("_RSGALLERY_DELCAT_TEXT",			"�r du s�ker p� att du vill ta bort detta album?\\nOm albumet inneh�ller bilder kommer dessa ocks� att raderas.");
//function showMyImages
DEFINE("_RSGALLERY_MY_IMAGES",				"Mina bilder");
DEFINE("_RSGALLERY_MY_IMAGES_NAME",			"Namn");
DEFINE("_RSGALLERY_NOIMG_USER",				"Inga bilder i anv�ndaralbum");
//function RSGalleryUserInfo
DEFINE("_RSGALLERY_USER_INFO",				"Anv�ndarinformation");
DEFINE("_RSGALLERY_USER_INFO_NAME",			"Anv�ndarnamn");
DEFINE("_RSGALLERY_USER_INFO_ACL",			"Anv�ndarniv�");
DEFINE("_RSGALLERY_USER_INFO_MAX_GAL",		"Max. antal album");
DEFINE("_RSGALLERY_USER_INFO_CREATED",				"skapad)");
DEFINE("_RSGALLERY_USER_INFO_MAX_IMG",		"Max. antal till�tna bilder");
DEFINE("_RSGALLERY_USER_INFO_UPL",			"uppladdade)");
//function myGalleries
//function showRandom
DEFINE("_RSGALLERY_RANDOM_TITLE",			"Slumpm�ssiga bilder");
//function showLatest
DEFINE("_RSGALLERY_LATEST_TITLE",			"Senaste bilder");

//rsgallery2.php
//function my_galleries
DEFINE("_RSGALLERY_NO_USERCATS",            "Anv�ndaralbumen �r sp�rrade av administrat�ren");
//function save_image
DEFINE("_RSGALLERY_SAVE_SUCCESS",		    "Uppgifterna har sparats");
//function delete_image
DEFINE("_RSGALLERY_USERIMAGE_NOTOWNER",		"Du �r inte �gare av den h�r bilden, �terg�r till startsidan");
DEFINE("_RSGALLERY_DELIMAGE_OK",			"Bilden har raderats");
DEFINE("_RSGALLERY_DELIMAGE_NOID",			"Inget Id angett. Kontakta utvecklaren av den h�r komponenten");
//function addVote
DEFINE("_RSGALLERY_THANK_VOTING",			"Tack f�r att du r�stade");
DEFINE("_RSGALLERY_VOTING_FAILED",			"Din r�stning misslyckades");
//function deleteComment
DEFINE("_RSGALLERY_COMMENT_DELETED",		"Kommentaren har tagits bort!");
DEFINE("_RSGALLERY_COMMENT_NOT_DELETED",	"Kommentaren kunde inte tas bort");
//function addComment
DEFINE("_RSGALLERY_COMMENT_FIELD_CHECK",	"V.g. ange namn och/eller kommentar!");
DEFINE("_RSGALLERY_COMMENT_ADDED",			"Kommentaren har lagts till!");
DEFINE("_RSGALLERY_COMMENT_NOT_ADDED",		"Kommentaren har inte lagts till!");
//function makeusercat
DEFINE("_RSGALLERY_ALERT_CATDETAILSOK",		"Albuminformation uppdaterad!");
DEFINE("_RSGALLERY_ALERT_CATDETAILSNOTOK",	"Kunde inte uppdatera albuminformation!");
DEFINE("_RSGALLERY_MAX_USERCAT_ALERT",		"Max. antal album uppn�tt, �terg�r till startsidan");
DEFINE("_RSGALLERY_ALERT_NEWCAT",			"Nytt album skapat!");
DEFINE("_RSGALLERY_ALERT_NONEWCAT",			"Albumet kunde inte skapas!\\n�terg�r till f�reg�ende sida.");
//function delUserCat
DEFINE("_RSGALLERY_USERCAT_SUBCATS",        "Det h�r albumet inneh�ller underkataloger och kan inte raderas. Om du vill radera detta album, m�ste du f�rst flytta eller ta bort underkatalogerna");
DEFINE("_RSGALLERY_ALERT_CATDELNOTOK",		"Albumet kunde inte raderas!");
DEFINE("_RSGALLERY_ALERT_CATDELOK",			"Album raderat!");
//function doFrontUpload
DEFINE("_RSGALLERY_MAX_USERIMAGES_ALERT",   "Du har redan laddat upp max. antal bilder. Radera n�gra f�rst.");
DEFINE("_RSGALLERY_BATCH_ERROR_SIZE",		"ZIP-filen �r f�r stor. Max. storlek (definierad i php.ini) �r ");
DEFINE("_RSGALLERY_ALERT_UPLOADOK",			"Bilden har laddadts upp!");
DEFINE("_RSGALLERY_ALERT_NOWRITE",			"Uppladdningen misslyckades.\\n�terg�r till uppladdningssidan");
DEFINE("_RSGALLERY_ALERT_WRONGFORMAT",		"Fel filformat.\\n�terg�r till uppladdningssidan");

//admin.rsgallery2.html.php
DEFINE("_RSGALLERY_TAB_GALLERIES",			"Album");
DEFINE("_RSGALLERY_MOST_RECENT_GAL",		"Senast tillagda album");
DEFINE("_RSGALLERY_GALLERY",				"Album");
DEFINE("_RSGALLERY_USER",					"Anv�ndare");
DEFINE("_RSGALLERY_ID",						"ID");
DEFINE("_RSGALLERY_TAB_IMAGES",				"Bilder");
DEFINE("_RSGALLERY_MOST_RECENT_IMG", 		"Senast tillagda bilder");
DEFINE("_RSGALLERY_DATE",					"Datum");
DEFINE("_RSGALLERY_CREDITS",				"Tack till:");
DEFINE("_RSGALLERY_INSTALLED_VERSION",		"Installerad version");

DEFINE("_RSGALLERY_LICENSE",			"Licens");
//Cpanel
DEFINE("_RSGALLERY_C_CONFIG",				"Konfiguration");
DEFINE("_RSGALLERY_C_UPLOAD",				"Ladda upp");
DEFINE("_RSGALLERY_C_UPLOAD_ZIP",			"Ladda upp flera");
DEFINE("_RSGALLERY_C_IMAGES",				"Hantera bilder");
DEFINE("_RSGALLERY_C_CATEGORIES",			"Hantera album");
DEFINE("_RSGALLERY_C_DATABASE",				"Konsolidera databas");
DEFINE("_RSGALLERY_C_MIGRATION",			"Migreringsalternativ");
DEFINE("_RSGALLERY_C_CSS_EDIT",				"Redigera CSS");
DEFINE("_RSGALLERY_C_DEBUG_ON",				"Avancerade fels�kningsalternativ.  Debugmod st�lls in i <a href='index2.php?option=com_rsgallery2&task=showConfig'>konfiguration</a>.");
DEFINE("_RSGALLERY_C_PURGE",				"Rensa/Radera allt");
DEFINE("_RSGALLERY_C_REALLY_UNINSTALL",		"TOTAL avinstallation - Raderar alla bilder, kataloger, databastabeller. Endast p� Linux med standardkataloger.");
DEFINE("_RSGALLERY_C_VIEW_CONFIG",			"Konfig - Visa");
DEFINE("_RSGALLERY_C_EDIT_CONFIG",			"Konfig - direktredigering");
//function requestCatCreation
DEFINE("_RSGALLERY_C_CAT_FIRST",			"Skapa en kategori f�rst!");
//function batch_upload
DEFINE("_RSGALLERY_BATCH_NO_ZIP",			"ZIP-uppladdning vald, men ingen fil har angetts");
DEFINE("_RSGALLERY_BATCH_GAL_FIRST",		"V.g. v�lj en kategori f�rst");
DEFINE("_RSGALLERY_BATCH_NO_FTP",			"FTP uppladdning vald, men ingen FTP-s�kv�g har angetts");
DEFINE("_RSGALLERY_BATCH_STEP1",			"Steg 1");
DEFINE("_RSGALLERY_BATCH_METHOD",			"V�lj metod f�r uppladdning");
DEFINE("_RSGALLERY_BATCH_METHOD_TIP",		"V�lj om du vill ladda upp en fil, ett ZIP-arkiv eller en komplett katalog/mapp");
DEFINE("_RSGALLERY_BATCH_ZIPFILE",			"ZIP-fil");
DEFINE("_RSGALLERY_BATCH_UPLOAD_LIMIT",		"Max. gr�ns f�r uppladdning �r ");
DEFINE("_RSGALLERY_BATCH_IN_PHPINI",		" Megabytes (satt i php.ini)");
DEFINE("_RSGALLERY_BATCH_FTP_PATH",			"FTP-s�kv�g");
DEFINE("_RSGALLERY_BATCH_DONT_FORGET_SLASH","(Gl�m inte inledande och avslutande snedstreck)");
DEFINE("_RSGALLERY_BATCH_CATEGORY",			"Ange album");
DEFINE("_RSGALLERY_BATCH_YES_IMAGES_IN",	"Ja, alla bilder l�ggs i ");
DEFINE("_RSGALLERY_BATCH_NO_SPECIFY",		"Nej, ange album per bild i steg 2");
DEFINE("_RSGALLERY_BATCH_NEXT",				"N�sta -->");
DEFINE("_RSGALLERY_BATCH_DELETE",	"Radera");
DEFINE("_RSGALLERY_BATCH_TITLE",		"Rubrik");
DEFINE("_RSGALLERY_BATCH_GAL",		"Album");
DEFINE("_RSGALLERY_BATCH_UPLOAD",	"Ladda upp");
DEFINE("_RSGALLERY_BATCH_FTP_PATH_OVERL",	"Var s�ker p� att FTP-s�kv�gen �r i webroten. Den f�r inte vara p� en annan server! Avsluta likas� med ett snedstreck.");
//function editImage
DEFINE("_RSGALLERY_PROP_TITLE",				"Redigera bildegenskaper");
DEFINE("_RSGALLERY_TITLE",					"Rubrik");
DEFINE("_RSGALLERY_CONF_OPTION_TABLE",		"Tabell");
DEFINE("_RSGALLERY_CONF_OPTION_FLOAT",		"Flytande");
DEFINE("_RSGALLERY_CONF_OPTION_MAGIC",		"Magic (st�ds f.n. ej!)");
DEFINE("_RSGALLERY_CONF_OPTION_L2R",		"V�nster till h�ger");
DEFINE("_RSGALLERY_CONF_OPTION_R2L",		"H�ger till v�nster");
DEFINE("_RSGALLERY_CONF_OPTION_PROP",		"Proportionell");
DEFINE("_RSGALLERY_CONF_OPTION_SQUARE",		"Fyrkantig");
DEFINE("_RSGALLERY_CONF_OPTION_DEFAULT_SIZE",		"Standardstorlek");
DEFINE("_RSGALLERY_CONF_OPTION_REZ_LARGE",		"Storleksanpassa stora bilder");
DEFINE("_RSGALLERY_CONF_OPTION_REZ_SMALL",		"Storleksanpassa sm� bilder");
DEFINE("_RSGALLERY_CONF_OPTION_REZ_2FIT",		"Anpassa bilder");
DEFINE("_RSGALLERY_CONF_OPTION_TL",		"�vre v�nstra");
DEFINE("_RSGALLERY_CONF_OPTION_TC",		"�vre mitten");
DEFINE("_RSGALLERY_CONF_OPTION_TR",		"�vre h�gra");
DEFINE("_RSGALLERY_CONF_OPTION_L",		"V�nster");
DEFINE("_RSGALLERY_CONF_OPTION_C",		"Centrerad");
DEFINE("_RSGALLERY_CONF_OPTION_R",		"H�ger");
DEFINE("_RSGALLERY_CONF_OPTION_BL",		"Nedre v�nstra");
DEFINE("_RSGALLERY_CONF_OPTION_BC",		"Nedre mitten");
DEFINE("_RSGALLERY_CONF_OPTION_BR",		"Nedre h�gra");
DEFINE("_RSGALLERY_CONF_POPUP_STYLE",		"Popup stil");
DEFINE("_RSGALLERY_CONF_POPUP_NO",		"Inga popup");
DEFINE("_RSGALLERY_CONF_POPUP_NORMAL",		"Normal popup");
DEFINE("_RSGALLERY_CONF_POPUP_FANCY",		"Snygga popup (Felaktiga i IE6!)");
//function showconfig
DEFINE("_RSGALLERY_FREETYPE_INSTALLED",		"(Freetype library installerat, vattenst�mpling �r m�jlig)");
DEFINE("_RSGALLERY_FREETYPE_NOTINSTALLED",	"(Freetype library INTE installerat! Vattenst�mplar kommer inte att fungera)");
DEFINE("_RSGALLERY_CONF_GENERALTAB",		"Allm�nt");
DEFINE("_RSGALLERY_CONF_IMAGESTAB",		"Bilder");
DEFINE("_RSGALLERY_CONF_DISPLAY",		"Visa");
DEFINE("_RSGALLERY_CONF_USERS",		"R�ttigheter");
//function showUploadStep1
DEFINE("_RSGALLERY_PICK",					"V�lj ett album");
//function showUploadStep2
DEFINE("_RSGALLERY_BATCH_STEP2",			"Steg 2");
DEFINE("_RSGALLERY_UPLOAD_NUMBER",			"Antal uppladdningar");
//function showUploadStep3
DEFINE("_RSGALLERY_BATCH_STEP3",			"Steg 3");
DEFINE("_RSGALLERY_UPLOAD_FORM_IMAGE",		"Bild");
DEFINE("_RSGALLERY_CATNAME",				"Albumnamn");
DEFINE("_RSGALLERY_UPLOAD_FORM_FILE",		"Fil");
//function viewImages
DEFINE("_RSGALLERY_DELETE",					"Radera");
DEFINE("_RSGALLERY_MOVETO",					"Flytta till");
DEFINE("_RSGALLERY_NUMDISPLAY",				"Visa #");
DEFINE("_RSGALLERY_SEARCH",					"S�k");
DEFINE("_RSGALLERY_IMAGENAME",				"Namn");
DEFINE("_RSGALLERY_IMAGEFILE",				"Filnamn");
DEFINE("_RSGALLERY_IMAGECAT",				"Album");
DEFINE("_RSGALLERY_IMAGEHITS",				"Tr�ffar");
DEFINE("_RSGALLERY_IMAGEDATE",				"Datum f�r uppladdning");
DEFINE("_RSGALLERY_REORDER",				"Ordna om");
//function showTemplates
DEFINE("_RSGALLERY_TEMP_MANG",				"Mallhanterare");
DEFINE("_RSGALLERY_RSG_NAME",				"RSGallery2");
DEFINE("_RSGALLERY_TEMP_PREV",				"F�rhandsgranska mall");
//function consolidateDbGo
DEFINE("_RSGALLERY_CONSDB_IN_DB",			"I<br>databasen");
DEFINE("_RSGALLERY_CONSDB_DISP",			"Visa<br>mapp");
DEFINE("_RSGALLERY_CONSDB_ORIG",			"Original<br>mapp");
DEFINE("_RSGALLERY_CONSDB_THUMB",			"Miniatyr<br>mapp");
DEFINE("_RSGALLERY_CONSDB_ACT",				"�tg�rd");
DEFINE("_RSGALLERY_CONSDB_DELETE_DB",		"[&nbsp;Radera fr�n databas&nbsp;]");
DEFINE("_RSGALLERY_CONSDB_CREATE_IMG",		"[&nbsp;Skapa saknade bilder&nbsp;]");
DEFINE("_RSGALLERY_CONSDB_CREATE_DB",		"[&nbsp;Skapa databaspost&nbsp;]");
DEFINE("_RSGALLERY_CONSDB_DELETE_IMG",		"[&nbsp;Radera bilder&nbsp;]");
DEFINE("_RSGALLERY_CONSDB_NO_INCOS",		"Ingen inkonsistens i databasen");
DEFINE("_RSGALLERY_CONSDB_NOTICE",	"&nbsp;<span style='text-size: 14px;font-weight:bold;'>MEDDELANDE</span>:<br />Funktionen konsolidera databas �r till st�rsta delen fungerande. Funktionen 'Skapa databaspost' har ocks� lagts till.<br />M�rk v�kl att du hursomhelst inte kan l�gga till multipla poster till databasen. F�rn�rvarande m�ste du l�gga till dem en och en!");
DEFINE("_RSGALLERY_NOT_WORKING",		"Fungerar inte �n");
DEFINE("_RSGALLERY_DEL_FROM_SYSTEM",		"Radera fr�n filsystem");
DEFINE("_RSGALLERY_CREATE_MISSING_IMG",		"Skapa saknade bilder");
DEFINE("_RSGALLERY_CREATE_DB_ENTRIES",		"Skapa databasposter");


//admin.rsgallery2.php
DEFINE("_RSGALLERY_HEAD_CONFIG",		"Konfiguration");
DEFINE("_RSGALLERY_HEAD_CPANEL",		"Kontrollpanel");
DEFINE("_RSGALLERY_HEAD_EDIT",		"Redigera");
DEFINE("_RSGALLERY_HEAD_UPLOAD",		"Ladda upp");
DEFINE("_RSGALLERY_HEAD_MIGRATE",		"Installera och Migrera");
DEFINE("_RSGALLERY_HEAD_UPLOAD_ZIP",		"Ladda upp ZIP-fil");
DEFINE("_RSGALLERY_HEAD_CONSDB",		"Konsolidera databas");
DEFINE("_RSGALLERY_HEAD_LOG",		"�ndringslogg");
DEFINE("_RSGALLERY_HEAD_CONF_VARIA",		"Konfiguration - Variabler");
DEFINE("_RSGALLERY_HEAD_CONF_RAW_EDIT",		"Konfiguration direktredigering");
DEFINE("_RSGALLERY_HEAD_MISS_IMG_CREATE",	"Saknade bilder skapade");
//function config_rawEdit_save
DEFINE("_RSGALLERY_CONF_SAVED",				"Konfigurationen sparad");
DEFINE("_RSGALLERY_CONF_SAVE_ERROR",		"Det gick inte att spara konfigurationen");
DEFINE("_RSGALLERY_CONF_CREATE_DIR",		"Funktionen f�r att skapa bildkataloger �r inte implementerad �n.");
//function RSInstall
DEFINE("_RSGALLERY_MIGR_OK",				"migrering klar");
//function purgeEverything
DEFINE("_RSGALLERY_PURGE_IMG",				"bildposter rensade i databasen.");
DEFINE("_RSGALLERY_PURGE_GAL",				"album rensade i databasen.");
DEFINE("_RSGALLERY_PURGE_CONFIG",			"konfigurationen rensad i databasen.");
DEFINE("_RSGALLERY_PURGE_COMMENTS",			"kommentarer rensade i databasen.");
//function reallyUninstall
DEFINE("_RSGALLERY_REAL_UNINST_DIR",		"Anv�nde rm -r f�r att f�rs�ka radera JPATH_SITE/images/rsgallery");
DEFINE("_RSGALLERY_REAL_UNINST_DROP_FILES",	"Tog bort #__rsgallery2_files");
DEFINE("_RSGALLERY_REAL_UNINST_DROP_GAL",	"Tog bort #__rsgallery2_galleries");
DEFINE("_RSGALLERY_REAL_UNINST_DROP_CONF",	"Tog bort #__rsgallery2_config");
DEFINE("_RSGALLERY_REAL_UNINST_DROP_COM",	"Tog bort #__rsgallery2_comments");
DEFINE("_RSGALLERY_REAL_UNINST_DONE",		"Klar. Tag manuellt bort allt ovan om det uppstod n�got fel. Avinstallera RSGallery2 NU, annars kommer det att uppst� fel.");
//function deleteImage
DEFINE("_RSGALLERY_ALERT_IMGDELETEOK",		"Bild(er) har tagits bort!");
//function c_delete
DEFINE("_RSGALLERY_ALERT_IMGDELETENOTOK",	"Bild(er) togs inte bort!");
//function save_batchupload
DEFINE("_RSGALLERY_ALERT_NOCATSELECTED",	"V.g. v�lj album f�r ALLA bilder!\\n(�ven f�r bilder som kommer att tas bort.\\nnDetta kommer fixas i en framtida version!)");
DEFINE("_RSGALLERY_ZIP_TO_BIG",				"ZIP-filen �r f�r stor!");
//function myPreExtractCallBack
DEFINE("_RSGALLERY_NOT_ALLOWED_FILETYPE",	"�r inte en till�ten filtyp, den kommer att tas bort!");
DEFINE("_RSGALLERY_BATCH_ERROR_FTP1",		"Katalogen du har valt finns inte.\\n �terg�r till uppladdningssidan.");
//function showUpload
DEFINE("_RSGALLEY_ALERT_REST_UPLOADOK",		"resten av filerna har laddats upp utan problem");
//function saveImage
DEFINE("_RSGALLERY_ALERT_IMAGEDETAILSOK",	"Uppgifterna har uppdaterats!");
DEFINE("_RSGALLERY_ALERT_IMAGEDETAILSNOTOK","Uppgifterna uppdaterades inte!");
//function showConfig
DEFINE("_RSGALLERY_CONF_NOGD2",				"GD2 hittades inte");
DEFINE("_RSAGALLERY_CONF_NOIMGMAGICK",		"ImageMagick hittades inte");
DEFINE("_RSAGALLERY_CONF_NONETPBM",			"netPBM hittades inte");
//function viewImages
DEFINE("_RSGALLERY_VIEW_GAL",				"Visa album");
DEFINE("_RSGALLERY_ALL_GAL",				"- Alla album");
DEFINE("_RSGALLERY_SELECT_GAL",				"V�lj album");
//function consolidateDbInform
DEFINE("_RSGALLERY_CONSOLIDATE_DB",
" Funktionen 'Konsolidera Databas' utf�r en kontroll av RSGallerys databastabeller och bildfiler i albumkatalogerna,".
" och genererar en rapport �ver de eventuella avvikelser som uppt�ckts. Anv�ndaren kommer d� att kunna v�lja mellan".
" att l�gga till eller ta bort databasposter eller fysiska bildfiler f�r att bibeh�lla konsistenta album.".
" <br/><br/>Denna funktion ska ocks� anv�ndas om man l�gger till eller tar bort filer direkt i de kataloger som".
" inneh�ller albumen.  Exempel: En anv�ndare kan via FTP ladda upp bildfiler till albumets underkatalog".
" och d�refter anropa den h�r funktionen f�r att uppdatera databasen.<br/><br/>V.g. v�lj 'Forts�tt' eller 'Avbryt' nedan.  Inga".
" �ndringar kommer att ske f�rr�n anv�ndaren har bekr�ftat dem.<br/>");
DEFINE("_RSGALLERY_PROCEED",				"Forts�tt");
//function editTemplateCSS
DEFINE("_RSGALLERY_EDITCSS_FAIL_NOOPEN",	"�tg�rden avbr�ts: Kan inte �ppna");
DEFINE("_RSGALLERY_EDITCSS_NOT_WRITABLE",	"�tg�rden avbr�ts: Filen �r inte skrivbar.");
DEFINE("_RSGALLERY_EDITCSS_FAIL_NOTWRITING","�tg�rden avbr�ts: Kan inte �ppna filen f�r skrivning.");
//function editCSSSource
DEFINE("_RSGALLERY_EDITCSS_TITLE",			"RSgallery2 CSS Editor");
DEFINE("_RSGALLERY_ISWRITABLE",				"RSgallery2.css �r :");
DEFINE("_RSGALLERY_ISWRITABLE_WRITABLE",	"Skrivbar");
DEFINE("_RSGALLERY_ISWRITABLE_UNWRITABLE",	"Skrivskyddad");
DEFINE("_RSGALLERY_MAKE_WRITABLE",			"S�tt skrivskydd n�r den sparats");
DEFINE("_RSGALLERY_OVERWRITE_WRITABLE",		"�sidos�tt skrivskydd medan den sparas");

//config.rsgallery2.php
//function galleriesSelectList
DEFINE("_RSGALLERY_SELECT_GAL_TOP",		"Topp");
//function newImages
DEFINE("_RSGALLERY_NEW",				"Nytt!");
//function writeWarningBox
DEFINE("_RSGALLERY_NO_IMGLIBRARY",			"Inget tillg�ngligt 'image library' kunde hittas! V.g. kontakta ditt webhotell f�r att installera GD2 eller s�k p� olika forum hur man installerar antingen ImageMagick eller NETPBM!");
DEFINE("_RSGALLERY_NOT_WRITABLE",			" �r INTE skrivbar!");
DEFINE("_RSGALLERY_FOLDER_NOTEXIST",		" finns INTE! V.g. skapa den h�r katalogen och se till att du g�r en CHMOD p� den till 0755!");
DEFINE("_RSGALLERY_ERROR_SETTINGS",			"F�ljande inst�llningar hindrar RSGallery2 fr�n att fungera felfritt:");
DEFINE("_RSGALLERY_REFRESH",				"Uppdatera");
//function writeDownloadLink
DEFINE("_RSGALLERY_DOWNLOAD",				"Ladda ner");

//install.rsgallery2.php
//function com_install
DEFINE("_RSGALLERY_MIGRATING_FROM",			"Migrering from RSGallery2 ");
DEFINE("_RSGALLERY_INSTALL_SUCCESS",		"Klart.  Nu anv�nds RSGallery2 ");
DEFINE("_RSGALLERY_INSTALL_FAIL",			"Fel: ");

//toolbar.rsgallery2.html.php
DEFINE("_RSGALLERY_TOOL_CLOSE",				"St�ng");
DEFINE("_RSGALLERY_TOOL_PANEL",				"CPanel");
DEFINE("_RSGALLERY_TOOL_GAL",				"Album");
DEFINE("_RSGALLERY_TOOL_IMG",				"bilder");
DEFINE("_RSGALLERY_TOOL_UP",				"Ladda upp");
DEFINE("_RSGALLERY_TOOL_NEXT",				"N�sta");
DEFINE("_RSGALLERY_TOOL_DELETE",			"Radera");

//config.html
DEFINE("_RSGALLERY_C_TMPL_VERSION",			"Version:");
DEFINE("_RSGALLERY_C_TMPL_INTRO_TEXT",		"Introducerande text:");
DEFINE("_RSGALLERY_C_TMPL_DEBUG",			"Debug:");
DEFINE("_RSGALLERY_C_TMPL_IMG_MANIP",		"Bildbehandling");
DEFINE("_RSGALLERY_C_TMPL_DISP_WIDTH",		"Bildstorlek (bredd):");
DEFINE("_RSGALLERY_C_TMPL_THUMB_WIDTH",		"Miniatyrstorlek (bredd):");
DEFINE("_RSGALLERY_C_TMPL_THUMBNAIL_STYLE",		"Miniatyrstil:");
DEFINE("_RSGALLERY_C_TMPL_JPEG_QUALITY",	"JPEG kvalitet i procent");
DEFINE("_RSGALLERY_C_TMPL_GRAPH_LIB",		"Graphics Library");
DEFINE("_RSGALLERY_C_TMPL_NOTE_GLIB_PATH",	"Note:</span> L�mna f�ljande f�lt tomma s�vida du inte har problem.");
DEFINE("_RSGALLERY_C_TMPL_IMGMAGICK_PATH",	"S�kv�g till ImageMagick:");
DEFINE("_RSGALLERY_C_TMPL_NETPBM_PATH",		"S�kv�g till Netpbm:");
DEFINE("_RSGALLERY_C_TMPL_FTP_PATH",		"FTP s�kv�g:");
DEFINE("_RSGALLERY_C_TMPL_IMG_STORAGE",		"Lagringskataloger");
DEFINE("_RSGALLERY_C_TMPL_KEEP_ORIG",		"Spara originalbild:");
DEFINE("_RSGALLERY_C_TMPL_ORIG_PATH",		"S�kv�g till originalbild:");
DEFINE("_RSGALLERY_C_TMPL_DISP_PATH",		"S�kv�g till visningsbild:");
DEFINE("_RSGALLERY_C_TMPL_THUMB_PATH",		"S�kv�g till miniatyrer:");
DEFINE("_RSGALLERY_C_TMPL_CREATE_DIR",		"Skapa kataloger om de inte redan finns:");
DEFINE("_RSGALLERY_C_TMPL_FRONT_PAGE",		"Huvudsida");
DEFINE("_RSGALLERY_C_TMPL_DISP_RAND",		"Visa slumpm�ssig");
DEFINE("_RSGALLERY_C_TMPL_DISP_LATEST",		"Visa senaste");
DEFINE("_RSGALLERY_C_TMPL_DISP_BRAND",		"Visa RSGallery logga");
DEFINE("_RSGALLERY_C_TMPL_DISP_DOWN",		"Visa nerladdningsl�nk");
DEFINE("_RSGALLERY_C_TMPL_WATERMARK",		"Bildm�rkning ");
DEFINE("_RSGALLERY_C_TMPL_DISP_WTRMRK",		"Visa vattenst�mpel");
DEFINE("_RSGALLERY_C_TMPL_WTRMRK_TEXT",		"Text i vattenst�mpel");
DEFINE("_RSGALLERY_C_TMPL_WTRMRK_FONTSIZE",	"Teckenstorlek");
DEFINE("_RSGALLERY_C_TMPL_WTRMRK_ANGLE",	"Vrid vattenst�mpel");
DEFINE("_RSGALLERY_C_TMPL_WTRMRK_POS",		"Placering av vattenst�mpel");
DEFINE("_RSGALLERY_C_TMPL_GAL_VIEW",		"Albumvisning");
DEFINE("_RSGALLERY_C_TMPL_THUMB_STYLE",		"Thumbnail Style:<br>Anv�nd 'flytande' f�r mallar med variabel bredd.");
DEFINE("_RSGALLERY_C_TMPL_FLOATDIRECTION",	"Riktning (fungerar bara f�r 'flytande'):");
DEFINE("_RSGALLERY_C_TMPL_COLS_PERPAGE",	"Antal kolumner f�r miniatyrer (bara f�r tabell):");
DEFINE("_RSGALLERY_C_TMPL_THUMBS_PERPAGE",	"Miniatyrer per sida:");
DEFINE("_RSGALLERY_C_TMPL_DISP_SLIDE",		"Visa bildspel");
DEFINE("_RSGALLERY_C_TMPL_IMG_DISP",		"Bildvisning");
DEFINE("_RSGALLERY_C_TMPL_RESIZE_OPT",		"Storleksalternativ");
DEFINE("_RSGALLERY_C_TMPL_DISP_DESCR",		"Visa beskrivning");
DEFINE("_RSGALLERY_C_TMPL_DISP_HITS",		"Visa antal tr�ffar");
DEFINE("_RSGALLERY_C_TMPL_DISP_VOTE",		"Visa betyg");
DEFINE("_RSGALLERY_C_TMPL_DISP_COMM",		"Visa kommentarer");
DEFINE("_RSGALLERY_C_TMPL_DISP_EXIF",		"Visa EXIF data");
DEFINE("_RSGALLERY_C_TMPL_ENABLE_U_UP",		"Aktivera anv�ndaruppladdning?");
DEFINE("_RSGALLERY_C_TMPL_ONLY_REGISTERED",	"Endast registrerade anv�ndare");
DEFINE("_RSGALLERY_C_TMPL_U_CREATE_GAL",	"L�t anv�ndare skapa nya album?");
DEFINE("_RSGALLERY_C_TMPL_U_MAX_GAL",		"Max. antal album en anv�ndare kan ha:");
DEFINE("_RSGALLERY_C_TMPL_U_MAX_IMG",		"Max. antal bilder en anv�ndare kan ha:");
DEFINE("_RSGALLERY_C_TMPL_SHOW_IMGNAME",		"Visa bildnamn under miniatyr:");
DEFINE("_RSGALLERY_C_TMPL_ACL_SETINGS",		"Inst�llningar f�r accesskontroll");
DEFINE("_RSGALLERY_C_TMPL_ACL_ENABLE",		"Aktivera accesskontroll");
DEFINE("_RSGALLERY_C_TMPL_SHOW_MYGAL",		"Visa 'Mina album'");
DEFINE("_RSGALLERY_C_TMPL_USER_SET",		"Anv�ndarspecifika inst�llningar");
DEFINE("_RSGALLERY_C_DISP_STATUS_ICON",		"* Visa Statusikoner *");
DEFINE("_RSGALLERY_C_GEN_SET",			"Generella inst�llningar");
DEFINE("_RSGALLERY_C_HTML_ROOT",			"HTML-root �r");
DEFINE("_RSGALLERY_C_DISP_LIMIB",		"Visa galleriets begr�nsningsruta.");
DEFINE("_RSGALLERY_C_NUMB_GAL_FRONT",	"Antal gallerier p� startsidan som standard.");
DEFINE("_RSGALLERY_C_FONT",				"Typsnitt");
DEFINE("_RSGALLERY_C_WATER_TRANS",		"Vattenm�rkets genomskinlighet");
DEFINE("_RSGALLERY_C_ALLOWED_FILE",		"Till�tna filtyper");

//galleries.class.php
//function check
DEFINE("_RSGALLERY_GAL_EXIST_ERROR",		"Det finns redan ett album med det namnet, v.g. f�rs�k igen.");
//galleries.html.php
//function show
DEFINE("_RSGALLERY_GAL_MANAGE",			"Albumhantering");
DEFINE("_RSGALLERY_GAL_MAX_LEVELS",		"Max. antal niv�er");
DEFINE("_RSGALLERY_GAL_FILTER",			"Filter");
DEFINE("_RSGALLERY_GAL_NAME",			"Namn");
DEFINE("_RSGALLERY_GAL_REORDER",			"�ndra ordning");
DEFINE("_RSGALLERY_GAL_HITS",			"Tr�ffar");
//function edit
DEFINE("_RSGALLERY_GAL_GAL",				"Album");
DEFINE("_RSGALLERY_GAL_DETAILS",			"Detaljer");
DEFINE("_RSGALLERY_GAL_DESCR",			"Beskrivning");
DEFINE("_RSGALLERY_GAL_PARENT",			"Parent Item");
DEFINE("_RSGALLERY_GAL_THUMB",			"Albumminiatyr");
DEFINE("_RSGALLERY_GAL_ORDERING",		"Ordning");
DEFINE("_RSGALLERY_GAL_PUBLISHED",		"Publicerad");
DEFINE("_RSGALLERY_GAL_PARAMETERS",		"Parametrar");
DEFINE("_RSGALLERY_GAL_OWNER",	"�gare");
DEFINE("_RSGALLERY_GAL_PERMS",	"Tillst�nd");
DEFINE("_RSGALLERY_GAL_DEF_PERM_CREATE",	"Standardtillst�nd �r skapade. <br />Efter att du skapat ett galleri kan du komma tillbaka f�r att redigera galleritillst�nden.");
DEFINE("_RSGALLERY_GAL_NO_PERM_FOUND",	"Inga tillst�nd hittades f�r detta galleri. Klicka p� <strong>SPARA</strong> knappen i verktygsf�ltet ovan f�r att skapa standardtillst�nd. Efter det kan du komma tillbaka hit f�r att skapa tillst�nd.");
DEFINE("_RSGALLERY_GAL_USERTYPE",		"Anv�ndartyp");
DEFINE("_RSGALLERY_GAL_VIEW_GAL",		"Visa<br/>Galleri</span>");
DEFINE("_RSGALLERY_GAL_UPL_EDIT_IMG",	"Ladda upp/redigera<br/>bilder</span>");
DEFINE("_RSGALLERY_GAL_DEL_IMG",			"Radera bilder</span>");
DEFINE("_RSGALLERY_GAL_MOD_GAL",			"Modifiera<br/>Galleri</span>");
DEFINE("_RSGALLERY_GAL_DEL_GAL",			"Radera<br/>Galleri</span>");
DEFINE("_RSGALLERY_GAL_ACL_PUB",			"Publik</span>");
DEFINE("_RSGALLERY_GAL_ACL_REG",			"Registrerad</span>");
DEFINE("_RSGALLERY_GAL_SEL_DESEL_ALL",	"&nbsp;Markera/Avmarkera Allt");
DEFINE("_RSGALLERY_GAL_ORDER",	"Ordning");

//install.class.php
//function echo_values
DEFINE("_RSGALLERY_INSTALL_THUMBDIR",			"Miniatyrkatalog �r:");
//function changeMenuIcon
DEFINE("_RSGALLERY_INSTALL_MENU_ICON_OK",		"Menybilden RSGallery2 har �ndrats");
DEFINE("_RSGALLERY_INSTALL_MENU_ICON_ERROR",		"Menybilden kunde inte �ndras");
//function createDirStructure
DEFINE("_RSGALLERY_INSTALL_DIR_EXISTS",			" finns redan");
DEFINE("_RSGALLERY_ISNTALL_IS_CREATED",			" har skapats");
DEFINE("_RSGALLERY_INSTALL_NOT_CREATED",			" kunde inte skapas");
//function createTableStructure
DEFINE("_RSGALLERY_ISNTALL_DB_OK",				"Databastabeller har skapats.");
//function copyFiles
DEFINE("_RSGALLERY_INSTALL_FILE_COPY_FROM",		"Filen kopierad fr�n ");
DEFINE("_RSGALLERY_INSTALL_FILE_COPY_TO",		" till ");
DEFINE("_RSGALLERY_INSTALL_FILE_NOTCOPY_FROM",	"kan inte kopiera fil fr�n ");
DEFINE("_RSGALLERY_INSTALL_DIR_CREATED",			"Katalog skapad: ");
DEFINE("_RSGALLERY_INSTALL_DIR_NOTCREATED",		"kunde inte skapa katalog ");
//function deleteGalleryDir
DEFINE("_RSGALLERY_DELGAL_PROCES",				"Behandlar: ");
DEFINE("_RSGALLERY_DELGAL_OK",					"Katalogstruktur raderad!");
DEFINE("_RSGALLERY_DELGAL_NOTOK",				"Raderering av gammal katalogstruktur misslyckades.");
DEFINE("_RSGALLERY_DELGAL_NO_OLD_DIR",			"Ingen gammal katalogstruktur hittades. L�t oss forts�tta");
//function checkDirPerms
DEFINE("_RSGALLERY_PERMS_NOT_EXIST",				" Finns inte. V.g. skapa manuellt via FTP och kontrollera r�ttigheter");
DEFINE("_RSGALLERY_PERMS_NOT_SET",				" hittades, men korrekta r�ttigheter (777) kunde inte s�ttas.\nR�ttigheterna �r f.n. satta till ");
DEFINE("_RSGALLERY_PERMS_NOT_SET_TRY_FTP",		".<br />F�rs�k korrigera dessa r�ttigheter via FTP.");
DEFINE("_RSGALLERY_PERMS_OK",					" hittades, r�ttigheterna �r OK.");
//function installComplete
DEFINE("_RSGALLERY_INSTALL_COMPLETE",			"Installationen av RSGallery �r klar");
DEFINE("_RSGALLERY_INSTALL_STATUS_MSGS",			"Om det finns n�gra statusmeddelanden som kr�ver �tg�rd, ta hand om dessa nu, innan du �ppnar Kontrollpanelen.");
//function deleteTable
DEFINE("_RSGALLERY_TABLEDEL_OK",					" �r raderad");
DEFINE("_RSGALLERY_TABLEDEL_NOTOK",				" kunde inte raderas.<br />Radera manuellt.");
//function migrateOldFiles
DEFINE("_RSGALLERY_MIGRATE_NOT_ALL",			"Inte all filinformation blev migrerad till RSGallery2 databas, pga. ok�nda orsaker(");
DEFINE("_RSGALLERY_MIGRATE_OUT_OF",			" av ");
DEFINE("_RSGALLERY_MIGRATE_ENTRIES_OK",		" poster behandlades)");
DEFINE("_RSGALLERY_MIGRATE_ALL",				"All filinformation migrerad till RSGallery2 databas(");
//function migrateOldCats
DEFINE("_RSGALLERY_MIGRATE_NOT_ALL_GAL",		"Inte all albuminformation blev migrerad till RSGallery2 databas, pga. ok�nda orsaker(");
DEFINE("_RSGALLERY_MIGRATE_ALL_GAL",			"All albuminformation migrerad till RSGallery2 databas(");
DEFINE("_RSGALLERY_MIGRATE_ALL_FILES",		"Filerna har kopierats till den nya katalogstrukturen");
DEFINE("_RSGALLERY_MIGRATE_NOTALL_FILES",		"Det uppstod fel vid kopieringen till den nya katalogstrukturen");
DEFINE("_RSGALLERY_MIGRATE_ZOOM_OK",		"migrering av Zoom Gallery slutf�rd. G� till kontrollpanelen.");
//function upgradeInstall
DEFINE("_RSGALLERY_UPGRADE_RSG",		"Uppgradering fr�n RSGallery");
DEFINE("_RSGALLERY_UPGRADE_REC_FULL",		"Komponenten hittades, men det finns ingen versionsinformation tillg�nglig.<br />Fullinstallation rekommenderas.");
DEFINE("_RSGALLERY_UPGRADE_FILES_TRANF",		"Original filerna �r �verf�rda");
DEFINE("_RSGALLERY_UPGRADE_FILES_TRANF_ERROR",		"Det uppstod fel vid �verf�ringen av originalfilerna till den nya katalogstrukturen.\nV.g. kontrollera via FTP");
DEFINE("_RSGALLERY_UPGRADE_THUMB_TRANF",		"Miniatyrfilerna har �verf�rts");
DEFINE("_RSGALLERY_UPGRADE_THUMB_TRANF_ERROR",		"Det uppstod fel vid �verf�ringen av miniatyrfilerna till den nya katalogstrukturen.<br />V.g. kontrollera via FTP");
DEFINE("_RSGALLERY_UPGRADE_DISP_CREATE",		"Visningsbilderna har skapats.");
DEFINE("_RSGALLERY_UPGRADE_DISP_CREATE_ERROR",		"Kunde inte skapa vissa eller alla visningsbilder.<br />Kontakta utvecklarna av den h�r komponenten.");
DEFINE("_RSGALLERY_UPGRADE_TABLE",		"Tabell ");
DEFINE("_RSGALLERY_UPGRADE_TABLE_ALTER_OK",		" har �ndrats");
DEFINE("_RSGALLERY_UPGRADE_TABLE_ALTER_ERROR",		" har INTE �ndrats");
DEFINE("_RSGALLERY_UPGRADE_TABLE_RENAME_OK",		" har d�pts om");
DEFINE("_RSGALLERY_UPGRADE_TABLE_RENAME_ERROR",		"");
DEFINE("_RSGALLERY_UPGRADE_DUMMY_ERROR",		"Det gick inte att skapa dummytabeller. Avinstallation av RSGallery 2.0 beta 5 kanske inte kommer att fungera.");
DEFINE("_RSGALLERY_UPGRADE_NOT_POSSIBLE",		"Uppgradering �r inte m�jlig. Ingen uppgraderbar RSGallery hittades\nFull installation rekommenderas.");
DEFINE("_RSGALLERY_UPGRADE_SUCCESS",		"Uppgradering till RSGallery2 �r klar.\nDet �r nu s�kert att att avinstallera den gamla RSGallery");
//function showMigrationOptions
DEFINE("_RSGALLERY_MIGRATION",		"Migrering");
DEFINE("_RSGALLERY_MIGRATION_NO_SYSTEMS",		"Inget annat albumsystem �r installerat");
//function doMigration
DEFINE("_RSGALLERY_MIGRATION_NOT_VALID",		"�r inte en giltig typ f�r migrering.");

/*
* newly added language constants as of 20th of January 2007
* these will be moved to the correct location when most translations have been completed
* easiest would be for you to translate and place in the correct position in the file afterwards
* this way your file will already have the correct order
*/

//uninstall.rsgallery2.php
//function com_uninstall
DEFINE("_RSGALLERY_UNINSTALL_OK",		"Avinstallation genomf�rd");

//toolbar.rsgallery2.html.php
DEFINE("_RSGALLERY_TOOL_CONFIRM_DEL",		"Bekr�fta borttagning");

//slideshow.rsgallery2.php
DEFINE("_RSGALLERY_SLIDE_START",		"Start");
DEFINE("_RSGALLERY_SLIDE_STOP",			"Stopp");
DEFINE("_RSGALLERY_SLIDE_NEXT",			"N�sta");
DEFINE("_RSGALLERY_SLIDE_PREV",			"F�reg�ende");

//images.html.php
//function showImages
DEFINE("_RSGALLERY_IMG_IMG_MANAGE",			"Bildhanterare");
DEFINE("_RSGALLERY_IMG_FILTER",				"Filter:");
DEFINE("_RSGALLERY_IMG_TITLE",				"Titel (filnamn)");
DEFINE("_RSGALLERY_IMG_ORDER",				"Ordning");
DEFINE("_RSGALLERY_IMG_DATE_TIME",			"Datum & tid");
DEFINE("_RSGALLERY_IMG_EDIT_IMG",			"Redigera bilder");
//access.class.php
//function checkGallery
DEFINE("_RSGALLERY_ACL_NO_PERM_FOUND",			"Inga tillst�nd funna s� standradtillst�nd skapas. F�rs�k igen.");

//function editImage
DEFINE("_RSGALLERY_IMG_IMAGE",			"Bild");
DEFINE("_RSGALLERY_IMG_DETAILS",			"Detaljer");
DEFINE("_RSGALLERY_IMG_ORDERING",			"Sortering");
DEFINE("_RSGALLERY_IMG_IMG_PREV",			"F�rhandsgranska bild");
DEFINE("_RSGALLERY_IMG_PARAMETERS",			"Parametrar");
//function uploadImage
DEFINE("_RSGALLERY_IMG_SELECT_GAL",			"Du m�ste markera ett galleri.");/*javascript alert*/
DEFINE("_RSGALLERY_IMG_NO_FILE_SELECT",		"Inga filer markerades i ett eller flera f�lt.");/*javascript alert*/
DEFINE("_RSGALLERY_IMG_UPLOAD",			"Ladda upp");
DEFINE("_RSGALLERY_IMG_UPL_DETAILS",			"Uppladdningsdetaljer");
DEFINE("_RSGALLERY_IMG_UPL_GALLERY",			"Ladda upp Galleri");
DEFINE("_RSGALLERY_IMG_GEN_DESCR",			"Generell beskrivning");
DEFINE("_RSGALLERY_IMG_IMG_FILES",			"Bildfiler");
DEFINE("_RSGALLERY_IMG_IMAGES",			"Bilder");
DEFINE("_RSGALLERY_IMG_FILE",			"Fil");
DEFINE("_RSGALLERY_IMG_MORE",			"(fler bilder)");
//rsgallery2.php
//function my_galleries
DEFINE("_RSGALLERY_MYGAL_NOT_AUTH",		"Otill�tet �tkomstf�rs�k till 'Mina album'!");

//function save_image
DEFINE("_RSGALLERY_ERROR_SAVE",		"Fel: ");

//function viewChangelog
DEFINE("_RSGALLERY_FEAT_INDEBUG",		"Endast tillg�ngligt i Debug-mod.");

//rsgallery2.html.php
//RSShowPictures
DEFINE("_RSGALLERY_MAGIC_NOTIMP",		"Magic �r inte implementerat �n");

//showMyGalleries
DEFINE("_RSGALLERY_FEAT_NOTIMP",		"Funktionen �r inte implementerad �n");

//function myGalleries
DEFINE("_RSGALLERY_USERGAL_DISABLED",		"Anv�ndaralbumen har st�ngts av administrat�ren.");

//config.rsgallery2.php
//function toString
DEFINE("_RSGALLERY_CONF_ERROR_UPLOAD",		" - Fel vid uppladdning : ");
//function showCategories
DEFINE("_RSGALLERY_SELECT_GAL_DROP_BOX",		"- V�lj album -");

?>