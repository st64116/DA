-- CHECK dostupnost a stav REZERVACE
CREATE OR REPLACE PROCEDURE p_check_dostupnost_rezervace
    (v_id_rezervace IN NUMBER)
    IS
    v_aktualni_stav NUMBER;
    v_novy_stav NUMBER;
    v_do DATE;
    v_id_mistnosti NUMBER;
BEGIN
    SELECT id_stavu, casDo INTO v_aktualni_stav, v_do
        FROM rezervace WHERE id_rezervace = v_id_rezervace;

    IF (v_aktualni_stav = 1) THEN
        v_novy_stav := pckg_rez_vlas_mist.f_check_stav_rezervace(v_id_rezervace, v_id_mistnosti);
        UPDATE rezervace
            SET id_stavu = v_novy_stav, id_mistnosti = v_id_mistnosti
                WHERE id_rezervace = v_id_rezervace;
    END IF;

    IF (v_aktualni_stav = 2 AND v_do <= sysdate) THEN
        UPDATE rezervace SET id_stavu = 3 WHERE id_rezervace = v_id_rezervace;
    END IF;
END;
/

-- CHECK dostupnost vsech cekajicich rezervaci, a cas ukonceni bezicich
CREATE OR REPLACE PROCEDURE p_check_stavy_rezervaci
    IS
BEGIN
    FOR r_rezervace IN
        (SELECT id_rezervace, id_stavu FROM rezervace
            WHERE id_stavu IN (1, 2))
    LOOP
        P_CHECK_DOSTUPNOST_REZERVACE(r_rezervace.id_rezervace);
    END LOOP;
END;

-- pravidelne opakovani (nelze spustit, na JOBS nemame prava)
DECLARE
    v_job_id NUMBER;
BEGIN
    DBMS_JOB.SUBMIT(
        v_job_id,
        what => 'P_CHECK_STAVY_REZERVACI',
        next_date => sysdate,
        interval => 'sysdate + 1/(24*60)'
        );
    DBMS_OUTPUT.PUT_LINE(v_job_id);
    COMMIT;
END;