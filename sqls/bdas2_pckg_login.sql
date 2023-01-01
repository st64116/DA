--- PACKAGE pro prihlasovani a kontrolu hesel
CREATE OR REPLACE PACKAGE pckg_login AS

    --- vraci id_nadrizeneho nebo null podle loginu
    FUNCTION f_get_id_nadrizeneho
        (v_login_nadrizeneho IN VARCHAR2)
        RETURN NUMBER;

    --- vraci zasifrovane heslo
    FUNCTION f_get_zasifrovane_heslo 
        (v_heslo IN VARCHAR2)
        RETURN VARCHAR2;

    --- vyhodi vyjimku pokud udaje netvori spravnou kombinaci
    PROCEDURE p_exec_login 
        (v_login IN VARCHAR2, 
        v_heslo IN VARCHAR2);
        
END pckg_login;
/

--- PACKAGE BODY

CREATE OR REPLACE PACKAGE BODY pckg_login AS
    
/*PRIVATE*/
    -- vraci md5 hash ze stringu
    FUNCTION f_get_zasifrovany_string 
        (v_string IN VARCHAR2)
        RETURN VARCHAR2
        IS
        v_hash VARCHAR2(64);
    BEGIN
        SELECT standard_hash(v_string, 'MD5') INTO v_hash FROM dual;
        RETURN v_hash;
    END;
    
/*PUBLIC*/
    FUNCTION f_get_zasifrovane_heslo 
        (v_heslo IN VARCHAR2)
        RETURN VARCHAR2
        IS
    BEGIN
        RETURN f_get_zasifrovany_string(v_heslo);
    END;

/*PUBLIC*/
    PROCEDURE p_exec_login 
        (v_login IN VARCHAR2, 
        v_heslo IN VARCHAR2)
        IS
        v_heslo_login VARCHAR2(64);
        v_heslo_saved VARCHAR2(64);
    BEGIN
        SELECT heslo INTO v_heslo_saved 
            FROM zajemci WHERE login LIKE v_login;
        v_heslo_login := f_get_zasifrovane_heslo(v_heslo);
        IF (v_heslo_login <> v_heslo_saved) THEN
            raise_application_error(-20203, 'The requested login and password does not match');
        END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            raise_application_error(-20202, 'The requested login was not found');
        WHEN others THEN RAISE;
    END;

/*PUBLIC*/
    FUNCTION f_get_id_nadrizeneho
        (v_login_nadrizeneho IN VARCHAR2)
        RETURN NUMBER
        IS
        v_id_nadrizeneho NUMBER;
    BEGIN
        SELECT id_zajemce INTO v_id_nadrizeneho
            FROM zajemci WHERE login LIKE v_login_nadrizeneho;
        RETURN v_id_nadrizeneho;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN NULL;
        WHEN others THEN RAISE;
    END;
    
END pckg_login;
/