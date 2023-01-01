-- REZERVACE default pro id_stavu

ALTER TABLE rezervace
    MODIFY id_stavu NUMBER DEFAULT 1;

-- ZAJEMCI ADD opravneni

ALTER TABLE zajemci 
    ADD opravneni NUMBER
    DEFAULT 0 NOT NULL;
    
-- VLASTNOSTI ADD patri

ALTER TABLE skupiny_vlastnosti 
    ADD patri CHAR(9) NOT NULL;

-- ZAJEMCI ADD nadrizeny 

ALTER TABLE osoby
    ADD id_nadrizeneho NUMBER 
    CONSTRAINT NADRIZENY_ZAJEMCI_FK REFERENCES zajemci (id_zajemce);

-- OSOBY ADD detail

ALTER TABLE osoby
    ADD detail NUMBER 
    DEFAULT 0 NOT NULL;

-- LOGY ADD tabulku

CREATE TABLE logy (
    id_logu NUMBER NOT NULL,
    typ_operace VARCHAR2(6) NOT NULL,
    tabulka VARCHAR2(32) NOT NULL,
    info VARCHAR2(1024) NOT NULL,
    cas DATE DEFAULT sysdate NOT NULL
);

ALTER TABLE logy ADD CONSTRAINT logy_pk PRIMARY KEY ( id_logu );

-- LOGY ADD sekvenci

CREATE SEQUENCE s_logy START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER t_logy_id BEFORE
    INSERT ON logy
    REFERENCING
        NEW AS new
    FOR EACH ROW
BEGIN
    IF ( :new.id_logu IS NULL ) THEN
        SELECT s_mistnosti.NEXTVAL INTO :new.id_logu
            FROM DUAL;
    END IF;
END;
/

ALTER TRIGGER "T_LOGY_ID" ENABLE;

-- SOUBORY

    -- TODO pridat soubory
