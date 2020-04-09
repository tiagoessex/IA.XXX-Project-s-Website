<?php
// database name
define('DOMAIN_URL', '/');//'/map/');

// ***********************************
// MYSQL
// ***********************************
define('IS_REAL_SERVER', 'false');
define('DB_NAME', 'XXX');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
//define('DB_SERVER', '192.168.99.102');	// docker
define('DB_SERVER', 'localhost');	// local
define('DB_CHARSET', 'utf8');
define('CONST_SERVER_TIMEZONE', 'UTC'); 
define('CONST_SERVER_DATEFORMAT', 'Ymd');
// tables
define('TABLE_ENTITIES', 'entidade');
define('DB_TABLE_USERS', 'users');
//define('DB_TABLE_USERS', 'utilizador');


// ***********************************
// ORACLE
// ***********************************

define('DB_ORACLE_DB_NAME', 'YYY');
define('DB_ORACLE_DB_PASSWORD', 'YYY');
define('DB_ORACLE_HOST', 'localhost');
define('DB_ORACLE_PORT', '1521');
define('DB_ORACLE_SERVICE_NAME', 'orcl.168.1.67'); // orcl.168.1.77
define('DB_ORACLE_SID', 'orcl');
define('DB_ORACLE_CHARSET', 'AL32UTF8');


// ***********************************
// PYTHON
// ***********************************
define('PYTHON_SRV_DOOR', 'http://127.0.0.1:5000/');	// local


// *****************************************
// *****************************************
// *****************************************
// *****************************************
define('ROUTES_FLASK_API', 'http://127.0.0.1:5050/');
// the osrm server's ip is not static => check it from time to time
//define('ROUTES_OSRM_API', 'http://127.0.0.1:5000');
define('ROUTES_OSRM_API', 'http://xx.xx.xx.xx:xx/');


// ***********************************
// DATE
// ***********************************
define('MIN_DATE', '1999-01-01');
//define('MAX_DATE', date("Y-m-d"));
define('MAX_DATE', '2019-02-01');

// TEMPORARY --- REMOVE WHEN ACCESS TO REAL TIME DB
define('TEMP_DENUNCIAS_OUT_START', '2018-11-01');
define('TEMP_DENUNCIAS_OUT_END', '2019-02-01');
// DEFAULT: 1 MONTH
define('TEMP_DENUNCIAS_IN_START', '2019-01-01');
define('TEMP_DENUNCIAS_IN_END', '2019-02-01');


// ***********************************
// JWT
// ***********************************
define('JWT_SECRET_KEY', 'xx');


// ***********************************
// TRAINING
// ***********************************
define('CLASSIFICADOR_LUIS', 'http://localhost:5005/');
define('CLASSIFICADOR_LUIS_IP', 'localhost');
