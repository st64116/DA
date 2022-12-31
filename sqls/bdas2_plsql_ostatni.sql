
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

-- CHECK dostupnost a stav REZERVACE

CREATE OR REPLACE PROCEDURE p_check_dostupnost_rezervace
    (v_id_rezervace IN NUMBER)
    IS
    v_aktualni_stav NUMBER;
    v_novy_stav NUMBER;
    v_id_mistnosti NUMBER;
BEGIN
    SELECT id_stavu INTO v_aktualni_stav 
        FROM rezervace WHERE id_rezervace = v_id_rezervace;
    v_novy_stav := pckg_rez_vlas_mist.
        f_check_stav_rezervace(v_id_rezervace, v_id_mistnosti);
    IF (v_novy_stav <> v_aktualni_stav) THEN
        UPDATE rezervace 
            SET id_stavu = v_aktualni_stav, id_mistnosti = v_id_mistnosti
            WHERE id_rezervace = v_id_rezervace;
    END IF;
END;
/