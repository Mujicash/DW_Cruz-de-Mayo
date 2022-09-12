import React from "react";
import '../css/ContButtonsTab.css'

const ContButtonsTab = (props)=>{
    return(
        <div className="cont_btn_tab">
           {props.children}
        </div>
    );
}

export default ContButtonsTab;