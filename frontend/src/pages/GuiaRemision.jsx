import React from "react";
import Cookies from "universal-cookie";
import '../css/css-pages/GuiaRemision.css'

const cookies = new Cookies();

function GuiaRemision() {

    function componentDidMount() {
        // if(!cookies.get('id')){
        //     window.location.href = '/';
        // }
    }



    return (
        <>
            <h1 className="tit_general">Guía de Remisión</h1>


            <div className="cabecera-cont">
                <div className="input-group">
                    <input className="input-numero" type="text" placeholder="Número de ornden de compra" />
                    <div>
                        <button className="a-container">Buscar</button>
                    </div>
                </div>

                <div className="cont-main">
                    <div className="cont-datos-proveedor">
                        <span>
                            <label>Nombre de Proveedor</label>
                            <input type="text" />
                        </span>
                        <span>
                            <label>Direccion de Proveedor</label>
                            <input type="text" />
                        </span>
                    </div>
                    <hr />
                    <div className="cont-second">
                        <div className="cont-datos-guia">
                            <div>
                                <label>N° de Guía de Remisión</label>
                                <input type="text" />
                            </div>
                            <div>
                                <label >
                                    Fecha de translado
                                </label>
                            </div>
                            <div className="cont-inputs">
                                <input type="text" />
                                <input type="text" />
                                <input type="text" />
                            </div>
                            <div>
                                <label>Motivo de traslado</label>
                                <textarea></textarea>
                            </div>
                        </div>

                        <div className="cont-adjunto">
                            <button>Adjuntar Guía de Remision</button>
                            <img alt='Imagen guia de remision'></img>
                            <input type="file" />
                        </div>
                    </div>
                </div>
            </div>
        </>
    );


}

export default GuiaRemision;