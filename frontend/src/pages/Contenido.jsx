import React from "react";
import Navbar from "../components/Navbar";
import Slidebar from '../components/Slidebar';
import { Outlet } from 'react-router-dom';

const Contenido = () => {
  return (
    <div>
      <div className='cont_navBar'>
        <Navbar />
        <Slidebar />
      </div>
      <div className="content">

        <Outlet />
      </div>

    </div>
  );
}

export default Contenido;