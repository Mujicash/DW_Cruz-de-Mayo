import React from "react";
import '../css/ContButtonsTab.css'

const ButtonTab = (props)=>{
    return(
        <button
            className="btn_tab"
            style={{
                backgroundColor: props.back,
                border: `1px solid ${props.border}`
            }}
            onClick={props.click}
        >
            {props.texto}
        </button>
    );
}

export default ButtonTab;