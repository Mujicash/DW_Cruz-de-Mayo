import React from "react";
import BarraRegistroFiltro  from "../components/BarraRegistroFiltro";
import '../css/Usuarios.css';
import ContButtonsTab from "../components/ContButtonsTab";
import ButtonTab from "../components/ButtonTab";
import Cookies from "universal-cookie";
import RegistrarUsuario from "../components/RegistrarUsuario";
import axios from "axios";
import PopUpSiNo from "../components/PopUpSiNo";

const cookies = new Cookies();

const config = {
    headers:{
        "Authorization": `Bearer ${cookies.get('token')}` 
    }
};

const infoUser = `?id_usuario=${parseInt(cookies.get("id"))}&tipo=${cookies.get("tipo")}`;


const Fila =(props)=>{
    return(
        <tr>
        <td>{props.idd}</td>
        <td>{props.nombre}</td>
        <td>{props.usuario}</td>
        <td>{props.tipo}</td>
        <td>{props.sucursal}</td>
        <td>
            <ContButtonsTab>
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
        </td>

        </tr>
    );
}

let usuarios = [];

class Usuario extends React.Component{

    constructor() {
        super();
    
        this.state = {
            dialog:{
                titulo: 'login',
                texto :'!Bienvenido',
                button : 'Aceptar',
                cerrar : this.cerrarModal,
                color : 'var(--primary)'
            },
            users : {
                data : []
            },
            busqueda : '',
            usuarioData:{
                id: '',
                usuario: '',
                nombre: '',
                apellidoPaterno: '',
                apellidoMaterno: '',
                password: '',
                tipo_usuario: '',
                sucursal: '',
                update : false
            },
            mensaje:{
                texto : 'Hola :)',
                color: 'var(--normal-dark)',
                titulo : 'Registrar Usuario'
            },
            boton:{
                texto:'Registrar',
                accion : this.registrarUsuario,
                pregunta: '¿Desea Registrar el Usuario?',
                titulo: 'Registrar Usuario'
            },
            eliminar:{
                texto: '',
                id: ''
            }
        };

        this.actualizar = this.actualizar.bind(this);
        this.eliminar = this.eliminar.bind(this);
    }

    traerUsuarios = async ()=>{
    //    console.log('trayendo usuarios ....');
        
        await axios.get(`http://localhost:8000/api/usuarios${infoUser}`, config)
        .then(res =>{
            // console.log(res.data);
            usuarios = res.data;
            this.setState({
                users:{
                    data: usuarios
                }
            });
        })
        .catch(err =>{
            console.log(err);
        })
    }

    capturarBusqueda = async(e)=>{
        e.persist();
        await this.setState({busqueda: e.target.value});
        // console.log(this.state.busqueda);
        this.filtraBusqueda();
    }

    filtraBusqueda = ()=>{
        let search = usuarios.filter(i => {
            if(i.nombre.toLowerCase().includes(this.state.busqueda.toLowerCase()) || 
            i.usuario.toLowerCase().includes(this.state.busqueda.toLowerCase())){
                return i;
            }
        });
        console.log(search);
        this.setState({users:{data : search}})
    }

    capturarCambios = async (e)=>{
        e.persist();
        console.log(e.target.name, e.target.value);
        await this.setState({
            usuarioData:{
                ...this.state.usuarioData,
                [e.target.name] : e.target.value
            }
        });
        console.log(this.state.usuarioData);
    }

    registrarUsuario = (e)=>{
        axios.post('http://localhost:8000/api/usuarios',{
            id_usuario: parseInt(cookies.get("id")),
            tipo: cookies.get("tipo"),
            usuario: this.state.usuarioData.usuario,
            nombre:  this.state.usuarioData.nombre,
            apellidoPaterno:  this.state.usuarioData.apellidoPaterno,
            apellidoMaterno:  this.state.usuarioData.apellidoMaterno,
            password:  this.state.usuarioData.password,
            tipo_usuario:  this.state.usuarioData.tipo_usuario,
            sucursal:  this.state.usuarioData.sucursal
        },config)
        .then(res =>{
            this.setState({
                mensaje:{
                    titulo : 'Registrar Usuario',
                    texto : 'Usuario registrado exitosamente',
                    color : 'var(--success)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
            this.traerUsuarios();
        })
        .catch(err=>{
            console.log(err);
            this.setState({
                mensaje:{
                    titulo : 'Registrar Usuario',
                    texto : `Error al registrar usuario: ${err.message}`,
                    color : 'var(--danger)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
        })
        document.getElementById('pop-reg-sino').close();
    }

    async actualizar(id){
        await axios.get(`http://localhost:8000/api/usuarios/${id}${infoUser}`,config)
        .then(res=>{
            this.setState({
                usuarioData:{
                    id: res.data.id,
                    usuario: res.data.usuario,
                    nombre: res.data.nombre,
                    apellidoPaterno: res.data.apellidoPaterno,
                    apellidoMaterno: res.data.apellidoMaterno,
                    password: '',
                    tipo_usuario: res.data.tipo,
                    sucursal: res.data.sucursal,
                    update : true
                },
                boton:{
                    texto:'Actualizar',
                    titulo: 'Actualizar Usuario',
                    accion : this.confirmarActualizacion,
                    pregunta: `Desea Actualizar el usuario ${res.data.nombre}`,
                }
            })
            this.abrirRegistro();
        })
        .catch(err =>{
            console.log(err);
            this.setState({
                mensaje:{
                    titulo: 'Cargar Usuario',
                    texto : `Error al traer al usuario: ${err.message}`,
                    color : 'var(--danger)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
        })
       
    }

     confirmarActualizacion = async()=>{
        console.log(this.state.usuarioData);
        console.log('tipo: ',document.getElementById('tipo_user').value);
        await axios.put(`http://localhost:8000/api/usuarios/${this.state.usuarioData.id}`,{
            id_usuario: parseInt(cookies.get("id")),
            tipo: cookies.get("tipo"),
            usuario: this.state.usuarioData.usuario,
            nombre:  this.state.usuarioData.nombre,
            apellidoPaterno:  this.state.usuarioData.apellidoPaterno,
            apellidoMaterno:  this.state.usuarioData.apellidoMaterno,
            password:  this.state.usuarioData.password,
            tipo_usuario: document.getElementById('tipo_user').value,
            sucursal: document.getElementById('sucursal_user').value
        },config)
        .then(res=>{
            this.setState({
                mensaje:{
                    titulo : 'Actualizar Usuario',
                    texto : 'Usuario actualizado exitosamente',
                    color : 'var(--success)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
            this.traerUsuarios();
        })
        .catch(err =>{
            console.log(err);
            this.setState({
                mensaje:{
                    titulo : 'Actualizar Usuario',
                    texto : `Error al actualizar al usuario: ${err.message}`,
                    color : 'var(--danger)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
        })
        document.getElementById('pop-reg-sino').close();
        document.getElementById('pop-registrar-user').close();  
        this.limpiarUsuario();
    }

    eliminar = async()=>{
        // console.log(`eliminando usuario ${this.state.eliminar.id}`);
        await axios.delete(`http://localhost:8000/api/usuarios/${this.state.eliminar.id}${infoUser}`,config)
          .then(res =>{
            console.log(res);
            this.setState({
                mensaje:{
                    titulo : 'Eliminar Usuario',
                    texto : 'Usuario eliminado exitosamente',
                    color : 'var(--success)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
            this.traerUsuarios();
          })
          .catch(err =>{
            console.log(err);
            this.setState({
                mensaje:{
                    titulo : 'Eliminar Usuario',
                    texto : `Error al eliminar usuario: ${err.message}`,
                    color : 'var(--danger)'
                }
            })
            document.getElementById('pop-reg-conf').showModal();
          })
          document.getElementById('pop-eliminar-sino').close();  
    }

    limpiarUsuario = ()=>{
        this.setState({
            usuarioData:{
                id: '',
                usuario: '',
                nombre: '',
                apellidoPaterno: '',
                apellidoMaterno: '',
                password: '',
                tipo_usuario: '',
                sucursal: '',
                update : false
            },
            boton:{
                texto:'Registrar',
                titulo: 'Registrar Usuario',
                accion : this.registrarUsuario,
                pregunta: '¿Desea Registrar el Usuario?'
            }
        })
    }

    abrirRegistro(e){
        // e.preventDefault();
        document.getElementById('pop-registrar-user').showModal();  
    }

    cerrarRegistro = (e)=>{
        e.preventDefault();
        this.limpiarUsuario();
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

    componentDidMount(){
        if(!cookies.get('id')){
            window.location.href = '/';
        }
     
        this.traerUsuarios();
    }
    render(){
        return(
            <>
                <h1 className="tit_general">Usuarios</h1>
                <BarraRegistroFiltro 
                    registro={true}
                    txtRegistro='Registrar Usuario'
                    place='Buscar...'
                    buscar={this.abrirModalNoEncontrado}
                    registrar={this.abrirRegistro}
                    change = {this.capturarBusqueda}
                />
                <div className="cont_table_user">
                <table className="table_user">
                        <caption className="tit_table_user">
                            Lista de Usuarios
                        </caption>

                        <thead className="thead_user">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Sucursal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody className="tbody_user">
                            {this.state.users.data.map(u =>
                                <Fila 
                                    key={u.id}
                                    idd={u.id}
                                    nombre = {u.nombre}
                                    usuario = {u.usuario}
                                    tipo = {u.tipo}
                                    sucursal = {u.sucursal}
                                    actualizar = {()=>this.actualizar(u.id)}
                                    eliminar = {()=>this.ModalEliminar(u.id,u.nombre)} 
                                />
                            )}
                        </tbody>
                    </table>
                </div>
               
                <RegistrarUsuario
                    idd='pop-registrar-user'
                    cerrar={this.cerrarRegistro}
                    update = {this.state.usuarioData.update}
                    cambio = {this.capturarCambios}
                    /*-------------------------------------------------- */
                    textBtn = {this.state.boton.texto}
                    registrar = {this.state.boton.accion}
                    /*----------------------------------------------- */
                    mensajeConf = {this.state.mensaje.texto}
                    colorConf = {this.state.mensaje.color}
                    pregunta = {this.state.boton.pregunta}
                    /*--------------------------------------------- */
                    nombre = {this.state.usuarioData.nombre}
                    apellidoPaterno = {this.state.usuarioData.apellidoPaterno}
                    apellidoMaterno = {this.state.usuarioData.apellidoMaterno}
                    usuario = {this.state.usuarioData.usuario}
                    tipo = {this.state.usuarioData.tipo_usuario}
                    sucursal = {this.state.usuarioData.sucursal}
                    tituloPop = {this.state.mensaje.titulo}

                    titPrincipal = {this.state.boton.titulo}

                                
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


export default Usuario;