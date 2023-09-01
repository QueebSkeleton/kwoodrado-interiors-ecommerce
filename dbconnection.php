<?php
    // Tell mysqli to throw exceptions
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    function get_connection() {
        // Parse configuration file
        $config = parse_ini_file($_SERVER["DOCUMENT_ROOT"]."/config.ini");
        $conn = mysqli_connect($config["DB_HOST"],
                               $config["DB_USER"],
                               $config["DB_PASSWORD"],
                               $config["DB_NAME"]);
        return $conn;
    }

