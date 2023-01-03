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
DECLARE
    ucel_pracovni NUMBER;
    ucel_konferencni NUMBER;
    ucel_jiny NUMBER;
    umisteni_A NUMBER;
    umisteni_B NUMBER;
    umisteni_C NUMBER;
    patro_prizemi NUMBER;
    patro_prvni NUMBER;
    patro_druhe NUMBER;
    patro_treti NUMBER;
    velikost_1 NUMBER;
    velikost_2 NUMBER;
    velikost_3 NUMBER;
    velikost_4 NUMBER;
BEGIN
    SELECT id_ucelu INTO ucel_pracovni FROM ucely WHERE nazev LIKE 'Pracovní';
    SELECT id_ucelu INTO ucel_konferencni FROM ucely WHERE nazev LIKE 'Konferenční';
    SELECT id_ucelu INTO ucel_jiny FROM ucely WHERE nazev LIKE 'Jiný';
    SELECT id_umisteni INTO umisteni_A FROM umisteni WHERE nazev LIKE 'Koridor A';
    SELECT id_umisteni INTO umisteni_B FROM umisteni WHERE nazev LIKE 'Koridor B';
    SELECT id_umisteni INTO umisteni_C FROM umisteni WHERE nazev LIKE 'Chodba C';
    SELECT id_patra INTO patro_prizemi FROM patra WHERE nazev LIKE 'Přízemí';
    SELECT id_patra INTO patro_prvni FROM patra WHERE nazev LIKE 'První';
    SELECT id_patra INTO patro_druhe FROM patra WHERE nazev LIKE 'Druhé';
    SELECT id_patra INTO patro_treti FROM patra WHERE nazev LIKE 'Třetí';
    SELECT id_velikosti INTO velikost_1 FROM velikosti WHERE nazev LIKE '1 osoba';
    SELECT id_velikosti INTO velikost_2 FROM velikosti WHERE nazev LIKE '2-5 osob';
    SELECT id_velikosti INTO velikost_3 FROM velikosti WHERE nazev LIKE '6-15 osob';
    SELECT id_velikosti INTO velikost_4 FROM velikosti WHERE nazev LIKE '15-30 osob';

    p_insert_mistnost('A01', ucel_pracovni, umisteni_A, patro_prizemi, velikost_4, 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('A02', ucel_pracovni, umisteni_A, patro_prizemi, velikost_3, 'Pracovní stůl/stoly;Osobní počítač/če;Tabule;Automat na vodu');
    p_insert_mistnost('A03', ucel_pracovni, umisteni_A, patro_prizemi, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('A04', ucel_pracovni, umisteni_A, patro_prizemi, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('A05', ucel_jiny, umisteni_A, patro_prizemi, velikost_1, 'Sedačka/ky');
    p_insert_mistnost('A11', ucel_konferencni, umisteni_A, patro_prvni, velikost_4, 'Konferenční stůl;Projektor;Tabule;Kávovar;Automat na vodu');
    p_insert_mistnost('A12', ucel_konferencni, umisteni_A, patro_prvni, velikost_3, 'Konferenční stůl;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('A13', ucel_konferencni, umisteni_A, patro_prvni, velikost_3, 'Konferenční stůl;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('A14', ucel_konferencni, umisteni_A, patro_prvni, velikost_2, 'Projektor;Tabule');
    p_insert_mistnost('A21', ucel_pracovni, umisteni_A, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('A22', ucel_pracovni, umisteni_A, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('A23', ucel_pracovni, umisteni_A, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če');
    p_insert_mistnost('A31', ucel_konferencni, umisteni_A, patro_treti, velikost_3, 'Osobní počítač/če;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('A32', ucel_konferencni, umisteni_A, patro_treti, velikost_2, 'Konferenční stůl;Projektor;Tabule');
    
    p_insert_mistnost('B01', ucel_pracovni, umisteni_B, patro_prizemi, velikost_3, 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('B02', ucel_pracovni, umisteni_B, patro_prizemi, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če');
    p_insert_mistnost('B03', ucel_konferencni, umisteni_B, patro_prizemi, velikost_3, 'Konferenční stůl;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('B11', ucel_pracovni, umisteni_B, patro_prvni, velikost_4, 'Pracovní stůl/stoly;Osobní počítač/če;Projektor;Tabule;Kávovar;Automat na vodu');
    p_insert_mistnost('B12', ucel_pracovni, umisteni_B, patro_prvni, velikost_3, 'Pracovní stůl/stoly;Osobní počítač/če;Automat na vodu');
    p_insert_mistnost('B13', ucel_jiny, umisteni_B, patro_prvni, velikost_1, 'Sedačka/ky;Automat na vodu');
    p_insert_mistnost('B21', ucel_pracovni, umisteni_B, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Projektor');
    p_insert_mistnost('B22', ucel_pracovni, umisteni_B, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Projektor');
    
    p_insert_mistnost('C11', ucel_konferencni, umisteni_C, patro_prvni, velikost_3, 'Konferenční stůl;Osobní počítač/če;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('C12', ucel_konferencni, umisteni_C, patro_prvni, velikost_3, 'Konferenční stůl;Osobní počítač/če;Projektor;Tabule;Automat na vodu');
    p_insert_mistnost('C13', ucel_konferencni, umisteni_C, patro_prvni, velikost_2, 'Konferenční stůl;Projektor');
    p_insert_mistnost('C21', ucel_pracovni, umisteni_C, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
    p_insert_mistnost('C22', ucel_pracovni, umisteni_C, patro_druhe, velikost_2, 'Pracovní stůl/stoly;Osobní počítač/če;Tabule');
END;
/

-- FIRMY
BEGIN
    p_insert_firmu('CzechSoft', 'podpora@czechsoft.eu',
        'czech1soft2heslo','CzechSoft s.r.o.');

    p_insert_firmu('HezkyACesky', 'informace@hezkyacesky.cz',
        'cutepassnotveryczech123','Hezky A Česky a.s.');

    p_insert_firmu('Kavantena', 'kontakt@kavantena.cz',
        'k4v4nt6n4','Kavanténa s.r.o.');

    p_insert_firmu('HrubkaASyn', 'info@hrubkaasyn.cz',
        'silneheslo123','Hrubka a syn s.r.o.');
END;
/

-- OSOBY
BEGIN
    p_insert_osobu('josef.novak', 'josef.novak@czechsoft.eu','YNrWBqn4Ek',
                   'Josef', 'Novák', 'CzechSoft');

    p_insert_osobu('pavel.novotny', 'pavel.novotny@czechsoft.eu','RjuQvwm1mC',
                   'Pavel', 'Novotný', 'josef.novak');

    p_insert_osobu('karel.polak', 'karel.polak@czechsoft.eu','vRH7NsUWZm',
                   'Karel', 'Polák', 'pavel.novotny');

    p_insert_osobu('tereza.fialova', 'tereza.fialova@czechsoft.eu','u69K9c7gba',
                   'Tereza', 'Fialová', 'CzechSoft');

    p_insert_osobu('martin.loupak', 'martin.loupak@czechsoft.eu','1fqZZBGNut',
                   'Martin', 'Loupák', 'tereza.fialova');

    p_insert_osobu('barbora.hruba', 'barbora.hruba@czechsoft.eu','C7WkbHNR6P',
                   'Barbora', 'Hrubá', 'tereza.fialova');

    p_insert_osobu('Loupakova', 'v.loupakova@emailsuper.cz','juYJbE0gxS',
                   'Veronika', 'Loupáková', null);

    p_insert_osobu('Bulova', 'bulova@kavantena.cz','EjJbu0gxSY',
                   'Klára', 'Bulová', 'Kavantena');

    p_insert_osobu('Krasny', 'krasny@kavantena.cz','Fy8h8JAVKw',
                   'Petr', 'Krásný', 'Kavantena');

    p_insert_osobu('Vladykova', 'sara.vladykova@hezkyacesky.cz','KjFIVPG1jq',
                   'Sára', 'Vladyková', 'HezkyACesky');

    p_insert_osobu('Hrubka', 'martin@hrubkaasyn.cz','KjFIVPG1jq',
                   'Martin', 'Hrubka', 'HrubkaASyn');

    p_insert_osobu('admin', null, 'adm1n1str4t0r', 'Master', 'Admin', null);
    UPDATE zajemci SET opravneni = 1 WHERE login LIKE 'admin';
    COMMIT;
END;
/

-- logy se vlozi sami skrze triggery
-- rezervace lepsi vkladat manualne
-- soubory nutno vkladat manualne