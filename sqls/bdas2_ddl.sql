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