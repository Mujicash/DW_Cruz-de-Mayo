create table formatos
(
    id      int auto_increment primary key,
    formato varchar(100) not null
);

create table productos
(
    id           int auto_increment primary key,
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
    id        int auto_increment primary key,
    nombre    varchar(200) null,
    ruc       varchar(50)  not null,
    telefono  varchar(20)  not null,
    direccion varchar(255) not null,
    correo    varchar(100) not null,
    constraint nombre_unique
        unique (nombre)
);

create table sucursales
(
    id        int auto_increment primary key,
    nombre    varchar(100) not null,
    direccion varchar(300) not null
);

create table stocks
(
    id          int auto_increment primary key,
    cantidad    int not null,
    id_producto int not null,
    id_sucursal int not null,
    constraint fk_stock_producto
        foreign key (id_producto) references productos (id)
            on update cascade on delete cascade,
    constraint fk_stock_sucursal
        foreign key (id_sucursal) references sucursales (id)
            on update cascade on delete cascade
);

create table tipo_usuarios
(
    id   int auto_increment primary key,
    tipo varchar(100) not null
);

create table usuarios
(
    id              int auto_increment primary key,
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
);

create table orden_compras
(
    id           int auto_increment primary key,
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
);

create table compra_detalles
(
    id          int auto_increment primary key,
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
);

create table guia_remisiones
(
    id              int auto_increment primary key,
    num_guia        varchar(30)  not null,
    motivo          varchar(300) null,
    fecha_inicio    datetime     not null,
    fecha_recepcion datetime     not null,
    img             longblob     null,
    id_compra       int          not null,
    constraint fk_guiaRemision_orden
        foreign key (id_compra) references orden_compras (id)
            on update cascade on delete cascade
);

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
    id          int auto_increment primary key,
    fecha       datetime default CURRENT_TIMESTAMP not null,
    id_usuario  int                                not null,
    id_sucursal int                                not null,
    constraint fk_salidas_sucursal
        foreign key (id_sucursal) references sucursales (id)
            on update cascade on delete cascade,
    constraint fk_salidas_usuario
        foreign key (id_usuario) references usuarios (id)
            on update cascade on delete cascade
);

create table detalle_salidas
(
    id          int auto_increment primary key,
    id_salida   int not null,
    cantidad    int not null,
    id_producto int not null,
    constraint fk_detalleSalida_orden
        foreign key (id_salida) references orden_salidas (id)
            on update cascade on delete cascade
);

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