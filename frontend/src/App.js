import React from 'react';
import './App.css';
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";

import Login from './pages/Login';
import Inicio from './pages/Inicio';
import Productos from './pages/Productos';
import Proveedores from './pages/Proveedores';
import Contenido from './pages/Contenido';
import GuiaRemision from './pages/GuiaRemision';
import OrdenCompra from './pages/OrdenesCompa';
import OrdenSalida from './pages/OrdenSalida';
import Stock from './pages/Stock';
import Usuarios from './pages/Usuarios';


function App() {
  return (
    <div className="App">
      <Router>
        <Routes>
          <Route path='/' exact={true} element={<Login />} />
          <Route path='/con' element={<Contenido />}>
            <Route index path='inicio' element={<Inicio />} />
            <Route path='productos' element={<Productos />} />
            <Route path='proveedores' element={<Proveedores />} />
            <Route path='stock' element={<Stock />} />
            <Route path='orden_compra' element={<OrdenCompra />} />
            <Route path='guia_remision' element={<GuiaRemision />} />
            <Route path='usuarios' element={<Usuarios />} />
            <Route path='orden_salida' element={<OrdenSalida />} />
          </Route>
        </Routes>
      </Router>
    </div>
  );
}

export default App;
