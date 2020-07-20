<?php
namespace model\web\Jobs;

use lib\model\BaseTypedObject;
use lib\model\BaseException;
use PharIo\Manifest\Type;

class BaseWorkerDefinitionException extends BaseException 
{
    // TODO: declarar posibles errores
    const ERR_TEST = 1;
    const TXT_TEST = "Error in field [%field%]";
}

class BaseWorkerDefinition extends BaseTypedObject
{
    /**
     * 
     * @property Enum Type
     * @property String name
     * @property Int max_running_children
     * @property Int max_retries
     * @property String on
     * @property Bool run_on_partials
     * @property Container task
     * @property Container jobs
     * @property Container job
     */
    static public $definition;
    static public $baseDefinition = [
        'FIELDS' => [
            'type' => [
                'TYPE'   => 'Enum',
                'LABEL'  => 'Tipo',
                'VALUES' => [
                    'job',
                    'task',
                    'trigger',
                ],
            ],
            'name' => [
                'TYPE'       => 'String',
                'LABEL'      => 'Nombre descriptivo',
                'SHORTLABEL' => 'Nombre',
                'MINLENGTH'  => 2,
                'MAXLENGTH'  => 128,
            ],
            'max_running_children' => [
                'TYPE'       => 'Integer',
                'LABEL'      => 'Máximo número de procesos simultáneos',
                'SHORTLABEL' => 'Máx.procesos',
                'MIN'        => 1,
                'MAX'        => 65535,
                'DEFAULT'    => 64,
            ],
            'max_retries' => [
                'TYPE'    => 'Integer',
                'LABEL'   => 'Reintentos máximos',
                'MIN'     => 0,
                'MAX'     => 64,
                'DEFAULT' => 0,
            ],
            'on' => [  // tipo de job que dispara el trigger
                'TYPE'       => 'String',
                'LABEL'      => 'Disparado por',
                'SHORTLABEL' => 'Disparador',
                'MINLENGTH'  => 2,
                'MAXLENGTH'  => 128,
                'DEFAULT'    => null,
            ],
            'run_on_partials' => [ // el trigger salta con resultados parciales
                'TYPE'       => 'Boolean',
                'LABEL'      => 'Ejecutar por partes',
                'SHORTLABEL' => 'Parciales',
                'DEFAULT'    => false,
            ],
            'jobs' => [ // lista de jobs a ejecutar en paralelo
                'TYPE'       => 'Container',
                'LABEL'      => 'Trabajos dependientes',
                'SHORTLABEL' => 'Trabajos',
                'FIELDS'    => null,
            ],
            'task' => [ // tarea final (relacionada con un worker)
                'TYPE'    => 'Container',
                'LABEL'   => 'Tarea',
                'FIELDS' => null,
            ],
            'job'  => [ // job a lanzar mediante un trigger
                'TYPE'    => 'Container',
                'LABEL'   => 'Trabajo',
                'FIELDS' => null,
            ],
        ],
        'PERMISSIONS' => [],
    ];
    
    const TYPE_SINGLE   = 0;
    const TYPE_MULTIPLE = 1;
    const TYPE_TRIGGER  = 2;
    
    const VARIABLE_FIELDS = [
        self::TYPE_SINGLE   => 'task',
        self::TYPE_MULTIPLE => 'jobs',
        self::TYPE_TRIGGER  => 'job',
    ];
    
    protected $type = self::TYPE_SINGLE;
    
    public $actDef;
    
    public function __construct($def=null)
    {
        $this->actDef = static::$baseDefinition;
        if (isset($this->type)) {
            $this->actDef['FIELDS'][static::VARIABLE_FIELDS[$this->type]]['FIELDS'] = static::$definition;
        }
        parent::__construct($this->actDef);
    }
    
    public function getDefinition()
    {
        return $this->actDef;
    }
}