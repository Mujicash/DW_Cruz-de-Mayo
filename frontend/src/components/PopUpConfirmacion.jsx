import React from "react";
import '../css/PopUpConfirmacion.css'

const PopUpConfirmacion = (prop)=>{

    return(
        <dialog id={prop.idd} className='dialog'>
             <div className="cont_dialog">
                <h1 
                    className="tit_dialog"
                    style={{color: prop.color}}
                >{prop.titulo}</h1>
                <div className="text_dialog">
                    <p>
                        {prop.texto}
                    </p>
                </div>    
                <div className="cont_button">
                    <button 
                        id="cerrar_modal"
                        className="btn_pop_aceptar"
                        onClick={prop.cerrar}
                        style={{backgroundColor: prop.color}}
                    >
                        {prop.button}
                    </button>
                </div>
            </div>   
        </dialog>
    );
}

export default PopUpConfirmacion;