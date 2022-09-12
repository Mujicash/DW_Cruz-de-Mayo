import React from "react";
import logo from '../img/logo_cruz.png'
import '../css/Navbar.css'

const Navbar = ()=>{
    return(
        <div className="cont_logo_bar">
            <img
                className="logo_navBar" 
                src={logo} 
                alt="logo cruz de mayo" />
            <h2>Farmacia</h2>
            <h3>Cruz de Mayo</h3>
        </div>
    );
}

export default Navbar;