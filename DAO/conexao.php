<?php
class conexao
{


    // private $db_host = '107.180.48.129'; // servidor
    // private $db_user = 'srb_db_admin'; // usuario do banco
    // private $db_port = '3306'; // usuario do banco
    // private $db_pass = 'Sistema@123'; // senha do usuario do banco
    // private $db_name = 'db_srb'; // nome do banco

    // private $db_host = 'bd_idealize.mysql.dbaas.com.br'; // servidor
    // private $db_user = 'bd_idealize'; // usuario do banco
    // private $db_port = '3306'; // usuario do banco
    // private $db_pass = 'Sistema@123'; // senha do usuario do banco
    // private $db_name = 'bd_idealize'; // nome do banco

    private $db_host = 'localhost'; // servidor
    private $db_user = 'root'; // usuario do banco
    private $db_port = '3306'; // usuario do banco
    private $db_pass = ''; // senha do usuario do banco
    private $db_name = 'db_bolao'; // nome do banco

    private $con = false;

    public function connect() // estabelece conexao
    {
        if (!$this->con) {
            $myconn = @mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name, $this->db_port);

            if ($myconn) {
                $seldb = @mysqli_select_db($myconn, $this->db_name);
                if ($seldb) {
                    $this->con = true;
                    mysqli_query($myconn, "SET NAMES 'utf8'");
                    return $myconn;
                } else {
                    if (mysqli_connect_errno()) {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        exit();
                    }
                    return false;
                }
            } else {
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    exit();
                }
                return false;
            }
        } else {
            return $myconn;
        }
    }

    public function disconnect($str) // fecha conexao
    {
        if ($this->con) {
            if (mysqli_close($str)) {
                $this->con = false;
                return true;
            } else {
                return false;
            }
        }
    }
}
