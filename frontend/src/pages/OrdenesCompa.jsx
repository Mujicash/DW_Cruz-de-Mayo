import React from "react";
import BarraRegistroFiltro from "../components/BarraRegistroFiltro";
import '../css/Usuarios.css';
import ContButtonsTab from "../components/ContButtonsTab";
import ButtonTab from "../components/ButtonTab";
import Cookies from "universal-cookie";
import RegistrarOrdenCompra from "../components/RegistrarOrdenCompra";
import axios from "axios";
import DetalleOrdenCompra from '../components/DetalleOrdenCompra'

const cookies = new Cookies();

const config = {
    headers: {
        "Authorization": `Bearer ${cookies.get('token')}`
    }
};

const infoUser = `?id_usuario=${parseInt(cookies.get("id"))}&tipo=${cookies.get("tipo")}`;

const Fila = (props) => {
    return (
        <tr>
            <td>{props.idd}</td>
            <td>{props.fecha}</td>
            <td>{props.ruc}</td>
            <td>{props.costo}</td>
            <td>{props.estado}</td>
            <td>
                <ContButtonsTab>
                    <ButtonTab
                        texto='Detallar'
                        back='var(--normal)'
                        border='var(--normal-dark)'
                        click={props.detallar}
                    />
                </ContButtonsTab>
            </td>

        </tr>
    );
}

const FilaDetalle = (props) => {
    return (
        <tr>
            <td>{props.codigo}</td>
            <td>{props.producto}</td>
            <td>{props.cantidad}</td>
            <td>{props.costo}</td>
            <td>{props.subtotal}</td>
        </tr>
    );
}

let ordenes = [];

class OrdenCompra extends React.Component {

    constructor() {
        super();

        this.state = {
            ordenes: {
                data: []
            },
            busqueda: '',
            detalleOrden: {
                numero: '',
                fecha: '',
                estado: '',
                empresa: '',
                ruc: '',
                productos: [],
                total: ''
            }
        };
    }

    traerOrdenes = async () => {
        await axios.get(`http://localhost:8000/api/ordenesCompra${infoUser}`, config)
            .then(res => {
                // console.log(res.data);
                ordenes = res.data;
                this.setState({
                    ordenes: {
                        data: ordenes
                    }
                });
                console.log(ordenes);
            })
            .catch(err => {
                console.log(err);
            })
    }

    traerDataOrden = async (id) => {
        await axios.get(`http://localhost:8000/api/ordenesCompra/${id}${infoUser}`, config)
            .then(res => {
                let totOrden = 0;
                console.log(res.data);
                res.data.productos.map(p => {
                    totOrden = totOrden + (parseInt(p.cantidad) * parseFloat(p.precio))
                })
                this.setState({
                    detalleOrden: {
                        numero: res.data.id,
                        fecha: res.data.fecha_compra,
                        estado: res.data.estado,
                        empresa: res.data.nombre_proveedor,
                        ruc: res.data.ruc_proveedor,
                        productos: res.data.productos,
                        total: totOrden
                    }
                });

            })
            .catch(err => {
                console.log(err);
            })
        document.getElementById('pop-detalle-oc').showModal();
    }

    capturarBusqueda = async (e) => {
        e.persist();
        await this.setState({ busqueda: e.target.value });
        // console.log(this.state.busqueda);
        this.filtraBusqueda();
    }

    filtraBusqueda = () => {
        let search = ordenes.filter(i => {
            if (i.rucProveedor.toLowerCase().includes(this.state.busqueda.toLowerCase())) {
                return i;
            }
        });
        console.log(search);
        this.setState({ ordenes: { data: search } })
    }

    cerrarModal = () => {
        document.getElementById('pop-conf-oc').close();
        document.getElementById('pop-registrar-oc').close();
        this.traerOrdenes();
    }

    componentDidMount() {
        if (!cookies.get('id')) {
            window.location.href = '/';
        }
        this.traerOrdenes();
    }

    abrirRegistro = () => {
        document.getElementById('pop-registrar-oc').showModal();
    }

    render() {
        return (
            <>
                <h1 className="tit_general">Orden de Compra</h1>
                <BarraRegistroFiltro
                    registro={true}
                    txtRegistro='Registrar Orden'
                    place='Proveedor...'
                    registrar={this.abrirRegistro}
                    change={this.capturarBusqueda}
                />
                <div className="cont_table_user">
                    <table className="table_user">
                        <caption className="tit_table_user">
                            Lista de Ordenes de Compra
                        </caption>

                        <thead className="thead_user">
                            <tr>
                                <th>N°</th>
                                <th>Fecha de Compra</th>
                                <th>RUC Proveedor</th>
                                <th>Costo Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody className="tbody_user">
                            {this.state.ordenes.data.map(u =>
                                <Fila
                                    key={u.idOrden}
                                    idd={u.idOrden}
                                    fecha={u.fechaCompra}
                                    ruc={u.rucProveedor}
                                    costo={`S/. ${u.costoTotal}`}
                                    estado={u.estado}
                                    detallar={() => this.traerDataOrden(u.idOrden)}
                                />
                            )}
                        </tbody>
                    </table>
                </div>
                <RegistrarOrdenCompra
                    idd='pop-registrar-oc'
                    cerrar={() => document.getElementById('pop-registrar-oc').close()}
                    /*------------------------- */
                    cerrarConf={this.cerrarModal}
                />
                <DetalleOrdenCompra
                    idd='pop-detalle-oc'
                    numero={this.state.detalleOrden.numero}
                    fecha={this.state.detalleOrden.fecha}
                    estado={this.state.detalleOrden.estado}
                    empresa={this.state.detalleOrden.empresa}
                    ruc={this.state.detalleOrden.ruc}
                    total={this.state.detalleOrden.total}
                    /*------------------------------------*/
                    volver={() => document.getElementById('pop-detalle-oc').close()}
                    generar={() => console.log('...Generar Reporte...')}
                >
                    {this.state.detalleOrden.productos.map(d =>
                        <FilaDetalle
                            key={d.id}
                            codigo={d.id}
                            cantidad={d.cantidad}
                            producto={d.nombre}
                            costo={d.precio}
                            subtotal={parseInt(d.cantidad) * parseFloat(d.precio)}
                        />)}

                </DetalleOrdenCompra>
            </>
        );
    }

}


export default OrdenCompra;