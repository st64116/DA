-- pomocny CHECK casy rezervace
CREATE OR REPLACE PROCEDURE p_check_casy_rezervace
    (v_od IN DATE, v_do IN DATE)
    IS
BEGIN
    IF ( v_od < sysdate + INTERVAL '5' MINUTE ) THEN
        raise_application_error(-20001, 'The reservation start time must be at least 5 minutes in the future');
    END IF;

    IF ( v_od > v_do ) THEN
        raise_application_error(-20002, 'The start time must not be later than the end time');
    END IF;

    IF ( v_do < sysdate ) THEN
        raise_application_error(-20003, 'The end time must not be in the past');
    END IF;
END;

-- REZERVACE CHECK CAS
CREATE OR REPLACE TRIGGER t_rezervace_check_cas
    BEFORE INSERT ON rezervace
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    P_CHECK_CASY_REZERVACE(:new.casOd, :new.casDo);
END;

ALTER TRIGGER "T_REZERVACE_CHECK_CAS" ENABLE;

-- REZERVACE CHECK stav s mistnost
CREATE OR REPLACE TRIGGER t_rezervace_check_validitu_a_stav 
    BEFORE INSERT OR UPDATE ON rezervace
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_stavu NOT BETWEEN 1 AND 4) THEN
        raise_application_error(-20012, 'The reservation must be in one of the integrated states');
    END IF;

    /* je reseno tim ze stav se meni automaticky(tam se toto testuje), v triggeru nelze spustis z duvodu dotazu na table rezervace
    IF (:new.id_stavu = 2) THEN
        IF (pckg_rez_vlas_mist.f_check_kolize_rezervace(:new.id_rezervace, :new.id_mistnosti) = 1) THEN
            raise_application_error(-20006, 'The reservation can not have the same room and overlapping times with another reservation');
        END IF;
    END IF;
    */

    IF (:new.id_stavu = 2 OR :new.id_stavu = 3) THEN
        IF (:new.id_mistnosti IS NULL) THEN
            raise_application_error(-20008, 'The reservation in reserved or completed state must have an assigned room');
        END IF;
    END IF;
END;
/

ALTER TRIGGER "T_REZERVACE_CHECK_VALIDITU_A_STAV" ENABLE;

-- ZAJEMCI CHECK opravneni (musí být 0 - uzivatel)

CREATE OR REPLACE TRIGGER t_zajemci_check_opravneni
    BEFORE INSERT ON zajemci
    REFERENCING NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.opravneni <> 0 ) THEN
        raise_application_error(-20004, 'Authorization must be at level 0 when inserting a user');
    END IF;
END;
/

ALTER TRIGGER "T_ZAJEMCI_CHECK_OPRAVNENI" ENABLE;

-- PRISLUSENSTVI CHECK nazev (znak ; je interne pouzivan jako oddelovac)

CREATE OR REPLACE TRIGGER t_prislusenstvi_check_nazev
    BEFORE INSERT OR UPDATE ON prislusenstvi
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( INSTR(:new.nazev, ';') <> 0) THEN
        raise_application_error(-20007, 'The accessory must not contain semicolon in its name');
    END IF;
END;
/

ALTER TRIGGER "T_PRISLUSENSTVI_CHECK_NAZEV" ENABLE;

-- VLASTNOSTI CHECK mistnosti.vlastnosti a rezervace.vlastnosti 
    -- mistnosti musi mit vsechny vlastnosti (krom prislus)
    -- vlastnosti rezervace musi byt podmnouzinou vlastnosti mistnosti

CREATE OR REPLACE TRIGGER t_vlastnosti_check_validitu
    BEFORE INSERT OR UPDATE ON skupiny_vlastnosti
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF (:new.patri IS NULL OR :new.patri NOT IN ('mistnosti', 'rezervaci')) THEN
        raise_application_error(-20010, 'Propreties must belong to room or reservation');
    END IF;

    IF ((:new.patri LIKE 'mistnosti') 
        AND (:new.id_ucelu IS NULL OR :new.id_umisteni IS NULL 
            OR :new.id_patra IS NULL OR :new.id_velikosti IS NULL)) THEN
        raise_application_error(-20005, 'The room must have all properties except accessories');
    END IF;
    /* opravit
    IF (:new.patri LIKE 'rezervaci' 
        AND pckg_rez_vlas_mist.f_check_vlastnosti_podmnozinou(:new.id_skupiny) = 1) THEN
            raise_application_error(-20011, 'The reservation properties must be subset of existing room properties');
    END IF;
    */
END;
/

ALTER TRIGGER "T_VLASTNOSTI_CHECK_VALIDITU" ENABLE;

-- STAVY CHECK integritni stavy

CREATE OR REPLACE TRIGGER t_stavy_delete_check_integritu
    BEFORE DELETE ON stavy
    REFERENCING
        OLD AS old
    FOR EACH ROW
BEGIN
    IF (:old.id_stavu IN (1,2,3,4)) THEN
        raise_application_error(-20009, 'The integrity status cannot be deleted');
    END IF;
END;
/

ALTER TRIGGER "T_STAVY_DELETE_CHECK_INTEGRITU" ENABLE;