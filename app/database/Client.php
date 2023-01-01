<?php

class Client
{
    private $connection;
    public $error;

    function __construct() {
        $this->connection = $this->connect();
        $this->error = false;
    }

    private function connect() {
        return oci_connect("st64163","abcde",
            "fei-sql3.upceucebny.cz:1521/BDAS.UPCEUCEBNY.CZ", 'AL32UTF8');
    }

    // GENERIC FUNCTIONS

    function custom_query(string $sql) {
        return $this->make_query($sql);
    }

    private function make_query(string $sql) {
        try {
            if ($this->connection) {
                $statement = oci_parse($this->connection, $sql);
                if ($statement) {
                    $isSuccess = oci_execute($statement);
                    if ($isSuccess) {
                        $this->error = false;
                        return $statement;
                    }
                }
                $this->error = oci_error($statement);
            } else $this->error = oci_error();
        } catch (Exception $e) {
            $this->error = array(["code" => $e->getCode(), "message" => $e->getMessage(),
                "offset" => $e->getLine(), "sqltext" => $sql]);
        }
        return false;
    }

    private function view(string $viewName, string $conditions = "")
    {
        $query = $this->make_query("SELECT * FROM $viewName " . $conditions);
        if (!$query)
            return false;
        $array = array();
        while ($row = oci_fetch_assoc($query)) {
            if (isset($row["PRISLUSENSTVI"]) && $viewName != 'VIEW_PRISLUSENSTVI') // TODO otestovat
                $row["PRISLUSENSTVI"] = $this->string_prislusenstvi_to_array($row["PRISLUSENSTVI"]);
            $array[] = $row;
        }
        return $array;
    }

    private function execute(string $sqlBody) : bool {
        $result = $this->make_query("BEGIN $sqlBody END;");
        return !!$result;
    }

    // VIEWs

    function view_rezervace() {
            return $this->view('VIEW_REZERVACE');
    }

    function view_rezervaci(string $login) {
            return $this->view('VIEW_REZERVACE', "WHERE '$login' LIKE \"Zajemce\"");
    }

    function view_mistnosti() {
        return $this->view('VIEW_MISTNOSTI');
    }

    function view_firmy() {
        return $this->view('VIEW_FIRMY');
    }

    function view_osoby() {
        return $this->view('VIEW_OSOBY');
    }

    function view_patra() {
        return $this->view('VIEW_PATRA');
    }

    function view_prislusenstvi() {
        return $this->view('VIEW_PRISLUSENSTVI');
    }

    function view_stavy() {
        return $this->view('VIEW_STAVY');
    }

    function view_ucely() {
        return $this->view('VIEW_UCELY');
    }

    function view_umisteni() {
        return $this->view('VIEW_UMISTENI');
    }

    function view_velikosti() {
        return $this->view('VIEW_VELIKOSTI');
    }

    function view_zajemce(?string $login) {
        if (isset($login)) {
            $zajemci = $this->view('VIEW_ZAJEMCE', "WHERE '$login' LIKE login");
            if (!$zajemci)
                return false;
            return $zajemci[0];
        } else {
            return $this->view('VIEW_ZAJEMCE');
        }
    }

    // INSERTs

    function insert_firmu(string $login, string $email,
                          string $heslo, string $nazev) : bool
    {
        return $this->execute(
            "P_INSERT_FIRMU('$login', '$email', '$heslo', '$nazev');"
        );
    }

    function insert_mistnost(string $nazev, int $id_ucelu, int $id_umisteni,
                             int $id_patra, int $id_velikosti, array $prislusenstvi) : bool
    {
        $prislusString = $this->array_prislusenstvi_to_string($prislusenstvi);
        return $this->execute(
            "P_INSERT_MISTNOST('$nazev', $id_ucelu, $id_umisteni, 
            $id_patra, $id_velikosti, '$prislusString');"
        );
    }

    function insert_osobu(string $login, string $email, string $heslo,
                          string $jmeno, string $prijmeni, ?string $loginNadrizeneho = null) : bool
    {
        $nadrizeny = (is_null($loginNadrizeneho)) ? 'NULL' : "'$loginNadrizeneho'";
        return $this->execute(
            "P_INSERT_OSOBU('$login', '$email', '$heslo', '$jmeno', '$prijmeni', $nadrizeny);"
        );
    }

    function insert_patro(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_PATRO('$nazev');"
        );
    }

    function insert_prislusenstvi(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_PRISLUSENSTVI('$nazev');"
        );
    }

    // TODO otestovat
    function insert_rezervaci_mistnosti(string $casOd, string $casDo,
                                        string $loginZajemce, string $nazevMistnosti) : bool {
        return $this->execute(
            "P_INSERT_REZERVACI_SKRZ_MISTNOST('$casOd', '$casDo', 
            '$loginZajemce', '$nazevMistnosti');"
        );
    }

    // TODO otestovat
    function insert_rezervaci_vlastnostmi(string $casOd, string $casDo, string $loginZajemce,
                                          ?int $id_ucelu, ?int $id_umisteni, ?int $id_patra,
                                          ?int $id_velikosti, ?array $prislusenstvi) : bool
    {
        $prislusString = $this->array_prislusenstvi_to_string($prislusenstvi);
        return $this->execute(
            "P_INSERT_REZERVACI_SKRZ_VLASTNOSTI('$casOd', '$casDo', '$loginZajemce', 
            $id_ucelu, $id_umisteni, $id_patra, $id_velikosti, '$prislusString');"
        );
    }

    function insert_stav(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_STAV('$nazev');"
        );
    }

    function insert_ucel(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_UCEL('$nazev');"
        );
    }

    function insert_umisteni(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_UMISTENI('$nazev');"
        );
    }

    function insert_velikost(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_VELIKOST('$nazev');"
        );
    }

    // UPDATEs // TODO otestovat

    function update_rezervaci(int $id_rezervace, string $casOd, string $casDo,
                             ?int $id_ucelu, ?int $id_umisteni, ?int $id_patra,
                             ?int $id_velikosti, array $prislusenstvi) : bool
    {
        $prislusString = $this->array_prislusenstvi_to_string($prislusenstvi);
        $ucel = (is_null($id_ucelu)) ? 'NULL' : "$id_ucelu";
        $umisteni = (is_null($id_umisteni)) ? 'NULL' : "$id_umisteni";
        $patro = (is_null($id_patra)) ? 'NULL' : "$id_patra";
        $velikost = (is_null($id_velikosti)) ? 'NULL' : "$id_velikosti";
        return $this->execute(
            "P_UPDATE_MISTNOST($id_rezervace, '$casOd', '$casDo', 
            $ucel, $umisteni, $patro, $velikost, '$prislusString');"
        );
    }


    function update_mistnost(int $id_mistnosti, string $nazev,
                             int $id_ucelu, int $id_umisteni, int $id_patra,
                             int $id_velikosti, array $prislusenstvi) : bool
    {
        $prislusString = $this->array_prislusenstvi_to_string($prislusenstvi);
        return $this->execute(
            "P_UPDATE_MISTNOST($id_mistnosti, '$nazev', 
            $id_ucelu, $id_umisteni, $id_patra, $id_velikosti, '$prislusString');"
        );
    }

    function update_firmu(string $login, string $email, string $nazev, int $opravneni) : bool
    {
        return $this->execute(
            "P_UPDATE_FIRMU('$login', '$email', '$nazev',$opravneni);"
        );
    }

    function update_osobu(string $login, string $email, int $opravneni, string $jmeno, string $prijmeni,
                          int $detail, ?string $loginNadrizeneho) : bool
    {
        $nadrizeny = (is_null($loginNadrizeneho)) ? 'NULL' : "'$loginNadrizeneho'";
        return $this->execute(
            "P_UPDATE_OSOBU('$login', '$email', $opravneni, 
            '$jmeno', '$prijmeni', $detail, $nadrizeny);"
        );
    }

    function update_heslo(string $login, string $heslo) : bool {
        return $this->execute(
            "P_UPDATE_HESLO('$login', '$heslo');"
        );
    }

    function update_patro(int $id, string $nazev) : bool {
        return $this->execute(
            "P_UPDATE_PATRO($id, '$nazev');"
        );
    }

    function update_prislusenstvi(int $id, string $nazev) : bool {
        return $this->execute(
            "P_UPDATE_PRISLUSENSTVI($id, '$nazev');"
        );
    }

    function update_stav(int $id, string $nazev) : bool {
        return $this->execute(
            "P_UPDATE_STAV($id, '$nazev');"
        );
    }

    function update_ucel(int $id, string $nazev) : bool {
        return $this->execute(
            "P_UPDATE_UCEL($id, '$nazev');"
        );
    }

    function update_umisteni(int $id, string $nazev) : bool {
        return $this->execute(
            "P_UPDATE_UMISTENI($id, '$nazev');"
        );
    }


    function update_velikost(int $id, string $nazev) : bool {
        return $this->execute(
            "P_UPDATE_VELIKOST($id, '$nazev');"
        );
    }

    // DELETEs // TODO otestovat

    function delete_zajemce(string $login) : bool {
        return $this->execute(
            "P_DELETE_ZAJEMCE('$login');"
        );
    }

    function delete_mistnost(int $id) : bool {
        return $this->execute(
            "P_DELETE_MISTNOST($id);"
        );
    }

    function delete_patro(int $id) : bool {
        return $this->execute(
            "P_DELETE_PATRO($id);"
        );
    }

    function delete_prislusenstvi(int $id) : bool {
        return $this->execute(
            "P_DELETE_PRISLUSENSTVI($id);"
        );
    }

    function delete_rezervaci(int $id) : bool {
        return $this->execute(
            "P_DELETE_REZERVACI($id);"
        );
    }

    function delete_stav(int $id) : bool {
        return $this->execute(
            "P_DELETE_STAV($id);"
        );
    }

    function delete_ucel(int $id) : bool {
        return $this->execute(
            "P_DELETE_UCEL($id);"
        );
    }

    function delete_umisteni(int $id) : bool {
        return $this->execute(
            "P_DELETE_UMISTENI($id);"
        );
    }


    function delete_velikost(int $id) : bool {
        return $this->execute(
            "P_DELETE_VELIKOST($id);"
        );
    }

    // OTHERs // TODO otestovat

    function check_login(string $login, string $heslo) : bool {
        return $this->execute("pckg_login.p_exec_login('$login', '$heslo');");
    }

    private function array_prislusenstvi_to_string(?array $prislusenstvi) : string {
        if (!isset($prislusenstvi) || count($prislusenstvi) == 0 )
            return "";
        return implode(';', $prislusenstvi);
    }

    private function string_prislusenstvi_to_array(string $prislusenstvi) : array {
        if ($prislusenstvi == "")
            return array();
        return explode(';', $prislusenstvi);
    }
}