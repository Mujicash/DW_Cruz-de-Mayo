import React from "react";
import '../css/Login.css'
import logo from '../img/logo_cruz.png';
import PopUpConfirmacion  from '../components/PopUpConfirmacion.jsx';
import axios from "axios";
import Cookies from "universal-cookie";

const cookies = new Cookies();


class Login extends React.Component{

    constructor() {
        super();
    
        this.state = {
            form:{
                usuario:'',
                password:''
            },
            dialog:{
                titulo: 'login',
                texto :'!Bienvenido',
                button : 'Aceptar',
                cerrar : this.cerrarModal,
                color : 'var(--primary)'
            }
        };
    
        this.capturarCambios = this.capturarCambios.bind(this);
    }


    iniciarSesion = async (e)=>{
        e.preventDefault();
        await axios.get('http://localhost:8000/api/login',{
            params:{
                usuario : this.state.form.usuario,
                password: this.state.form.password
            }
        })
        .then(res =>{
            if (res.data) {
                return res.data;
            }else {
                this.setState({
                    dialog:{
                        ...this.state.dialog,
                        texto :'Usuario o Contraseña incorrecto',
                        color: 'var(--danger)'
                    }
                });
                this.abrirModal();
            }
        })
        .then(res=>{
            cookies.set('id',res.id,{path:'/'});
            cookies.set('nombre',res.nombre,{path:'/'});
            cookies.set('tipo',res.tipo,{path:'/'});
            cookies.set('sucursal',res.sucursal,{path:'/'});
            cookies.set('usuario',res.usuario,{path:'/'});
            cookies.set('token',res.apiToken,{path:'/'});
            cookies.set('direccion',res.direccion,{path:'/'});
            console.log(res);
            this.setState({
                dialog:{
                    ...this.state.dialog,
                    texto :'!Bienvenido '+  cookies.get('nombre'),
                    color: 'var(--primary)'
                }
            });
            this.abrirModal();
        })
        .catch(err =>{
            console.log(err);
        })
    }

    componentDidMount(){
        if(cookies.get('id')){
            window.location.href = './con/inicio';
        }
    }

    async capturarCambios(e){
        await this.setState({
            form:{
                ...this.state.form,
                [e.target.name]: e.target.value
            }
        });
        console.log(this.state.form);
    }

    abrirModal(){
        document.getElementById('dia-log').showModal();
    }

    cerrarModal(e){
        e.preventDefault();
       
        document.getElementById('dia-log').close();
        if(cookies.get('id')){
            window.location.href='./con/inicio';
        }

    }

    render(){
        return(
            <>
            <div className="cont_login">
                <div className="login">
                    <div className="titulo_login">
                        <img src={logo} alt="" />
                        <h1>Farmacia <span>Cruz de Mayo</span></h1>
                    </div>
                    <div className="form_login">
                        <div>
                            <label htmlFor="lg_user">Nombre de Usuario</label>
                            <input type="text" placeholder="Usuario" id="lg_user" name="usuario" onChange={this.capturarCambios}/>
                            <label htmlFor="lg_pass">Contraseña</label>
                            <input type="password" id="lg_pass" placeholder="Contraseña" name="password" onChange={this.capturarCambios}/>
                            <button
                                onClick={this.iniciarSesion}
                            >
                                Ingresar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <PopUpConfirmacion
                idd='dia-log'
                titulo={this.state.dialog.titulo}
                texto={this.state.dialog.texto}
                button = {this.state.dialog.button}
                cerrar = {this.state.dialog.cerrar}
                color= {this.state.dialog.color}
            />
            </>
        );
    }

}


export default Login;