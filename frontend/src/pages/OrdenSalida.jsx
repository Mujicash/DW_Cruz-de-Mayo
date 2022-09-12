import React from "react";
import BarraRegistroFiltro from "../components/BarraRegistroFiltro";
import ContButtonsTab from "../components/ContButtonsTab";
import '../css/Proveedores.css';
import ButtonTab from "../components/ButtonTab";
import Cookies from "universal-cookie";
import axios from "axios";
import RegistrarOrdenSalida from "../components/RegistrarOrdenSalida";
import DetalleOrdenSalida from "../components/DetalleOrdenSalida";


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
            <td>{props.id}</td>
            <td>{props.ejecutor}</td>
            <td>{props.fecha}</td>
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

const FilaDetalle =(props)=>{
    return(
        <tr>
            <td>{props.codigo}</td>
            <td>{props.producto}</td>
            <td>{props.cantidad}</td>
        </tr>
    );
}

let ordenes = [];


class OrdenSalida extends React.Component {

    constructor() {
        super();

        this.state = {
            dialog: {
                titulo: 'login',
                texto: '!Bienvenido',
                button: 'Aceptar',
                cerrar: this.cerrarModal,
                color: 'var(--primary)'
            },
            orders: {
                data: []
            },
            busqueda: '',
            detalleOrden : {
                numero : '',
                fecha : '',
                ejecutor : '',
                productos : [],
            }
        };
    }

    traerOrdenes = async () => {
        await axios.get(`http://localhost:8000/api/ordenesSalida${infoUser}`, config)
            .then(res => {
                ordenes = res.data;
                this.setState({
                    orders: {
                        data: ordenes
                    }
                })
            })
            .catch(err => {
                console.log(err);
            })
    }

    capturarBusqueda = async (e) => {
        e.persist();
        await this.setState({ busqueda: e.target.value });
        // console.log(this.state.busqueda);
        this.filtraBusqueda();
    }

    filtraBusqueda = () => {
        let search = ordenes.filter(i => {
            if (i.ejecutor.toLowerCase().includes(this.state.busqueda.toLowerCase())) {
                return i;
            }
        });
        console.log(search);
        this.setState({ orders: { data: search } })
    }



    detallar = async (id) => {
        console.log("Detalle orden de salida " + id);
        await axios.get(`http://localhost:8000/api/ordenesSalida/${id}${infoUser}`, config)
        .then(res =>{
            console.log(res.data);
            this.setState({
                detalleOrden : {
                    numero : res.data.idOrden,
                    fecha : res.data.fecha,
                    ejecutor : res.data.ejecutor,
                    productos : res.data.productos,
                }
            });

        })
        .catch(err =>{
            console.log(err);
        })
        document.getElementById('pop-detalle-oc').showModal();
    }

    limpiarOrden = () => {
        this.setState({
            ordenData: {
                id: '',
                ejecutor: '',
                fecha: '',
                editable: true
            },
            boton: {
                texto: 'Registrar',
                accion: this.RegistrarOrdenSalida,
                pregunta: '¿Desea Registrar la Orden de Salida?',
                titulo: 'Registrar Orden Salida'
            }
        })
    }

    abrirRegistro(e) {
        // e.preventDefault();
        document.getElementById('pop-registrar-os').showModal();
    }

    cerrarRegistro = (e) => {
        e.preventDefault();
        this.limpiarOrden();
        document.getElementById('pop-registrar-os').close();
        document.getElementById('orden_c_prod').value = '-1';
        document.getElementById('orden_c_cant').value = '';
        this.traerOrdenes();
    }

    componentDidMount() {
        if (!cookies.get('id')) {
            window.location.href = '/';
        }

        this.traerOrdenes();
    }

    render() {
        return (
            <>
                <h1 className="tit_general">Orden de Salida</h1>
                <BarraRegistroFiltro
                    registro={true}
                    txtRegistro='Registrar Orden Salida'
                    place='Nombre del Ejecutor'
                    buscar={this.abrirModalNoEncontrado}
                    registrar={this.abrirRegistro}
                    change={this.capturarBusqueda}
                />
                <div className="cont_table_suppliers">
                    <table className="table_suppliers">
                        <caption className="tit_table_suppliers">
                            Ordenes de Salida
                        </caption>
                        <thead className="thead_suppliers">
                            <tr>
                                <th>ID</th>
                                <th>EJECUTOR</th>
                                <th>FECHA</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody className="tbody_suppliers">
                            {this.state.orders.data.map(u =>
                                <Fila
                                    key={u.idOrden}
                                    id={u.idOrden}
                                    ejecutor={u.ejecutor}
                                    fecha={u.fecha}
                                    detallar={() => this.detallar(u.idOrden)}
                                />
                            )}
                        </tbody>
                    </table>
                </div>

                <RegistrarOrdenSalida
                    idd='pop-registrar-os'
                    cerrar={this.cerrarRegistro}
                />
                <DetalleOrdenSalida
                    idd='pop-detalle-oc'
                    numero  = {this.state.detalleOrden.numero}
                    fecha  = {this.state.detalleOrden.fecha}
                    ejecutor  ={this.state.detalleOrden.ejecutor}
                    /*------------------------------------*/
                    volver = {()=>document.getElementById('pop-detalle-oc').close()}

                >
                    {this.state.detalleOrden.productos.map(d => 
                        <FilaDetalle
                            key={d.id}
                            codigo={d.id}
                            cantidad={d.cantidad}
                            producto={d.nombre}
                    />)}

                </DetalleOrdenSalida>
            </>
        );
    }

}


export default OrdenSalida;