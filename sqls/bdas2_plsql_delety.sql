-- REZERVACE (+ VLASTNOSTI + INVENTAR)

CREATE OR REPLACE PROCEDURE p_delete_rezervaci
    (v_id_rezervace IN NUMBER)
    IS
    v_id_skupiny NUMBER;
BEGIN
    SAVEPOINT point_pred_deletem;
    SELECT id_skupiny INTO v_id_skupiny 
        FROM rezervace WHERE id_rezervace = v_id_rezervace;
    DELETE FROM rezervace WHERE id_rezervace = v_id_rezervace;
    DELETE FROM skupiny_vlastnosti WHERE id_skupiny = v_id_skupiny;
    -- DELETE FROM inventare -- neni potreba, invantare maji cascade delete
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- MISTNOST (+ VLASTNOSTI + INVENTAR)

CREATE OR REPLACE PROCEDURE p_delete_mistnost
    (v_id_mistnosti IN NUMBER)
    IS
    v_id_skupiny NUMBER;
BEGIN
    SAVEPOINT point_pred_deletem;
    SELECT id_skupiny INTO v_id_skupiny 
        FROM mistnosti WHERE id_mistnosti = v_id_mistnosti;
    DELETE FROM mistnosti WHERE id_mistnosti = v_id_mistnosti;
    DELETE FROM skupiny_vlastnosti WHERE id_skupiny = v_id_skupiny;
    -- DELETE FROM inventare -- neni potreba, invantare maji cascade delete
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- OSOBA (+ ZAJEMCE)

CREATE OR REPLACE PROCEDURE p_delete_osobu
    (v_login IN VARCHAR2)
    IS
    v_id_zajemce NUMBER;
BEGIN
    SAVEPOINT point_pred_deletem;
    SELECT id_zajemce INTO v_id_zajemce 
        FROM zajemci WHERE login LIKE v_login;
    DELETE FROM osoby WHERE id_zajemce = v_id_zajemce;
    --DELETE FROM zajemci WHERE id_zajemce = v_id_zajemce; -- neni potreba, ma to cascade delete
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- FIRMA (+ ZAJEMCE)

CREATE OR REPLACE PROCEDURE p_delete_firmu
    (v_login IN VARCHAR2)
    IS
    v_id_zajemce NUMBER;
BEGIN
    SAVEPOINT point_pred_deletem;
    SELECT id_zajemce INTO v_id_zajemce 
        FROM zajemci WHERE login LIKE v_login;
    DELETE FROM firmy WHERE id_zajemce = v_id_zajemce;
    --DELETE FROM zajemci WHERE id_zajemce = v_id_zajemce; -- neni potreba, ma to cascade delete
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- UCEL

CREATE OR REPLACE PROCEDURE p_delete_ucel
    (v_id_ucelu IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM ucely WHERE id_ucelu = v_id_ucelu;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- UMISTENI

CREATE OR REPLACE PROCEDURE p_delete_umisteni
    (v_id_umisteni IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM umisteni WHERE id_umisteni = v_id_umisteni;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- PATRO

CREATE OR REPLACE PROCEDURE p_delete_patro
    (v_id_patra IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM patra WHERE id_patra = v_id_patra;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- VELIKOST

CREATE OR REPLACE PROCEDURE p_delete_velikost
    (v_id_velikosti IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM velikosti WHERE id_velikosti = v_id_velikosti;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- PRISLUSENSTVI

CREATE OR REPLACE PROCEDURE p_delete_prislusenstvi
    (v_id_prislusenstvi IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM prislusenstvi WHERE id_prislusenstvi = v_id_prislusenstvi;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- STAVY

CREATE OR REPLACE PROCEDURE p_delete_stav
    (v_id_stavu IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM stavy WHERE id_stavu = v_id_stavu;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- LOGY

CREATE OR REPLACE PROCEDURE p_delete_log
    (v_id_logu IN NUMBER)
    IS
BEGIN
    SAVEPOINT point_pred_deletem;
    DELETE FROM logy WHERE id_logu = v_id_logu;
    COMMIT;
EXCEPTION
    WHEN others THEN
        ROLLBACK TO point_pred_deletem;
        RAISE;
END;
/

-- SOUBORY

    -- TODO pridat soubory