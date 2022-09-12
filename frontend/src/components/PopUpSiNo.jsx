import React from "react";
import '../css/PopUpConfirmacion.css'

const PopUpSiNo = (prop)=>{

    // const cerrar = ()=>{
    //     document.getElementById(prop.id).close();
    // }

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
                        className="btn_pop_no"
                        onClick={prop.no}
                    >
                       No
                    </button>
                    <button 
                        className="btn_pop_si"
                        onClick={prop.si}
                        style={{backgroundColor: prop.color}}
                    >
                       Si
                    </button>
                </div>
            </div>   
        </dialog>
    );
}

export default PopUpSiNo;