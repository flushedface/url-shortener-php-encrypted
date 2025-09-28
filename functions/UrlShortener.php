<?php

require_once("../config.php");

class UrlShortener {
    protected $cache = array();
    protected $db;
    
    public function __construct() {
        $this->db = new PDO(sprintf("mysql:host=localhost;dbname=%s", DB_NAME), USER_NAME, USER_PASSWORD, array());
    }
    
    /**
     * Creates MD5 hash out of the encrypted url and substrings the first 8 characters
     *
     * @param string $cryptData
     *
     * @return string
     */
    public function generateUniqueCode($cryptData) {
        return substr(md5($cryptData . microtime()), 0, 8);
    }
    
    private function prepare($insertInDatabase) {
        return $this->db->prepare($insertInDatabase,[PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    }

    /**
     * Stores encrypted url in database and returns the unique code
     *
     * @param string $url Real Url
     *
     * @return string
     */
    
    public function validateUrlAndReturnCode($cryptData) {#
        // Check if string is valid crypt
        if(strlen($cryptData) > 264) { 
            return;
        }

        $uniqueCode = $this->generateUniqueCode($cryptData);

        $cache[] = array($uniqueCode => [session_id(),$cryptData]);

        // ? are placeholders for the query command
        $insertInDatabase  = "INSERT INTO link (url,code,created) VALUES (?,?,NOW())";
            
        $query = $this->prepare($insertInDatabase);
        $query->execute([$cryptData, $uniqueCode]);
        return $uniqueCode;
    }
    
    /**
     * Returns the orignal URL based on the shorten url
     *
     * @param string $string Real Url
     *
     * @return string
     */
    
    public function getOrignalURL($uniqueCode) {
        if($url == $cache[$uniqueCode]) {
            return $url;
        }

        $sql = "SELECT url FROM link WHERE code = ?";

        $query = $this->prepare($sql);
        $query->execute([$uniqueCode]);
        $rows = $query->fetchAll();
        
        return $rows[0]['url'];
    }
    
    /**
     * Generates link tag for the new shorten url
     *
     * @param string $uniqueCode
     *
     * @return string
     */
    
    public function generateLinkForShortURL($uniqueCode = '') {
        return '<a href="' . BASE_URL . $uniqueCode . '">' . BASE_URL . $uniqueCode . '</a>';
    }
}

?>