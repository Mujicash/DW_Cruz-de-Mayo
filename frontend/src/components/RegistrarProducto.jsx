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

let tipos = [];

class RegistrarProducto extends React.Component {

    constructor() {
        super();

        this.state = {
            tipos: []
        }
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

    traerTipos = async () => {
        await axios.get(`http://localhost:8000/api/formatos${infoUser}`, config)
            .then(res => {
                this.setState({
                    tipos: res.data
                });
                tipos = res.data;
            })
            .catch(err => {
                console.log(err);
            })
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

    componentDidMount() {
        this.traerTipos();
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
                                    <div>Nombre</div>
                                    <input
                                        type="text"
                                        name="nombre"
                                        className="input_reg_supplier"
                                        value={this.props.nombre}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                                <label htmlFor="">
                                    <div>Precio</div>
                                    <input
                                        type="number"
                                        name="precio"
                                        className="input_reg_supplier"
                                        value={this.props.precio}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                                <label htmlFor="">
                                    <div>Formato</div>
                                    <select className="input_reg" onChange={this.props.cambio} name="unidad" id="unidad" disabled={!this.props.editable}>
                                        <option value="-1">Seleccione el Formato</option>
                                        {this.state.tipos.map(s => <option key={s.id} value={s.nombre} selected={this.props.unidad === s.id}>{s.nombre}</option>)}
                                    </select>
                                </label>
                            </div>
                            <div className="cont_form_reg_supplier">
                                <label htmlFor="">
                                    <div>Laboratorio</div>
                                    <input
                                        type="text"
                                        name="laboratorio"
                                        className="input_reg2_supplier"
                                        value={this.props.laboratorio}
                                        disabled={!this.props.editable}
                                        onChange={this.props.cambio} />
                                </label>
                                <label htmlFor="">
                                    <div>Descripcion</div>
                                    <input
                                        type="textarea"
                                        name="descripcion"
                                        className="input_reg2_supplier"
                                        value={this.props.descripcion}
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
                    titulo='Registrar Producto'
                    texto={this.props.mensajeConf}
                    button='Aceptar'
                    cerrar={this.cerrarModal}
                    color={this.props.colorConf}
                />
                <PopUpSiNo
                    idd='pop-reg-sino'
                    titulo='Registrar Producto'
                    texto={this.props.pregunta}
                    si={this.props.registrar}
                    no={this.cerrarModalSino}
                    color='var(--success)'
                />
            </>
        );
    }

}

export default RegistrarProducto;