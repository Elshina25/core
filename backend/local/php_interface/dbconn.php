<?define("BX_CRONTAB_SUPPORT", true);?><?define("BX_CRONTAB_SUPPORT", true);?><?define("BX_CRONTAB_SUPPORT", true);?><?
define("DBPersistent", false);
$DBType = "mysql";
$DBHost = "localhost";
$DBLogin = "bitrix0";
$DBPassword = "i%XVoLh+i[lU{nl2]LE3";
$DBName = "sitemanager";

$DBDebug = false;
$DBDebugToFile = false;

@set_time_limit(60);

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_UTF", true);
define("BX_USE_MYSQLI", true);
define("BX_FILE_PERMISSIONS", 0664);
define("BX_DIR_PERMISSIONS", 0775);
@umask(~BX_DIR_PERMISSIONS);
@ini_set("memory_limit", "512M");
define("BX_DISABLE_INDEX_PAGE", true);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.log");
?>