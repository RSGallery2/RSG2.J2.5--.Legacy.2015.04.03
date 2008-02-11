<?php
/**
* Swedish languagefile for RSGallery
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* ï¿½versatt av Christer Tjernqvist 
* christer@kraftcirkeln.se - www.kraftcirkeln.se
* RSGallery is Free Software
**/
defined( '_VALID_MOS' ) or die( 'Restricted access' );

//Admin Control panel
DEFINE("_RSGALLERY_C_CONFIG",			"Konfiguration");
DEFINE("_RSGALLERY_C_UPLOAD",			"Ladda upp");
DEFINE("_RSGALLERY_C_UPLOAD_ZIP",		"Batch uppladdning");
DEFINE("_RSGALLERY_C_IMAGES",			"Hantera bilder");
DEFINE("_RSGALLERY_C_CATEGORIES",		"Hantera gallerier");
DEFINE("_RSGALLERY_C_DATABASE",			"Sammanfoga databas");
DEFINE("_RSGALLERY_C_CAPTIONS",			"Importera rubriker");
DEFINE("_RSGALLERY_C_PURGE",			"Rensa/ta bort allting");
DEFINE("_RSGALLERY_C_MIGRATION",		"Migration alternativ");

//Batch upload
DEFINE("_RSGALLERY_BATCH_METHOD",		"Specificera uppladdningsmetod");
DEFINE("_RSGALLERY_BATCH_METHOD_TIP",		"Välj om du vill ladda upp en enkel fil, a ZIP-arkiv eller en hel katalog");
DEFINE("_RSGALLERY_BATCH_ZIPFILE",		"ZIP-fil");
DEFINE("_RSGALLERY_BATCH_FTP_PATH",		"FTP-sökväg");
DEFINE("_RSGALLERY_BATCH_CATEGORY",		"Specificera kategori");
DEFINE("_RSGALLERY_BATCH_YES_IMAGES_IN",	"Ja, alla bilder i ");
DEFINE("_RSGALLERY_BATCH_NO_SPECIFY",		"Nej. Specificera per bild i steg 2");
DEFINE("_RSGALLERY_BATCH_NEXT",			"Nästa -->");
DEFINE("_RSGALLERY_BATCH_STEP1",		"Steg 1");
DEFINE("_RSGALLERY_BATCH_STEP2",		"Steg 2");
DEFINE("_RSGALLERY_BATCH_STEP3",		"Steg 3");
DEFINE("_RSGALLERY_BATCH_ERROR_SIZE",		"ZIP-filen är för stor för uppladdning. Din uppladdningsbegränsning (definierad i php.ini) är ");
DEFINE("_RSGALLERY_BATCH_ERROR_FTP1",		"Katalogen du valt existerar inte.\\n Du tas nu till uppladdningsskärmen");


//config screen
DEFINE("_RSGALLERY_ALLOWED_FILETYPES",		"Tillåtna filtyper");
DEFINE("_RSGALLERY_FILETYPES_DESCR",		"Ange filändelserna av tillåtna filtyper, separerade av komma(,).");
DEFINE("_RSGALLERY_THUMBPATH",			"Galleri miniatyr sökväg");



//Admin 
DEFINE("_RSGALLERY_REORDER",			"Sortera om");
DEFINE("_RSGALLERY_ADMIN_TITLE",		"RSGallery administratör");
DEFINE("_RSGALLERY_VERSION_LONG",		"RSGallery2 1.10.3 Alpha");
DEFINE("_RSGALLERY_VERSION_SHORT",     	        "1.10.3a");
DEFINE("_RSGALLERY_PROP_TITLE", 		"Ändra bildegenskaper");
DEFINE("_RSGALLERY_TITLE",			"Titel");
DEFINE("_RSGALLERY_FILENAME",			"Filnamn");
DEFINE("_RSGALLERY_DESCR",			"Beskrivning");
DEFINE("_RSGALLERY_NODESCR",			"Ingen beskrivning");
DEFINE("_RSGALLERY_VERYGOOD",			"&nbsp;Väldigt bra&nbsp;");
DEFINE("_RSGALLERY_GOOD",			"&nbsp;Bra&nbsp;");
DEFINE("_RSGALLERY_OK",			        "&nbsp;Ok&nbsp;");
DEFINE("_RSGALLERY_BAD",			"&nbsp;Kass&nbsp;");
DEFINE("_RSGALLERY_VERYBAD",			"&nbsp;Skitkass&nbsp;");
DEFINE("_RSGALLERY_NO_RATINGS",		 	"Ingen uppskattning");
DEFINE("_RSGALLERY_TAB1_CONFIG",		"Admin Konfiguration");
DEFINE("_RSGALLERY_TAB2_CONFIG",		"Utseende");
DEFINE("_RSGALLERY_TAB3_CONFIG",		"Stilmall");
DEFINE("_RSGALLERY_TAB4_CONFIG",		"Användaruppladdningar");
DEFINE("_RSGALLERY_THUMB_GEN",			"Miniatyrgenerator");
DEFINE("_RSGALLERY_THUMB_WIDTH",		"Miniatyrbredd");
DEFINE("_RSGALLERY_THUMB_QUAL",			"Miniatyrkvalitet");
DEFINE("_RSGALLERY_INLINE",			"Inline/popup");
DEFINE("_RSGALLERY_IMAGEPATH",			"Galleri underkatalog (under MOS root)");
DEFINE("_RSGALLERY_FTPPATH",      		"FTP Sökväg");
DEFINE("_RSGALLERY_AUTO_DETECTED",		"auto-upptäckt version: ");
DEFINE("_RSGALLERY_NETPBMPATH",			"NETPBM sökväg");
DEFINE("_RSGALLERY_IMAGEMAGICKPATH",		"ImageMagick sökväg");
DEFINE("_RSGALLERY_NUMCOLUMNS",			"Antal kolumner");
DEFINE("_RSGALLERY_NUMPICS",			"Antal bilder");
DEFINE("_RSGALLERY_PICWIDTH",			"Bredd på originalbild");
DEFINE("_RSGALLERY_RESIZEPIC",    		"Storleksalternativ");
DEFINE("_RSGALLERY_FRONTMENU",			"Frontmeny");
DEFINE("_RSGALLERY_INTROTEXT",			"Introtext");
DEFINE("_RSGALLERY_CATNAME",			"Gallerinamn");
DEFINE("_RSGALLERY_CATDESCR",			"Beskrivning");
DEFINE("_RSGALLERY_CATHITS",			"Träffar");
DEFINE("_RSGALLERY_CATPUBLISHED",		"Publicerad");
DEFINE("_RSGALLERY_NEWCAT",			"Nytt galleri");
DEFINE("_RSGALLERY_UPLOAD_TITLE",		"Ladda upp");
DEFINE("_RSGALLERY_UPLOAD_NUMBER",		"Antal uppladdningar");
DEFINE("_RSGALLERY_FORM_INGALLERY",		"I galleriet");
DEFINE("_RSGALLERY_PICK",			"Välj ett galleri");
DEFINE("_RSGALLERY_UPLOAD_FORM_TITLE",		"Titel");
DEFINE("_RSGALLERY_UPLOAD_FORM_FILE",		"Fil");
DEFINE("_RSGALLERY_UPLOAD_FORM_THUMB",		"Miniatyr");
DEFINE("_RSGALLERY_NUMDISPLAY",			"Visa #");
DEFINE("_RSGALLERY_SEARCH",			"Sök");
DEFINE("_RSGALLERY_IMAGENAME",			"Namn");
DEFINE("_RSGALLERY_IMAGEFILE",			"Filnamn");
DEFINE("_RSGALLERY_IMAGECAT",			"Galleri");
DEFINE("_RSGALLERY_IMAGEHITS",			"Träffar");
DEFINE("_RSGALLERY_IMAGEDATE",			"Uppladdningsdatum");
DEFINE("_RSGALLERY_DELETE",			"Ta bort");
DEFINE("_RSGALLERY_MOVETO",			"Flytta till");
DEFINE("_RSGALLERY_ID",			    	"ID");
DEFINE("_RSGALLERY_TAB_1",			"Beskrivning");
DEFINE("_RSGALLERY_TAB_2",			"Rösta");
DEFINE("_RSGALLERY_TAB_3",			"Kommentarer");
DEFINE("_RSGALLERY_TAB_4",			"EXIF-information");
DEFINE("_RSGALLERY_VOTES",			"Rösta");
DEFINE("_RSGALLERY_VOTES_NR",			"Röster");
DEFINE("_RSGALLERY_VOTES_AVG",			"Genomsnittlig röst");
DEFINE("_RSGALLERY_THANK_VOTING",		"Tack för att du röstade!");
DEFINE("_RSGALLERY_VOTING_FAILED",		"Din röst gick inte igenom.");
DEFINE("_RSGALLERY_RATING_NOTSELECT",		"Var snäll och välj värde");
DEFINE("_RSGALLERY_NO_COMMENTS",		"Inga kommentarer ännu.");
DEFINE("_RSGALLERY_COMMENT_ADD",		"Lägg till kommentar");
DEFINE("_RSGALLERY_COMMENT_NAME",   		"Ditt namn");
DEFINE("_RSGALLERY_COMMENT_TEXT",   		"Din kommentar");
DEFINE("_RSGALLERY_COMMENT_DATE",   		"Datum");
DEFINE("_RSGALLERY_COMMENT_BY",     		"Kommentar av");
DEFINE("_RSGALLERY_COMMENT_FIELD_CHECK",	"Var snäll och skriv in namn och/eller kommentar!");
DEFINE("_RSGALLERY_COMMENT_ADDED",		"Kommentar tillagd!");
DEFINE("_RSGALLERY_COMMENT_NOT_ADDED",		"Kommentar inte tillagd.");
DEFINE("_RSGALLERY_SHOWDETAILS", 		"Visa bilddetaljer");
DEFINE("_RSGALLERY_UPLOAD_BUTTON_DESC",		"Spara-knappen fungerar inte - använd denna istället.");
DEFINE("_RSGALLERY_YES",			"Yupp");
DEFINE("_RSGALLERY_NO",				"Nepp");

//admin.rsgallery.php
DEFINE("_RSGALLERY_ALERT_NOCAT",		"Inget galleri valt.\\nDu tas tillbaka till föregående skärm.");
DEFINE("_RSGALLERY_ALERT_NOWRITE",		"Upladdning misslyckades.\\nTillbaka till uppladdningen...");
DEFINE("_RSGALLERY_ALERT_WRONGFORMAT",		"Fel bildformat.\\nDu tas tillbaka till uppladdningen...");
DEFINE("_RSGALLERY_ALERT_UPLOADTHUMBOK",	"Miniatyr uppladdad!");
DEFINE("_RSGALLERY_ALERT_UPLOADOK",		"Bild uppladdad!");
DEFINE("_RSGALLERY_ALERT_IMAGEDETAILSOK",	"Detaljer uppdaterade!");
DEFINE("_RSGALLERY_ALERT_IMAGEDETAILSNOTOK",	"Detaljer INTE uppdaterade!");
DEFINE("_RSGALLERY_ALERT_CATDETAILSOK",		"Galleri detaljer uppdaterade!");
DEFINE("_RSGALLERY_ALERT_CATDETAILSNOTOK",	"Kunde inte uppdatera detaljer för galleriet!");
DEFINE("_RSGALLERY_ALERT_NEWCAT",		"Nytt galleri skapat!");
DEFINE("_RSGALLERY_ALERT_NONEWCAT",		"Galleri kunde inte skaps!\\nDu tas nu tillbaka till föregående skärm.");
DEFINE("_RSGALLERY_ALERT_CATDELOK",		"Galleri borttaget!");
DEFINE("_RSGALLERY_ALERT_CATDELNOTOK",		"Galleri kunde inte tas bort!");
DEFINE("_RSGALLERY_ALERT_IMGDELETEOK",		"Bild(/er) borttagna!");
DEFINE("_RSGALLERY_ALERT_NOCATSELECTED",	"Välj galleri för ALLA bilder!\\n(Även för bilder som ska tas bort).\\nnDetta kommer ändras i framtiden!)");//New!!!
DEFINE("_RSGALLERY_CANCEL",			"Avbryt");
DEFINE("_RSGALLERY_PROCEED",			"Fortsätt");
DEFINE("_RSGALLERY_CONSOLIDATE_DB",
"The 'Consolidate Database' function performs a check on the RSGallery database tables and the physical image".
" files in the gallery directory, and generates a report based on discrepancies found.  The user will then have".
" the option of adding or deleting database entries or physical image files to maintain consistency in the".
" galleries.<br/><br/>This function should also be run if any additions or deletions are done to any image files".
" contained within the gallery directory.  EG. A user can FTP additional image files into the gallery subdirectory".
" and then call this function to update the database.<br/><br/>Please chose 'Proceed' or 'Cancel' below.  No".
" changes will occur until the user confirms them.<br/>");


//rsgallery.php

DEFINE("_RSGALLERY_COMPONENT_TITLE",		"Galleri");
DEFINE("_RSGALLERY_GALLERY_PICK",		"Välj ett galleri");
DEFINE("_RSGALLERY_GALLERY_TEXT",		"Välj introtext här");
DEFINE("_RSGALLERY_NUMBEROFPICS",		"# bilder");
DEFINE("_RSGALLERY_BACKBUTTON",			"Tillbaka");
DEFINE("_RSGALLERY_NOIMG",			"Inga bilder i galleriet");
DEFINE("_RSGALLERY_IMGS",			" bild(/er) funna - ");
DEFINE("_RSGALLERY_PAGE",			" Sida ");
DEFINE("_RSGALLERY_OF",				" av ");
DEFINE("_RSGALLERY_MAX_USERCAT_ALERT",		"Max antal gallerier redan skapade. Du tas nu till huvudskärmen.");
DEFINE("_RSGALLERY_MAX_USERIMAGES_ALERT",	"Max antal bilder uppladdade. Ta bort lite bilder först.");
DEFINE("_RSGALLERY_NO_USERCATS",		"Användargallerier är inte tillåtna");


//slideshow.rsgallery.php
DEFINE("_RSGALLERY_SETSPEED",			"Ange hastighet först!");

//New
DEFINE("_RSGALLERY_NOT_LOGGED_IN_COMMENT",	"Logga in för att skriva kommentar.");
DEFINE("_RSGALLERY_NOT_LOGGED_IN_VOTE",		"Logga in för att rösta.");
DEFINE("_RSGALLERY_USERUPLOAD_TITLE",		"Användaruppladdning");
DEFINE("_RSGALLERY_USERUPLOAD_TEXT",
"Se till att du har skapat ett galleri. Din fil laddas automatiskt upp till servern och en miniatyr skapas.".
" Detta galleri är endast tillgängligt för dig, när du är inloggad. För att göra det publikt måste du ändra egenskaperna.".
" <br/>Du kan ladda upp enstaka filer".
" såväl som zip-filer.<br/><br/>");

DEFINE("_RSGALLERY_CATLEVEL",			"Huvudgalleri");
DEFINE("_RSGALLERY_SUBCAT",			"Underkategorier");
DEFINE("_RSGALLERY_RANDOM_TITLE",		"Slumpbilder");

DEFINE("_RSGALLERY_CREATED_BY",			"Galleri skapat av ");
DEFINE("_RSGALLERY_MAX_IMAGES",			"Max antal bilder per galleri");
DEFINE("_RSGALLERY_MAX_USERCAT",		"Max antal gallerier per användare");
DEFINE("_RSGALLERY_USERGAL_HEADER",		"Användargallerier");
DEFINE("_RSGALLERY_DELCAT_TEXT",		"Är du säker på att du vill ta bort detta galleri?\\nÄr det fortfarande bilder i galleriet kommer även dessa att tas bort.");
DEFINE("_RSGALLERY_USERCAT_NOTOWNER",		"Du är inte ägare av detta galleri.");
DEFINE("_RSGALLERY_USERCAT_SUBCATS",		"Detta galleri innehåller underkategorier och kan inte tas bort. Vill du ta bort galleriet får du först ta bort eller flytta underkategorierna.");
DEFINE("_RSGALLERY_USERIMAGE_NOTOWNER",		"Du äger inte denna bild.");

DEFINE("_RSGALLERY_USERCAT_HEADER",		"Användargallerier");
DEFINE("_RSGALLERY_USERCAT_NAME",		"Galleri namn");
DEFINE("_RSGALLERY_USERCAT_EDIT",		"Editera");
DEFINE("_RSGALLERY_USERCAT_DELETE",		"Ta bort");
DEFINE("_RSGALLERY_USERCAT_ACL",		"ACL");
DEFINE("_RSGALLERY_NEW_7DAYS",			"Nya under 7 dagar");
DEFINE("_RSGALLERY_NEW",			"NY!");
DEFINE("_RSGALLERY_DELIMAGE_TEXT",		"Är du säker på att du vill ta bort denna bild?");
DEFINE("_RSGALLERY_DELIMAGE_OK",		"Borttagen!");
DEFINE("_RSGALLERY_UPLOAD_ALERT_CAT",		"Du måste förse en kategori.");
DEFINE("_RSGALLERY_UPLOAD_ALERT_FILE",		"Du måste förse en bild att ladda upp.");
DEFINE("_RSGALLERY_UPLOAD_ALERT_TITLE",		"Du måste förse en titel för bilden.");
DEFINE("_RSGALLERY_MAKECAT_ALERT_NAME",		"Du måste förse ett kategorinamn.");
DEFINE("_RSGALLERY_MAKECAT_ALERT_DESCR",	"Du måste förse en beskrivning.");
DEFINE("_RSGALLERY_LATEST_TITLE",		"Senaste titlar");
DEFINE("_RSGALLERY_MAXWIDTHPOPUP",		"Maxbredd av popup");
DEFINE("_RSGALLERY_SHOWFULLDESC",		"Visa fulla beskrivningar.");

// Slideshow config.
DEFINE("_RSGALLERY_SLIDESHOW",			"Bildspel");

// New for beta2
DEFINE("_RSGALLERY_SAVE"		        ,"Spara");
DEFINE("_RSGALLERY_CREATE_GALLERY"		,"Skapa galleri");
DEFINE("_RSGALLERY_MY_IMAGES"			,"Mina bilder");
DEFINE("_RSGALLERY_ADD_IMAGE"			,"Lägg till bild");
DEFINE("_RSGALLERY_USER_PROPERTIES"		,"Användaregenskaper");
DEFINE("_RSGALLERY_USERNAME"			,"Namn");
DEFINE("_RSGALLERY_ACL_FOR"			,"Behörighetskontrollista för ");
DEFINE("_RSGALLERY_ACL_METHOD"			,"Behörighetskontrolltyp");

DEFINE("_RSGALLERY_ACL_REGISTERED"		,"Registrerad(1)");
DEFINE("_RSGALLERY_ACL_VISITORS"		,"Publik(2)");
DEFINE("_RSGALLERY_ACL_SELECTED"		,"Särskild(3)");

/********************
changed from hard coded 28th of april 2006 
please move to correct places after every laguage file has been modified
********************/

//Changed form _RSGALLERY_ACL_OWNER to _RSGALLERY_ACL_OWNER_ONLY
DEFINE("_RSGALLERY_ACL_OWNER_ONLY"		,"Enbart ägaren(0)");

//Added
DEFINE("_RSGALLERY_SLIDESHOW_EXIT",		"Avsluta bildspel");
DEFINE("_RSGALLERY_MY_GALLERIES",		"Mina gallerier");
DEFINE("_RSGALLERY_MAIN_GALLERY_PAGE",		"Huvudsida för galleri");
DEFINE("_RSGALLERY_ACL_CATEGORY_DETAILS",	"Behörighetskontroll");
DEFINE("_RSGALLERY_ACL_ATEGORY_DETAILS",	"Kategoridetaljer");
DEFINE("_RSGALLERY_ACL_CATEGORY_NAME",		"Kategorinamn");
DEFINE("_RSGALLERY_ACL_OWNER",			"Ägare");
DEFINE("_RSGALLERY_ACL_CURRENT_ACL",		"Nuvarande behörighetsnivå");
DEFINE("_RSGALLERY_EDIT_IMAGE",			"Editera bild");
DEFINE("_RSGALLERY_EDIT_FILENAME",		"Filnamn");
DEFINE("_RSGALLERY_EDIT_DESCRIPTION",		"Beskrivning");
DEFINE("_RSGALLERY_USERUPLOAD_CATEGORY",	"Kategori");
DEFINE("_RSGALLERY_UPLOAD_THUMB",		"Miniatyr:");
DEFINE("_RSGALLERY_SUB_GALLERIES",		"Under galleri:");
DEFINE("_RSGALLERY_USERGALLERIES",		"Användargalleri");
DEFINE("_RSGALLERY_WELCOME",			"Välkommen,");
DEFINE("_RSGALLERY_MY_IMAGES_NAME",		"Namn");
DEFINE("_RSGALLERY_MY_IMAGES_CATEGORY",		"Kategori");
DEFINE("_RSGALLERY_MY_IMAGES_EDIT",		"Editera");
DEFINE("_RSGALLERY_MY_IMAGES_DELETE",		"Ta bort");
DEFINE("_RSGALLERY_MY_IMAGES_PUBLISHED",	"Publicerad");
DEFINE("_RSGALLERY_MY_IMAGES_PERMISSIONS",	"Rättigheter");
DEFINE("_RSGALLERY_NOIMG_USER",			"Inga bilder i användarkategorier");
DEFINE("_RSGALLERY_USER_INFO",			"Användarinformation");
DEFINE("_RSGALLERY_USER_INFO_NAME",		"Användarnamn");
DEFINE("_RSGALLERY_USER_INFO_ACL",		"Användarnivå");
DEFINE("_RSGALLERY_USER_INFO_MAX_GAL",		"Max användargallerier");
DEFINE("_RSGALLERY_USER_INFO_MAX_IMG",		"Max bilder tillåtna");
DEFINE("_RSGALLERY_USER_INFO_UPL",		"uppladdade)");
DEFINE("_RSGALLERY_USER_MY_GAL",		"Mina gallerier");
DEFINE("_RSGALLERY_NO_USER_GAL",		"Inga användargallerier skapade");
DEFINE("_RSGALLERY_IMAGES",			" bilder");

?>
