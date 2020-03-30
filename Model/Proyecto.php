<?php
/**
 * This file is part of Proyectos plugin for FacturaScripts
 * Copyright (C) 2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\Proyectos\Model;

use FacturaScripts\Core\Model\Base;

/**
 * Description of Proyecto
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Proyecto extends Base\ModelOnChangeClass
{

    use Base\ModelTrait;

    /**
     *
     * @var string
     */
    public $codalmacen;

    /**
     *
     * @var string
     */
    public $codcliente;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var bool
     */
    public $editable;

    /**
     *
     * @var string
     */
    public $fecha;

    /**
     *
     * @var string
     */
    public $fechafin;

    /**
     *
     * @var string
     */
    public $fechainicio;

    /**
     *
     * @var integer
     */
    public $idempresa;

    /**
     *
     * @var integer
     */
    public $idestado;

    /**
     *
     * @var integer
     */
    public $idproyecto;

    /**
     *
     * @var string
     */
    public $nick;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var bool
     */
    public $privado;

    public function clear()
    {
        parent::clear();
        $this->editable = true;
        $this->fecha = \date(self::DATE_STYLE);
        $this->privado = false;

        /// select default status
        foreach ($this->getAvaliableStatus() as $status) {
            if ($status->predeterminado) {
                $this->editable = $status->editable;
                $this->idestado = $status->idestado;
                break;
            }
        }
    }

    /**
     * 
     * @return EstadoProyecto[]
     */
    public function getAvaliableStatus()
    {
        $avaliable = [];
        $statusModel = new EstadoProyecto();
        foreach ($statusModel->all([], [], 0, 0) as $status) {
            $avaliable[] = $status;
        }

        return $avaliable;
    }

    /**
     * 
     * @return EstadoProyecto
     */
    public function getStatus()
    {
        $status = new EstadoProyecto();
        $status->loadFromCode($this->idestado);
        return $status;
    }

    /**
     * 
     * @return string
     */
    public function install()
    {
        /// needed dependencies
        new EstadoProyecto();

        return parent::install();
    }

    /**
     * 
     * @return string
     */
    public static function primaryColumn(): string
    {
        return 'idproyecto';
    }

    /**
     * 
     * @return string
     */
    public function primaryDescriptionColumn(): string
    {
        return 'nombre';
    }

    /**
     * 
     * @return string
     */
    public static function tableName(): string
    {
        return 'proyectos';
    }

    /**
     * 
     * @param string $field
     *
     * @return bool
     */
    protected function onChange($field)
    {
        switch ($field) {
            case 'idestado':
                $this->editable = $this->getStatus()->editable;
                return true;
        }

        return parent::onChange($field);
    }

    /**
     * 
     * @param array $fields
     */
    protected function setPreviousData(array $fields = [])
    {
        parent::setPreviousData(\array_merge(['idestado'], $fields));
    }
}