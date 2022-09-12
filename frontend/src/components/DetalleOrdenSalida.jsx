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

class DetalleOrdenCompra extends React.Component{
    
    constructor() {
      super();

    }

    render(){
      return(
        <>
            <dialog className="dia-reg-user dia-reg-oc" id={this.props.idd}>
              <div  className="cont-reg-user">
                <div className="cont-tit-reg-user">
                  <h1>Detallar orden de salida</h1>
                </div>
                <div className="cont-form-reg-user">
                  <div className="cont_form_reg">
                    <label htmlFor="">
                      <div>N° de orden</div>
                      <input 
                        type="text" 
                        value={this.props.numero}
                        className="input_reg"
                        id="orden_c_costo"
                        disabled
                       />
                    </label>
                    <label htmlFor="">
                      <div>Ejecutor</div>
                      <input 
                        type="text" 
                        value={this.props.ejecutor}
                        className="input_reg"
                        id="orden_c_costo"
                        disabled
                        />
                    </label>
                    <label htmlFor="">
                      <div>Fecha</div>
                      <input 
                        type="text" 
                        value={this.props.fecha}
                        className="input_reg"
                        id="orden_c_costo"
                        disabled
                        />
                    </label>
                  </div>
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
                            </tr>
                        </thead>
                        <tbody className="tbody_oc">
                            {this.props.children}
                        </tbody>
                    </table>
                </div>
                <div className="cont_button_reg">
                  <button 
                    className="btn_cancelar_user"
                    onClick={this.props.volver}>
                    Volver
                  </button>
                </div>
            </div>
          </dialog>
        </>
      );
    }
  
}

export default DetalleOrdenCompra;