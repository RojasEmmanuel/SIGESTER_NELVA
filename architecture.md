```mermaid
flowchart TD

  %% === CAPA PRESENTACIÓN ===
  User["Usuario (Browser)"] --> WebServer["Servidor Web"]
  WebServer --> Entry["public/index.php"]
  Entry --> Laravel["Laravel Framework"]

  %% === CONTROLADORES ===
  Laravel --> C_Controller["Controlador: Controller"]

  %% === MODELOS ===
  Laravel --> M_AmenidadFraccionamiento["Modelo: AmenidadFraccionamiento"]
  Laravel --> M_Apartado["Modelo: Apartado"]
  Laravel --> M_ApartadoDeposito["Modelo: ApartadoDeposito"]
  Laravel --> M_ArchivosFraccionamiento["Modelo: ArchivosFraccionamiento"]
  Laravel --> M_AsesorInfo["Modelo: AsesorInfo"]
  Laravel --> M_BeneficiarioClienteVenta["Modelo: BeneficiarioClienteVenta"]
  Laravel --> M_ClienteContacto["Modelo: ClienteContacto"]
  Laravel --> M_ClienteDireccion["Modelo: ClienteDireccion"]
  Laravel --> M_ClienteVenta["Modelo: ClienteVenta"]
  Laravel --> M_Credito["Modelo: Credito"]
  Laravel --> M_Fraccionamiento["Modelo: Fraccionamiento"]
  Laravel --> M_Galeria["Modelo: Galeria"]
  Laravel --> M_HistorialCambiosLote["Modelo: HistorialCambiosLote"]
  Laravel --> M_InfoFraccionamiento["Modelo: InfoFraccionamiento"]
  Laravel --> M_Lote["Modelo: Lote"]
  Laravel --> M_LoteApartado["Modelo: LoteApartado"]
  Laravel --> M_LoteMedida["Modelo: LoteMedida"]
  Laravel --> M_LoteZona["Modelo: LoteZona"]
  Laravel --> M_PlanoFraccionamiento["Modelo: PlanoFraccionamiento"]
  Laravel --> M_Promocion["Modelo: Promocion"]
  Laravel --> M_TipoUsuario["Modelo: TipoUsuario"]
  Laravel --> M_User["Modelo: User"]
  Laravel --> M_Usuario["Modelo: Usuario"]
  Laravel --> M_Venta["Modelo: Venta"]
  Laravel --> M_Zona["Modelo: Zona"]

  %% === RELACIONES ENTRE MODELOS ===
  M_AmenidadFraccionamiento -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Apartado -->|belongsTo| M_Usuario, 'id_usuario', 'id_usuario'
  M_Apartado -->|hasOne| M_ApartadoDeposito, 'id_apartado', 'id_apartado'
  M_Apartado -->|hasMany| M_LoteApartado, 'id_apartado', 'id_apartado'
  M_Apartado -->|hasOne| M_Venta, 'id_apartado', 'id_apartado'
  M_ApartadoDeposito -->|belongsTo| M_Apartado, 'id_apartado', 'id_apartado'
  M_ArchivosFraccionamiento -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_AsesorInfo -->|belongsTo| M_Usuario, 'id_usuario', 'id_usuario'
  M_BeneficiarioClienteVenta -->|belongsTo| M_Venta, 'id_venta', 'id_venta'
  M_BeneficiarioClienteVenta -->|belongsTo| M_ClienteVenta, 'id_cliente', 'id_cliente'
  M_ClienteContacto -->|belongsTo| M_ClienteVenta, 'id_cliente', 'id_cliente'
  M_ClienteDireccion -->|belongsTo| M_ClienteVenta, 'id_cliente', 'id_cliente'
  M_ClienteVenta -->|belongsTo| M_Venta, 'id_venta', 'id_venta'
  M_ClienteVenta -->|hasOne| M_ClienteContacto, 'id_cliente', 'id_cliente'
  M_ClienteVenta -->|hasOne| M_ClienteDireccion, 'id_cliente', 'id_cliente'
  M_Credito -->|belongsTo| M_Venta, 'id_venta', 'id_venta'
  M_Fraccionamiento -->|hasMany| M_Lote, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Fraccionamiento -->|hasOne| M_InfoFraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Fraccionamiento -->|hasMany| M_PlanoFraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Fraccionamiento -->|hasMany| M_AmenidadFraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Fraccionamiento -->|hasMany| M_Galeria, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Fraccionamiento -->|hasMany| M_ArchivosFraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Fraccionamiento -->|belongsToMany| M_Promocion,
            'fraccionamiento_promocion',
            'id_fraccionamiento',
            'id_promocion'
        )->withTimestamps(
  M_Fraccionamiento -->|hasMany| M_Zona, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Galeria -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_HistorialCambiosLote -->|belongsTo| M_Lote, 'id_lote', 'id_lote'
  M_HistorialCambiosLote -->|belongsTo| M_Usuario, 'id_usuario', 'id_usuario'
  M_InfoFraccionamiento -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Lote -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Lote -->|hasOne| M_LoteMedida, 'id_lote', 'id_lote'
  M_Lote -->|hasOne| M_LoteZona, 'id_lote', 'id_lote'
  M_LoteApartado -->|belongsTo| M_Apartado, 'id_apartado', 'id_apartado'
  M_LoteApartado -->|belongsTo| M_Lote, 'id_lote', 'id_lote'
  M_LoteMedida -->|belongsTo| M_Lote, 'id_lote', 'id_lote'
  M_LoteZona -->|belongsTo| M_Lote, 'id_lote', 'id_lote'
  M_LoteZona -->|belongsTo| M_Zona, 'id_zona', 'id_zona'
  M_PlanoFraccionamiento -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_PlanoFraccionamiento -->|belongsTo| M_Usuario, 'id_usuario', 'id_usuario'
  M_Promocion -->|belongsToMany| M_Fraccionamiento,
            'fraccionamiento_promocion',
            'id_promocion',
            'id_fraccionamiento'
        )->withTimestamps(
  M_Usuario -->|belongsTo| M_TipoUsuario, 'tipo_usuario', 'id_tipo'
  M_Usuario -->|hasOne| M_AsesorInfo, 'id_usuario', 'id_usuario'
  M_Venta -->|belongsTo| M_Apartado, 'id_apartado', 'id_apartado'
  M_Venta -->|hasOne| M_ClienteVenta, 'id_venta', 'id_venta'
  M_Venta -->|hasOne| M_BeneficiarioClienteVenta, 'id_venta', 'id_venta'
  M_Venta -->|hasOne| M_Credito, 'id_venta', 'id_venta'
  M_Zona -->|belongsTo| M_Fraccionamiento, 'id_fraccionamiento', 'id_fraccionamiento'
  M_Zona -->|hasMany| M_LoteZona, 'id_zona', 'id_zona'

  %% === FIN ===
```