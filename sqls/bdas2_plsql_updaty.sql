-- REZERVACE (+ VLASTNOSTI + INVENTAR)
CREATE OR REPLACE PROCEDURE p_update_rezervaci
    (v_id_rezervace IN NUMBER,
    v_casOd IN VARCHAR2,
    v_casDo IN VARCHAR2,
    v_id_ucelu IN NUMBER,
    v_id_umisteni IN NUMBER,
    v_id_patra IN NUMBER,
    v_id_velikosti IN NUMBER,
    v_prislusenstvi IN VARCHAR2)
    IS
    v_od DATE;
    v_do DATE;
    v_id_skupiny NUMBER;
    v_id_stavu NUMBER;
BEGIN
    SAVEPOINT point_pred_updatem;
    v_od := to_date(v_casOd, 'yyyy-mm-dd hh24:mi');
    v_do := to_date(v_casDo, 'yyyy-mm-dd hh24:mi');
    SELECT id_stavu INTO v_id_stavu 
        FROM rezervace WHERE id_rezervace = v_id_rezervace;
    IF (v_id_stavu <> 1) THEN
        raise_application_error(-20204, 'The reservation can be updated only with status "waiting"');
    END IF;
    SELECT id_skupiny INTO v_id_skupiny 
        FROM rezervace WHERE id_rezervace = v_id_rezervace;
    UPDATE rezervace SET casod = v_od, casdo = v_do 
        WHERE id_rezervace = v_id_rezervace;
    UPDATE skupiny_vlastnosti SET id_ucelu = v_id_ucelu, 
        id_umisteni = v_id_umisteni, id_patra = v_id_patra, 
        id_velikosti = v_id_velikosti
        WHERE id_skupiny = v_id_skupiny;
    pckg_rez_vlas_mist.p_update_inventar(v_id_skupiny, v_prislusenstvi);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- MISTNOST (+ VLASTNOSTI + INVENTAR)

CREATE OR REPLACE PROCEDURE p_update_mistnost
    (v_id_mistnosti IN NUMBER,
    v_nazev IN VARCHAR2,
    v_id_ucelu IN NUMBER,
    v_id_umisteni IN NUMBER,
    v_id_patra IN NUMBER,
    v_id_velikosti IN NUMBER,
    v_prislusenstvi IN VARCHAR2)
    IS
    v_id_skupiny NUMBER;
BEGIN
    SAVEPOINT point_pred_updatem;
    SELECT id_skupiny INTO v_id_skupiny 
        FROM mistnosti WHERE id_mistnosti = v_id_mistnosti;
    UPDATE mistnosti SET nazev = v_nazev WHERE id_mistnosti = v_id_mistnosti;
    UPDATE skupiny_vlastnosti SET id_ucelu = v_id_ucelu, 
        id_umisteni = v_id_umisteni, id_patra = v_id_patra, 
        id_velikosti = v_id_velikosti
        WHERE id_skupiny = v_id_skupiny;
    pckg_rez_vlas_mist.p_update_inventar(v_id_skupiny, v_prislusenstvi);
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- OSOBA (+ ZAJEMCE)

CREATE OR REPLACE PROCEDURE p_update_osobu 
    (v_login IN VARCHAR2,
    v_email IN VARCHAR2,
    v_opravneni IN NUMBER,
    v_jmeno IN VARCHAR2,
    v_prijmeni IN VARCHAR2,
    v_detail IN NUMBER,
    v_nadrizeny IN VARCHAR2)
    IS
    v_id_zajemce NUMBER;
    v_id_nadrizeneho NUMBER;
BEGIN
    SAVEPOINT point_pred_updatem;
    SELECT id_zajemce INTO v_id_zajemce
        FROM zajemci WHERE login LIKE v_login;
    v_id_nadrizeneho := pckg_login.f_get_id_nadrizeneho(v_nadrizeny);
    UPDATE zajemci SET email = v_email, opravneni = v_opravneni
        WHERE id_zajemce = v_id_zajemce;
    UPDATE osoby SET jmeno = v_jmeno, prijmeni = v_prijmeni,
        detail = v_detail, id_nadrizeneho = v_id_nadrizeneho
            WHERE id_zajemce = v_id_zajemce;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- FIRMA (+ ZAJEMCE)

CREATE OR REPLACE PROCEDURE p_update_firmu 
    (v_login IN VARCHAR2,
    v_email IN VARCHAR2,
    v_nazev IN VARCHAR2,
    v_opravneni IN NUMBER)
    IS
    v_id NUMBER;
BEGIN
    SAVEPOINT point_pred_updatem;
    SELECT id_zajemce INTO v_id
        FROM zajemci WHERE login LIKE v_login;
    UPDATE zajemci SET email = v_email, opravneni = v_opravneni WHERE id_zajemce = v_id;
    UPDATE firmy SET nazev = v_nazev WHERE id_zajemce = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;

-- heslo (ZAJEMCI)

CREATE OR REPLACE PROCEDURE p_update_heslo 
    (v_login IN VARCHAR2,
    v_heslo IN VARCHAR2)
    IS
    v_hash VARCHAR2(64);
BEGIN
    SAVEPOINT point_pred_updatem;
    v_hash := pckg_login.f_get_zasifrovane_heslo(v_heslo);
    UPDATE zajemci SET heslo = v_hash WHERE login LIKE v_login;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- UCEL

CREATE OR REPLACE PROCEDURE p_update_ucel 
    (v_id IN NUMBER, v_nazev IN VARCHAR2) 
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE ucely SET nazev = v_nazev WHERE id_ucelu = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- UMISTENI

CREATE OR REPLACE PROCEDURE p_update_umisteni 
    (v_id IN NUMBER, v_nazev IN VARCHAR2) 
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE umisteni SET nazev = v_nazev WHERE id_umisteni = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- PATRO

CREATE OR REPLACE PROCEDURE p_update_patro 
    (v_id IN NUMBER, v_nazev IN VARCHAR2) 
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE patra SET nazev = v_nazev WHERE id_patra = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- VELIKOST

CREATE OR REPLACE PROCEDURE p_update_velikost 
    (v_id IN NUMBER, v_nazev IN VARCHAR2) 
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE velikosti SET nazev = v_nazev WHERE id_velikosti = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- PRISLUSENSTVI

CREATE OR REPLACE PROCEDURE p_update_prislusenstvi
    (v_id IN NUMBER, v_nazev IN VARCHAR2) 
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE prislusenstvi SET nazev = v_nazev WHERE id_prislusenstvi = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- STAVY

CREATE OR REPLACE PROCEDURE p_update_stav 
    (v_id IN NUMBER, v_nazev IN VARCHAR2) 
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE stavy SET nazev = v_nazev WHERE id_stavu = v_id;
    COMMIT;
EXCEPTION
    WHEN others THEN 
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- LOGY

CREATE OR REPLACE PROCEDURE p_update_log
    (v_id_logu IN NUMBER,
    v_typ_operace IN VARCHAR2,
    v_tabulka IN VARCHAR2,
    v_info IN VARCHAR2)
    IS
BEGIN
    SAVEPOINT point_pred_updatem;
    UPDATE logy SET typ_operace = v_typ_operace, tabulka = v_tabulka, info = v_info
        WHERE id_logu = v_id_logu;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_updatem;
        RAISE;
END;
/

-- SOUBORY

    -- TODO pridat soubory
