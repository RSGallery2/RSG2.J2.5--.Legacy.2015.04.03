<?php
/**
* English languagefile for RSGallery
* @version $Id$
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

//Admin Control panel
DEFINE("_RSGALLERY_C_CONFIG",			"Konfigurācija");
DEFINE("_RSGALLERY_C_UPLOAD",			"Ielādēt");
DEFINE("_RSGALLERY_C_UPLOAD_ZIP",		"Ielādēt kopumu");
DEFINE("_RSGALLERY_C_IMAGES",			"Pārvaldīt bildes");
DEFINE("_RSGALLERY_C_CATEGORIES",		"Pārvaldīt galerijas");
DEFINE("_RSGALLERY_C_DATABASE",			"Apvienot datubāzes");
DEFINE("_RSGALLERY_C_CAPTIONS",			"Importa iespējas");
DEFINE("_RSGALLERY_C_PURGE",			"Dzēst visu");
DEFINE("_RSGALLERY_C_MIGRATION",			"Migrācijas iespējas");

//Batch upload
DEFINE("_RSGALLERY_BATCH_METHOD",			"Definēt ielādes metodi");
DEFINE("_RSGALLERY_BATCH_METHOD_TIP",		"Izvēlies vai tu vēlies ielādēt vienu failu, zip failu vai ielādēt esošu direktoriju");
DEFINE("_RSGALLERY_BATCH_ZIPFILE",			"ZIP-faili");
DEFINE("_RSGALLERY_BATCH_FTP_PATH",			"FTP-taka");
DEFINE("_RSGALLERY_BATCH_CATEGORY",			"Norādīt galerija");
DEFINE("_RSGALLERY_BATCH_YES_IMAGES_IN",	"Jā visas bildes iekšā ");
DEFINE("_RSGALLERY_BATCH_NO_SPECIFY",		"Nē, norādīt galeriju pēc bildes solī 2");
DEFINE("_RSGALLERY_BATCH_NEXT",				"Tālāk -->");
DEFINE("_RSGALLERY_BATCH_STEP1",			"Solis 1");
DEFINE("_RSGALLERY_BATCH_STEP2",			"Solis 2");
DEFINE("_RSGALLERY_BATCH_STEP3",			"Solis 3");
DEFINE("_RSGALLERY_BATCH_ERROR_SIZE",		"ZIP fails ir pārak liels. Ielādes lielums ir definēts php.ini failā!");
DEFINE("_RSGALLERY_BATCH_ERROR_FTP1",		"Izvēlētā direktorija neeksiste. \\n Tiks vēlreiz parādīta ielādešanas vieta.");


//config screen
DEFINE("_RSGALLERY_ALLOWED_FILETYPES",		"Atļautie failu tipi");
DEFINE("_RSGALLERY_FILETYPES_DESCR",		"Ieraksti paplašinājumus atbalstītajiem failiem. Atdali tos ar komatu(,).");
DEFINE("_RSGALLERY_THUMBPATH",			    "Galerijas taka");



//Admin 
DEFINE("_RSGALLERY_REORDER",			"Pār-sakārtot");
DEFINE("_RSGALLERY_ADMIN_TITLE",		"RSGalerijas administrator");
DEFINE("_RSGALLERY_VERSION_LONG",		"RSGallery2 1.10.3 Alpha");
DEFINE("_RSGALLERY_VERSION_SHORT",      "1.10.3a");
DEFINE("_RSGALLERY_PROP_TITLE",		"Rediģēt attēla uzstādījumus");
DEFINE("_RSGALLERY_TITLE",				"Virsraksts");
DEFINE("_RSGALLERY_FILENAME",			"Faila vārds");
DEFINE("_RSGALLERY_DESCR",				"Apraksts");
DEFINE("_RSGALLERY_NODESCR",			"Bez apraksta");
DEFINE("_RSGALLERY_VERYGOOD",			"&nbsp;Ļoti labi&nbsp;");
DEFINE("_RSGALLERY_GOOD",				"&nbsp;Labi&nbsp;");
DEFINE("_RSGALLERY_OK",			      "&nbsp;Ok&nbsp;");
DEFINE("_RSGALLERY_BAD",				"&nbsp;Slikti&nbsp;");
DEFINE("_RSGALLERY_VERYBAD",			"&nbsp;Ļoti slikti&nbsp;");
DEFINE("_RSGALLERY_NO_RATINGS",		"Nav vēl vērtējuma!");
DEFINE("_RSGALLERY_TAB1_CONFIG",	"Administratora konfigurācija");
DEFINE("_RSGALLERY_TAB2_CONFIG",	"Izskats");
DEFINE("_RSGALLERY_TAB3_CONFIG",	"Lapas stils");
DEFINE("_RSGALLERY_TAB4_CONFIG",	"Lietotāja ielādes");
DEFINE("_RSGALLERY_THUMB_GEN",		"Sīktēla veidotājs");
DEFINE("_RSGALLERY_THUMB_WIDTH",		"Sīktēla platums");
DEFINE("_RSGALLERY_THUMB_QUAL",		"Sīktēla kvalitāte");
DEFINE("_RSGALLERY_INLINE",			"Izlecošais logs");
DEFINE("_RSGALLERY_IMAGEPATH",		"Galerijas apakšdirektorija (zem root)");
DEFINE("_RSGALLERY_FTPPATH",      	"FTP taka");
DEFINE("_RSGALLERY_AUTO_DETECTED",	"automātiski atklāta  versija: ");
DEFINE("_RSGALLERY_NETPBMPATH",		"NETPBM taka");
DEFINE("_RSGALLERY_IMAGEMAGICKPATH","ImageMagick taka");
DEFINE("_RSGALLERY_NUMCOLUMNS",		"Kolonnu skaits");
DEFINE("_RSGALLERY_NUMPICS",			"Bilžu skaits");
DEFINE("_RSGALLERY_PICWIDTH",			"Platums pilnai bildei");
DEFINE("_RSGALLERY_RESIZEPIC",    	"Palielināšanas iespējas");
DEFINE("_RSGALLERY_FRONTMENU",		"Priekšējais Menu");
DEFINE("_RSGALLERY_INTROTEXT",		"Ievadteksts");
DEFINE("_RSGALLERY_CATNAME",			"Galerijas nosaukums");
DEFINE("_RSGALLERY_CATDESCR",			"Apraksts");
DEFINE("_RSGALLERY_CATHITS",			"Apmeklētāju daudzums");
DEFINE("_RSGALLERY_CATPUBLISHED",	"Publicēt");
DEFINE("_RSGALLERY_NEWCAT",			"Jauna galerija");
DEFINE("_RSGALLERY_UPLOAD_TITLE",	"Ielādēt");
DEFINE("_RSGALLERY_UPLOAD_NUMBER",	"Ielādes daudzums");
DEFINE("_RSGALLERY_FORM_INGALLERY",	"Galerijā");
DEFINE("_RSGALLERY_PICK",				"Izvēlēties galeriju");
DEFINE("_RSGALLERY_UPLOAD_FORM_TITLE","Virsraksts");
DEFINE("_RSGALLERY_UPLOAD_FORM_FILE","Fails");
DEFINE("_RSGALLERY_UPLOAD_FORM_THUMB","Sīktēla fails");
DEFINE("_RSGALLERY_NUMDISPLAY",		"Rādit #");
DEFINE("_RSGALLERY_SEARCH",			"Meklēt");
DEFINE("_RSGALLERY_IMAGENAME",		"Vārds");
DEFINE("_RSGALLERY_IMAGEFILE",		"Faila vārds");
DEFINE("_RSGALLERY_IMAGECAT",			"Galerija");
DEFINE("_RSGALLERY_IMAGEHITS",		"Skatīts");
DEFINE("_RSGALLERY_IMAGEDATE",		"Ielādes datums");
DEFINE("_RSGALLERY_DELETE",			"Dzēsts");
DEFINE("_RSGALLERY_MOVETO",			"Pārvilkt uz");
DEFINE("_RSGALLERY_ID",			    	"ID");
DEFINE("_RSGALLERY_TAB_1",			   "Apraksts");
DEFINE("_RSGALLERY_TAB_2",			   "Vērtēt");
DEFINE("_RSGALLERY_TAB_3",			   "Komentāri");
DEFINE("_RSGALLERY_TAB_4",			   "EXIF-informācija");
DEFINE("_RSGALLERY_VOTES",			   "Vērtēt");
DEFINE("_RSGALLERY_VOTES_NR",			"Balsis");
DEFINE("_RSGALLERY_VOTES_AVG",		"Vidējais balsojums");
DEFINE("_RSGALLERY_THANK_VOTING",	"Paldies par vērtējumu");
DEFINE("_RSGALLERY_VOTING_FAILED",	"Tavs vērtējums nav izdevies");
DEFINE("_RSGALLERY_RATING_NOTSELECT","Lūdzu izvēlies vērtību");
DEFINE("_RSGALLERY_NO_COMMENTS",		"Nav komentu vēl!");
DEFINE("_RSGALLERY_COMMENT_ADD",		"Pievienot komentāru");
DEFINE("_RSGALLERY_COMMENT_NAME",   "Jūsu vārds");
DEFINE("_RSGALLERY_COMMENT_TEXT",   "Jūsu komentārs");
DEFINE("_RSGALLERY_COMMENT_DATE",   "Datums");
DEFINE("_RSGALLERY_COMMENT_BY",     "Komentāru sniedzis");
DEFINE("_RSGALLERY_COMMENT_FIELD_CHECK","Lūdzu ieraksti vārdu vai komentāru!");
DEFINE("_RSGALLERY_COMMENT_ADDED",		"Komentārs ir veiksmīgi pievienots!");
DEFINE("_RSGALLERY_COMMENT_NOT_ADDED",	"Komentāri nav pielikti!");
DEFINE("_RSGALLERY_COMMENT_NOT_DELETED",	"Komentāru nevar dzēst");
DEFINE("_RSGALLERY_COMMENT_DELETED",	"Komentārs izdzēsts!");
DEFINE("_RSGALLERY_SHOWDETAILS", 		"Rādīt attēla detaļas");
DEFINE("_RSGALLERY_UPLOAD_BUTTON_DESC","Save poga nedarbojas, lūdzu lietot šo!");
DEFINE("_RSGALLERY_YES",					"Jā");
DEFINE("_RSGALLERY_NO",						"Nē");

//admin.rsgallery.php
DEFINE("_RSGALLERY_ALERT_NOCAT",			"Nav izvēlēta galerija!\\n Tiks piedāvata iepriekšējā atrašanās vieta.");
DEFINE("_RSGALLERY_ALERT_NOWRITE",			"Ielāde nav notikusi.\\n Atpakaļ pie ielādes vietas");
DEFINE("_RSGALLERY_ALERT_WRONGFORMAT",		"Nepareizs formāts.\\n Atpakaļ pie ielādes vietas");
DEFINE("_RSGALLERY_ALERT_UPLOADTHUMBOK",	"Sīktēls ielādēts veiksmīgi!");
DEFINE("_RSGALLERY_ALERT_UPLOADOK",			"Attēls veiksmīgi ielādēts");
DEFINE("_RSGALLERY_ALERT_IMAGEDETAILSOK",	"Detaļas veiksmīgi ielādētas!");
DEFINE("_RSGALLERY_ALERT_IMAGEDETAILSNOTOK","Detaļas nav atjaunotas!");
DEFINE("_RSGALLERY_ALERT_CATDETAILSOK",		"Galerijas detaļas atjaunotas!");
DEFINE("_RSGALLERY_ALERT_CATDETAILSNOTOK",	"Nevarēja atjaunot galerijas detaļas!");
DEFINE("_RSGALLERY_ALERT_NEWCAT",			"Jauna galerija ir izveidota!");
DEFINE("_RSGALLERY_ALERT_NONEWCAT",			"Galeriju nevarēja izveidot \\n Atpakaļ pie ielādes vietas");
DEFINE("_RSGALLERY_ALERT_CATDELOK",			"Galerija dzēsta!");
DEFINE("_RSGALLERY_ALERT_CATDELNOTOK",		"Galeriju nebija iespējams izdzēst!");
DEFINE("_RSGALLERY_ALERT_IMGDELETEOK",		"Bilde(s) veiksmīgi idzēstas!");
DEFINE("_RSGALLERY_ALERT_NOCATSELECTED",	"Lūdzu izvēlies galeriju visām bildēm!\\n (Arī bildēm, kas tiks dzēstas.\\nn Šī funkcija strādas nākamajā versijā!)");//New!!!
DEFINE("_RSGALLERY_CANCEL",					"Atlikt");
DEFINE("_RSGALLERY_PROCEED",				"Turpināt");
DEFINE("_RSGALLERY_CONSOLIDATE_DB",
"The 'Consolidate Database' function performs a check on the RSGallery database tables and the physical image".
" files in the gallery directory, and generates a report based on discrepancies found.  The user will then have".
" the option of adding or deleting database entries or physical image files to maintain consistency in the".
" galleries.<br/><br/>This function should also be run if any additions or deletions are done to any image files".
" contained within the gallery directory.  EG. A user can FTP additional image files into the gallery subdirectory".
" and then call this function to update the database.<br/><br/>Please chose 'Proceed' or 'Cancel' below.  No".
" changes will occur until the user confirms them.<br/>");


//rsgallery.php

DEFINE("_RSGALLERY_COMPONENT_TITLE",		"Galerija");
DEFINE("_RSGALLERY_GALLERY_PICK",			"Izvēlēties galeriju");
DEFINE("_RSGALLERY_GALLERY_TEXT",			"Izvēlies ievadtekstu");
DEFINE("_RSGALLERY_NUMBEROFPICS",			"# izvēlēti");
DEFINE("_RSGALLERY_BACKBUTTON",				"Atpakaļ");
DEFINE("_RSGALLERY_NOIMG",					"Galerijā nav bilžu");
DEFINE("_RSGALLERY_IMGS",					" atrastās bildes(e) - ");
DEFINE("_RSGALLERY_PAGE",					" Lapa ");
DEFINE("_RSGALLERY_OF",						" no ");
DEFINE("_RSGALLERY_MAX_USERCAT_ALERT"		,"Maksimālais galeriju skaits ir sasniegts. Atpakaļ pie ielādes vietas");
DEFINE("_RSGALLERY_MAX_USERIMAGES_ALERT"    ,"Maksimālais galeriju skaits ir sasniegts. Izdzēs bildes vispirms.");
DEFINE("_RSGALLERY_NO_USERCATS"             ,"Lietotāja galerijas ir nepieejamas");


//slideshow.rsgallery.php
DEFINE("_RSGALLERY_SETSPEED"					,"Izvēlies ātrumu vispirms!");

//New
DEFINE("_RSGALLERY_NOT_LOGGED_IN_COMMENT"	,"Ielogojies, lai pievienotu komenāru.");
DEFINE("_RSGALLERY_NOT_LOGGED_IN_VOTE"		,"Ielogojies, lai balsotu.");
DEFINE("_RSGALLERY_USERUPLOAD_TITLE"		,"Ielāde lietotājam");
DEFINE("_RSGALLERY_USERUPLOAD_TEXT",
"Vispirms pārliecinie vai ir izveidota galerija. Tavs fails tiks ielādēts serverī.".
" Šī galerija ir pieejama tikai tev, kad esi ielogojies. Lai to padaritu publisku, nomaini uzstādījumus.".
" <br/>Tu vari ielādēt failus".
" kā arī ZIP failus.<br/><br/>");


DEFINE("_RSGALLERY_CATLEVEL"					,"Galvenā galerija");
DEFINE("_RSGALLERY_SUBCAT"						,"Apakškategorijas");
DEFINE("_RSGALLERY_RANDOM_TITLE"				,"Random attēli");

DEFINE("_RSGALLERY_CREATED_BY"				,"Galeriju izveidoja ");
DEFINE("_RSGALLERY_MAX_IMAGES"				,"Maksimālais bilžu daudzums galerijā");
DEFINE("_RSGALLERY_MAX_USERCAT"				,"Maksmimalais galeriju daudzums lietotājam");
DEFINE("_RSGALLERY_USERGAL_HEADER"			,"Lietotāju galerijas");
DEFINE("_RSGALLERY_DELCAT_TEXT"				,"Vai tu esi pārliecināts, ka vēlies dzēst galeriju?\\nJa galerija satur attēlus, arī tie tiks dzēsti.");
DEFINE("_RSGALLERY_USERCAT_NOTOWNER"		,"Tu neesi galerijas īpašnieks\\n, atpakaļ uz galveno daļu");
DEFINE("_RSGALLERY_USERCAT_SUBCATS"         ,"Šī galerija satur apakškategorijas un nevar tikt dzēsta. Ja tu vēlies šo galeriju dzēst, vispirms izdzēs apakškategorijas.");
DEFINE("_RSGALLERY_USERIMAGE_NOTOWNER"		,"Tu neesi bildes īpašnieks");

DEFINE("_RSGALLERY_USERCAT_HEADER"			,"Lietotāju galerijas");
DEFINE("_RSGALLERY_USERCAT_NAME"			,"Galerijas nosaukums");
DEFINE("_RSGALLERY_USERCAT_EDIT"			,"Rediģēt");
DEFINE("_RSGALLERY_USERCAT_DELETE"			,"Dzēst");
DEFINE("_RSGALLERY_USERCAT_ACL"				,"ACL");
DEFINE("_RSGALLERY_NEW_7DAYS"				,"Jaunākais 7 pēdējās dienās");
DEFINE("_RSGALLERY_NEW"						,"Jaunākās");
DEFINE("_RSGALLERY_DELIMAGE_TEXT"			,"Vai tu esi pārliecināts(a), ka vēlies dzēst bildi?");
DEFINE("_RSGALLERY_DELIMAGE_OK"				,"Bilde dzēsta");
DEFINE("_RSGALLERY_UPLOAD_ALERT_CAT"		,"Izvēlies galeriju.");
DEFINE("_RSGALLERY_UPLOAD_ALERT_FILE"		,"Izvēlies failu, ko ielādēt.");
DEFINE("_RSGALLERY_UPLOAD_ALERT_TITLE"		,"Izvēlies nosaukumu bildēm, kas tiks ielādētas.");
DEFINE("_RSGALLERY_MAKECAT_ALERT_NAME"		,"Izvēlies galerijas nosaukumu.");
DEFINE("_RSGALLERY_MAKECAT_ALERT_DESCR"		,"Galerijas apraksts.");
DEFINE("_RSGALLERY_LATEST_TITLE"			,"Pēdējās bildes");
DEFINE("_RSGALLERY_MAXWIDTHPOPUP"			,"Iznirstoša loga platums");
DEFINE("_RSGALLERY_SHOWFULLDESC"			,"Rādit pilnu aprakstu, kad galerija atvērta.");

// Slideshow config.
DEFINE("_RSGALLERY_SLIDESHOW"		        	,"Bilžu filma");

// New for beta2
DEFINE("_RSGALLERY_SAVE"		        ,"Saglabāt");
DEFINE("_RSGALLERY_CREATE_GALLERY"		,"Izveidot galeriju");
DEFINE("_RSGALLERY_MY_IMAGES"			,"Manas bildes");
DEFINE("_RSGALLERY_ADD_IMAGE"			,"Pievienot bildi");
DEFINE("_RSGALLERY_USER_PROPERTIES"		,"Lietotāja uzstādījumi");
DEFINE("_RSGALLERY_USERNAME"			,"Vārds");
DEFINE("_RSGALLERY_ACL_FOR"				,"Piekļuves kontroles lapa ");
DEFINE("_RSGALLERY_ACL_METHOD"			,"Piekļuves kontroles tips");

DEFINE("_RSGALLERY_ACL_REGISTERED"		,"Reģistrēti(1)");
DEFINE("_RSGALLERY_ACL_VISITORS"		,"Publiski(2)");
DEFINE("_RSGALLERY_ACL_SELECTED"		,"Dažadi(3)");

/********************
changed from hard coded 28th of april 2006 
please move to correct places after every laguage file has been modified
********************/

//Changed form _RSGALLERY_ACL_OWNER to _RSGALLERY_ACL_OWNER_ONLY
DEFINE("_RSGALLERY_ACL_OWNER_ONLY"		,"Owner only(0)");

//Added
DEFINE("_RSGALLERY_SLIDESHOW_EXIT",			"Iziet no bilžu filmas");
DEFINE("_RSGALLERY_MY_GALLERIES",			"Manas galerijas");
DEFINE("_RSGALLERY_MAIN_GALLERY_PAGE",		"Galeriju lapa");
DEFINE("_RSGALLERY_ACL_CATEGORY_DETAILS",	"Piekļuves kontrole");
DEFINE("_RSGALLERY_ACL_ATEGORY_DETAILS",	"Kategorijas detaļas");
DEFINE("_RSGALLERY_ACL_CATEGORY_NAME",		"Kategorijas vārds");
DEFINE("_RSGALLERY_ACL_OWNER",				"Īpašnieks");
DEFINE("_RSGALLERY_ACL_CURRENT_ACL",		"Piekļuves līmenis");
DEFINE("_RSGALLERY_EDIT_IMAGE",				"Rediģēt bildi");
DEFINE("_RSGALLERY_EDIT_FILENAME",			"Faila vārds");
DEFINE("_RSGALLERY_EDIT_TITLE",			    "Nosaukums");
DEFINE("_RSGALLERY_EDIT_DESCRIPTION",		"Apraksts");
DEFINE("_RSGALLERY_SAVE_SUCCESS",		    "Detaļas veiksmīgi saglabātas");
DEFINE("_RSGALLERY_USERUPLOAD_CATEGORY",	"Galerija");
DEFINE("_RSGALLERY_UPLOAD_THUMB",			"Sīkbildes");
DEFINE("_RSGALLERY_SUB_GALLERIES",			"Apakš-galerijas:");
DEFINE("_RSGALLERY_USERGALLERIES",			"Lietotāja - galerijas");
DEFINE("_RSGALLERY_WELCOME",				"Sveicināts,");
DEFINE("_RSGALLERY_MY_IMAGES_NAME",			"Vārds");
DEFINE("_RSGALLERY_MY_IMAGES_CATEGORY",		"Galerija");
DEFINE("_RSGALLERY_MY_IMAGES_EDIT",			"Rediģēt");
DEFINE("_RSGALLERY_MY_IMAGES_DELETE",		"Dzēst");
DEFINE("_RSGALLERY_MY_IMAGES_PUBLISHED",	"Publiskot");
DEFINE("_RSGALLERY_MY_IMAGES_PERMISSIONS",	"Atļaujas");
DEFINE("_RSGALLERY_NOIMG_USER",				"Nav attēlu lietotāju galerijās");
DEFINE("_RSGALLERY_USER_INFO",				"Lietotāja informācija");
DEFINE("_RSGALLERY_USER_INFO_NAME",			"Lietotājvārds");
DEFINE("_RSGALLERY_USER_INFO_ACL",			"Lietotāja līmenis");
DEFINE("_RSGALLERY_USER_INFO_MAX_GAL",		"Maksimums lietotāj-galerijas");
DEFINE("_RSGALLERY_USER_INFO_MAX_IMG",		"Maksimālais bilžu daudzums");
DEFINE("_RSGALLERY_USER_INFO_UPL",			"ielādēts)");
DEFINE("_RSGALLERY_USER_MY_GAL",			"Manas galerijas");
DEFINE("_RSGALLERY_NO_USER_GAL",			"Nav izveidotas lietotāju galerijas");
DEFINE("_RSGALLERY_IMAGES",					" bildes");
DEFINE("_RSGALLERY_COMMENT_DELETE"			,"Vai tu esi pārliecināts(a), ka vēlies dzēst komentāru?");
?>
