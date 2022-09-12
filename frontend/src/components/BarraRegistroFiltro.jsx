import React from "react";
import '../css/BarraRegistroFiltro.css'

const BarraRegistroFiltro = (prop) => {

    if (prop.registro) {
        return (
            <div className="cont_brf">
                <button className="btn_registrar" onClick={prop.registrar}>
                    {prop.txtRegistro}
                </button>

                <div className="cont_input_brf">
                    <input type="text" placeholder={prop.place} className="inp_brf" onChange={prop.change} autoComplete="off" />
                    {/* <button className="btn_brf_buscar" onClick={prop.buscar}>Buscar</button>
                    <button className="btn_brf_refrescar">Refrescar</button> */}
                </div>

            </div>
        );
    } else {
        return (
            <div className="cont_brf cont_brf_fend">
                <div className="cont_input_brf">

                    <input type="text" placeholder={prop.place} className="inp_brf" onChange={prop.change} autoComplete="off"/>
                    {/* <button className="btn_brf_buscar" onClick={prop.buscar}>Buscar</button>
                    <button className="btn_brf_refrescar">Refrescar</button> */}

                </div>

            </div>
        );
    }


}

export default BarraRegistroFiltro;