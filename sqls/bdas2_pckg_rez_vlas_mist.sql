--- PACKAGE pro pomocne funkce k rezervacim, mistnostem a vlastnostem (namaji vlastni commit a rollback)
CREATE OR REPLACE PACKAGE pckg_rez_vlas_mist AS

--> TYPES

    TYPE type_pole_prislusenstvi IS TABLE OF NUMBER
        INDEX BY BINARY_INTEGER;
    
    TYPE type_pole_mistnosti IS TABLE OF mistnosti%ROWTYPE 
        INDEX BY BINARY_INTEGER;

--> SKUPINY VLASTNOSTI

    --- vraci/vytvori skupinu pro mistnosti podle zadanych vlastnosti
        --- vyzaduje vsechny vlastnosti (zadny null)
    FUNCTION f_add_vlastnost_pro_mistnost 
        (v_id_ucelu IN NUMBER,
        v_id_umisteni IN NUMBER,
        v_id_patra IN NUMBER,
        v_id_velikosti IN NUMBER,
        v_prislusenstvi IN VARCHAR2)
        RETURN NUMBER;
        
    --- vraci/vytvori skupinu pro rezervace podle zadanych vlastnosti
        --- null = cokoliv
    FUNCTION f_add_vlastnost_pro_rezervace 
        (v_id_ucelu IN NUMBER,
        v_id_umisteni IN NUMBER,
        v_id_patra IN NUMBER, 
        v_id_velikosti IN NUMBER,
        v_prislusenstvi IN VARCHAR2)
        RETURN NUMBER;
    
    --- vraci/vytvori skupinu pro rezervace jako "kopii" vlastnosti mistnosti
    FUNCTION f_add_vlastnost_pro_rezervace
        (v_id_mistnosti IN NUMBER)
        RETURN NUMBER;

    --- vraci mistnosti odpovidajici zadanym vlastnostem
        --- null = cokoliv
    FUNCTION f_get_mistnosti_podle_vlastnosti
        (v_id_ucelu IN NUMBER,
        v_id_umisteni IN NUMBER,
        v_id_patra IN NUMBER,
        v_id_velikosti IN NUMBER,
        v_prislusenstvi IN VARCHAR2)
        RETURN type_pole_mistnosti;

    --- vraci id_skupiny pokud se shoduje se zadanymi vlastnostmi
        --- null = cokoliv
    FUNCTION f_get_id_skupiny_podle_vlastnosti
        (v_id_ucelu IN NUMBER,
        v_id_umisteni IN NUMBER,
        v_id_patra IN NUMBER,
        v_id_velikosti IN NUMBER,
        v_prislusenstvi IN VARCHAR2)
        RETURN NUMBER;

--> INVENTAR

    --- vraci oddelene nazvy prislusenstvi
        --- "x;y;z" => ["x","y","z"]
    FUNCTION f_get_split_prislusenstvi
        (v_prislusenstvi IN VARCHAR2)
        RETURN type_pole_prislusenstvi;

    --- vraci spojene nazvy prislusenstvi
        --- ["x","y","z"] => "x;y;z"
    FUNCTION f_get_concat_prislusenstvi
        (v_id_skupiny IN NUMBER)
        RETURN VARCHAR2;

    --- nahradi puvodni inventar nove zadanym
    PROCEDURE p_update_inventar 
        (v_id_skupiny IN NUMBER,
        v_prislusenstvi IN VARCHAR2);

    --- smaze inventar skupiny
    PROCEDURE p_delete_inventar
        (v_id_skupiny IN NUMBER);

    --- vytvori novy inventar
    PROCEDURE p_insert_inventar
        (v_id_skupiny IN NUMBER,
        v_prislusenstvi IN VARCHAR2);

--> REZERVACE

    --- vraci 1 pokud vlastnosti rezervace nejsou podmnozinou zadne z mistnosti
        --- jinak vraci 0
    FUNCTION f_check_vlastnosti_podmnozinou_mistnosti
        (v_id_skupiny IN NUMBER)
        RETURN NUMBER;

    --- vraci 1 pokud rezervace koliduje s jinou
        --- jinak vraci 0
    FUNCTION f_check_kolize_rezervace
        (v_id_rezervace IN NUMBER,
        v_id_mistnosti IN NUMBER)
        RETURN NUMBER;

    --- vraci stav rezervace, ktery by mela aktualne dostat
        --- v out vraci mistnost, kterou by mela rezervace dostat
    FUNCTION f_check_stav_rezervace 
        (v_id_rezervace IN NUMBER,
        v_id_mistnosti OUT NUMBER)
        RETURN NUMBER;

END pckg_rez_vlas_mist;
/

---------------------------------------------------------------------------------

CREATE OR REPLACE PACKAGE BODY pckg_rez_vlas_mist AS

/*PRIVATE*/
    --- v out parametrech vraci id-cka vlastnosti
    PROCEDURE p_get_ids_vlastnosti
        (v_ucel IN VARCHAR2, 
        v_umisteni IN VARCHAR2,
        v_patro IN VARCHAR2,
        v_velikost IN VARCHAR2,

        v_id_ucelu OUT NUMBER, 
        v_id_umisteni OUT NUMBER,
        v_id_patra OUT NUMBER, 
        v_id_velikosti OUT NUMBER)
        IS
    BEGIN
        SELECT id_ucelu INTO v_id_ucelu FROM ucely
            WHERE v_ucel LIKE nazev;
        SELECT id_umisteni INTO v_id_umisteni FROM umisteni 
            WHERE v_umisteni LIKE nazev;
        SELECT id_patra INTO v_id_patra FROM patra
            WHERE v_patro LIKE nazev;
        SELECT id_velikosti INTO v_id_velikosti FROM velikosti 
            WHERE v_velikost LIKE nazev;
    END;
    
/*PRIVATE*/
    --- v out parametrech vraci id-cka vlastnosti
    PROCEDURE p_get_ids_vlastnosti
        (v_id_mistnosti IN NUMBER,

        v_id_ucelu OUT NUMBER, 
        v_id_umisteni OUT NUMBER,
        v_id_patra OUT NUMBER, 
        v_id_velikosti OUT NUMBER,
        v_prislusenstvi OUT VARCHAR2)
        IS
        v_id_skupiny NUMBER;
    BEGIN
        SELECT id_skupiny INTO v_id_skupiny 
            FROM mistnosti WHERE id_mistnosti = v_id_mistnosti;
        SELECT id_ucelu, id_umisteni, id_patra, id_velikosti
            INTO v_id_ucelu, v_id_umisteni, v_id_patra, v_id_velikosti
            FROM skupiny_vlastnosti WHERE id_skupiny = v_id_skupiny;
        v_prislusenstvi := f_get_concat_prislusenstvi(v_id_skupiny);
    END;

/*PRIVATE*/
    --- vrati id_skupiny kterou vytvori
    FUNCTION f_add_skupinu
        (v_id_ucelu IN NUMBER, 
        v_id_umisteni IN NUMBER,
        v_id_patra IN NUMBER,
        v_id_velikosti IN NUMBER, 
        v_prislusenstvi IN VARCHAR2,
        v_patri IN VARCHAR2)
        RETURN NUMBER
        IS
        v_id_skupiny NUMBER;
        v_prislus_pole type_pole_prislusenstvi;
    BEGIN
        INSERT INTO SKUPINY_VLASTNOSTI 
            (id_umisteni, id_patra, id_ucelu, id_velikosti, patri) 
            VALUES (v_id_umisteni, v_id_patra, v_id_ucelu, 
                v_id_velikosti, v_patri)
            RETURNING id_skupiny INTO v_id_skupiny;
        
        IF (v_prislusenstvi IS NULL) THEN
            RETURN v_id_skupiny;
        END IF;
    
        v_prislus_pole := f_get_split_prislusenstvi(v_prislusenstvi);
        FOR i IN v_prislus_pole.FIRST .. v_prislus_pole.LAST LOOP
            INSERT INTO INVENTARE (id_skupiny, id_prislusenstvi) 
                VALUES (v_id_skupiny, v_prislus_pole(i));
        END LOOP;
        RETURN v_id_skupiny;
    END;
    
/*PRIVATE*/
    --- vraci 1 pokud se intervaly protinaji, jinak vraci 0
    FUNCTION f_casy_se_protinaji
        (v_a_od IN DATE, v_a_do IN DATE, v_b_od IN DATE, v_b_do IN DATE)
        RETURN NUMBER
        IS
    BEGIN
        IF ((v_b_od <= v_a_od AND v_a_od < v_b_do) 
            OR (v_b_od < v_a_do AND v_a_do <= v_b_do))
        THEN
            RETURN 1;
        END IF;
        RETURN 0;
    END;

/**> VLASTNOSTI <**/

/*PUBLIC*/
    FUNCTION f_add_vlastnost_pro_mistnost
        (v_id_ucelu IN NUMBER, v_id_umisteni IN NUMBER, v_id_patra IN NUMBER, 
        v_id_velikosti IN NUMBER, v_prislusenstvi IN VARCHAR2) RETURN NUMBER
        IS
    BEGIN
        RETURN f_add_skupinu (v_id_ucelu, v_id_umisteni, v_id_patra, 
            v_id_velikosti, v_prislusenstvi, 'mistnosti');
    END;

/*PUBLIC*/
    FUNCTION f_add_vlastnost_pro_rezervace 
        (v_id_ucelu IN NUMBER, v_id_umisteni IN NUMBER, v_id_patra IN NUMBER, 
        v_id_velikosti IN NUMBER, v_prislusenstvi IN VARCHAR2) RETURN NUMBER
        IS
    BEGIN
        RETURN f_add_skupinu (v_id_ucelu, v_id_umisteni, v_id_patra, 
            v_id_velikosti, v_prislusenstvi, 'rezervaci');
    END;

/*PUBLIC*/
    FUNCTION f_add_vlastnost_pro_rezervace
        (v_id_mistnosti IN NUMBER) RETURN NUMBER
        IS
        v_id_ucelu NUMBER;
        v_id_umisteni NUMBER;
        v_id_patra NUMBER;
        v_id_velikosti NUMBER;
        v_prislusenstvi VARCHAR2(1000);
    BEGIN
        p_get_ids_vlastnosti
            (v_id_mistnosti, v_id_ucelu, v_id_umisteni, v_id_patra, 
            v_id_velikosti, v_prislusenstvi);
        RETURN f_add_skupinu (v_id_ucelu, v_id_umisteni, v_id_patra, 
            v_id_velikosti, v_prislusenstvi, 'rezervaci');
    END;
    
/*PUBLIC*/
    --- deprecated -- TODO nahradit a smazat, SELECT bude vracet vic zaznamu
    FUNCTION f_get_id_skupiny_podle_vlastnosti
        (v_id_ucelu IN NUMBER, v_id_umisteni IN NUMBER, v_id_patra IN NUMBER,
        v_id_velikosti IN NUMBER, v_prislusenstvi IN VARCHAR2) RETURN NUMBER
        IS
        v_id_skupiny NUMBER;
    BEGIN
        SELECT id_skupiny INTO v_id_skupiny 
            FROM skupiny_vlastnosti
            WHERE id_ucelu = v_id_ucelu AND id_umisteni = v_id_umisteni
                AND id_patra = v_id_patra AND id_velikosti = v_id_velikosti
                AND ((v_prislusenstvi IS NULL 
                    AND f_get_concat_prislusenstvi(id_skupiny) IS NULL)
                    OR
                    v_prislusenstvi LIKE f_get_concat_prislusenstvi(id_skupiny));
        RETURN v_id_skupiny;
    END;

/*PUBLIC*/
    FUNCTION f_get_mistnosti_podle_vlastnosti
        (v_id_ucelu IN NUMBER, v_id_umisteni IN NUMBER, v_id_patra IN NUMBER, 
        v_id_velikosti IN NUMBER, v_prislusenstvi IN VARCHAR2)
        RETURN type_pole_mistnosti
        IS
        CURSOR c_mistnosti IS
            SELECT mi.* FROM mistnosti mi  
            JOIN skupiny_vlastnosti vl ON (mi.id_skupiny = vl.id_skupiny)
            WHERE (v_id_ucelu IS NULL OR id_ucelu = v_id_ucelu) 
                AND (v_id_umisteni IS NULL OR id_umisteni = v_id_umisteni)
                AND (v_id_patra IS NULL OR id_patra = v_id_patra)  
                AND (v_id_velikosti IS NULL OR id_velikosti = v_id_velikosti)
                AND (f_get_concat_prislusenstvi(mi.id_skupiny) IS NULL 
                    OR f_get_concat_prislusenstvi(mi.id_skupiny) 
                        LIKE v_prislusenstvi);
        v_pole_mistnosti type_pole_mistnosti;
        v_i NUMBER := 1;
    BEGIN
        OPEN c_mistnosti;
        LOOP
          v_i := v_i + 1;
          FETCH c_mistnosti INTO v_pole_mistnosti(v_i);
          EXIT WHEN c_mistnosti%NOTFOUND;
        END LOOP;
        CLOSE c_mistnosti;
        RETURN v_pole_mistnosti;
    END;


/*PUBLIC*/
    FUNCTION f_check_vlastnosti_podmnozinou_mistnosti
    (v_id_skupiny_rezervace IN NUMBER) RETURN NUMBER
        IS
        v_id_skupiny_mistnosti NUMBER;
    BEGIN
        --SELECT id_skupiny INTO v_id_skupiny_mistnosti
        --    FROM skupiny_vlastnosti
        --    WHERE v_id_skupiny <> id_skupiny AND patri LIKE 'mistnosti';
        -- TODO select into pole
        -- TODO check vsechny z pole jestli jim sedi prislusenstvi
        RETURN 0;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN 1;
        WHEN others THEN
            RAISE;
    END;


/*** INVENTARE ***/

/*PUBLIC*/
    FUNCTION f_get_split_prislusenstvi
    (v_prislusenstvi IN VARCHAR2)
        RETURN type_pole_prislusenstvi
        IS
        v_pole type_pole_prislusenstvi;
        v_zacatek NUMBER := 1;
        v_konec NUMBER;
        v_delka NUMBER := LENGTH(v_prislusenstvi);
        v_aktualni_id NUMBER;
        v_aktualni VARCHAR2(32);
    BEGIN
        LOOP
            v_konec := INSTR( v_prislusenstvi, ';', v_zacatek);
            IF (v_konec = 0) THEN
                v_konec := v_delka;
                v_aktualni := SUBSTR( v_prislusenstvi, v_zacatek, v_delka - v_zacatek + 1);
            ELSE
                v_aktualni := SUBSTR( v_prislusenstvi, v_zacatek, v_konec - v_zacatek);
            END IF;
            SELECT id_prislusenstvi INTO v_aktualni_id
            FROM prislusenstvi WHERE nazev LIKE v_aktualni;
            v_pole(v_pole.count) := v_aktualni_id;
            v_zacatek := v_konec + 1;
            EXIT WHEN (v_konec = v_delka);
        END LOOP;
        RETURN v_pole;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            raise_application_error(-20201, 'The requested accessory was not found');
        WHEN others THEN
            RAISE;
    END;

/*PUBLIC*/
    FUNCTION f_get_concat_prislusenstvi
    (v_id_skupiny IN NUMBER) RETURN VARCHAR2
        IS
        v_string VARCHAR2(1000) := '';
    BEGIN
        FOR r_prislus IN
            (SELECT nazev FROM inventare JOIN prislusenstvi USING (id_prislusenstvi)
             WHERE id_skupiny = v_id_skupiny)
            LOOP
                v_string := v_string || r_prislus.nazev ||';';
            END LOOP;
        IF (v_string NOT LIKE '') THEN
            v_string := NULL;
        ELSE
            v_string := SUBSTR(v_string, 0, LENGTH(v_string) - 1);
        END IF;
        RETURN v_string;
    END;

/*PUBLIC*/
    PROCEDURE p_update_inventar
        (v_id_skupiny IN NUMBER,
        v_prislusenstvi IN VARCHAR2)
        IS
    BEGIN
        p_delete_inventar(v_id_skupiny);
        p_insert_inventar(v_id_skupiny, v_prislusenstvi);
    END;

/*PUBLIC*/
    PROCEDURE p_delete_inventar
        (v_id_skupiny IN NUMBER)
        IS
    BEGIN
        RETURN;
        -- TODO implement
    END;

/*PUBLIC*/
    PROCEDURE p_insert_inventar
        (v_id_skupiny IN NUMBER,
        v_prislusenstvi IN VARCHAR2)
        IS
    BEGIN
        RETURN;
    END;

/**> REZERVACE <**/

/*PUBLIC*/
    FUNCTION f_check_kolize_rezervace
        (v_id_rezervace IN NUMBER, v_id_mistnosti IN NUMBER)
        RETURN NUMBER
        IS
    BEGIN
        -- TODO najdi vsechny rezervace se stavem 2 a stejnou mistnosti
        -- TODO porovnej vsechny jejich casy
        RETURN 0;
    END;

/*PUBLIC*/
    -- ocekava rezervaci ve stavu 1
    -- TODO rozdelit podle aktualniho stavu
    FUNCTION f_check_stav_rezervace 
        (v_id_rezervace IN NUMBER, v_id_mistnosti OUT NUMBER) 
        RETURN NUMBER
        IS
        v_id_ucelu NUMBER;
        v_id_umisteni NUMBER;
        v_id_patra NUMBER;
        v_id_velikosti NUMBER;
        v_id_skupiny NUMBER;
        v_prislusenstvi VARCHAR2(1000);
        v_od DATE;
        v_do DATE;
        v_lze_priradit NUMBER := 0;
        
        CURSOR c_mistnosti IS
            SELECT * FROM mistnosti  
            JOIN skupiny_vlastnosti USING (id_skupiny)
            WHERE (v_id_ucelu IS NULL OR id_ucelu = v_id_ucelu) 
                AND (v_id_umisteni IS NULL OR id_umisteni = v_id_umisteni)
                AND (v_id_patra IS NULL OR id_patra = v_id_patra)  
                AND (v_id_velikosti IS NULL OR id_velikosti = v_id_velikosti)
                AND (f_get_concat_prislusenstvi(id_skupiny) IS NULL 
                    OR f_get_concat_prislusenstvi(id_skupiny) 
                        LIKE v_prislusenstvi);
        r_mistnost c_mistnosti%ROWTYPE;
    BEGIN
        SELECT id_ucelu, id_umisteni, id_patra, id_velikosti, 
            id_skupiny, casOd, casDo
            INTO v_id_ucelu, v_id_umisteni, v_id_patra, v_id_velikosti, 
                v_id_skupiny, v_od, v_do
            FROM rezervace JOIN skupiny_vlastnosti USING (id_skupiny) 
            WHERE id_rezervace = v_id_rezervace;
        v_prislusenstvi := f_get_concat_prislusenstvi(v_id_skupiny);
        
        LOOP
            FETCH c_mistnosti INTO r_mistnost;
            EXIT WHEN c_mistnosti%NOTFOUND; 
            v_lze_priradit := 1;
            FOR r_rezervace IN 
                (SELECT casOd, casDo FROM rezervace 
                    WHERE r_mistnost.id_mistnosti = id_mistnosti)
            LOOP
                IF (f_casy_se_protinaji(v_od, v_do, 
                    r_rezervace.casOd, r_rezervace.casDo) = 1)
                THEN
                    v_lze_priradit := 0;
                    EXIT;
                END IF;
            END LOOP;
            IF (v_lze_priradit = 1) THEN
                v_id_mistnosti := r_mistnost.id_mistnosti;
                RETURN 2; -- stav rezervovano
            END IF;
        END LOOP;
        v_id_mistnosti := NULL;
        RETURN 1; -- stav pozadovano
    END;
    
END pckg_rez_vlas_mist;
/