-- INSERT
CREATE OR REPLACE PROCEDURE p_make_log
    (v_typ_operace IN VARCHAR2, v_tabulka IN VARCHAR2, v_info IN VARCHAR2)
    IS
BEGIN
    INSERT INTO logy (typ_operace, tabulka, info)
        VALUES (v_typ_operace, v_tabulka, v_info);
END;
/

-- REZERVACE

    -- TODO doplnit rezervace

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

-- OSOBY

    -- TODO doplnit osoby

-- FIRMY

    -- TODO doplnit firmy