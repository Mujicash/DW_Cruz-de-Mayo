import React from "react";
import PopUpConfirmacion from "./PopUpConfirmacion";
import PopUpSiNo from "./PopUpSiNo";
import '../css/RegistrarUsuario.css';
import '../css/RegistrarOrdenCompra.css'
import Cookies from "universal-cookie";
import ContButtonsTab from "./ContButtonsTab";
import ButtonTab from "./ButtonTab";
import axios from "axios";


const cookies = new Cookies();

const config = {
    headers:{
        "Authorization": `Bearer ${cookies.get('token')}` 
    }
};

const infoUser = `?id_usuario=${parseInt(cookies.get("id"))}&tipo=${cookies.get("tipo")}`;

let productosData = [];
let detalleData = [];

const Fila = (prop)=>{
    return(
        <tr>
            <td>{prop.idd}</td>
            <td>{prop.producto}</td>
            <td>{prop.cantidad}</td>
            <td>
                <ContButtonsTab>
                    <ButtonTab
                        texto='Eliminar'
                        back='var(--danger)'
                        border='var(--danger-dark)'
                        click={prop.eliminar}
                    />
                </ContButtonsTab>
            </td>
        </tr>
    );
}

class RegistrarOrdenSalida extends React.Component{
    
    constructor() {
      super();

      this.state={
        filaDetalle:{
            id: '' ,
            nombre:'',
            cantidad: ''
        },
        productos: [],
        detalles: [],
        mensaje:{
            texto : 'Hola :)',
            color: 'var(--normal-dark)',
            titulo : 'Registrar Usuario',
        }
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




    traerProductos = async ()=>{
        await axios.get(`http://localhost:8000/api/productos${infoUser}`, config)
        .then(res =>{
            productosData = res.data;
            this.setState({
                productos : productosData
            });
            
        })
        .catch(err =>{
            console.log(err);
        })
      }

    capturarCambios = async ()=>{
        await this.setState({
            filaDetalle:{
                id:  parseInt(document.getElementById('orden_c_prod').value) ,
                nombre: document.getElementById('orden_c_prod').selectedOptions[0].dataset.nombre,
                cantidad: parseInt(document.getElementById('orden_c_cant').value)
            }
        });
        detalleData.push(this.state.filaDetalle);
        console.log(this.state.filaDetalle);
    }

    agregarDetalle = async () => {
        await this.capturarCambios();
        await this.setState({
            detalles : detalleData
        });
        document.getElementById('orden_c_prod').value = '-1';
        document.getElementById('orden_c_cant').value = '';
    }

    elminarDetalle = async (id) =>{
        let filtro = detalleData.filter(d => d.id != id);
        detalleData = filtro;
        await this.setState({
            detalles : detalleData
        });
    }

    registrarOrden = async () =>{
        console.log('fila detalle: ',this.state.detalles);
        await axios.post('http://localhost:8000/api/ordenesSalida',{
            id_usuario: parseInt(cookies.get("id")),
            tipo: cookies.get("tipo"),
            productos : this.state.detalles
        },config)
        .then(res => {
            console.log(res);
            this.setState({
                mensaje:{
                    texto : 'Orden de salida registrado satisfactoriamente',
                    color: 'var(--success)',
                    titulo : 'Registrar Orden de Compra',
                }
            })
            document.getElementById('pop-conf-oc').showModal();
            this.setState({
                detalles : []
            });
        })
        .catch(err => {
            console.log(err);
        })
       
    }

    componentDidMount(){
        this.traerProductos();
    }
    
    render(){
      return(
        <>
            <dialog className="dia-reg-user dia-reg-oc" id={this.props.idd}>
              <div  className="cont-reg-user">
                <div className="cont-tit-reg-user">
                  <h1>Registrar orden de compra</h1>
                </div>
                <div className="cont-form-reg-user">
                  <div className="cont_form_reg">
                    <label htmlFor="">
                      <div>Productos</div>
                      <select className="input_reg" name="producto" id="orden_c_prod">
                        <option value="-1">Seleccione un producto</option>
                        {this.state.productos.map(s=><option key={s.id} value={s.id} data-nombre={s.nombre}>{s.nombre}</option>)}
                      </select>
                    </label>
                    
                    <label htmlFor="">
                      <div>Cantidad</div> 
                      <input 
                        type="number" 
                        name="cantidad"
                        className="input_reg"
                        id="orden_c_cant"/>
                    </label>
                  </div>
                </div>
                <div className="cont_button_reg">
                  <button 
                    className="btn_cancelar_user"
                    onClick={this.agregarDetalle}>
                    Agregar
                  </button>
                 </div>
                <div className="cont_table_orden">
                <table className="table_oc">
                        <caption className="tit_table_oc">
                            Lista de compra de productos
                        </caption>

                        <thead className="thead_oc">
                            <tr>
                                <th>codigo</th>
                                <th>producto</th>
                                <th>cantidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody className="tbody_oc">
                            {this.state.detalles.map(d =>
                                <Fila 
                                    key={d.id}
                                    idd={d.id}
                                    producto = {d.nombre}
                                    cantidad = {parseInt(d.cantidad)}
                                    eliminar = {()=>this.elminarDetalle(d.id)} 
                                />
                            )}
                        </tbody>
                    </table>
                </div>
                <div className="cont_button_reg">
                  <button 
                    className="btn_cancelar_user"
                    onClick={ this.props.cerrar}>
                    Cancelar
                  </button>
                  <button 
                    className="btn_registrar_user"
                    onClick={this.registrarOrden}>
                    Registrar
                  </button>
                 </div>
            </div>
          </dialog>
          <PopUpConfirmacion
            idd='pop-conf-oc'
            titulo={this.state.mensaje.titulo}
            texto={this.state.mensaje.texto}
            button = 'Aceptar'
            cerrar = {()=>document.getElementById('pop-conf-oc').close()}
            color= {this.state.mensaje.color}
          />
        </>
      );
    }
  
}

export default RegistrarOrdenSalida;