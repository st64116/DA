-- Generated by Oracle SQL Developer Data Modeler 20.4.1.406.0906
--   at:        2021-12-10 18:53:46 CET
--   site:      Oracle Database 11g
--   type:      Oracle Database 11g



-- predefined type, no DDL - MDSYS.SDO_GEOMETRY

-- predefined type, no DDL - XMLTYPE

CREATE TABLE firmy (
    id_zajemce  NUMBER NOT NULL,
    nazev       VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE firmy ADD CONSTRAINT firmy_pk PRIMARY KEY ( id_zajemce );

ALTER TABLE firmy ADD CONSTRAINT firmy_nazev_un UNIQUE ( nazev );

CREATE TABLE inventare (
    id_skupiny        NUMBER NOT NULL,
    id_prislusenstvi  NUMBER NOT NULL
);

ALTER TABLE inventare ADD CONSTRAINT inventare_pk PRIMARY KEY ( id_skupiny,
                                                                id_prislusenstvi );

CREATE TABLE mistnosti (
    id_mistnosti  NUMBER NOT NULL,
    nazev         VARCHAR2(32 CHAR) NOT NULL,
    id_skupiny    NUMBER NOT NULL
);

ALTER TABLE mistnosti ADD CONSTRAINT mistnosti_pk PRIMARY KEY ( id_mistnosti );

ALTER TABLE mistnosti ADD CONSTRAINT mistnosti_nazev_un UNIQUE ( nazev );

CREATE TABLE osoby (
    id_zajemce  NUMBER NOT NULL,
    jmeno       VARCHAR2(16 CHAR) NOT NULL,
    prijmeni    VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE osoby ADD CONSTRAINT osoby_pk PRIMARY KEY ( id_zajemce );

CREATE TABLE patra (
    id_patra  NUMBER NOT NULL,
    nazev     VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE patra ADD CONSTRAINT patra_pk PRIMARY KEY ( id_patra );

ALTER TABLE patra ADD CONSTRAINT patra_nazev_un UNIQUE ( nazev );

CREATE TABLE prislusenstvi (
    id_prislusenstvi  NUMBER NOT NULL,
    nazev             VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE prislusenstvi ADD CONSTRAINT prislusenstvi_pk PRIMARY KEY ( id_prislusenstvi );

ALTER TABLE prislusenstvi ADD CONSTRAINT prislusenstvi_nazev_un UNIQUE ( nazev );

CREATE TABLE rezervace (
    id_rezervace  NUMBER NOT NULL,
    casod         DATE NOT NULL,
    casdo         DATE NOT NULL,
    id_mistnosti  NUMBER,
    id_zajemce    NUMBER NOT NULL,
    id_stavu      NUMBER NOT NULL,
    id_skupiny    NUMBER NOT NULL
);

ALTER TABLE rezervace ADD CONSTRAINT rezervace_pk PRIMARY KEY ( id_rezervace );

CREATE TABLE skupiny_vlastnosti (
    id_skupiny    NUMBER NOT NULL,
    id_velikosti  NUMBER,
    id_umisteni   NUMBER,
    id_ucelu      NUMBER,
    id_patra      NUMBER
);

ALTER TABLE skupiny_vlastnosti ADD CONSTRAINT skupiny_pk PRIMARY KEY ( id_skupiny );

CREATE TABLE stavy (
    id_stavu  NUMBER NOT NULL,
    nazev     VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE stavy ADD CONSTRAINT stavy_pk PRIMARY KEY ( id_stavu );

ALTER TABLE stavy ADD CONSTRAINT stavy_nazev_un UNIQUE ( nazev );

CREATE TABLE ucely (
    id_ucelu  NUMBER NOT NULL,
    nazev     VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE ucely ADD CONSTRAINT ucely_pk PRIMARY KEY ( id_ucelu );

ALTER TABLE ucely ADD CONSTRAINT ucely_nazev_un UNIQUE ( nazev );

CREATE TABLE umisteni (
    id_umisteni  NUMBER NOT NULL,
    nazev        VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE umisteni ADD CONSTRAINT umisteni_pk PRIMARY KEY ( id_umisteni );

ALTER TABLE umisteni ADD CONSTRAINT umisteni_nazev_un UNIQUE ( nazev );

CREATE TABLE velikosti (
    id_velikosti  NUMBER NOT NULL,
    nazev         VARCHAR2(32 CHAR) NOT NULL
);

ALTER TABLE velikosti ADD CONSTRAINT velikosti_pk PRIMARY KEY ( id_velikosti );

ALTER TABLE velikosti ADD CONSTRAINT velikosti_nazev_un UNIQUE ( nazev );

CREATE TABLE zajemci (
    id_zajemce     NUMBER NOT NULL,
    login          VARCHAR2(32 CHAR) NOT NULL,
    email          VARCHAR2(128 CHAR),
    heslo          CHAR(64 CHAR) NOT NULL,
    diskriminator  VARCHAR2(5) NOT NULL
);

ALTER TABLE zajemci
    ADD CONSTRAINT ch_inh_zajemci CHECK ( diskriminator IN ( 'FIRMY', 'OSOBY' ) );

ALTER TABLE zajemci
    ADD CONSTRAINT zajemci_email_check CHECK ( email IS NULL
                                               OR email LIKE '%@%.%' );

ALTER TABLE zajemci ADD CONSTRAINT zajemci_pk PRIMARY KEY ( id_zajemce );

ALTER TABLE zajemci ADD CONSTRAINT zajemci_email_un UNIQUE ( email );

ALTER TABLE zajemci ADD CONSTRAINT zajemci_login_un UNIQUE ( login );

ALTER TABLE firmy
    ADD CONSTRAINT firmy_zajemci_fk FOREIGN KEY ( id_zajemce )
        REFERENCES zajemci ( id_zajemce )
            ON DELETE CASCADE;

ALTER TABLE inventare
    ADD CONSTRAINT inventare_predmety_fk FOREIGN KEY ( id_prislusenstvi )
        REFERENCES prislusenstvi ( id_prislusenstvi );

ALTER TABLE inventare
    ADD CONSTRAINT inventare_skupiny_fk FOREIGN KEY ( id_skupiny )
        REFERENCES skupiny_vlastnosti ( id_skupiny )
            ON DELETE CASCADE;

ALTER TABLE mistnosti
    ADD CONSTRAINT mistnosti_skupiny_fk FOREIGN KEY ( id_skupiny )
        REFERENCES skupiny_vlastnosti ( id_skupiny );

ALTER TABLE osoby
    ADD CONSTRAINT osoby_zajemci_fk FOREIGN KEY ( id_zajemce )
        REFERENCES zajemci ( id_zajemce )
            ON DELETE CASCADE;

ALTER TABLE rezervace
    ADD CONSTRAINT rezervace_mistnosti_fk FOREIGN KEY ( id_mistnosti )
        REFERENCES mistnosti ( id_mistnosti );

ALTER TABLE rezervace
    ADD CONSTRAINT rezervace_skupiny_fk FOREIGN KEY ( id_skupiny )
        REFERENCES skupiny_vlastnosti ( id_skupiny );

ALTER TABLE rezervace
    ADD CONSTRAINT rezervace_stavy_fk FOREIGN KEY ( id_stavu )
        REFERENCES stavy ( id_stavu );

ALTER TABLE rezervace
    ADD CONSTRAINT rezervace_zajemci_fk FOREIGN KEY ( id_zajemce )
        REFERENCES zajemci ( id_zajemce );

ALTER TABLE skupiny_vlastnosti
    ADD CONSTRAINT skupiny_patra_fk FOREIGN KEY ( id_patra )
        REFERENCES patra ( id_patra );

ALTER TABLE skupiny_vlastnosti
    ADD CONSTRAINT skupiny_ucely_fk FOREIGN KEY ( id_ucelu )
        REFERENCES ucely ( id_ucelu );

ALTER TABLE skupiny_vlastnosti
    ADD CONSTRAINT skupiny_umisteni_fk FOREIGN KEY ( id_umisteni )
        REFERENCES umisteni ( id_umisteni );

ALTER TABLE skupiny_vlastnosti
    ADD CONSTRAINT skupiny_velikosti_fk FOREIGN KEY ( id_velikosti )
        REFERENCES velikosti ( id_velikosti );

CREATE OR REPLACE TRIGGER arc_fkarc_1_osoby BEFORE
    INSERT OR UPDATE OF id_zajemce ON osoby
    FOR EACH ROW
DECLARE
    d VARCHAR2(5);
BEGIN
    SELECT
        a.diskriminator
    INTO d
    FROM
        zajemci a
    WHERE
        a.id_zajemce = :new.id_zajemce;

    IF ( d IS NULL OR d <> 'OSOBY' ) THEN
        raise_application_error(-20223,
                               'FK OSOBY_ZAJEMCI_FK in Table OSOBY violates Arc constraint on Table ZAJEMCI - discriminator column diskriminator doesn''t have value ''OSOBY''');
    END IF;

EXCEPTION
    WHEN no_data_found THEN
        NULL;
    WHEN OTHERS THEN
        RAISE;
END;
/

CREATE OR REPLACE TRIGGER arc_fkarc_1_firmy BEFORE
    INSERT OR UPDATE OF id_zajemce ON firmy
    FOR EACH ROW
DECLARE
    d VARCHAR2(5);
BEGIN
    SELECT
        a.diskriminator
    INTO d
    FROM
        zajemci a
    WHERE
        a.id_zajemce = :new.id_zajemce;

    IF ( d IS NULL OR d <> 'FIRMY' ) THEN
        raise_application_error(-20223,
                               'FK FIRMY_ZAJEMCI_FK in Table FIRMY violates Arc constraint on Table ZAJEMCI - discriminator column diskriminator doesn''t have value ''FIRMY''');
    END IF;

EXCEPTION
    WHEN no_data_found THEN
        NULL;
    WHEN OTHERS THEN
        RAISE;
END;
/

CREATE SEQUENCE s_mistnosti START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_mistnosti_id BEFORE
    INSERT ON mistnosti
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_mistnosti IS NULL ) THEN
        SELECT
            s_mistnosti.NEXTVAL
        INTO :new.id_mistnosti
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_MISTNOSTI_ID" ENABLE;

CREATE SEQUENCE s_patra START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_patra_id BEFORE
    INSERT ON patra
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_patra IS NULL ) THEN
        SELECT
            s_patra.NEXTVAL
        INTO :new.id_patra
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_PATRA_ID" ENABLE;

CREATE SEQUENCE s_prislusenstvi START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_prislusenstvi_id BEFORE
    INSERT ON prislusenstvi
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_prislusenstvi IS NULL ) THEN
        SELECT
            s_prislusenstvi.NEXTVAL
        INTO :new.id_prislusenstvi
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_PRISLUSENSTVI_ID" ENABLE;

CREATE SEQUENCE s_rezervace START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_rezervace_id BEFORE
    INSERT ON rezervace
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_rezervace IS NULL ) THEN
        SELECT
            s_rezervace.NEXTVAL
        INTO :new.id_rezervace
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_REZERVACE_ID" ENABLE;

CREATE OR REPLACE TRIGGER t_rezervace_check_cas BEFORE
    INSERT OR UPDATE ON rezervace
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.casod < sysdate + INTERVAL '30' MINUTE ) THEN
        raise_application_error(-20001, '?as za?�tku rezervace mus� b�t minim�ln? 30 minut v budoucnosti.');
    END IF;

    IF ( :new.casod > :new.casdo ) THEN
        raise_application_error(-20002, '?as za?�tku nesm� b�t a� po ?ase ukon?en�.');
    END IF;

    IF ( :new.casdo < sysdate ) THEN
        raise_application_error(-20003, '?as ukon?en� nesm� b�t v minulosti.');
    END IF;

END;
/

ALTER TRIGGER "T_REZERVACE_CHECK_CAS" ENABLE;

CREATE SEQUENCE s_skupiny START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_skupiny_id BEFORE
    INSERT ON skupiny_vlastnosti
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_skupiny IS NULL ) THEN
        SELECT
            s_skupiny.NEXTVAL
        INTO :new.id_skupiny
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_SKUPINY_ID" ENABLE;

CREATE SEQUENCE s_stavy START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_stavy_id BEFORE
    INSERT ON stavy
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_stavu IS NULL ) THEN
        SELECT
            s_stavy.NEXTVAL
        INTO :new.id_stavu
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_STAVY_ID" ENABLE;

CREATE SEQUENCE s_ucely START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_ucely_id BEFORE
    INSERT ON ucely
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_ucelu IS NULL ) THEN
        SELECT
            s_ucely.NEXTVAL
        INTO :new.id_ucelu
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_UCELY_ID" ENABLE;

CREATE SEQUENCE s_umisteni START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_umisteni_id BEFORE
    INSERT ON umisteni
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_umisteni IS NULL ) THEN
        SELECT
            s_umisteni.NEXTVAL
        INTO :new.id_umisteni
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_UMISTENI_ID" ENABLE;

CREATE SEQUENCE s_velikosti START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_velikosti_id BEFORE
    INSERT ON velikosti
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_velikosti IS NULL ) THEN
        SELECT
            s_velikosti.NEXTVAL
        INTO :new.id_velikosti
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_VELIKOSTI_ID" ENABLE;

CREATE SEQUENCE s_zajemci START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_zajemci_id BEFORE
    INSERT ON zajemci
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_zajemce IS NULL ) THEN
        SELECT
            s_zajemci.NEXTVAL
        INTO :new.id_zajemce
        FROM
            dual;

    END IF;
END;
/

ALTER TRIGGER "T_ZAJEMCI_ID" ENABLE;

-- CREATED MANUALY
-- semestralka BDAS2

