<?php

require_once("../config.php");

class UrlShortener {
    protected $db;
    
    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=short", USER_NAME, USER_PASSWORD, array(
            PDO::ATTR_PERSISTENT => true
        ));
    }
    
    /**
     * Generates unique code for each URLsd
     *
     * @param string $orignalURL
     *
     * @return string
     */

    public function generateUniqueCode($orignalURL) {
        return substr(md5($orignalURL . microtime()), 0, 8);
    }
    
    private function prepare($insertInDatabase) {
        return $this->db->prepare($insertInDatabase,[PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    }

    /**
     * Validates URL, checks if already present in database and finally inserts
     * in database
     *
     * @param string $url Real Url
     *
     * @return string
     */
    
    public function validateUrlAndReturnCode($orignalURL) { 
        $insertInDatabase  = 'INSERT INTO link (url,code,created) VALUES (:og_url,:u_code,NOW())';

        $uniqueCode = $this->generateUniqueCode("$orignalURL");
            
        $query = $this->prepare($insertInDatabase);
        $query->bindValue(':og_url', $orignalURL);
        $query->bindValue(':u_code', $uniqueCode);
        $query->execute();
            
        return $uniqueCode;
    }
    
    /**
     * Returns the orignal URL based on the shorten url
     *
     * @param string $string Real Url
     *
     * @return string
     */
    
    public function getOrignalURL($string) {
        $rows   = "SELECT url FROM link WHERE code = 'pCODE'";

        $query = $this->prepare($insertInDatabase);
        $query->execute(['pCODE' => "$string"]);
        $rows  = $query->fetchAll();
        
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