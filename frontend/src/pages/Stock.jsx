import React from "react";
import BarraRegistroFiltro from "../components/BarraRegistroFiltro";
import '../css/Proveedores.css';
import Cookies from "universal-cookie";
import axios from "axios";


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
            <td>{props.nombre}</td>
            <td>{props.formato}</td>
            <td>{props.cantidad}</td>
            {/* <td>
                <ContButtonsTab>
                    <ButtonTab
                        texto='Detallar'
                        back='var(--normal)'
                        border='var(--normal-dark)'
                        click={props.detallar}
                    />
                    <ButtonTab
                        texto='Actualizar'
                        back='var(--warning)'
                        border='var(--warning-dark)'
                        click={props.actualizar}
                    />
                    <ButtonTab
                        texto='Eliminar'
                        back='var(--danger)'
                        border='var(--danger-dark)'
                        click={props.eliminar}
                    />
                </ContButtonsTab>
            </td> */}

        </tr>
    );
}

let productos = [];


class Stock extends React.Component {

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
            products: {
                data: []
            },
            busqueda: '',
            productosData: {
                id: '',
                nombre: '',
                formato: '',
                cantidad: '',
                editable: true
            },
            mensaje: {
                texto: 'Hola :)',
                color: 'var(--normal-dark)',
                titulo: 'Registrar Proveedor'
            },
            boton: {
                texto: 'Registrar',
                accion: this.registrarProveedor,
                pregunta: '¿Desea Registrar el proveedor?',
                titulo: 'Registrar Proveedor'
            },
            eliminar: {
                texto: '',
                id: ''
            }
        };
    }

    traerProductos = async () => {
        await axios.get(`http://localhost:8000/api/stock${infoUser}`, config)
            .then(res => {
                productos = res.data;
                console.log(productos);
                this.setState({
                    products: {
                        data: productos
                    }
                })
                console.log(this.state.products);
            })
            .catch(err => {
                console.log(err);
            })
    }

    capturarBusqueda = async (e) => {
        e.persist();
        console.log(e.target.value)
        await this.setState({ busqueda: e.target.value });
        // console.log(this.state.busqueda);
        this.filtraBusqueda();
    }

    filtraBusqueda = () => {
        let search = productos.filter(i => {
            if (i.nombre.toLowerCase().includes(this.state.busqueda.toLowerCase())) {
                return i;
            }
        });
        console.log(search);
        this.setState({ products: { data: search } })
    }


    componentDidMount() {
        if (!cookies.get('id')) {
            window.location.href = '/';
        }

        this.traerProductos();
    }

    render() {
        return (
            <>
                <h1 className="tit_general">Stock</h1>
                <BarraRegistroFiltro
                    registro={false}
                    txtRegistro=''
                    place='Producto'
                    buscar={this.abrirModalNoEncontrado}
                    change={this.capturarBusqueda}
                />
                <div className="cont_table_suppliers">
                    <table className="table_suppliers">
                        <caption className="tit_table_suppliers">
                            Lista de Stock
                        </caption>
                        <thead className="thead_suppliers">
                            <tr>
                                <th>ID</th>
                                <th>PRODUCTO</th>
                                <th>FORMATO</th>
                                <th>STOCK</th>
                            </tr>
                        </thead>
                        <tbody className="tbody_suppliers">
                            {this.state.products.data.map(u =>
                                <Fila
                                    key={u.id}
                                    id={u.id}
                                    nombre={u.nombre}
                                    formato={u.formato}
                                    cantidad={u.cantidad}
                                />
                            )}
                        </tbody>
                    </table>
                </div>
            </>
        );
    }

}


export default Stock;