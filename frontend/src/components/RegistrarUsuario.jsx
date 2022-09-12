import React from "react";
import PopUpConfirmacion from "./PopUpConfirmacion";
import PopUpSiNo from "./PopUpSiNo";
import '../css/RegistrarUsuario.css';
import Cookies from "universal-cookie";
import axios from "axios";

const cookies = new Cookies();

const config = {
    headers:{
        "Authorization": `Bearer ${cookies.get('token')}` 
    }
};

const infoUser = `?id_usuario=${parseInt(cookies.get("id"))}&tipo=${cookies.get("tipo")}`;

let sucursales = [];

class RegistrarUsuario extends React.Component{
    
    constructor() {
      super();

      this.state={
        sucursales : []
      }

    }

    abrirModalSino = (e)=>{
      e.preventDefault();
      document.getElementById('pop-reg-sino').showModal();
      document.getElementById('pop-reg-conf').close();
    }

    cerrarModal = (e)=>{
      e.preventDefault();
      document.getElementById('pop-reg-conf').close();
    }

    cerrarModalSino = (e)=>{
      e.preventDefault();
      document.getElementById('pop-reg-sino').close();
    }

    traerSucursales = async ()=>{
      await axios.get(`http://localhost:8000/api/sucursales${infoUser}`, config)
      .then(res =>{
          this.setState({
            sucursales : res.data
          });
          sucursales = res.data;
          
      })
      .catch(err =>{
          console.log(err);
      })
    }

    componentDidMount(){
      this.traerSucursales();
    }
    
    render(){
      return(
        <>
            <dialog className="dia-reg-user" id={this.props.idd}>
              <div  className="cont-reg-user">
                <div className="cont-tit-reg-user">

                  <h1>{this.props.titPrincipal}</h1>

                </div>
                <div className="cont-form-reg-user">
                  <div className="cont_form_reg">
                    <label htmlFor="">
                      <div>Nombre(s)</div>
                      <input 
                        type="text" 
                        name="nombre"
                        className="input_reg"
                        value={this.props.nombre}
                        onChange={this.props.cambio}/>
                    </label>
                    <label htmlFor="">
                      <div>Apellido Paterno</div>
                      <input 
                        type="text" 
                        name="apellidoPaterno"
                        className="input_reg"
                        value={this.props.apellidoPaterno}
                        onChange={this.props.cambio}/>
                    </label>
                    <label htmlFor="">
                      <div>Apellido Materno</div>
                      <input 
                        type="text" 
                        name="apellidoMaterno"
                        className="input_reg"
                        value={this.props.apellidoMaterno}
                        onChange={this.props.cambio}/>
                    </label>
                  </div>
                  <div className="cont_form_reg">
                    <label htmlFor="">
                      <div>Nombre de usuario</div> 
                      <input 
                        type="text" 
                        name="usuario"
                        className="input_reg"
                        value={this.props.usuario}
                        onChange={this.props.cambio}/>
                    </label>
                    <label htmlFor="">
                      <div>Tipo</div> 
                      <select className="input_reg" onChange={this.props.cambio} name="tipo_usuario" id="tipo_user">
                      <option value="-1">Seleccione el tipo de usuario</option>
                        <option value="Administrador" selected={this.props.tipo === 1 ? true : false}>Administrador</option>
                        <option value="Jefe Almacen" selected={this.props.tipo === 2 ? true : false}>Jefe de almacen</option>
                        <option value="Encargado Botica" selected={this.props.tipo === 3 ? true : false}>Encargado de botica</option>
                      </select>
                    </label>
                  </div>
                  <div className="cont_form_reg">
                    <label htmlFor="">
                      <div>Contraseña</div> 
                      <input type="password" className="input_reg" disabled={this.props.update} onChange={this.props.cambio} name="password" />
                              
                    </label>
                    <label htmlFor="">
                      <div>Sucursal</div>
                      <select className="input_reg" onChange={this.props.cambio} name="sucursal" id="sucursal_user" >
                        <option value="-1">Seleccione la sucursal</option>
                        {this.state.sucursales.map(s=><option key={s.id} value={s.nombre} selected={this.props.sucursal === s.id ? true : false}>{s.nombre}</option>)}
                      </select>
                    </label>
                  </div>
                </div>
                <div className="cont_button_reg">
                  <button 
                    className="btn_cancelar_user"
                    onClick={this.props.cerrar}>
                    Cancaelar
                  </button>
                  <button 
                    className="btn_registrar_user"
                    onClick={this.abrirModalSino}>
                    {this.props.textBtn}
                  </button>
                 </div>
              
            </div>
          </dialog>
          <PopUpConfirmacion
            idd='pop-reg-conf'
            titulo={this.props.tituloPop}
            texto={this.props.mensajeConf}
            button = 'Aceptar'
            cerrar = {this.cerrarModal}
            color= {this.props.colorConf}
          />
          <PopUpSiNo
            idd='pop-reg-sino'
            titulo={this.props.tituloPop}
            texto={this.props.pregunta}
            si={this.props.registrar}
            no={this.cerrarModalSino}
            color= 'var(--success)'
          />
        </>
      );
    }
  
}

export default RegistrarUsuario;