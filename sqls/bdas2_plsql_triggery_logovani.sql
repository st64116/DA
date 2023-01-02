-- INSERT bez commitu
CREATE OR REPLACE PROCEDURE p_make_log
    (v_typ_operace IN VARCHAR2, v_tabulka IN VARCHAR2, v_info IN VARCHAR2)
    IS
BEGIN
    INSERT INTO logy (typ_operace, tabulka, info)
        VALUES (v_typ_operace, v_tabulka, v_info);
END;
/

--------------------------------------------------------------------------------------------

-- REZERVACE insert
CREATE OR REPLACE TRIGGER t_log_insert_rezervace
    AFTER INSERT ON rezervace
    REFERENCING
        NEW AS new
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
    v_od CHAR(16);
    v_do CHAR(16);
BEGIN
    v_od := TO_CHAR(:new.casOd, 'yyyy-mm-dd hh24:mi');
    v_do := TO_CHAR(:new.casDo, 'yyyy-mm-dd hh24:mi');
    v_info := '' || :new.id_rezervace || ', "' || v_od || '", "' || v_do || '", '
        || :new.id_mistnosti || ', ' || :new.id_zajemce || ', ' || :new.id_stavu || ', ' || :new.id_skupiny;
    P_MAKE_LOG('insert', 'rezervace', v_info);
END;
/

ALTER TRIGGER "T_LOG_INSERT_REZERVACE" ENABLE;

-- REZERVACE update
CREATE OR REPLACE TRIGGER t_log_update_rezervace
    AFTER UPDATE ON rezervace
    REFERENCING
        NEW AS new
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info_old VARCHAR2(1022);
    v_info_new VARCHAR2(1022);
    v_od_old CHAR(16);
    v_do_old CHAR(16);
    v_od_new CHAR(16);
    v_do_new CHAR(16);
BEGIN
    v_od_old := TO_CHAR(:old.casOd, 'yyyy-mm-dd hh24:mi');
    v_do_old := TO_CHAR(:old.casDo, 'yyyy-mm-dd hh24:mi');
    v_od_new := TO_CHAR(:new.casOd, 'yyyy-mm-dd hh24:mi');
    v_do_new := TO_CHAR(:new.casDo, 'yyyy-mm-dd hh24:mi');
    v_info_old := '' || :old.id_rezervace || ', "' || v_od_old || '", "' || v_do_old || '", '
        || :old.id_mistnosti || ', ' || :old.id_zajemce || ', ' || :old.id_stavu || ', ' || :old.id_skupiny;
    v_info_new := '' || :new.id_rezervace || ', "' || v_od_new || '", "' || v_do_new || '", '
        || :new.id_mistnosti || ', ' || :new.id_zajemce || ', ' || :new.id_stavu || ', ' || :new.id_skupiny;
    P_MAKE_LOG('update', 'rezervace', v_info_old || ' -> ' || v_info_new);
END;
/

ALTER TRIGGER "T_LOG_UPDATE_REZERVACE" ENABLE;

-- REZERVACE delete
CREATE OR REPLACE TRIGGER t_log_delete_rezervace
    AFTER DELETE ON rezervace
    REFERENCING
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
    v_od CHAR(16);
    v_do CHAR(16);
BEGIN
    v_od := TO_CHAR(:old.casOd, 'yyyy-mm-dd hh24:mi');
    v_do := TO_CHAR(:old.casDo, 'yyyy-mm-dd hh24:mi');
    v_info := '' || :old.id_rezervace || ', "' || v_od || '", "' || v_do || '", '
        || :old.id_mistnosti || ', ' || :old.id_zajemce || ', ' || :old.id_stavu || ', ' || :old.id_skupiny;
    P_MAKE_LOG('delete', 'rezervace', v_info);
END;
/

ALTER TRIGGER "T_LOG_DELETE_REZERVACE" ENABLE;

--------------------------------------------------------------------------------------------

-- ZAJEMCI insert
CREATE OR REPLACE TRIGGER t_log_insert_zajemci
    AFTER INSERT ON zajemci
    REFERENCING NEW AS new
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
BEGIN
    v_info := '' || :new.id_zajemce || ', "' || :new.login || '", "' || :new.email || '", "'
                  || :new.heslo || '", "' || :new.diskriminator || '", ' || :new.opravneni;
    P_MAKE_LOG('insert', 'zajemci', v_info);
END;
/

ALTER TRIGGER "T_LOG_INSERT_ZAJEMCI" ENABLE;

-- ZAJEMCI update
CREATE OR REPLACE TRIGGER t_log_update_zajemci
    AFTER UPDATE ON zajemci
    REFERENCING
        NEW AS new
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info_old VARCHAR2(1022);
    v_info_new VARCHAR2(1022);
BEGIN
    v_info_old := '' || :old.id_zajemce || ', "' || :old.login || '", "' || :old.email || '", "'
        || :old.heslo || '", "' || :old.diskriminator || '", ' || :old.opravneni;
    v_info_new := '' || :new.id_zajemce || ', "' || :new.login || '", "' || :new.email || '", "'
        || :new.heslo || '", "' || :new.diskriminator || '", ' || :new.opravneni;
    P_MAKE_LOG('update', 'zajemci', v_info_old || ' -> ' || v_info_new);
END;
/

ALTER TRIGGER "T_LOG_UPDATE_ZAJEMCI" ENABLE;

-- ZAJEMCI delete
CREATE OR REPLACE TRIGGER t_log_delete_zajemci
    AFTER DELETE ON zajemci
    REFERENCING OLD AS old
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
BEGIN
    v_info := '' || :old.id_zajemce || ', "' || :old.login || '", "' || :old.email || '", "'
        || :old.heslo || '", "' || :old.diskriminator || '", ' || :old.opravneni;
    P_MAKE_LOG('delete', 'zajemci', v_info);
END;
/

ALTER TRIGGER "T_LOG_DELETE_ZAJEMCI" ENABLE;

--------------------------------------------------------------------------------------------

-- OSOBY insert
CREATE OR REPLACE TRIGGER t_log_insert_osoby
    AFTER INSERT ON osoby
    REFERENCING
        NEW AS new
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
BEGIN
    v_info := '' || :new.id_zajemce || ', "' || :new.jmeno || '", "' || :new.prijmeni || '", '
        || :new.id_nadrizeneho;
    P_MAKE_LOG('insert', 'osoby', v_info);
END;
/

ALTER TRIGGER "T_LOG_INSERT_OSOBY" ENABLE;

-- OSOBY update
CREATE OR REPLACE TRIGGER t_log_update_osoby
    AFTER UPDATE ON osoby
    REFERENCING
        NEW AS new
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info_old VARCHAR2(1022);
    v_info_new VARCHAR2(1022);
BEGIN
    v_info_old := '' || :old.id_zajemce || ', "' || :old.jmeno || '", "' || :old.prijmeni || '", '
        || :old.id_nadrizeneho;
    v_info_new := '' || :new.id_zajemce || ', "' || :new.jmeno || '", "' || :new.prijmeni || '", '
        || :new.id_nadrizeneho;
    P_MAKE_LOG('update', 'osoby', v_info_old || ' -> ' || v_info_new);
END;
/

ALTER TRIGGER "T_LOG_UPDATE_OSOBY" ENABLE;

-- OSOBY delete
CREATE OR REPLACE TRIGGER t_log_delete_osoby
    AFTER DELETE ON osoby
    REFERENCING
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
BEGIN
    v_info := '' || :old.id_zajemce || ', "' || :old.jmeno || '", "' || :old.prijmeni || '", '
        || :old.id_nadrizeneho;
    P_MAKE_LOG('delete', 'osoby', v_info);
END;
/

ALTER TRIGGER "T_LOG_DELETE_OSOBY" ENABLE;

--------------------------------------------------------------------------------------------

-- FIRMY insertW
CREATE OR REPLACE TRIGGER t_log_insert_firmy
    AFTER INSERT ON firmy
    REFERENCING
        NEW AS new
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
BEGIN
    v_info := '' || :new.id_zajemce || ', "' || :new.nazev || '"';
    P_MAKE_LOG('insert', 'firmy', v_info);
END;
/

ALTER TRIGGER "T_LOG_INSERT_FIRMY" ENABLE;

-- FIRMY update
CREATE OR REPLACE TRIGGER t_log_update_firmy
    AFTER UPDATE ON firmy
    REFERENCING
        NEW AS new
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info_old VARCHAR2(1022);
    v_info_new VARCHAR2(1022);
BEGIN
    v_info_old := '' || :old.id_zajemce || ', "' || :old.nazev || '"';
    v_info_new := '' || :new.id_zajemce || ', "' || :new.nazev || '"';
    P_MAKE_LOG('update', 'firmy', v_info_old || ' -> ' || v_info_new);
END;
/

ALTER TRIGGER "T_LOG_UPDATE_FIRMY" ENABLE;

-- FIRMY delete
CREATE OR REPLACE TRIGGER t_log_delete_firmy
    AFTER DELETE ON firmy
    REFERENCING
        OLD AS old
    FOR EACH ROW
DECLARE
    v_info VARCHAR2(2042);
BEGIN
    v_info := '' || :old.id_zajemce || ', "' || :old.nazev || '"';
    P_MAKE_LOG('delete', 'firmy', v_info);
END;
/

ALTER TRIGGER "T_LOG_DELETE_FIRMY" ENABLE;