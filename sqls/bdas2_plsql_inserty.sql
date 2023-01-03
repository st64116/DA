-- REZERVACE (+ VLASTNOSTI + INVENTAR)
CREATE OR REPLACE PROCEDURE p_insert_rezervaci_skrz_vlastnosti 
    (v_casOd IN VARCHAR2,
    v_casDo IN VARCHAR2,
    v_login IN VARCHAR2,
    v_id_ucelu IN NUMBER,
    v_id_umisteni IN NUMBER,
    v_id_patra IN NUMBER,
    v_id_velikosti IN NUMBER,
    v_prislusenstvi IN VARCHAR2) 
    IS
    v_od DATE;
    v_do DATE;
    v_id_zajemce NUMBER;
    v_id_skupiny NUMBER;
    v_id_rezervace NUMBER;
BEGIN
    SAVEPOINT point_pred_insertem;
    v_od := to_date(v_casOd, 'yyyy-mm-dd hh24:mi');
    v_do := to_date(v_casDo, 'yyyy-mm-dd hh24:mi');
    v_id_skupiny := pckg_rez_vlas_mist.f_add_vlastnost_pro_rezervace
        (v_id_ucelu, v_id_umisteni, v_id_patra, 
            v_id_velikosti, v_prislusenstvi);
    p_check_vlastnosti_podmnozinou(v_id_skupiny);
    SELECT id_zajemce INTO v_id_zajemce
        FROM zajemci WHERE login LIKE v_login;
    INSERT INTO REZERVACE (casOd, casDo, id_zajemce, id_skupiny)
        VALUES (v_od, v_do, v_id_zajemce, v_id_skupiny)
        RETURNING id_rezervace INTO v_id_rezervace;
    p_check_dostupnost_rezervace(v_id_rezervace);
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

CREATE OR REPLACE PROCEDURE p_insert_rezervaci_skrz_mistnost
    (v_casOd IN VARCHAR2,
    v_casDo IN VARCHAR2,
    v_login IN VARCHAR2,
    v_id_mistnosti IN NUMBER)
    IS
    v_id_rezervace NUMBER;
    v_id_zajemce NUMBER;
    v_id_skupiny NUMBER;
    v_od DATE;
    v_do DATE;
BEGIN
    SAVEPOINT point_pred_insertem;
    v_od := to_date(v_casOd, 'yyyy-mm-dd hh24:mi');
    v_do := to_date(v_casDo, 'yyyy-mm-dd hh24:mi');
    v_id_skupiny := pckg_rez_vlas_mist.f_add_vlastnost_pro_rezervace
        (v_id_mistnosti);
    SELECT id_zajemce INTO v_id_zajemce
        FROM zajemci WHERE login LIKE v_login;
    INSERT INTO REZERVACE (casOd, casDo, id_zajemce, id_skupiny)
        VALUES (v_od, v_do, v_id_zajemce, v_id_skupiny)
        RETURNING id_rezervace INTO v_id_rezervace;
    p_check_dostupnost_rezervace(v_id_rezervace);
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- MISTNOST (+ VLASTNOSTI + INVENTAR)
CREATE OR REPLACE PROCEDURE p_insert_mistnost
    (v_nazev IN VARCHAR2,
    v_id_ucelu IN NUMBER,
    v_id_umisteni IN NUMBER,
    v_id_patra IN NUMBER,
    v_id_velikosti IN NUMBER,
    v_prislusenstvi IN VARCHAR2)
    IS
    v_id_skupiny NUMBER;
BEGIN
    SAVEPOINT point_pred_insertem;
    v_id_skupiny := pckg_rez_vlas_mist.f_add_vlastnost_pro_mistnost
        (v_id_ucelu, v_id_umisteni, v_id_patra, v_id_velikosti, v_prislusenstvi);
    INSERT INTO MISTNOSTI (nazev, id_skupiny) VALUES (v_nazev, v_id_skupiny);
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- OSOBA (+ ZAJEMCE)

CREATE OR REPLACE PROCEDURE p_insert_osobu 
    (v_login IN VARCHAR2,
    v_email IN VARCHAR2,
    v_heslo IN VARCHAR2,
    v_jmeno IN VARCHAR2,
    v_prijmeni IN VARCHAR2,
    v_nadrizeny IN VARCHAR2) 
    IS
    v_id_zajemce NUMBER;
    v_hash VARCHAR2(64);
    v_id_nadrizeneho NUMBER;
BEGIN
    SAVEPOINT point_pred_insertem;
    v_id_nadrizeneho := pckg_login.f_get_id_nadrizeneho(v_nadrizeny);
    v_hash := pckg_login.f_get_zasifrovane_heslo(v_heslo);
    INSERT INTO ZAJEMCI (email, login, heslo, diskriminator) 
        VALUES (v_email, v_login, v_hash, 'OSOBY')
        RETURNING id_zajemce into v_id_zajemce;
    INSERT INTO OSOBY (id_zajemce, jmeno, prijmeni, id_nadrizeneho) 
        VALUES (v_id_zajemce, v_jmeno, v_prijmeni, v_id_nadrizeneho);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- FIRMA (+ ZAJEMCE)

CREATE OR REPLACE PROCEDURE p_insert_firmu 
    (v_login IN VARCHAR2,
    v_email IN VARCHAR2,
    v_heslo IN VARCHAR2,
    v_nazev IN VARCHAR2)
    IS
    v_id NUMBER;
    v_hash VARCHAR2(64);
BEGIN
    SAVEPOINT point_pred_insertem;
    v_hash := pckg_login.f_get_zasifrovane_heslo(v_heslo);
    INSERT INTO ZAJEMCI (email, login, heslo, diskriminator) 
        VALUES (v_email, v_login, v_hash, 'FIRMY') 
        RETURNING id_zajemce into v_id; 
    INSERT INTO FIRMY (id_zajemce, nazev) VALUES (v_id, v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- UCEL

CREATE OR REPLACE PROCEDURE p_insert_ucel (v_nazev IN VARCHAR2) IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO ucely (nazev) VALUES (v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- UMISTENI

CREATE OR REPLACE PROCEDURE p_insert_umisteni (v_nazev IN VARCHAR2) IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO umisteni (nazev) VALUES (v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- PATRO

CREATE OR REPLACE PROCEDURE p_insert_patro (v_nazev IN VARCHAR2) IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO patra (nazev) VALUES (v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- VELIKOST

CREATE OR REPLACE PROCEDURE p_insert_velikost (v_nazev IN VARCHAR2) IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO velikosti (nazev) VALUES (v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- PRISLUSENSTVI

CREATE OR REPLACE PROCEDURE p_insert_prislusenstvi (v_nazev IN VARCHAR2) IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO prislusenstvi (nazev) VALUES (v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- STAVY

CREATE OR REPLACE PROCEDURE p_insert_stav (v_nazev IN VARCHAR2) IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO stavy (nazev) VALUES (v_nazev);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;

-- LOGY

CREATE OR REPLACE PROCEDURE p_insert_log
    (v_typ_operace IN VARCHAR2, v_tabulka IN VARCHAR2, v_info IN VARCHAR2)
    IS
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO logy (typ_operace, tabulka, info)
        VALUES (v_typ_operace, v_tabulka, v_info);
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/

-- SOUBORY

CREATE OR REPLACE PROCEDURE p_insert_profilovku
    (v_login IN VARCHAR2,
    v_nazev IN VARCHAR2,
    v_pripona IN VARCHAR2,
    v_obsah IN BLOB)
    IS
    v_id_profilovky NUMBER;
BEGIN
    SAVEPOINT point_pred_insertem;
    INSERT INTO soubory (nazev, pripona, obsah)
        VALUES (v_nazev, v_pripona, v_obsah)
            RETURNING id_souboru INTO v_id_profilovky;
    UPDATE zajemci SET id_profilovky = v_id_profilovky
        WHERE login LIKE v_login;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_insertem;
        RAISE;
END;
/