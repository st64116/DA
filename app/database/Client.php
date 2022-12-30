<?php

// poznamky TODO

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
            "fei-sql3.upceucebny.cz:1521/BDAS.UPCEUCEBNY.CZ");
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

    private function view(string $viewName)
    {
        $query = $this->make_query("SELECT * FROM $viewName");
        if (!$query)
            return false;
        $array = array();
        while ($row = oci_fetch_assoc($query)) {
            if (isset($row["PRISLUSENSTVI"])) // TODO otestovat
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

    function view_mistnosti() {
        return $this->view('V_MISTNOSTI_S_VLASTNOSTMI');
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

    // INSERTs

    function insert_firmu(string $login, string $email,
                          string $heslo, string $nazev) : bool
    {
        return $this->execute(
            "P_INSERT_FIRMU('$login', '$email', '$heslo', '$nazev');"
        );
    }

    function insert_mistnost(string $nazev, string $ucel, string $umisteni,
                             string $patro, string $velikost, array $prislusenstvi) : bool
    {
        $prislusString = $this->array_prislusenstvi_to_string($prislusenstvi);
        return $this->execute(
            "P_INSERT_MISTNOST('$nazev', '$ucel', '$umisteni', 
            '$patro', '$velikost', '$prislusString');"
        );
    }

    function insert_osobu(string $login, string $email, string $heslo,
                          string $jmeno, $prijmeni) : bool
    {
        return $this->execute(
            "P_INSERT_OSOBU('$login', '$email', '$heslo', '$jmeno', '$prijmeni');"
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

    function insert_rezervaci_mistnosti(string $casOd, string $casDo,
                                        string $loginZajemce, string $nazevMistnosti) : bool {
        return $this->execute(
            "P_INSERT_REZERVACI_SKRZ_MISTNOST('$casOd', '$casDo', '$loginZajemce', '$nazevMistnosti');" // TODO otestovat / string<>date
        );
    }

    function insert_rezervaci_vlastnostmi(string $casOd, string $casDo, string $loginZajemce,
                                          ?string $ucel, ?string $umisteni, ?string $patro,
                                          ?string $velikost, ?array $prislusenstvi) : bool
    {
        $prislusString = $this->array_prislusenstvi_to_string($prislusenstvi);
        return $this->execute(
            "P_INSERT_REZERVACI_SKRZ_VLASTNOSTI('$casOd', '$casDo', '$loginZajemce', 
            '$ucel', '$umisteni', '$patro', '$velikost', '$prislusString');" // TODO otestovat / string<>date
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

    // UPDATEs

    // TODO doplnit vsechny updaty

    // DELETEs

    // TODO doplnit vsechny delety

    // OTHERs

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