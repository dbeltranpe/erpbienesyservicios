<?php

/**
 * Interface que representa el Data Access Object (DAO) de los usuarios
 * @author Daniel Beltrán Penagos
 * 
 * <br><br>
 * <center> <b> Universidad El Bosque<br>
 * Ingeniería de Software<br>
 * Profesor Ricardo Camargo Lemos <br>
 * Proyecto E.R.P Bienes y Servicios de Manufactura</b> </center>
 */
interface iCompraDAO
{
    public function save($user, $arrNoms, $arrCans, $arrPres, $arrTots, $pro, $pago, $time, $totCan, $totSum);
}
?>