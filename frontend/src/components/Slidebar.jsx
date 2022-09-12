import React from "react";
import { NavLink, Link } from "react-router-dom";
import Cookies from "universal-cookie";


const cookies = new Cookies();
const tipo = cookies.get("tipo")


const menuTipo = () => {

    if (tipo === "Administrador") {
        return (
            <ul className="list_link_navbar">
                <li className="li_navbar">
                    <NavLink to='/con/inicio'>Inicio</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/usuarios'>Usuarios</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/proveedores'>Proveedores</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/productos'>Productos</NavLink>
                </li>
            </ul>
        );
    }

    if (tipo === "Jefe Almacen") {
        return (
            <ul className="list_link_navbar">
                <li className="li_navbar">
                    <NavLink to='/con/inicio'>Inicio</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/proveedores'>Proveedores</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/stock'>Stock</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/orden_compra'>Ordenes de Compra</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/guia_remision'>Guia de Remisión</NavLink>
                </li>
            </ul>
        );
    }

    if (tipo === "Encargado Botica") {
        return (
            <ul className="list_link_navbar">
                <li className="li_navbar">
                    <NavLink to='/con/inicio'>Inicio</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/stock'>Stock</NavLink>
                </li>
                <li className="li_navbar">
                    <NavLink to='/con/orden_salida'>Ordenes de Salida</NavLink>
                </li>
            </ul>
        );
    }
}

const Slidebar = () => {
    return (
        <nav className="navbar">
            {menuTipo()}
        </nav>
    );
}

export default Slidebar;