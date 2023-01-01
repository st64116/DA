/*BDAS1*/
-- TABULKY
DROP TABLE FIRMY;
DROP TABLE OSOBY;
DROP TABLE REZERVACE;
DROP TABLE ZAJEMCI;
DROP TABLE MISTNOSTI;
DROP TABLE INVENTARE;
DROP TABLE PRISLUSENSTVI;
DROP TABLE SKUPINY_VLASTNOSTI;
DROP TABLE STAVY;
DROP TABLE UCELY;
DROP TABLE VELIKOSTI;
DROP TABLE UMISTENI;
DROP TABLE PATRA;
-- SEKVENCE
DROP SEQUENCE S_MISTNOSTI;
DROP SEQUENCE S_PATRA;
DROP SEQUENCE S_PRISLUSENSTVI;
DROP SEQUENCE S_REZERVACE;
DROP SEQUENCE S_SKUPINY;
DROP SEQUENCE S_STAVY;
DROP SEQUENCE S_UCELY;
DROP SEQUENCE S_UMISTENI;
DROP SEQUENCE S_VELIKOSTI;
DROP SEQUENCE S_ZAJEMCI;
-- TRIGGERY
DROP TRIGGER ARC_FKARC_1_FIRMY;
DROP TRIGGER ARC_FKARC_1_OSOBY;
DROP TRIGGER T_MISTNOSTI_ID;
DROP TRIGGER T_PATRA_ID;
DROP TRIGGER T_PRISLUSENSTVI_ID;
DROP TRIGGER T_REZERVACE_ID;
DROP TRIGGER T_SKUPINY_ID;
DROP TRIGGER T_STAVY_ID;
DROP TRIGGER T_UCELY_ID;
DROP TRIGGER T_UMISTENI_ID;
DROP TRIGGER T_VELIKOSTI_ID;
DROP TRIGGER T_ZAJEMCI_ID;

/*BDAS2*/
-- TABULKY
-- SEKVENCE
-- TRIGGERY
DROP TRIGGER T_ZAJEMCI_CHECK_OPRAVNENI;
DROP TRIGGER T_REZERVACE_CHECK_VALIDITU_A_STAV;
DROP TRIGGER T_VLASTNOSTI_CHECK_VALIDITU;
DROP TRIGGER T_STAVY_DELETE_CHECK_INTEGRITU;
DROP TRIGGER T_PRISLUSENSTVI_CHECK_NAZEV;
-- POHLEDY
DROP VIEW VIEW_FIRMY;
DROP VIEW VIEW_OSOBY;
DROP VIEW VIEW_MISTNOSTI;
DROP VIEW VIEW_PATRA;
DROP VIEW VIEW_PRISLUSENSTVI;
DROP VIEW VIEW_REZERVACE;
DROP VIEW VIEW_STAVY;
DROP VIEW VIEW_UCELY;
DROP VIEW VIEW_UMISTENI;
DROP VIEW VIEW_VELIKOSTI;
DROP VIEW VIEW_ZAJEMCE;
-- PROCEDURY INSERTY
DROP PROCEDURE P_INSERT_FIRMU;
DROP PROCEDURE P_INSERT_OSOBU;
DROP PROCEDURE P_INSERT_MISTNOST;
DROP PROCEDURE P_INSERT_PATRO;
DROP PROCEDURE P_INSERT_PRISLUSENSTVI;
DROP PROCEDURE P_INSERT_REZERVACI_SKRZ_MISTNOST;
DROP PROCEDURE P_INSERT_REZERVACI_SKRZ_VLASTNOSTI;
DROP PROCEDURE P_INSERT_UCEL;
DROP PROCEDURE P_INSERT_UMISTENI;
DROP PROCEDURE P_INSERT_VELIKOST;
DROP PROCEDURE P_INSERT_STAV;
-- PROCEDURY DELETY
DROP PROCEDURE P_DELETE_REZERVACI;
DROP PROCEDURE P_DELETE_MISTNOST;
DROP PROCEDURE P_DELETE_ZAJEMCE;
DROP PROCEDURE P_DELETE_UCEL;
DROP PROCEDURE P_DELETE_UMISTENI;
DROP PROCEDURE P_DELETE_PATRO;
DROP PROCEDURE P_DELETE_VELIKOST;
DROP PROCEDURE P_DELETE_PRISLUSENSTVI;
DROP PROCEDURE P_DELETE_STAV;
-- PROCEDURY UPDATE
DROP PROCEDURE P_UPDATE_FIRMU;
DROP PROCEDURE P_UPDATE_OSOBU;
DROP PROCEDURE P_UPDATE_HESLO;
DROP PROCEDURE P_UPDATE_UCEL;
DROP PROCEDURE P_UPDATE_UMISTENI;
DROP PROCEDURE P_UPDATE_PATRO;
DROP PROCEDURE P_UPDATE_VELIKOST;
DROP PROCEDURE P_UPDATE_PRISLUSENSTVI;
DROP PROCEDURE P_UPDATE_STAV;
-- PROCEDURY OSTATNI
DROP PROCEDURE P_CHECK_DOSTUPNOST_REZERVACE;
DROP PROCEDURE P_CHECK_STAVY_REZERVACI;
-- SAMOSTATNE FUNKCE
-- BALICKY
DROP PACKAGE PCKG_REZ_VLAS_MIST;
DROP PACKAGE PCKG_LOGIN;