import React from "react";
import PopUpConfirmacion from "./PopUpConfirmacion";
import PopUpSiNo from "./PopUpSiNo";
import '../css/RegistrarProveedor.css';
import Cookies from "universal-cookie";
import axios from "axios";
import { render } from "@testing-library/react";

const cookies = new Cookies();

const config = {
    headers: {
        "Authorization": `Bearer ${cookies.get('token')}`
    }
};

const infoUser = `?id_usuario=${parseInt(cookies.get("id"))}&tipo=${cookies.get("tipo")}`;

class RegistrarProveedor extends React.Component {

    constructor() {
        super();


    }

    abrirModalSino = (e) => {
        e.preventDefault();
        document.getElementById('pop-reg-sino').showModal();
        document.getElementById('pop-reg-conf').close();
    }

    cerrarModal = (e) => {
        e.preventDefault();
        document.getElementById('pop-reg-conf').close();
    }

    cerrarModalSino = (e) => {
        e.preventDefault();
        document.getElementById('pop-reg-sino').close();
    }

    editable(val) {
        if (val) {
            return (
                <button
                    className="btn_registrar_supplier"
                    onClick={this.abrirModalSino}>
                    {this.props.textBtn}
                </button>
            )
        }
    }

    render() {
        return (
            <>
                <dialog className="dia-reg-supplier" id={this.props.idd}>
                    <div className="cont-reg-supplier">
                        <div className="cont-tit-reg-supplier">
                            <h1>{this.props.tituloPri}</h1>
                        </div>
                        <div className="cont-form-reg-supplier">
                            <div className="cont_form_reg_supplier">
                                <label htmlFor="">
                                    <div>RUC</div>
                                    <input
                                        type="text"
                                        name="ruc"
                                        className="input_reg_supplier"
                                        value={this.props.ruc}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                                <label htmlFor="">
                                    <div>Nombre(s)</div>
                                    <input
                                        type="text"
                                        name="nombre"
                                        className="input_reg_supplier"
                                        value={this.props.nombre}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                                <label htmlFor="">
                                    <div>telefono</div>
                                    <input
                                        type="text"
                                        name="telefono"
                                        className="input_reg_supplier"
                                        value={this.props.telefono}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                            </div>
                            <div className="cont_form_reg_supplier">
                                <label htmlFor="">
                                    <div>Direccion</div>
                                    <input
                                        type="text"
                                        name="direccion"
                                        className="input_reg2_supplier"
                                        value={this.props.direccion}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                                <label htmlFor="">
                                    <div>Correo</div>
                                    <input
                                        type="text"
                                        name="correo"
                                        className="input_reg2_supplier"
                                        value={this.props.correo}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                            </div>
                        </div>
                        <div className="cont_button_reg_supplier">
                            <button
                                className="btn_cancelar_supplier"
                                onClick={this.props.cerrar}>
                                Cancelar
                            </button>
                            {this.editable(this.props.editable)}
                        </div>
                    </div>
                </dialog>
                <PopUpConfirmacion
                    idd='pop-reg-conf'
                    titulo='Registrar Proveedor'
                    texto={this.props.mensajeConf}
                    button='Aceptar'
                    cerrar={this.cerrarModal}
                    color={this.props.colorConf}
                />
                <PopUpSiNo
                    idd='pop-reg-sino'
                    titulo='Registrar Proveedor'
                    texto={this.props.pregunta}
                    si={this.props.registrar}
                    no={this.cerrarModalSino}
                    color='var(--success)'
                />
            </>
        );
    }

}


export default RegistrarProveedor; 