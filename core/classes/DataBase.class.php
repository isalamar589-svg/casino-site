<?php 

if (!defined('mortalsoft')) {
    die('Direct access not permitted');
}

class DataBase {
    private $Host = DB_HOST;
    private $User = DB_USER;
    private $Pass = DB_PASS;
    private $DB_Name = DB_NAME;

    private $DBH;
    private $STMT;

    public function __construct() {
        $port = defined('DB_PORT') ? DB_PORT : 3306;
        $DSN = 'mysql:host=' . $this->Host . ';port=' . $port . ';dbname=' . $this->DB_Name;
        
        $ssl_ca = null;
        if (file_exists('/etc/ssl/certs/ca-certificates.crt')) {
            $ssl_ca = '/etc/ssl/certs/ca-certificates.crt';
        } elseif (file_exists('/etc/pki/tls/certs/ca-bundle.crt')) {
            $ssl_ca = '/etc/pki/tls/certs/ca-bundle.crt';
        }

        $Options = array(
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        );

        if ($ssl_ca) {
            $Options[PDO::MYSQL_ATTR_SSL_CA] = $ssl_ca;
        }

        try {
            $this->DBH = new PDO($DSN, $this->User, $this->Pass, $Options);
        } catch(PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function Query($Query) {
        try {
            $this->STMT = $this->DBH->prepare($Query);
        } catch(PDOException $e) {
            throw new Exception("Query preparation error: " . $e->getMessage());
        }
    }

    public function Bind($Param, $Value, $Type = null) {
        try {
            if (is_null($Type)) {
                $Type = $this->DetectType($Value);
            }
            $this->STMT->bindValue($Param, $Value, $Type);
        } catch(PDOException $e) {
            throw new Exception("Binding error: " . $e->getMessage());
        }
    }

    public function Execute() {
        try {
            return $this->STMT->execute();
        } catch(PDOException $e) {
            throw new Exception("Execution error: " . $e->getMessage());
        }
    }

    public function ResultSet() {
        if (!$this->STMT->rowCount()) {
            $this->Execute();
        }
        return $this->STMT->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Single() {
        if (!$this->STMT->rowCount()) {
            $this->Execute();
        }
        return $this->STMT->fetch(PDO::FETCH_ASSOC);
    }

    public function RowCount() {
        return $this->STMT->rowCount();
    }

    public function LastID() {
        return $this->DBH->lastInsertId();
    }

    public function StartTransaction() {
        return $this->DBH->beginTransaction();
    }

    public function EndTransaction() {
        return $this->DBH->commit();
    }

    public function CancelTransaction() {
        return $this->DBH->rollBack();
    }

    public function Quote($value) {
        return $this->DBH->quote($value);
    }

    public function DebugParams() {
        $output = "Statement:\n" . $this->STMT->queryString . "\n";
        $output .= "Bindings:\n";
        $bindings = $this->STMT->debugDumpParams();
        $output .= str_replace("\n", "\n  ", $bindings);
        return $output;
    }

    private function DetectType($value) {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } else {
            return PDO::PARAM_STR;
        }
    }
}
?>