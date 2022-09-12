create table formatos
(
    id      int auto_increment
        primary key,
    formato varchar(100) not null
)
    auto_increment = 10;

create table productos
(
    id           int auto_increment
        primary key,
    nombre       varchar(255) not null,
    laboratorio  varchar(100) not null,
    precio_venta float(7, 2)  not null,
    descripcion  varchar(500) null,
    id_unidad    int          not null,
    constraint nombre_unique
        unique (nombre),
    constraint fk_productos_formato
        foreign key (id_unidad) references formatos (id)
            on update cascade on delete cascade
)
    auto_increment = 34;

create trigger defaultStock
    after insert
    on productos
    for each row
begin
    declare idP int default 0;
    set idP = (select max(id) as id from productos);

    insert into stocks (cantidad, id_producto, id_sucursal) SELECT 0, idP, id from sucursales;
end;

create table proveedores
(
    id        int auto_increment
        primary key,
    nombre    varchar(200) null,
    ruc       varchar(50)  not null,
    telefono  varchar(20)  not null,
    direccion varchar(255) not null,
    correo    varchar(100) not null,
    constraint nombre_unique
        unique (nombre)
)
    auto_increment = 8;

create table sucursales
(
    id        int auto_increment
        primary key,
    nombre    varchar(100) not null,
    direccion varchar(300) not null
)
    auto_increment = 4;

create table stocks
(
    id          int auto_increment
        primary key,
    cantidad    int not null,
    id_producto int not null,
    id_sucursal int not null,
    constraint fk_stock_producto
        foreign key (id_producto) references productos (id)
            on update cascade on delete cascade,
    constraint fk_stock_sucursal
        foreign key (id_sucursal) references sucursales (id)
            on update cascade on delete cascade
)
    auto_increment = 137;

create table tipo_usuarios
(
    id   int auto_increment
        primary key,
    tipo varchar(100) not null
)
    auto_increment = 4;

create table usuarios
(
    id              int auto_increment
        primary key,
    usuario         varchar(70)  not null,
    nombre          varchar(70)  not null,
    apellido_pat    varchar(70)  not null,
    apellido_mat    varchar(70)  not null,
    pasword         varchar(255) not null,
    fecha_creacion  datetime     null,
    ultima_conexion datetime     null,
    id_tipo         int          not null,
    id_sucursal     int          not null,
    api_token       varchar(128) null,
    constraint fk_usuario_sucursal
        foreign key (id_sucursal) references sucursales (id)
            on update cascade on delete cascade,
    constraint fk_usuario_tipo
        foreign key (id_tipo) references tipo_usuarios (id)
            on update cascade on delete cascade
)
    auto_increment = 8;

create table orden_compras
(
    id           int auto_increment
        primary key,
    fecha_compra datetime   default CURRENT_TIMESTAMP not null,
    estado       tinyint(1) default 0                 not null,
    id_proveedor int                                  not null,
    id_usuario   int                                  not null,
    id_sucursal  int                                  not null,
    constraint fk_compras_proveedor
        foreign key (id_proveedor) references proveedores (id)
            on update cascade on delete cascade,
    constraint fk_compras_sucursal
        foreign key (id_sucursal) references sucursales (id)
            on update cascade on delete cascade,
    constraint fk_compras_usuario
        foreign key (id_usuario) references usuarios (id)
            on update cascade on delete cascade
)
    auto_increment = 15;

create table compra_detalles
(
    id          int auto_increment
        primary key,
    id_compra   int         not null,
    precio      float(5, 2) not null,
    cantidad    int         not null,
    id_producto int         not null,
    constraint fk_detalleCompra_orden
        foreign key (id_compra) references orden_compras (id)
            on update cascade on delete cascade,
    constraint fk_detalleCompra_producto
        foreign key (id_producto) references productos (id)
            on update cascade on delete cascade
)
    auto_increment = 38;

create table guia_remisiones
(
    id              int auto_increment
        primary key,
    num_guia        varchar(30)  not null,
    motivo          varchar(300) null,
    fecha_inicio    datetime     not null,
    fecha_recepcion datetime     not null,
    img             longblob     null,
    id_compra       int          not null,
    constraint fk_guiaRemision_orden
        foreign key (id_compra) references orden_compras (id)
            on update cascade on delete cascade
)
    auto_increment = 2;

create trigger cambiarEstadoCompra
    after insert
    on guia_remisiones
    for each row
begin
    declare idComp int default 0;
    set idComp = new.id_compra;

    update orden_compras set estado = 1 where id = idComp;
end;

create table orden_salidas
(
    id          int auto_increment
        primary key,
    fecha       datetime default CURRENT_TIMESTAMP not null,
    id_usuario  int                                not null,
    id_sucursal int                                not null,
    constraint fk_salidas_sucursal
        foreign key (id_sucursal) references sucursales (id)
            on update cascade on delete cascade,
    constraint fk_salidas_usuario
        foreign key (id_usuario) references usuarios (id)
            on update cascade on delete cascade
)
    auto_increment = 25;

create table detalle_salidas
(
    id          int auto_increment
        primary key,
    id_salida   int not null,
    cantidad    int not null,
    id_producto int not null,
    constraint fk_detalleSalida_orden
        foreign key (id_salida) references orden_salidas (id)
            on update cascade on delete cascade
)
    auto_increment = 62;

create trigger disminuirStock
    after insert
    on detalle_salidas
    for each row
begin
    declare idSu int default 0;
    declare idPr int default 0;
    declare cant int default 0;
    set idSu = (select id_sucursal from orden_salidas where id = new.id_salida);
    set idPr = new.id_producto;
    set cant = new.cantidad;

    update stocks set cantidad = cantidad - cant where id_sucursal = idSu and id_producto = idPr;

end;

insert into tipo_usuarios (id, tipo) values (1, 'Administrador');
insert into tipo_usuarios (id, tipo) values (2, 'Jefe Almacen');
insert into tipo_usuarios (id, tipo) values (3, 'Encargado de ventas');

insert into sucursales (id, nombre, direccion) values (1, 'Cruz de mayo principal', 'Jr, Ica N° 302, Huancayo');
insert into sucursales (id, nombre, direccion) values (2, 'Cruz de mayo Norte', 'Av. Alfredo Mendiola 2109, Los Olivos 15302');
insert into sucursales (id, nombre, direccion) values (3, 'Cruz de mayo Sur', 'Av. Pedro Miotta 1010, Lima 15801');

insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (1, 'P&G DISTRIBUIDORES SRL', '20258134568', '994616327', 'Cl. Añaquito 169 B, Cercado de Lima 15088', 'distribuidorespyg@gmail.com');
insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (2, 'Corporacion Pionero - Drogueria y Distribuidora Farmaceutica', '20545457386', '(01) 7483399', 'Jr. Sta. Rosa 801, Cercado de Lima 15001', 'corporacionPionero@hotmail.com');
insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (3, 'Distribuidora y Negociaciones Sebastian E.I.R.L.', '20550974615', '(01) 7483398', 'Jr. Ref Nro. 799 (Contralmirante Lizardo Montero)', 'drogueria_sebastian@hotmail.com');
insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (4, 'OVAS S.A.C.', '20474799671', '(01) 3987483', 'Av. Los Faisanes Mz. C, Lt. 20-B La Campania', 'ovas_sac@hotmail.com');
insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (5, 'MEDICAL AIR', '20557931156', '(01) 7143853', 'Av. Elmer Faucett Mz. E1, Lt. 01 Alameda Portuaria', 'medair@hotmail.com');
insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (6, 'PRAXAIR PERU', '20338570041', '(01) 5373814', 'Carretera central km 9.5 ZI. Zona Industrial', 'praxair_pe@hotmail.com');
insert into proveedores (id, nombre, ruc, telefono, direccion, correo) values (7, 'OXIGENO SANTA CLARA S.A.C.', '20557720999', '(01) 2336511', 'Mz. A, Lote 6 Urb. La estrella de Ate', 'oxigen_sc@hotmail.com');

insert into formatos (id, formato) values (1, 'Blister');
insert into formatos (id, formato) values (2, 'Caja');
insert into formatos (id, formato) values (3, 'Frasco');
insert into formatos (id, formato) values (4, 'Vial');
insert into formatos (id, formato) values (5, 'Aerosol');
insert into formatos (id, formato) values (6, 'Ampolla');
insert into formatos (id, formato) values (7, 'Capsula');
insert into formatos (id, formato) values (8, 'Crema');
insert into formatos (id, formato) values (9, 'Comprimido');

insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (1, 'Cama clinica de ortopedias', 'Corporacion Pionero - Drogueria y Distribuidora Farmaceutica', 2249.9, 'Paracetamol generico de 500 mg laboratorio Genfar', 1);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (2, 'Tensiómetro CITIZEN con Adaptador de Corriente', 'Corporacion Pionero - Drogueria y Distribuidora Farmaceutica', 220, 'Paracetamol generico de 100 mg laboratorio Genfar', 1);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (3, 'Mandil Emplomado Pediatrico con Collarin para niño', 'Corporacion Pionero - Drogueria y Distribuidora Farmaceutica', 690, 'Dormidina generico de 500 mg laboratorio Genfar, duermes bien rico.', 1);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (4, 'Pieza de mano dental rápida de alta velocidad de 2 orificios', 'OVAS S.A.C.', 141.3, 'Acetazolamida - 250mg, 10 tabletas en blister', 1);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (5, 'FÉRULA PARA DEDOS-INMOVILIZADOR 6 unidades', 'MEDICAL AIR', 37, 'Azitromicina - 500mg, blister de 15 unidades', 1);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (6, 'Cojín de Asiento con Gel Ortopédico Cervical y Lumbar', 'MEDICAL AIR', 139, 'Amikacina - 250mg/mL - Inyectable 2mL', 6);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (7, 'Pistola Desinfectante Burana Safety SOS Con Cable', 'PRAXAIR PERU', 400, 'Alcohol yodado 0.3g/100mL solucion frasco 120mL', 3);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (8, 'Caja esterilizadora UV-C + Ultrasonido KF240 - Asdagi', 'PRAXAIR PERU', 500, 'Beclometasona dipropionato 250aeg/dosis- Aerosol 200 dosis', 5);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (9, 'Purificador de aire Imaco AP9035', 'PRAXAIR PERU', 319.9, 'Calcitriol - 0.25aeg tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (10, 'Glucometro AQ Smart 50 tiras y 50 lancetas', 'Distribuidora y Negociaciones Sebastian', 350, 'Cefalexina - 250mg/5mL frasco 60mL', 3);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (11, 'Concentrador de Oxígeno 10 Lt Doble flujo Con Nebulizador', 'Distribuidora y Negociaciones Sebastian', 4999, 'Ceftriaxona sodica - 1g inyectable', 6);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (12, 'Tensiómetro Digital Automático de Brazo Contec 08D', 'Distribuidora y Negociaciones Sebastian', 255, 'Clonazepam 2mg- blister 10 und', 1);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (13, 'Lápiz Masajeador de Terapia Portátil', 'Distribuidora y Negociaciones Sebastian', 99.9, 'Dextrometorfano bromhidrato 15mg/5mL jarabe 120mL', 3);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (14, ' Termómetro Profesional Digital Parrillero Plegable Hakusa Hks-105', 'PRAXAIR PERU', 150, 'Estriol de 100mg/100g(0.1%), crema 15g', 8);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (15, 'Concentrador de Oxígeno Aerti 10LPM AE-8NW Gris', 'OVAS S.A.C.', 6999, 'Fenobarbital de 100mg, tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (16, 'Set Manicura Pedicura Acero Inoxidable 19 Piezas Cortauñas', 'OXIGENO SANTA CLARA S.A.C.', 130, 'Fluconazol de 150mg, tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (17, 'Audífono Medicado para Sordera SONIC Digital Programable Enchant-60', 'Distribuidora y Negociaciones Sebastian E.I.R.L.', 6285.4, 'Furosemida de 10mg/mL, inyectable de 2mL', 6);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (18, 'Audífono Medicado para Sordera MAICO Digital Programable Maico Helios', 'P&G DISTRIBUIDORES SRL', 3142.7, 'Furosemida de 40mg, tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (19, 'Podometro OMRON HJ-321', 'P&G DISTRIBUIDORES SRL', 159, 'Gabapentina de 300mg, tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (20, 'Nebulizador de Compresor Omron con VVT. NE-C801', 'Distribuidora y Negociaciones Sebastian E.I.R.L.', 299.9, 'Gemfibrozilo de 600mg, tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (21, 'Recoger basura pinzas giratorias alcanzando Selector de herramienta de ayuda a la extensión del brazo de plata y negro y amarillo', 'Distribuidora y Negociaciones Sebastian E.I.R.L.', 117, 'Hidroclorotiazida de 25mg, tableta', 9);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (22, 'Otoscopio con 6 luces LED digital de oído otoscopio limpieza kit de limpieza de cera en los oídos de cámara', 'P&G DISTRIBUIDORES SRL', 94, 'Ibuprofeno de 100mg/5mL, frasco de 60ml', 3);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (23, 'Cortador de pastillas Cortador de medicina Caja de medicina Dispensador de medicina portátil', 'OVAS S.A.C.', 45, 'Hidrocortisona de 1g/100g (1%), crema de 20g', 8);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (24, 'Doppler Fetal Jumper - Monitor Cardiaco prenatal', 'MEDICAL AIR', 349, 'Sulfadiazina de plata de 1g/100g, crema de 400g', 8);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (26, 'Botones De Llamada Remota Inalámbrica', 'Corporacion Pionero - Drogueria y Distribuidora Farmaceutica', 159, 'Clindamicina de 300mg, caja de 100 unidades', 2);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (27, 'Instrumentos maternos Monitor Doppler', 'Corporacion Pionero - Drogueria y Distribuidora Farmaceutica', 199, 'Clindamicina de 300mg, campsula', 7);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (28, 'De doble cara estetoscopio médicos', 'MEDICAL AIR', 60, 'Bismutol de 87.33mg/5mL, Frasco 340mL', 3);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (29, 'Orejas desechables Piercing Pierce Gun', 'P&G DISTRIBUIDORES SRL', 101, 'Inductal de 2mg, caja de 30 unidades', 2);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (30, 'Kit de sutura todo incluido
', 'P&G DISTRIBUIDORES SRL', 196, 'Inductal de 2mg, comprimido', 8);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (31, 'Orinal Masculino de Cuello Largo Botella', 'Distribuidora y Negociaciones Sebastian E.I.R.L.', 139, 'Vitamina D 2000UI, capsula blanda', 7);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (32, 'SILLA DE RUEDAS DE ALUMINIO ESTANDAR PZ0022', 'Distribuidora y Negociaciones Sebastian E.I.R.L.', 700, 'Nifedipino de 30mg, capsula de liberación prolongada', 7);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (33, 'COLCHON TUBULAR ANTIESCARAS APEX DOMUS 2S', 'OXIGENO SANTA CLARA S.A.C.', 550, 'Sumigram de 85mg - 500mg, caja de 2 unidades', 2);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (34, 'TENSIOMETRO DE BRAZO DIGITAL – CHOICEMMED CB111', 'MEDICAL AIR', 110, 'sdgfsdhefdgfdgdf', 8);
insert into productos (id, nombre, laboratorio, precio_venta, descripcion, id_unidad) values (35, 'ASPIRADOR DE SECRESIONES PORTATIL LABMEDICAL', 'MEDICAL AIR', 480, 'Sulfadiazina de plata en crema de 200g', 8);

insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (1, 'Ricardo12345', 'Ricardo', 'AAAA2', 'BBBB2', 'a194403985fb8d36ce7a4360809141a5385fb29e7445814ac1bc3a9c1b26150d53bd4f2f96f62ee7500bdabb830f86427233035272ab61df07b02246990d7371', '2022-08-04 23:27:36', '2022-08-04 23:27:36', 1, 1, null);
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (2, 'Ricardo123', 'Andre', 'Mujica', 'del Rosario', 'a194403985fb8d36ce7a4360809141a5385fb29e7445814ac1bc3a9c1b26150d53bd4f2f96f62ee7500bdabb830f86427233035272ab61df07b02246990d7371', '2022-08-06 21:19:32', '2022-08-06 21:19:32', 2, 1, null);
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (3, 'eagurtobriceno', 'Erick Jhoel', 'Agurto', 'Bricenio', '5744a481e5495f43dee30c4f24f69c1f7ee0e46656daf3cbcc90a8cf0e490ab7a6920ab2e36c6498e3096c879daa828e667aa482e4f48273a3ade8f37722ac9c', '2022-08-13 05:40:08', '2022-08-13 05:40:08', 2, 1, '81b97e81a8ed11b8ebfb1bc7e25f986bc50a34c42273d0294e7971f666ab84df0dfa79d275751b0534d88c60aa9cf3e184c1d2260bb8a771a8336307e12d8325');
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (4, 'vjarcabarra', 'Valerie Jazmin', 'Arca', 'Barra', '006580409a42222c4b8fa64c113125c0221c40691e19724893706776854db5639b279c71517fb7c2198fb469e2a0a29dbbf7af3439e56813da4ea251b9f6abea', '2022-08-13 08:33:56', '2022-08-13 08:33:56', 2, 2, 'f8af6d2406eacb0b566518c59c77ee852c50e50dcd452dc9793a4f32ae91fd854a0981b29acb6e60bf8e6fa03e910081280384d0acd8276fc12547d61134b7a7');
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (5, 'goguillenmelgarejo', 'Gabriel Omar', 'Guillen', 'Melgarejo', '0fc0971ef026aad16ad70db281cbbd480b21121f75eb38a2d026845c3059c4c452d4116b6d7e79d4c503da8546d75ee09f8a61c68772e90948d34ce5e53e317e', '2022-08-13 08:38:34', '2022-08-13 08:38:34', 3, 2, '9532b7be1c6188f96eefaa7d2d28b8d36907351b049a039d901ea9dae1c16512eaa82228a4ea8c8f2d6acfe5334bef47fe664a25470b12a6e9c50c789ce79f31');
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (6, 'lscahuanaguerra', 'Leonardo Sergio', 'Cahuana', 'Guerra', '96b290e3814e828e89099f305df88b8aba7281b796b300c6c854b4cade668d54bd02e2c1469806195d4d9f2cd730b63be830aa19e64ee20955941bfee94f377f', '2022-08-13 08:40:09', '2022-08-13 08:40:09', 3, 3, '3bb654a17c19ffb91285e98ce8ae1901327d6ac03c2972d2484b047dbb04216daa149f76ed6c284bee10690e65746a08d34e7c77a558cd9a2c6ba0e994809977');
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (7, 'jrreynosoarmas', 'Jhonas Raul', 'Reynoso', 'Armas', 'b7bb13c1a5e759abd2a3b035ba68d799a4da2029b87da37ebbe6e693284a78bfebd0a980cab8918f981c0f8d7ab7f146031a7d70d226f1ca218ea8d983639287', '2022-08-13 08:42:53', '2022-08-13 08:42:53', 2, 3, null);
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (8, 'gguillen', 'Gabriel', 'Guillen', 'Melgarejo', 'a194403985fb8d36ce7a4360809141a5385fb29e7445814ac1bc3a9c1b26150d53bd4f2f96f62ee7500bdabb830f86427233035272ab61df07b02246990d7371', '2022-08-17 11:45:48', '2022-08-17 11:45:48', 1, 1, null);
insert into usuarios (id, usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal, api_token) values (16, 'mujicash007', 'Andre', 'Mujica', 'del Rosario', '606b675a1e33027bfa4010a8731e9a8747ed18e9c53ee8a65846e5da0d4ebf35a737ba131f8091fd5fb09139d1472b56db8482070241dc31c8aaa9a4583f29f7', '2022-08-17 20:49:32', '2022-08-17 20:49:32', 1, 3, null);

insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (1, '2022-08-06 21:22:58', 1, 1, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (2, '2022-08-06 21:24:40', 1, 1, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (3, '2022-08-07 03:56:52', 1, 1, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (4, '2022-08-07 04:22:19', 1, 2, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (5, '2022-08-10 16:56:53', 1, 2, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (6, '2022-08-11 01:02:33', 1, 1, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (7, '2022-08-12 22:42:39', 1, 1, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (8, '2022-08-13 05:25:55', 1, 3, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (9, '2022-08-14 03:33:50', 1, 4, 4, 2);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (10, '2022-08-14 03:35:34', 1, 5, 4, 2);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (11, '2022-08-14 03:37:09', 1, 6, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (12, '2022-08-14 04:34:39', 1, 5, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (13, '2022-08-14 04:38:43', 1, 7, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (14, '2022-08-14 04:43:53', 1, 3, 4, 2);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (15, '2022-08-17 02:19:35', 1, 6, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (16, '2022-08-17 02:34:46', 1, 3, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (17, '2022-08-17 02:40:52', 1, 1, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (18, '2022-08-17 02:43:11', 1, 5, 4, 2);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (19, '2022-08-17 03:04:45', 1, 4, 4, 2);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (20, '2022-08-18 03:55:49', 0, 3, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (21, '2022-08-18 04:19:31', 0, 4, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (22, '2022-08-18 04:59:52', 0, 3, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (23, '2022-08-18 05:07:50', 0, 4, 2, 1);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (24, '2022-08-18 23:02:19', 0, 3, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (25, '2022-08-18 23:57:28', 0, 5, 7, 3);
insert into orden_compras (id, fecha_compra, estado, id_proveedor, id_usuario, id_sucursal) values (26, '2022-08-26 00:22:10', 0, 4, 7, 3);

insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (1, 2, 10, 100, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (2, 2, 10, 100, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (3, 3, 2.5, 150, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (4, 3, 1.2, 99, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (5, 4, 2.8, 75, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (6, 4, 1.5, 105, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (7, 5, 1.2, 25, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (8, 5, 1.5, 55, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (9, 6, 1.2, 10, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (10, 6, 1.5, 15, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (11, 7, 1.2, 5, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (12, 7, 1.5, 5, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (13, 8, 1.2, 15, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (14, 8, 1.5, 15, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (15, 8, 2.5, 150, 3);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (16, 9, 0.8, 150, 4);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (17, 9, 1.5, 100, 6);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (18, 9, 2.5, 100, 5);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (19, 10, 0.8, 150, 4);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (20, 10, 1.5, 100, 6);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (21, 10, 1, 100, 3);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (22, 10, 1.5, 100, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (23, 10, 0.5, 100, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (24, 11, 0.8, 150, 3);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (25, 11, 1.5, 100, 4);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (26, 11, 1, 100, 6);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (27, 11, 1.5, 100, 2);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (28, 11, 0.5, 100, 1);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (29, 12, 2.4, 40, 5);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (30, 12, 36, 20, 24);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (31, 12, 20.5, 30, 28);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (32, 13, 0.3, 100, 16);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (33, 13, 35.4, 25, 33);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (34, 14, 1.4, 50, 12);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (35, 14, 5.1, 40, 10);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (36, 14, 0.6, 55, 17);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (37, 14, 1.3, 60, 32);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (38, 15, 0.8, 50, 4);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (39, 15, 1.5, 50, 12);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (40, 16, 5, 100, 12);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (41, 16, 4, 100, 26);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (42, 16, 3, 100, 29);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (43, 16, 2, 100, 7);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (44, 16, 1, 100, 10);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (45, 16, 5, 100, 13);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (46, 16, 4, 100, 22);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (47, 16, 3, 100, 8);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (48, 16, 2, 100, 11);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (49, 16, 1, 100, 17);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (50, 16, 5, 100, 27);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (51, 16, 4, 100, 31);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (52, 16, 3, 100, 32);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (53, 16, 2, 100, 14);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (54, 16, 1, 100, 23);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (56, 16, 4, 100, 9);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (57, 16, 5, 100, 15);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (59, 16, 5, 100, 9);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (60, 16, 3, 100, 15);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (61, 16, 1, 100, 16);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (62, 16, 5, 100, 18);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (63, 16, 3, 100, 19);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (64, 16, 5, 100, 20);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (65, 16, 1, 100, 21);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (66, 16, 4, 100, 30);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (67, 17, 0.8, 25, 4);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (68, 17, 0.5, 20, 5);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (69, 17, 0.6, 25, 12);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (70, 17, 1.5, 50, 26);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (71, 17, 0.8, 40, 29);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (72, 17, 1.2, 45, 33);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (73, 17, 1.5, 60, 7);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (74, 17, 1, 50, 10);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (75, 17, 0.3, 30, 13);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (76, 17, 0.5, 40, 22);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (77, 17, 1.2, 35, 28);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (78, 17, 0.8, 10, 8);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (79, 17, 0.9, 16, 6);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (80, 17, 1.2, 20, 11);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (81, 17, 1.5, 25, 17);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (82, 17, 1.5, 30, 27);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (83, 17, 1.6, 45, 31);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (84, 17, 1.2, 45, 32);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (85, 17, 1, 40, 14);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (86, 17, 2, 30, 23);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (87, 17, 0.8, 20, 24);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (89, 17, 1.2, 20, 9);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (90, 17, 1.2, 40, 15);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (91, 17, 1.2, 30, 16);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (92, 17, 1.5, 25, 18);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (93, 17, 2, 14, 19);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (94, 17, 1.2, 47, 20);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (95, 17, 1.2, 50, 21);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (96, 17, 0.8, 50, 30);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (97, 18, 3, 100, 26);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (98, 18, 3, 100, 29);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (99, 18, 3, 100, 33);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (100, 18, 5, 100, 7);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (101, 18, 5, 100, 13);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (102, 18, 5, 100, 22);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (103, 18, 5, 100, 28);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (104, 18, 3, -3, 8);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (105, 18, 2, -3, 11);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (106, 18, 2, 100, 27);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (107, 18, 2, 100, 31);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (108, 18, 1.5, 100, 14);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (109, 18, 1.5, 100, 23);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (110, 18, 1.5, 100, 24);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (112, 18, 2.5, 100, 9);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (113, 18, 2.5, 100, 15);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (114, 18, 2.5, 100, 16);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (115, 18, 2.5, 100, 18);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (116, 18, 2.5, 100, 19);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (117, 18, 2.5, 100, 20);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (118, 18, 2.5, 100, 21);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (119, 18, 2.5, 100, 30);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (120, 19, 0.8, 25, 8);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (121, 19, 0.5, 25, 11);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (122, 20, 12, 2, 3);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (123, 20, 13, 1, 10);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (124, 21, 12, 2, 24);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (125, 21, 3, 3, 28);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (126, 21, 6, 5, 8);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (127, 22, 12, 2, 33);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (128, 22, 13, 1, 28);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (129, 23, 12, 2, 29);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (130, 23, 14, 4, 10);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (131, 24, 10, 2, 4);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (132, 24, 13, 3, 29);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (133, 25, 3, 1, 5);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (134, 26, 3.5, 10, 10);
insert into compra_detalles (id, id_compra, precio, cantidad, id_producto) values (135, 26, 2.8, 20, 3);

insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (1, '100-10082022-05', 'Llegada de productos a la sucursal', '2022-08-11 01:02:33', '2022-08-11 01:13:51', 0x313636303138303432322D3130302D31303038323032322D30352E706E67, 6);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (2, '100-14082022-01', 'Llegada de productos a la sucursal', '2022-08-12 22:42:39', '2022-08-14 18:57:00', 0x313636303530333431382D3130302D31343038323032322D30312E706E67, 7);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (3, '100-14082022-02', 'Llegada de productos a la sucursal', '2022-08-13 05:25:55', '2022-08-14 18:57:28', 0x313636303530333434372D3130302D31343038323032322D30322E706E67, 8);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (4, '100-14082022-03', 'Llegada de productos a la sucursal', '2022-08-14 03:33:50', '2022-08-14 19:00:11', 0x313636303530333631302D3130302D31343038323032322D30322E706E67, 9);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (5, '100-14082022-04', 'Llegada de productos a la sucursal', '2022-08-14 03:35:34', '2022-08-14 19:01:05', 0x313636303530333636342D3130302D31343038323032322D30342E6A7067, 10);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (6, '100-14082022-05', 'Llegada de productos a la sucursal', '2022-08-14 04:43:53', '2022-08-14 19:02:58', 0x313636303530333737372D3130302D31343038323032322D30342E6A7067, 14);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (7, '100-14082022-06', 'Llegada de productos a la sucursal', '2022-08-14 03:37:09', '2022-08-14 19:05:07', 0x313636303530333930362D3130302D31343038323032322D30362E6A7067, 11);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (8, '100-14082022-07', 'Llegada de productos a la sucursal', '2022-08-14 04:34:39', '2022-08-14 19:05:29', 0x313636303530333932382D3130302D31343038323032322D30372E706E67, 12);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (9, '100-14082022-08', 'Llegada de productos a la sucursal', '2022-08-14 04:38:43', '2022-08-14 19:05:44', 0x313636303530333934332D3130302D31343038323032322D30382E706E67, 13);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (10, '100-116082022-15', 'Llegada de productos a la sucursal', '2022-08-17 02:19:35', '2022-08-17 02:49:48', 0x313636303730343539302D3130302D3131363038323032322D31352E706E67, 15);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (11, '100-16082022-16', 'Llegada de productos a la sucursal', '2022-08-17 02:34:46', '2022-08-17 02:54:47', 0x313636303730343838392D3130302D31363038323032322D31362E706E67, 16);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (12, '100-16082022-17', 'Llegada de productos a la sucursal', '2022-08-17 02:40:52', '2022-08-17 02:55:36', 0x313636303730343933392D3130302D31363038323032322D31372E706E67, 17);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (13, '100-16082022-18', 'Llegada de productos a la sucursal', '2022-08-17 02:43:11', '2022-08-17 02:56:04', 0x313636303730343936372D3130302D31363038323032322D31382E706E67, 18);
insert into guia_remisiones (id, num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra) values (14, '100-16082022-19', 'Llegada de productos a la sucursal', '2022-08-17 03:04:45', '2022-08-17 03:14:32', 0x313636303730363037342D3130302D31363038323032322D31392E706E67, 19);

insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (1, '2022-08-08 01:11:23', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (2, '2022-08-10 17:18:00', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (3, '2022-08-10 22:52:04', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (4, '2022-08-14 04:01:16', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (5, '2022-08-14 04:01:38', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (6, '2022-08-14 04:01:54', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (7, '2022-08-14 04:02:23', 3, 1);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (8, '2022-08-14 04:15:46', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (9, '2022-08-14 04:15:59', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (10, '2022-08-14 04:16:08', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (11, '2022-08-14 04:16:19', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (12, '2022-08-14 04:17:13', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (13, '2022-08-14 04:17:22', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (14, '2022-08-14 04:17:37', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (15, '2022-08-14 04:17:56', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (16, '2022-08-14 04:21:32', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (17, '2022-08-14 04:49:41', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (18, '2022-08-14 04:50:52', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (19, '2022-08-14 04:52:17', 6, 3);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (20, '2022-08-14 04:54:35', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (21, '2022-08-14 04:55:06', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (22, '2022-08-14 04:55:32', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (23, '2022-08-14 04:56:09', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (24, '2022-08-14 04:56:27', 5, 2);
insert into orden_salidas (id, fecha, id_usuario, id_sucursal) values (32, '2022-08-26 00:23:53', 6, 3);

insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (1, 1, 1, 1);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (2, 1, 2, 2);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (3, 2, 2, 1);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (4, 2, 5, 2);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (5, 3, 15, 1);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (6, 3, 9, 2);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (7, 4, 4, 1);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (8, 4, 9, 2);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (9, 4, 5, 3);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (10, 5, 2, 4);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (11, 5, 4, 6);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (12, 5, 7, 7);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (13, 6, 5, 8);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (14, 6, 5, 12);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (15, 6, 5, 10);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (16, 7, 1, 1);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (17, 7, 5, 2);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (18, 7, 5, 3);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (19, 8, 3, 4);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (20, 8, 6, 3);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (21, 8, 6, 5);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (22, 9, 3, 8);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (23, 9, 4, 9);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (24, 9, 6, 10);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (25, 10, 3, 11);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (26, 10, 4, 2);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (27, 10, 6, 4);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (28, 11, 7, 10);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (29, 11, 4, 6);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (30, 11, 6, 4);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (31, 12, 7, 1);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (32, 12, 4, 6);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (33, 12, 6, 4);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (34, 13, 7, 20);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (35, 13, 4, 15);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (36, 13, 4, 4);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (37, 14, 2, 14);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (38, 14, 4, 12);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (39, 14, 4, 7);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (40, 15, 8, 16);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (41, 15, 9, 17);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (42, 15, 4, 12);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (43, 16, 8, 16);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (44, 16, 9, 17);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (45, 16, 4, 12);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (46, 17, 4, 5);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (47, 17, 3, 33);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (48, 18, 3, 24);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (49, 18, 1, 28);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (50, 18, 4, 16);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (51, 19, 5, 5);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (52, 19, 2, 16);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (53, 20, 2, 12);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (54, 20, 5, 17);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (55, 21, 1, 12);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (56, 21, 4, 10);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (57, 21, 3, 32);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (58, 22, 7, 10);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (59, 23, 3, 32);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (60, 23, 1, 17);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (61, 24, 4, 17);
insert into detalle_salidas (id, id_salida, cantidad, id_producto) values (78, 32, 2, 3);

insert into stocks (id, cantidad, id_producto, id_sucursal) values (1, 337, 1, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (2, 341, 2, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (4, 100, 1, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (5, 93, 1, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (7, 96, 2, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (8, 100, 2, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (10, 140, 3, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (11, 94, 3, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (12, 148, 3, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (13, 23, 4, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (14, 285, 4, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (15, 140, 4, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (16, 17, 5, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (17, 94, 5, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (18, 31, 5, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (19, 20, 12, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (20, 47, 12, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (21, 138, 12, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (22, 46, 26, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (23, 100, 26, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (24, 100, 26, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (25, 38, 29, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (26, 100, 29, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (27, 100, 29, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (28, 40, 33, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (29, 100, 33, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (30, 22, 33, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (31, 53, 7, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (32, 100, 7, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (33, 96, 7, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (34, 44, 10, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (35, 16, 10, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (36, 100, 10, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (37, 28, 13, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (38, 100, 13, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (39, 100, 13, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (40, 28, 22, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (41, 100, 22, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (42, 100, 22, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (43, 35, 28, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (44, 100, 28, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (45, 29, 28, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (46, 5, 8, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (47, 19, 8, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (48, 100, 8, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (49, 12, 6, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (50, 196, 6, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (51, 96, 6, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (52, 20, 11, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (53, 19, 11, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (54, 100, 11, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (55, 25, 17, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (56, 45, 17, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (57, 82, 17, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (58, 30, 27, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (59, 100, 27, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (60, 100, 27, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (61, 45, 31, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (62, 100, 31, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (63, 100, 31, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (64, 45, 32, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (65, 54, 32, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (66, 100, 32, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (67, 40, 14, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (68, 100, 14, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (69, 98, 14, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (70, 30, 23, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (71, 100, 23, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (72, 100, 23, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (73, 18, 24, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (74, 100, 24, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (75, 17, 24, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (79, 17, 9, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (80, 96, 9, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (81, 200, 9, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (82, 40, 15, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (83, 100, 15, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (84, 196, 15, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (85, 30, 16, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (86, 100, 16, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (87, 178, 16, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (88, 25, 18, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (89, 100, 18, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (90, 100, 18, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (91, 5, 19, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (92, 100, 19, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (93, 100, 19, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (94, 47, 20, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (95, 100, 20, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (96, 93, 20, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (97, 50, 21, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (98, 100, 21, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (99, 100, 21, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (100, 45, 30, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (101, 100, 30, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (102, 100, 30, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (137, -2, 34, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (138, 0, 34, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (139, 0, 34, 3);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (140, 0, 35, 1);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (141, 0, 35, 2);
insert into stocks (id, cantidad, id_producto, id_sucursal) values (142, 0, 35, 3);
