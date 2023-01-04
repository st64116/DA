-- REZERVACE
CREATE OR REPLACE VIEW view_rezervace AS
    SELECT id_rezervace as "ID", 
        to_char(re.casOd, 'yyyy-mm-dd hh24:mi') as "Od", 
        to_char(re.casDo, 'yyyy-mm-dd hh24:mi') as "Do", za.login "Zajemce",
        st.nazev as "Stav", mi.nazev as "Mistnost", uc.nazev as "Ucel",
        um.nazev as "Umisteni", pa.nazev as "Patro", ve.nazev as "Velikost",
        (pckg_rez_vlas_mist.f_get_concat_prislusenstvi(vl.id_skupiny)) 
            as "Prislusenstvi"
        FROM rezervace re
        JOIN stavy st ON (re.id_stavu = st.id_stavu)
        LEFT JOIN mistnosti mi ON (re.id_mistnosti = mi.id_mistnosti)
        JOIN skupiny_vlastnosti vl ON (re.id_skupiny = vl.id_skupiny)
        LEFT JOIN ucely uc ON (vl.id_ucelu = uc.id_ucelu)
        LEFT JOIN umisteni um ON (vl.id_umisteni = um.id_umisteni)
        LEFT JOIN patra pa ON (vl.id_patra = pa.id_patra)
        LEFT JOIN velikosti ve ON (vl.id_velikosti = ve.id_velikosti)
        JOIN zajemci za ON (re.id_zajemce = za.id_zajemce);

-- MISTNOSTI
CREATE OR REPLACE VIEW view_mistnosti AS
    SELECT mi.id_mistnosti, mi.nazev as "Mistnost", uc.nazev as "Ucel", 
        um.nazev as "Umisteni", pa.nazev as "Patro", ve.nazev as "Velikost", 
        (pckg_rez_vlas_mist.f_get_concat_prislusenstvi(vl.id_skupiny)) 
            as "Prislusenstvi"
        FROM mistnosti mi
        JOIN skupiny_vlastnosti vl ON (mi.id_skupiny = vl.id_skupiny)
        JOIN ucely uc ON (vl.id_ucelu = uc.id_ucelu)
        JOIN umisteni um ON (vl.id_umisteni = um.id_umisteni)
        JOIN patra pa ON (vl.id_patra = pa.id_patra)
        JOIN velikosti ve ON (vl.id_velikosti = ve.id_velikosti);
    
-- ZAJEMCE
CREATE OR REPLACE VIEW view_zajemce AS
    SELECT login, email, opravneni, nazev, jmeno, prijmeni, detail, id_profilovky,
        (SELECT n.login FROM zajemci n WHERE n.id_zajemce = o.id_nadrizeneho) 
        AS nadrizeny
            FROM zajemci z
            LEFT JOIN firmy f USING (id_zajemce)
            LEFT JOIN osoby o USING (id_zajemce);
    
-- FIRMY
CREATE OR REPLACE VIEW view_firmy AS
    SELECT login, email, nazev, opravneni, id_profilovky
        FROM zajemci JOIN firmy USING (id_zajemce);
        
-- OSOBY
CREATE OR REPLACE VIEW view_osoby AS
    SELECT login, email, jmeno, prijmeni, detail, opravneni, id_profilovky,
        (SELECT n.login FROM zajemci n WHERE n.id_zajemce = o.id_nadrizeneho) 
        AS nadrizeny
            FROM zajemci z JOIN osoby o USING (id_zajemce);
            
-- UCELY
CREATE OR REPLACE VIEW view_ucely AS
    SELECT id_ucelu, nazev FROM ucely;
    
-- PATRA
CREATE OR REPLACE VIEW view_patra AS
    SELECT id_patra, nazev FROM patra;
    
-- UMISTENI
CREATE OR REPLACE VIEW view_umisteni AS
    SELECT id_umisteni, nazev FROM umisteni;
    
-- VELIKOSTI
CREATE OR REPLACE VIEW view_velikosti AS
    SELECT id_velikosti, nazev FROM velikosti;
    
-- PRISLUSENSTVI
CREATE OR REPLACE VIEW view_prislusenstvi AS
    SELECT id_prislusenstvi, nazev FROM prislusenstvi;

-- STAVY
CREATE OR REPLACE VIEW view_stavy AS
    SELECT id_stavu, nazev FROM stavy;

-- LOGY
CREATE OR REPLACE VIEW view_logy AS
    SELECT id_logu, typ_operace, tabulka, info,
        TO_CHAR(cas, 'yyyy-mm-dd hh24:mi:ss') AS cas FROM logy
    ORDER BY cas DESC;

-- SOUBORY
CREATE OR REPLACE VIEW view_soubory AS
    SELECT id_souboru, nazev, pripona, obsah FROM soubory;

CREATE OR REPLACE VIEW view_profilovky AS
    SELECT login, nazev, pripona, obsah
        FROM zajemci z JOIN soubory s ON z.id_profilovky = s.id_souboru;

-- HIERARCHIE

CREATE OR REPLACE VIEW view_zajemce_hierarchicky AS
    SELECT login, jmeno, prijmeni, nazev,
           LEVEL as poradi, PRIOR login as nadrizeny, CONNECT_BY_ROOT login as koren
    FROM zajemci
    LEFT JOIN osoby USING (id_zajemce)
    LEFT JOIN firmy USING (id_zajemce)
        CONNECT BY id_nadrizeneho = PRIOR id_zajemce
        ORDER SIBLINGS BY login;


CREATE OR REPLACE VIEW view_rezervace_hierarchicky AS
    SELECT id_rezervace, casOd, casDo, id_mistnosti, id_stavu,
           id_skupiny, login, CONNECT_BY_ROOT login as koren
    FROM zajemci
    LEFT JOIN osoby USING (id_zajemce)
    LEFT JOIN firmy USING (id_zajemce)
    JOIN rezervace USING (id_zajemce)
        CONNECT BY id_nadrizeneho = PRIOR id_zajemce;
