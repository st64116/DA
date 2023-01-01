-- STAVY
BEGIN
    INSERT INTO STAVY (id_stavu, nazev) VALUES (1, 'Požadováno');
    INSERT INTO STAVY (id_stavu, nazev) VALUES (2, 'Rezervováno');
    INSERT INTO STAVY (id_stavu, nazev) VALUES (3, 'Dokončeno');
    INSERT INTO STAVY (id_stavu, nazev) VALUES (4, 'Zrušeno');
    COMMIT;
END; 
/

-- VELIKOSTI
BEGIN
    p_insert_velikost('1 osoba');
    p_insert_velikost('2-5 osob');
    p_insert_velikost('6-15 osob');
    p_insert_velikost('15-30 osob');
END;
/

-- UMISTENI
BEGIN
    p_insert_umisteni('Koridor A');
    p_insert_umisteni('Koridor B');
    p_insert_umisteni('Chodba C');
END;
/

-- UCELY
BEGIN
    p_insert_ucel('Pracovní');
    p_insert_ucel('Konferenční');
    p_insert_ucel('Jiný');
END;
/

-- PATRA
BEGIN
    p_insert_patro('Přízemí');
    p_insert_patro('První');
    p_insert_patro('Druhé');
    p_insert_patro('Třetí');
END;
/

-- PRISLUSENSTVI
BEGIN
    p_insert_prislusenstvi('Konferenční stůl');
    p_insert_prislusenstvi('Pracovní stůl/stoly');
    p_insert_prislusenstvi('Osobní počítač/če');
    p_insert_prislusenstvi('Projektor');
    p_insert_prislusenstvi('Tabule');
    p_insert_prislusenstvi('Sedačka/ky');
    p_insert_prislusenstvi('Kávovar');
    p_insert_prislusenstvi('Automat na vodu');
END;
/

-- MISTNOSTI
BEGIN
    p_insert_mistnost('A01', 'Pracovní', 'Koridor A', 'Přízemí', '15-30 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('A02', 'Pracovní', 'Koridor A', 'Přízemí', '6-15 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Tabule;Automat na vodu');
    p_insert_mistnost('A03', 'Pracovní', 'Koridor A', 'Přízemí', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('A04', 'Pracovní', 'Koridor A', 'Přízemí', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('A05', 'Jiný', 'Koridor A', 'Přízemí', '1 osoba', 'Sedačka/ky');
    p_insert_mistnost('A11', 'Konferenční', 'Koridor A', 'První', '15-30 osob', 'Konferenční stůl;Projektor;Tabule;Kávovar;Automat na vodu');
    p_insert_mistnost('A12', 'Konferenční', 'Koridor A', 'První', '6-15 osob', 'Konferenční stůl;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('A13', 'Konferenční', 'Koridor A', 'První', '6-15 osob', 'Konferenční stůl;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('A14', 'Konferenční', 'Koridor A', 'První', '2-5 osob', 'Projektor;Tabule');
    p_insert_mistnost('A21', 'Pracovní', 'Koridor A', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('A22', 'Pracovní', 'Koridor A', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('A23', 'Pracovní', 'Koridor A', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če');
    p_insert_mistnost('A31', 'Konferenční', 'Koridor A', 'Třetí', '6-15 osob', 'Osobní počítač/če;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('A32', 'Konferenční', 'Koridor A', 'Třetí', '2-5 osob', 'Konferenční stůl;Projektor;Tabule');
    
    p_insert_mistnost('B01', 'Pracovní', 'Koridor B', 'Přízemí', '6-15 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('B02', 'Pracovní', 'Koridor B', 'Přízemí', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če');
    p_insert_mistnost('B03', 'Konferenční', 'Koridor B', 'Přízemí', '6-15 osob', 'Konferenční stůl;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('B11', 'Pracovní', 'Koridor B', 'První', '15-30 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Projektor;Tabule;Kávovar;Automat na vodu');
    p_insert_mistnost('B12', 'Pracovní', 'Koridor B', 'První', '6-15 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('B13', 'Jiný', 'Koridor B', 'První', '1 osoba', 'Sedačka/ky;Automat na vodu');
    p_insert_mistnost('B21', 'Pracovní', 'Koridor B', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Projektor');
    p_insert_mistnost('B22', 'Pracovní', 'Koridor B', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Projektor');
    
    p_insert_mistnost('C11', 'Konferenční', 'Chodba C', 'První', '6-15 osob', 'Konferenční stůl;Osobní počítač/če;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('C12', 'Konferenční', 'Chodba C', 'První', '6-15 osob', 'Konferenční stůl;Osobní počítač/če;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('C13', 'Konferenční', 'Chodba C', 'První', '2-5 osob', 'Konferenční stůl;Projektor');
    p_insert_mistnost('C21', 'Pracovní', 'Chodba C', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('C22', 'Pracovní', 'Chodba C', 'Druhé', '2-5 osob', 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
END;
/

-- OSOBY
BEGIN
    p_insert_osobu('z0001', 'josef.novak@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Josef', 'Novák');
    p_insert_osobu('z0002', 'pavel.novotny@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Pavel', 'Novotný');
    p_insert_osobu('z0003', 'karel.polak@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Karel', 'Polák');
    p_insert_osobu('z0004', 'martin.loupak@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Martin', 'Loupák');
    p_insert_osobu('z0005', 'tereza.fialova@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Tereza', 'Fialová');
    p_insert_osobu('n0001', 'veronika.loupakova@jinafirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Veronika', 'Loupáková');
    p_insert_osobu('z0006', 'barbora.hruba@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Barbora', 'Hrubá');
    p_insert_osobu('n0002', 'klara.bulova@jinafirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Klára', 'Bulová');
    p_insert_osobu('z0007', 'petr.krasny@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Petr', 'Krásný');
    p_insert_osobu('z0008', 'sara.vladykova@tatofirma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Sára', 'Vladyková');
END;
/

-- FIRMY
BEGIN
    p_insert_firmu('prvniFirma', 'kontakt@prvni-firma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'První Firma s.r.o.');
    p_insert_firmu('druhaFirma', 'kontakt@druha-firma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Druhá Firma a.s.');
    p_insert_firmu('tretiFirma', 'kontakt@treti-firma.cz',
        '56b1db8133d9eb398aabd376f07bf8ab5fc584ea0b8bd6a1770200cb613ca005',
        'Třetí Firma s.r.o.');
END;
/

/* TODO predelat - nefunguje
-- REZERVACE (v minulosti - musí být vypnutý triger t_rezervace_check_cas a t_rezervace_check_stav)
ALTER TRIGGER t_rezervace_check_cas DISABLE;
ALTER TRIGGER t_rezervace_check_stav_a_mistnost DISABLE;

DECLARE
    PROCEDURE p_insert_rezervaci
        (v_casOd IN DATE, v_casDo IN DATE, 
        v_zajemce IN VARCHAR2, v_stav IN VARCHAR2, v_mistnost IN VARCHAR2,
        v_ucel IN VARCHAR2, v_umisteni IN VARCHAR2, v_patro IN VARCHAR2,
        v_velikost IN VARCHAR2, v_prislusenstvi IN VARCHAR2) 
    IS
        v_id_zajemce NUMBER;
        v_id_skupiny NUMBER;
        v_id_stavu NUMBER;
    BEGIN
        -- predelat, funkce nefunguje, funkce se zmenila
        v_id_skupiny := pckg_rez_vlas_mist.f_add_vlastnost_pro_rezervace
            (v_ucel, v_umisteni, v_patro, v_velikost, v_prislusenstvi);
        SELECT id_zajemce INTO v_id_zajemce
            FROM zajemci WHERE login LIKE v_zajemce;
        SELECT id_stavu INTO v_id_stavu
            FROM stavy WHERE nazev LIKE v_stav;
        INSERT INTO REZERVACE 
            (casOd, casDo, id_zajemce, id_mistnosti, id_stavu, id_skupiny) 
            VALUES (v_casOd, v_casDo, v_id_zajemce, v_id_mistnosti, 
                v_id_stavu, v_id_skupiny);
    END;
BEGIN
    p_insert_rezervaci
        (TO_DATE('09.12.2021 15:35', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('09.12.2021 20:00', 'DD.MM.YYYY HH24:MI'),
        'n0002', 'Zrušeno', 'C21', 'Pracovní', 'Chodba C', null, '2-5 osob', null);
    
    p_insert_rezervaci
        (TO_DATE('12.02.2022 07:30', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('12.02.2022 14:00', 'DD.MM.YYYY HH24:MI'),
        'n0001', 'Dokončeno', 'A14', 'Konferenční', null, null, '2-5 osob', 'Projektor;Tabule');
    
    p_insert_rezervaci
        (TO_DATE('11.02.2022 07:30', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('11.02.2022 14:00', 'DD.MM.YYYY HH24:MI'),
        'n0001', 'Dokončeno', 'A32', 'Konferenční', null, null, '2-5 osob', 'Projektor;Tabule');
    
    p_insert_rezervaci
        (TO_DATE('13.02.2022 07:30', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('14.02.2022 15:30', 'DD.MM.YYYY HH24:MI'),
        'prvniFirma', 'Dokončeno', 'C11', 'Konferenční', 'Chodba C', null, '6-15 osob', null);
    
    p_insert_rezervaci
        (TO_DATE('09.12.2021 12:30', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('09.12.2021 16:00', 'DD.MM.YYYY HH24:MI'),
        'z0003', 'Dokončeno', 'A02', 'Pracovní', null, null, '6-15 osob', 'Automat na vodu');
    
    p_insert_rezervaci
        (TO_DATE('12.02.2022 08:00', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('12.02.2022 15:30', 'DD.MM.YYYY HH24:MI'),
        'z0002', 'Dokončeno', 'B11', 'Pracovní', null, null, '15-30 osob', null);
        
    p_insert_rezervaci
        (TO_DATE('12.02.2022 12:00', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('12.02.2022 14:00', 'DD.MM.YYYY HH24:MI'),
        'z0002', 'Zrušeno', null, 'Pracovní', null, null, '15-30 osob', null);
        
    p_insert_rezervaci
        (TO_DATE('09.12.2021 14:00', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('09.12.2021 16:30', 'DD.MM.YYYY HH24:MI'),
        'druhaFirma', 'Dokončeno', 'A11', 'Konferenční', null, null, '15-30 osob', null);
        
    p_insert_rezervaci
        (TO_DATE('11.02.2022 18:00', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('11.02.2022 19:00', 'DD.MM.YYYY HH24:MI'),
        'n0002', 'Dokončeno', 'A03', null, 'Koridor A', 'Přízemí', null, null);
        
    p_insert_rezervaci
        (TO_DATE('11.02.2022 13:30', 'DD.MM.YYYY HH24:MI'),
        TO_DATE('11.02.2022 13:45', 'DD.MM.YYYY HH24:MI'),
        'z0008', 'Dokončeno', 'B13', 'Jiný', 'Koridor B', null, null, null);
END;
ALTER TRIGGER t_rezervace_check_cas ENABLE;
ALTER TRIGGER t_rezervace_check_stav_a_mistnost ENABLE;
/
 */