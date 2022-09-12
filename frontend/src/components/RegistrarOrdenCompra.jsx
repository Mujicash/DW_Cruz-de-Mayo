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

let provedoresData = [];
let productosData = [];
let detalleData = [];

const Fila = (prop)=>{
    return(
        <tr>
            <td>{prop.idd}</td>
            <td>{prop.producto}</td>
            <td>{prop.cantidad}</td>
            <td>{prop.precio}</td>
            <td>{prop.subtotal}</td>
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

class RegistrarOrdenCompra extends React.Component{
    
    constructor() {
      super();

      this.state={
        filaDetalle:{
            id: '' ,
            nombre:'',
            cantidad: '',
            precio: '',
            subtotal: ''
        },
        productos: [],
        provedores: [],
        detalles: [],
        total : 0,
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

    traerProveedores = async ()=>{
      await axios.get(`http://localhost:8000/api/proveedores${infoUser}`, config)
      .then(res =>{
        provedoresData = res.data;
        this.setState({
            provedores : provedoresData
        });
          
      })
      .catch(err =>{
          console.log(err);
      })
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
                cantidad: parseInt(document.getElementById('orden_c_cant').value),
                precio: parseFloat(document.getElementById('orden_c_costo').value),
                subtotal: parseInt(document.getElementById('orden_c_cant').value) * parseFloat(document.getElementById('orden_c_costo').value)
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
        let suma = 0;
        this.state.detalles.forEach(d => {
            suma = suma + d.subtotal;
        });
        this.setState({
            total : suma
        });
        document.getElementById('orden_c_prod').value = '-1';
        document.getElementById('orden_c_costo').value = '';
        document.getElementById('orden_c_cant').value = '';

    }

    elminarDetalle = async (id) =>{
        let filtro = detalleData.filter(d => d.id != id);
        detalleData = filtro;
        await this.setState({
            detalles : detalleData
        });
        let suma = 0;
        this.state.detalles.forEach(d => {
            suma = suma + d.subtotal;
        });
        this.setState({
            total : suma
        })
    }

    registrarOrden = async () =>{
        console.log('fila detalle: ',this.state.detalles);
        await axios.post('http://localhost:8000/api/ordenesCompra',{
            id_usuario: parseInt(cookies.get("id")),
            tipo: cookies.get("tipo"),
            proveedor : parseInt(document.getElementById('orden_c_prov').value),
            productos : this.state.detalles
        },config)
        .then(res => {
            console.log(res);
            this.setState({
                mensaje:{
                    texto : 'Orden de compra registrado satisfactoriamente',
                    color: 'var(--success)',
                    titulo : 'Registrar Orden de Compra',
                }
            })
            document.getElementById('pop-conf-oc').showModal();
        })
        .catch(err => {
            console.log(err);
        })
       
    }

    componentDidMount(){
        this.traerProveedores();
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
                      <div>Proveedor</div>
                      <select className="input_reg" onChange={this.props.cambio} name="proveedor" id="orden_c_prov">
                        <option value="-1">Seleccione un proveedor</option>
                        {this.state.provedores.map(s=><option key={s.id} value={s.id} data-nombre={s.nombre}>{s.nombre}</option>)}
                      </select>
                    </label>
                    <label htmlFor="">
                      <div>Productos</div>
                      <select className="input_reg" onChange={this.props.cambio} name="producto" id="orden_c_prod">
                        <option value="-1">Seleccione un producto</option>
                        {this.state.productos.map(s=><option key={s.id} value={s.id} data-nombre={s.nombre}>{s.nombre}</option>)}
                      </select>
                    </label>
                  </div>
                  <div className="cont_form_reg">
                    <label htmlFor="">
                      <div>Costo Unitario</div> 
                      <input 
                        type="text" 
                        name="costoUnitario"
                        className="input_reg"
                        id="orden_c_costo"
                        onChange={this.props.cambio}/>
                    </label>
                    <label htmlFor="">
                      <div>Cantidad</div> 
                      <input 
                        type="number" 
                        name="cantidad"
                        className="input_reg"
                        id="orden_c_cant"
                        onChange={this.props.cambio}/>
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
                                <th>costo unitario</th>
                                <th>subtotal</th>
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
                                    precio = {parseFloat(d.precio)}
                                    subtotal = {parseInt(d.cantidad) * parseFloat(d.precio)}
                                    eliminar = {()=>this.elminarDetalle(d.id)} 
                                />
                            )}
                        </tbody>
                    </table>
                </div>
                <div className="cont_total_oc">
                    <div>
                        <div className="oc_total">Total</div>
                        <div className="oc_precio">{`S/. ${this.state.total}`}</div>
                    </div>
                </div>
                <div className="cont_button_reg">
                  <button 
                    className="btn_cancelar_user"
                    onClick={this.props.cerrar}>
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
            cerrar = {this.props.cerrarConf}
            color= {this.state.mensaje.color}
          />
        </>
      );
    }
  
}

export default RegistrarOrdenCompra;