CREATE TYPE tipoSexo AS ENUM ('F', 'M', 'O');

CREATE TYPE tipoRazon AS ENUM (
'consulta general', 

'limpieza dental',

'revision de ortodoncia',

'extraccion dental',

'tratamiento de endodoncia',

'colocacion de implante',

'blanqueamiento dental',

'urgencia dental',

'control postoperatorio',

'otro'
);

CREATE TYPE tipoEstado AS ENUM ('asignada','cumplida','solicitada','cancelada');

CREATE TYPE tipoLog AS ENUM ('info', 'err', 'war');

 CREATE TABLE Consultorios (
  ConNumero int NOT NULL,
  ConNombre varchar(50) NOT NULL,
  ConDetalle varchar(60) NOT NULL,
  PRIMARY KEY (ConNumero)
 );

 INSERT INTO Consultorios(ConNumero, ConNombre, ConDetalle) VALUES 
 (01, 'Consultorio 1', 'Planta Baja'),
 (02, 'Consultorio 2', 'Planta Baja'),
 (11, 'Consultorio 3', 'Primer Piso'),
 (12, 'Consultorio 4', 'Primer Piso'),
 (21, 'Consultorio 5', 'Segundo Piso'),
 (22, 'Sala de Cirugia', 'Segundo Piso');

 CREATE TABLE Medicos (
  MedIdentificacion char(10) NOT NULL,
  MedNombres varchar(50) NOT NULL,
  MedApellidos varchar(50) NOT NULL,
  MedSexo tipoSexo NOT NULL,
  MedArea VARCHAR(100) NOT NULL,
  PRIMARY KEY (MedIdentificacion)
 );

  INSERT INTO Medicos(medidentificacion, MedNombres, MedApellidos, MedSexo, MedArea) VALUES ('123456789', 'Carlos', 'Mendoza', 'M', 'Odontologia general'),
  ('987654321', 'Maria', 'Garcia', 'F', 'Ortodoncia'),
  ('434324232', 'Luis', 'Rodriguez', 'M', 'Cirugia oral'),
  ('534234322', 'Ana', 'Martinez', 'F', 'Endodoncia'),
  ('456465464', 'Roberto', 'Silva', 'M', 'Periodoncia'),
  ('843848382', 'Carmen', 'Lopez', 'F', 'Odontopediatra');

 CREATE TABLE Pacientes (
  id SERIAL,
  PacIdentificacion char(10) NOT NULL,
  PacNombres varchar(50) NOT NULL,
  PacApellidos varchar(50) DEFAULT NULL,
  PacFechaNacimiento date NOT NULL,
  PacSexo tipoSexo NOT NULL,
  PRIMARY KEY (PacIdentificacion)
 );

 CREATE TABLE Tratamientos (
  TraNumero SERIAL,
  TraFechaAsignado date NOT NULL,
  TraDescripcion text NOT NULL,
  TraFechaInicio date NOT NULL,
  TraFechaFin date NOT NULL,
  TraObservaciones text NOT NULL,
  TraPaciente char(10) NOT NULL,
  PRIMARY KEY (TraNumero),
  CONSTRAINT Tratamientos_ibfk_1 FOREIGN KEY (TraPaciente) REFERENCES Pacientes (PacIdentificacion)
 );


 CREATE TABLE citas (
  CitNumero SERIAL,
  CitFecha date NOT NULL,
  CitHora varchar(10) NOT NULL,
  CitPaciente char(10) NOT NULL,
  CitMedico char(10) NOT NULL,
  CitConsultorio int NOT NULL,
  CitMotivo tipoRazon NOT NULL,
  CitObervaciones text NOT NULL,
  CitEstado tipoEstado NOT NULL DEFAULT 'asignada',
  CitFecha_agregada DATE DEFAULT CURRENT_DATE,
  PRIMARY KEY (CitNumero),
  CONSTRAINT citas_ibfk_1 FOREIGN KEY (CitPaciente) REFERENCES Pacientes (PacIdentificacion),
  CONSTRAINT citas_ibfk_2 FOREIGN KEY (CitMedico) REFERENCES Medicos
(MedIdentificacion),
  CONSTRAINT citas_ibfk_3 FOREIGN KEY (CitConsultorio) REFERENCES Consultorios (ConNumero)
 );

  CREATE TABLE logs_app (
  id SERIAL PRIMARY KEY,
  fecha TIMESTAMP DEFAULT NOW(),
  tipo tipoLog,
  origen TEXT,
  titulo TEXT,
  mensaje TEXT,
  datos JSONB
 );

CREATE OR REPLACE FUNCTION addCita(fecha DATE, hora VARCHAR, paciente CHAR, medicoNombre VARCHAR, medicoApellido VARCHAR, consultorio INT) RETURNS VOID AS $$
  DECLARE
    idMedico char(10);
    idConsultorio char(10);
  BEGIN
    SELECT MedIndentificacion INTO idMedico FROM Medicos WHERE MedNombres = medicoNombre AND MedApellidos = medicoApellido;
    SELECT ConNumero INTO idConsultorio FROM Consultorios WHERE ConNombre = consulturio;

    IF MedIdentificacion IS NOT NULL OR MedIdentificacion != '' AND ConNumero IS NOT NULL OR ConNumero != '' THEN
      INSERT INTO citas(citfecha, cithora, citpaciente, citmedico, citconsultorio) VALUES (fecha, hora, paciente, idMedico, idConsultorio);
    END IF;
  END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION searchCita(
    paciente VARCHAR DEFAULT NULL,
    medico VARCHAR DEFAULT NULL,
    fecha VARCHAR DEFAULT NULL,
    estado VARCHAR DEFAULT NULL
)
RETURNS TABLE(
    numeroCita INT,
    nombrePaciente VARCHAR, 
    apellidoPaciente VARCHAR, 
    nombreMedico VARCHAR,
    apellidoMedico VARCHAR,
    pacienteCita CHAR,
    medicoCita CHAR,
    fechaCita DATE,
    horaCita VARCHAR,
    consultorio INT,
    motivoCita tiporazon,
    estadoCita tipoestado
) 
AS $$
DECLARE
    consulta TEXT;
    params TEXT[] := ARRAY[]::TEXT[];
    param_index INT := 1;
BEGIN
    consulta := '
        SELECT 
            cita.citnumero AS numeroCita,
            p.pacnombres AS nombrePaciente,
            p.pacapellidos AS apellidoPaciente,
            m.mednombres AS nombreMedico,
            m.medapellidos AS apellidoMedico,
            cita.citpaciente AS pacienteCita,
            cita.citmedico AS medicoCita,
            cita.citfecha AS fechaCita,
            cita.cithora AS horaCita,
            cita.citconsultorio AS consultorio,
            cita.citmotivo AS motivoCita,
            cita.citestado AS estadoCita
        FROM citas cita
        INNER JOIN pacientes p ON cita.citpaciente = p.pacidentificacion
        INNER JOIN medicos m ON cita.citmedico = m.medidentificacion
        INNER JOIN consultorios c ON cita.citconsultorio = c.connumero
        WHERE 1=1
    ';

    IF paciente IS NOT NULL AND paciente <> '' THEN
        consulta := consulta || FORMAT(' AND (p.pacnombres ILIKE $%s OR p.pacidentificacion ILIKE $%s)', param_index, param_index + 1);
        params := params || ('%' || paciente || '%');
        params := params || ('%' || paciente || '%');
        param_index := param_index + 2;
    END IF; 

    IF medico IS NOT NULL AND medico <> '' THEN
        consulta := consulta || FORMAT(' AND m.medidentificacion = $%s', param_index);
        params := params || medico;
        param_index := param_index + 1;
    END IF;

    IF fecha IS NOT NULL AND fecha <> '' THEN
        consulta := consulta || FORMAT(' AND cita.citfecha = $%s::DATE', param_index);
        params := params || fecha;
        param_index := param_index + 1;
    END IF;

    IF estado IS NOT NULL AND estado <> '' THEN
        consulta := consulta || FORMAT(' AND cita.citestado = $%s::tipoestado', param_index);
        params := params || estado;
        param_index := param_index + 1;
    END IF;

    consulta := consulta || ' ORDER BY cita.citnumero ASC';

    RAISE NOTICE 'Consulta generada: %', consulta;
    RAISE NOTICE 'Parámetros: %', params;

    IF array_length(params, 1) IS NULL THEN
      RAISE NOTICE 'Sin parámetros, devolviendo todos los registros.';
      RETURN QUERY EXECUTE consulta;
    ELSE
      CASE array_length(params, 1)
        WHEN 1 THEN RETURN QUERY EXECUTE consulta USING params[1];
        WHEN 2 THEN RETURN QUERY EXECUTE consulta USING params[1], params[2];
        WHEN 3 THEN RETURN QUERY EXECUTE consulta USING params[1], params[2], params[3];
        WHEN 4 THEN RETURN QUERY EXECUTE consulta USING params[1], params[2], params[3], params[4];
        WHEN 5 THEN RETURN QUERY EXECUTE consulta USING params[1], params[2], params[3], params[4], params[5];
        ELSE
          RAISE EXCEPTION 'Demasiados parámetros (%), máximo 5.', array_length(params, 1);
        END CASE;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION countRows() RETURNS JSON AS $$
  DECLARE
    citasHoy INT := 0;
    citasAyer INT := 0;
    pacientesActivos INT := 0;
    citasPendientes INT := 0;
    medicosDisponibles INT := 0;
    resultado JSON;
  BEGIN
    SELECT COUNT(*) INTO citasHoy FROM citas WHERE CitFecha_agregada = CURRENT_DATE;
    SELECT COUNT(*) INTO citasAyer FROM citas WHERE CitFecha_agregada = CURRENT_DATE - 1;
    SELECT COUNT(*) INTO pacientesActivos FROM pacientes;
    SELECT COUNT(*) INTO citasPendientes FROM citas WHERE citestado = 'solicitada';
    SELECT COUNT(*) INTO medicosDisponibles FROM medicos;

    resultado := json_build_object(
      'citas-hoy', citasHoy,
      'citas-ayer', citasAyer,
      'pacientes', pacientesActivos,
      'citas', citasPendientes,
      'medicosDisponibles', medicosDisponibles
    );

    RETURN resultado;
  END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE PROCEDURE registrar_log(p_tipo tipoLog, p_origen TEXT, p_titulo TEXT, p_mensaje TEXT, p_datos JSONB DEFAULT '{}'::JSONB) AS $$
  BEGIN
    INSERT INTO 
      logs_app (tipo, origen, titulo, mensaje, datos)
    VALUES
      (p_tipo, p_origen, p_titulo, p_mensaje, p_datos);
  END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION tiempo_dinamico(fecha TIMESTAMP) RETURNS TEXT AS $$
  DECLARE
    segundos INT;
    minutos INT;
    horas INT;
    dias INT;
    resultado TEXT;
  BEGIN
    segundos := EXTRACT(EPOCH FROM NOW() - fecha);

    minutos := segundos / 60;
    horas := segundos / 3600;
    dias := segundos / 86400;

    CASE 
      WHEN segundos < 60 THEN resultado := segundos::TEXT || CASE WHEN segundos = 1 THEN ' segundo' ELSE ' segundos' END CASE;
      WHEN minutos < 60 THEN resultado := minutos::TEXT || CASE WHEN minutos = 1 THEN ' minuto' ELSE ' minutos' END CASE;
      WHEN horas < 24 THEN resultado := horas::TEXT || CASE WHEN horas = 1 THEN ' hora' ELSE ' horas' END CASE;
      ELSE
        resultado := dias::TEXT || CASE WHEN dias = 1 THEN ' dia' ELSE ' dias' END CASE;
    END CASE;

    RETURN 'hace ' || resultado;
  END;
$$ LANGUAGE plpgsql;