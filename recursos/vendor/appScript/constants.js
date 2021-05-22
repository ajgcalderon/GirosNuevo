const BASE_URL = "http://giralovzla.net/GirosVzla/";

const ESTADO_TRANSACCION_PENDIENTE = 'Pendiente';
const ESTADO_TRANSACCION_PROCESADO = 'Procesada';
const ESTADO_TRANSACCION_VERIFICACION = 'Espera de Corrección';
const ESTADO_TRANSACCION_CANCELADO = 'Cancelado';

const COMISIONES_GIRADORES = 0.02;

const DB_PREFIX = 'gvzla_';

const USUARIO_ADMINISTRADOR = 'Administrador';
const USUARIO_SUPERVISOR = 'Supervisor';
const USUARIO_GIRADOR = 'Girador';
const USUARIO_TIENDA = 'Tienda';
const USUARIO_DISTRIBUIDOR = 'Distribuidor';
const USUARIO_SUBDISTRIBUIDOR = 'Sub-Distribuidor';

const ESTADO_USUARIO_ENEABLE = 1;
const ESTADO_USUARIO_DISABLE = 0;

const ESTADO_CUENTA_ENEABLE = 1;
const ESTADO_CUENTA_DISABLE = 0;

const ESTADO_TRANFERENCIAS_EJECUTADA = 1;
const ESTADO_TRANFERENCIAS_DEVUELTA = 2;

const RECLAMO_ESTADO_INICIADO = 1;
const RECLAMO_ESTADO_RESUELTO = 0;

const GRUPO_TIENDAS_RECARGAS = 'Por Recarga';
const GRUPO_TIENDAS_CALLVOIP = 'Giralo Venezuela';
const GRUPO_EMPRESA = 'Giralo Venezuela';

const ESTADO_MENSAJE_ACTIVO = 'Activo';
const ESTADO_MENSAJE_INACTIVO = 'Inactivo';

const PUBLICO_MENSAJES_CLIENTES = 'Clientes';
const PUBLICO_MENSAJES_PERSONAL = 'Personal';
const PUBLICO_MENSAJES_TODOS = 'Todos';

const ESTADO_RECARGA_ESPERA = 'En Espera';
const ESTADO_RECARGA_ACEPTADA = 'Aceptada';
const ESTADO_RECARGA_RECHAZADA = 'Rechazada';