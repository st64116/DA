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
    v_novy_stav := pckg_rez_vlas_mist.f_check_stav_rezervace(v_id_rezervace, v_id_mistnosti);
    IF (v_novy_stav <> v_aktualni_stav) THEN
        UPDATE rezervace SET id_stavu = v_aktualni_stav, id_mistnosti = v_id_mistnosti
            WHERE id_rezervace = v_id_rezervace;
    END IF;
END;
/

-- CHECK dostupnost vsech cekajicich rezervaci, a cas ukonceni bezicich

CREATE OR REPLACE PROCEDURE p_check_stavy_rezervaci
    IS
BEGIN
    -- TODO select vsechny rezervace se stavem 1 a 2
    -- TODO na stavu 1 proved f_check_stav_rezervace
    -- TODO na stavu 2 check sysdate > casDO
END;

-- pravidelne opakovani (na JOBS nemame prava)

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