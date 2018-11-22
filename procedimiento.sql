
------Crear tabla
CREATE TABLE OTC_T_ENVIO_CNT(
	ID		INTEGER NOT NULL PRIMARY KEY,
	FECHA	DATE,
	EVENTO	VARCHAR2(50),
	TIPO	VARCHAR2(50),
	ESTADO	VARCHAR2(50),
	ERROR	CLOB NULL
);

-------Crear secuencia auto_increment
CREATE SEQUENCE OTC_T_ENVIOCNT INCREMENT BY 1 START WITH 1;

COMMENT ON TABLE OTC_T_ENVIO_CNT IS 'Tabla para registro de exito o error en la generacion y envio de reporte roaming cnt';
COMMENT ON COLUMN OTC_T_ENVIO_CNT.ID IS 'Secuencial autonumérico';
COMMENT ON COLUMN OTC_T_ENVIO_CNT.FECHA IS 'Fecha de ejecución del proceso';
COMMENT ON COLUMN OTC_T_ENVIO_CNT.EVENTO IS 'Evento de CONSULTA de datos o ENVIO del reporte';
COMMENT ON COLUMN OTC_T_ENVIO_CNT.TIPO IS 'SMS_IN para el trafico de sms entrante, SMS_OUT en el trafico sms saliente Y DATOS para trafico de datos';
COMMENT ON COLUMN OTC_T_ENVIO_CNT.ESTADO IS 'TRUE ejecutado con exito o FALSE error';
COMMENT ON COLUMN OTC_T_ENVIO_CNT.ERROR IS 'La excepción del error si es el caso';

-------INSERT
INSERT INTO OTC_T_ENVIO_CNT(ID, FECHA, EVENTO, TIPO, ESTADO, ERROR) VALUES(OTC_T_ENVIOCNT.nextval, sysdate, 'CONSULTA', 'SMS_IN', 'TRUE', null);

----------------------------------------------------------------------------------------
-------PAQUETE DE PROCEDIMIENTOS
CREATE OR REPLACE PROCEDURE OTC_SP_ROAMING_PROCESO (I_TIPO IN  VARCHAR2,
        A_ESTADO OUT VARCHAR2,
        A_EVENTO OUT VARCHAR2)  AS
         
        ---Reproceso
        intentos_Consulta NUMBER;
        intentos_Envio NUMBER;
          
BEGIN

    SELECT COUNT(ID) INTO intentos_Consulta FROM OTC_T_ENVIO_CNT WHERE TIPO = I_TIPO AND EVENTO = 'CONSULTA' AND EXTRACT(MONTH FROM sysdate)=EXTRACT(MONTH FROM FECHA);
    SELECT COUNT(ID) INTO intentos_Envio FROM OTC_T_ENVIO_CNT WHERE TIPO = I_TIPO AND EVENTO = 'ENVIO' AND EXTRACT(MONTH FROM sysdate)=EXTRACT(MONTH FROM FECHA);
    
    BEGIN
        ---Condición para saber que sea menor a 3 intentos y haya fallado en la consulta
        IF intentos_Consulta < 3 AND  intentos_Consulta >= intentos_Envio
        THEN          
          BEGIN
             SELECT ESTADO, EVENTO INTO A_ESTADO, A_EVENTO FROM OTC_T_ENVIO_CNT WHERE TIPO=I_TIPO AND EXTRACT(MONTH FROM sysdate)=EXTRACT(MONTH FROM FECHA) AND rownum = 1 ORDER BY FECHA DESC;
             DBMS_OUTPUT.put_line(A_EVENTO || '-' || A_ESTADO);
          END;
       ELSE
          DBMS_OUTPUT.put_line('INTENTOS EXCEDIDOS');
       END IF;            
    EXCEPTION
      WHEN NO_DATA_FOUND
      THEN
         DBMS_OUTPUT.PUT_LINE ('NO HAY DATOS');
      WHEN OTHERS
      THEN
         DBMS_OUTPUT.PUT_LINE (TO_CHAR (SQLCODE) || ' ' ||  SUBSTR (SQLERRM, 1, 250));
         ROLLBACK;
         END;
END OTC_SP_ROAMING_PROCESO;

----------------------

----PROCEDIMIENTO INSERT
CREATE OR REPLACE PROCEDURE OTC_SP_ROAMING_INSERT_CNT (
        I_EVENTO	IN VARCHAR2,
        I_TIPO	IN VARCHAR2,
        I_ESTADO	IN VARCHAR2,
        I_ERROR	IN CLOB)  AS
          
BEGIN
    INSERT INTO OTC_T_ENVIO_CNT(ID, FECHA, EVENTO, TIPO, ESTADO, ERROR) VALUES(OTC_T_ENVIOCNT.nextval, sysdate, I_EVENTO, I_TIPO, I_ESTADO, I_ERROR);
    DBMS_OUTPUT.put_line('TRUE');
    
    EXCEPTION
      WHEN NO_DATA_FOUND
      THEN
         DBMS_OUTPUT.PUT_LINE ('NO HAY DATOS');
      WHEN OTHERS
      THEN
         DBMS_OUTPUT.PUT_LINE (TO_CHAR (SQLCODE) || ' ' ||  SUBSTR (SQLERRM, 1, 250));
         ROLLBACK;
END OTC_SP_ROAMING_INSERT_CNT;


--- Listar paquetes y procedimientos
select object_name, object_type
 from user_objects
where object_type in ( 'PROCEDURE', 'FUNCTION','PACKAGE', 'PACKAGE BODY' )
 and rownum < 50;
--- Fin listar paquetes y procedimientos

--EJECUCIÓN EN TOAD
declare
A_ESTADO VARCHAR2(50); 
A_EVENTO VARCHAR2(50);
begin 
  OTC_SP_ROAMING_PROCESO('SMS', A_ESTADO, A_EVENTO);
  DBMS_OUTPUT.put_line(A_EVENTO || '-' || A_ESTADO);
end;

--------------------------------------------------
declare
I_EVENTO VARCHAR2(50) :=  'CONSULTA'; 
I_TIPO VARCHAR2(50) :='SMS_OUT';
I_ESTADO VARCHAR2(50) := 'FALSE';
I_ERROR CLOB := 'ASASAAASASASSSSSAS ';
begin 
  OTC_SP_ROAMING_INSERT_CNT(I_EVENTO, I_TIPO, I_ESTADO, I_ERROR);
end;
