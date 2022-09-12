import React from 'react'
import '../css/Tabla.css'

function Tabla({ title, headers, data, detallar, actualizar, eliminar }) {



    return (
        <div className='table-cont'>
            <table>
                <tr ><th colSpan="3">{title}</th></tr>
                <tr className='row-table'>
                    {
                        headers.map((header) =>
                            <th>{header}</th>
                        )
                    }
                </tr>
                <tr className='row-table'>
                    <td>8999</td>
                    <td>Amoxicilina</td>
                    <td>Drogopharmacy</td>
                    <td><button className='btn-detallar boton' onClick={detallar}>Detallar</button></td>
                    <td><button className='btn-actualizar boton' onClick={actualizar}>Actualizar</button></td>
                    <td><button className='btn-eliminar boton' onClick={eliminar}>Eliminar</button></td>
                </tr>
                <tr className='row-table'>
                    <td>2345</td>
                    <td>Pastilla</td>
                    <td>Exodo</td>
                    <td><button className='btn-detallar boton' >Detallar</button></td>
                    <td><button className='btn-actualizar boton'>Actualizar</button></td>
                    <td><button className='btn-eliminar boton'>Eliminar</button></td>
                </tr>
                <tr className='row-table'>
                    <td>1234</td>
                    <td>Amoxicilina</td>
                    <td>InkaFarm</td>
                    <td><button className='btn-detallar boton'>Detallar</button></td>
                    <td><button className='btn-actualizar boton'>Actualizar</button></td>
                    <td><button className='btn-eliminar boton'>Eliminar</button></td>
                </tr>

            </table>

        </div>
    )
}

export default Tabla