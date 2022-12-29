<?php

class Client
{
    public $connection;

    function __construct() {
        $this->connection = $this->connect();
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
        $statement = oci_parse($this->connection, $sql);
        if (!$statement)
            return false;
        $isSuccess = oci_execute($statement, OCI_DEFAULT);
        return ($isSuccess) ? $statement : false;
    }

    private function view(string $viewName)
    {
        $query = $this->make_query("SELECT * FROM $viewName");
        if (!$query)
            return false;
        $array = array();
        while ($row = oci_fetch_assoc($query))
            $array[] = $row;
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
            "P_INSERT_FIRMU($login, $email, $heslo, $nazev);"
        );
    }

    function insert_mistnost() : bool {
        return $this->execute(
            "P_INSERT_MISTNOST();" // TODO doplnit parametry
        );
    }

    function insert_osobu(string $login, string $email, string $heslo,
                          string $jmeno, $prijmeni) : bool
    {
        return $this->execute(
            "P_INSERT_OSOBU($login, $email, $heslo, $jmeno, $prijmeni);"
        );
    }

    function insert_patro(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_PATRO($nazev);"
        );
    }

    function insert_prislusenstvi(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_PRISLUSENSTVI($nazev);"
        );
    }

    function insert_rezervaci_mistnosti($todoParams) : bool {
        return $this->execute(
            "P_INSERT_REZERVACI_SKRZ_MISTNOST();" // TODO doplnit parametry
        );
    }

    function insert_rezervaci_vlastnosti($mistnost) : bool {
        return $this->execute(
            "P_INSERT_REZERVACI_SKRZ_VLASTNOSTI($mistnost);" // TODO zkontrolovat
        );
    }

    function insert_stav(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_STAV($nazev);"
        );
    }

    function insert_ucel(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_UCEL($nazev);"
        );
    }

    function insert_umisteni(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_UMISTENI($nazev);"
        );
    }

    function insert_velikost(string $nazev) : bool {
        return $this->execute(
            "P_INSERT_VELIKOST($nazev);"
        );
    }

    // UPDATEs

    // TODO doplnit vsechny updaty

    // DELETEs

    // TODO doplnit vsechny delety
}