import React from "react";
import BarraRegistroFiltro from "../components/BarraRegistroFiltro";
import ContButtonsTab from "../components/ContButtonsTab";
import "../css/Proveedores.css";
import ButtonTab from "../components/ButtonTab";
import Cookies from "universal-cookie";
import PopUpSiNo from "../components/PopUpSiNo";
import axios from "axios";
import RegistrarProducto from "../components/RegistrarProducto";

const cookies = new Cookies();

const config = {
    headers: {
        Authorization: `Bearer ${cookies.get("token")}`,
    },
};

const infoUser = `?id_usuario=${parseInt(cookies.get("id"))}&tipo=${cookies.get(
    "tipo"
)}`;

const Fila = (props) => {
    return (
        <tr>
            <td>{props.id}</td>
            <td>{props.nombre}</td>
            <td>{props.unidad}</td>
            <td>{props.laboratorio}</td>
            <td>
                <ContButtonsTab>
                    <ButtonTab
                        texto="Detallar"
                        back="var(--normal)"
                        border="var(--normal-dark)"
                        click={props.detallar}
                    />
                    <ButtonTab
                        texto="Actualizar"
                        back="var(--warning)"
                        border="var(--warning-dark)"
                        click={props.actualizar}
                    />
                    <ButtonTab
                        texto="Eliminar"
                        back="var(--danger)"
                        border="var(--danger-dark)"
                        click={props.eliminar}
                    />
                </ContButtonsTab>
            </td>
        </tr>
    );
};

let productos = [];

class Productos extends React.Component {

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
                unidad: '',
                laboratorio: '',
                precio: '',
                descripcion: '',
                editable: true
            },
            mensaje: {
                texto: 'Hola :)',
                color: 'var(--normal-dark)',
                titulo: 'Registrar Producto'
            },
            boton: {
                texto: 'Registrar',
                accion: this.registrarProducto,
                pregunta: '¿Desea Registrar el producto?',
                titulo: 'Registrar Producto'
            },
            eliminar: {
                texto: '',
                id: ''
            }
        };

        this.actualizar = this.actualizar.bind(this);
        this.eliminar = this.eliminar.bind(this);
    }

    traerProductos = async () => {
        await axios.get(`http://localhost:8000/api/productos${infoUser}`, config)
            .then(res => {
                productos = res.data;
                this.setState({
                    products: {
                        data: productos
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
        let search = productos.filter(i => {
            if (i.nombre.toLowerCase().includes(this.state.busqueda.toLowerCase())) {
                return i;
            }
        });
        console.log(search);
        this.setState({ products: { data: search } })
    }

    capturarCambios = async (e) => {
        e.persist();
        console.log(e.target.name, e.target.value);
        await this.setState({
            productosData: {
                ...this.state.productosData,
                [e.target.name]: e.target.value
            }
        });
    }

    registrarProducto = (e) => {
        console.log(this.state.productosData.unidad)
        axios.post('http://localhost:8000/api/productos', {
            id_usuario: parseInt(cookies.get("id")),
            tipo: cookies.get("tipo"),
            nombre: this.state.productosData.nombre,
            laboratorio: this.state.productosData.laboratorio,
            precio_venta: this.state.productosData.precio,
            formato: this.state.productosData.unidad,
            descripcion: this.state.productosData.descripcion,
        }, config)
            .then(res => {
                this.setState({
                    mensaje: {
                        titulo: 'Registrar Producto',
                        texto: 'Producto registrado exitosamente',
                        color: 'var(--success)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
                this.traerProductos();
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    mensaje: {
                        titulo: 'Registrar Producto',
                        texto: `Error al registrar producto: ${err.message}`,
                        color: 'var(--danger)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
            })
        document.getElementById('pop-reg-sino').close();
    }

    async detallar(id) {
        console.log(id);
        await axios.get(`http://localhost:8000/api/productos/${id}/${infoUser}`, config)
            .then(res => {
                this.setState({
                    productosData: {
                        id: res.data.id,
                        nombre: res.data.nombre,
                        laboratorio: res.data.laboratorio,
                        precio: res.data.precioVenta,
                        descripcion: res.data.descripcion,
                        unidad: res.data.idUnidad,
                        editable: false
                    },
                    boton: {
                        texto: 'Detallar',
                        pregunta: `Desea Detallar el producto ${res.data.nombre}`,
                        titulo: 'Detalle Producto'
                    }
                })
                console.log(res.data);
                this.abrirRegistro();
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    mensaje: {
                        titulo: 'Cargar Producto',
                        texto: `Error al traer al producto: ${err.message}`,
                        color: 'var(--danger)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
            })
    }

    async actualizar(id) {
        console.log(id);
        await axios.get(`http://localhost:8000/api/productos/${id}/${infoUser}`, config)
            .then(res => {
                this.setState({
                    productosData: {
                        id: res.data.id,
                        nombre: res.data.nombre,
                        laboratorio: res.data.laboratorio,
                        precio: res.data.precioVenta,
                        descripcion: res.data.descripcion,
                        unidad: res.data.idUnidad,
                        editable: true
                    },
                    boton: {
                        texto: 'Actualizar',
                        accion: this.confirmarActualizacion,
                        pregunta: `Desea Actualizar el producto ${res.data.nombre}`,
                        titulo: 'Registrar producto'
                    }
                })
                this.abrirRegistro();
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    mensaje: {
                        titulo: 'Cargar producto',
                        texto: `Error al traer al producto: ${err.message}`,
                        color: 'var(--danger)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
            })
    }

    confirmarActualizacion = async () => {
        console.log(this.state.productosData);
        await axios.put(`http://localhost:8000/api/productos/${this.state.productosData.id}`, {
            id_usuario: parseInt(cookies.get("id")),
            tipo: cookies.get("tipo"),
            nombre: this.state.productosData.nombre,
            laboratorio: this.state.productosData.laboratorio,
            precio_venta: this.state.productosData.precio,
            formato: this.state.productosData.unidad,
            descripcion: this.state.productosData.descripcion,
        }, config)
            .then(res => {
                this.setState({
                    mensaje: {
                        titulo: 'Actualizar Producto',
                        texto: 'Producto actualizado exitosamente',
                        color: 'var(--success)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
                this.traerProductos();
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    mensaje: {
                        titulo: 'Actualizar Producto',
                        texto: `Error al actualizar al Producto: ${err.message}`,
                        color: 'var(--danger)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
            })
        document.getElementById('pop-reg-sino').close();
        document.getElementById('pop-registrar-user').close();
        this.limpiarProducto();
    }

    eliminar = async () => {
        await axios.delete(`http://localhost:8000/api/productos/${this.state.eliminar.id}${infoUser}`, config)
            .then(res => {
                console.log(res);
                this.setState({
                    mensaje: {
                        titulo: 'Eliminar Producto',
                        texto: 'Producto eliminado exitosamente',
                        color: 'var(--success)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
                this.traerProductos();
            })
            .catch(err => {
                console.log(err);
                this.setState({
                    mensaje: {
                        titulo: 'Eliminar Producto',
                        texto: `Error al eliminar Producto: ${err.message}`,
                        color: 'var(--danger)'
                    }
                })
                document.getElementById('pop-reg-conf').showModal();
            })
        document.getElementById('pop-eliminar-sino').close();
    }


    limpiarProducto = () => {
        this.setState({
            productosData: {
                id: '',
                nombre: '',
                unidad: '',
                laboratorio: '',
                precio: '',
                descripcion: '',
                editable: true
            },
            boton: {
                texto: 'Registrar',
                accion: this.registrarProveedor,
                pregunta: '¿Desea Registrar el Producto?',
                titulo: 'Registrar Producto'
            }
        })
    }

    abrirRegistro(e) {
        // e.preventDefault();
        document.getElementById('pop-registrar-user').showModal();
    }

    cerrarRegistro = (e) => {
        e.preventDefault();
        this.limpiarProducto();
        document.getElementById('pop-registrar-user').close();
    }

    ModalEliminar = (id, nombre)=>{
        this.setState({
            eliminar:{
                texto: `¿Eliminar a ${nombre}?`,
                id: parseInt(id)
            }
        })
        document.getElementById('pop-eliminar-sino').showModal();  
    }

    cerrarEliminar(){
        document.getElementById('pop-eliminar-sino').close();  
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
                <h1 className="tit_general">Productos</h1>
                <BarraRegistroFiltro
                    registro={true}
                    txtRegistro='Registrar Producto'
                    place='Nombre del Producto'
                    buscar={this.abrirModalNoEncontrado}
                    registrar={this.abrirRegistro}
                    change={this.capturarBusqueda}
                />
                <div className="cont_table_suppliers">
                    <table className="table_suppliers">
                        <caption className="tit_table_suppliers">
                            Lista de Productos
                        </caption>
                        <thead className="thead_suppliers">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>UNIDAD</th>
                                <th>LABORATORIO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody className="tbody_suppliers">
                            {this.state.products.data.map(u =>
                                <Fila
                                    key={u.id}
                                    id={u.id}
                                    nombre={u.nombre}
                                    unidad={u.unidad}
                                    laboratorio={u.laboratorio}
                                    detallar={() => this.detallar(u.id)}
                                    actualizar={() => this.actualizar(u.id)}
                                    eliminar={() => this.ModalEliminar(u.id, u.nombre)}
                                />
                            )}
                        </tbody>
                    </table>
                </div>

                <RegistrarProducto
                    idd='pop-registrar-user'
                    cerrar={this.cerrarRegistro}
                    editable={this.state.productosData.editable}
                    cambio={this.capturarCambios}
                    /*-------------------------------------------------- */
                    textBtn={this.state.boton.texto}
                    registrar={this.state.boton.accion}
                    /*----------------------------------------------- */
                    mensajeConf={this.state.mensaje.texto}
                    colorConf={this.state.mensaje.color}
                    pregunta={this.state.boton.pregunta}
                    /*--------------------------------------------- */
                    nombre={this.state.productosData.nombre}
                    unidad={this.state.productosData.unidad}
                    laboratorio={this.state.productosData.laboratorio}
                    precio={this.state.productosData.precio}
                    descripcion={this.state.productosData.descripcion}
                    tituloPop={this.state.mensaje.titulo}
                    tituloPri={this.state.boton.titulo}
                />
                <PopUpSiNo
                    idd='pop-eliminar-sino'
                    titulo='Eliminar Usuario'
                    texto={this.state.eliminar.texto}
                    si={this.eliminar}
                    no={this.cerrarEliminar}
                    color= 'var(--warning)'
                />
            </>
        );
    }
}

export default Productos;