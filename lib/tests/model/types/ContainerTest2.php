<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");

use PHPUnit\Framework\TestCase;

class ContainerTest2 extends TestCase
{
    function test1()
    {
        $t=new \lib\model\types\Container(["fieldName"=>"a","path"=>"/"],[
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                "two"=>["TYPE"=>"String","DEFAULT"=>"Hola"]
            ]
        ]);
        $p=$t->__getFieldPath();
        $this->assertEquals("/a",$p);
        $this->assertEquals("/a/one",$t->{"*one"}->__getFieldPath());
        $t->setValue(["one"=>"ttt"]);
        $this->assertEquals("ttt",$t->one);
        $this->assertEquals("ttt",$t->{"*one"}->getValue());
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $t->one="t";
    }

    function getStateDefinition1()
    {
        return [
            'STATES' => [
                'E1' => [
                    'FIELDS' => ['EDITABLE' => ['one','two','inner']]
                ],
                'E2' => [
                    'ALLOW_FROM'=>["E1"],
                    'FIELDS' => ['EDITABLE' => ['two','three']]
                ],
                'E3' => [
                    'ALLOW_FROM'=>["E2"],
                    'FINAL'=>true,
                    'FIELDS' => ['REQUIRED' => ['three']]]
            ],
            'FIELD' => 'state'
        ];
    }
    function testDirtyFields()
    {
        $t=new \lib\model\types\Container(["fieldName"=>"a","path"=>"/"],[
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","REQUIRED"=>true],
                "two"=>["TYPE"=>"String","REQUIRED"=>true],
                "state"=>["TYPE"=>"State","VALUES"=>["E1","E2","E3"],"DEFAULT"=>"E1"]
            ],
            'STATES' => $this->getStateDefinition1()
        ]);
        //$t->apply(["one"=>"a","two"=>"b"],\lib\model\types\BaseType::VALIDATION_MODE_NONE);
        $this->assertEquals(false,$t->isDirty());
        $this->assertEquals(false,$t->__hasOwnValue());
        $t->setValue(["one"=>"aa","two"=>"bbb"]);
        $this->assertEquals(true,$t->{"*one"}->isDirty());
        $dFields=$t->getDirtyFields();
        $keys=array_keys($dFields);
        $this->assertEquals(2,count($keys));
        $this->assertEquals("one",$dFields[0]->__getFieldName());
        $this->assertEquals(true,$t->__hasOwnValue());
        $this->assertEquals("two",$dFields[1]->__getFieldName());
    }
    /* En este test se comprueban 2 controladores a la vez, anidados */
    function testNestedDirtyFields()
    {
        $t=new \lib\model\types\Container(["fieldName"=>"a","path"=>"/"],[
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","REQUIRED"=>true],
                "two"=>["TYPE"=>"String","REQUIRED"=>true],
                "state"=>["TYPE"=>"State","VALUES"=>["E1","E2","E3"],"DEFAULT"=>"E1"],
                "inner"=>[
                    "TYPE"=>"Container",
                    "REQUIRED"=>true,
                    "FIELDS"=>[
                        "one"=>["TYPE"=>"String","REQUIRED"=>true],
                        "two"=>["TYPE"=>"String","REQUIRED"=>true],
                        "state"=>["TYPE"=>"State","VALUES"=>["E1","E2","E3"],"DEFAULT"=>"E1"]
                    ],
                    'STATES' => $this->getStateDefinition1()
            ]
            ],
            'STATES' => $this->getStateDefinition1()
        ]);
        //$t->apply(["one"=>"a","two"=>"b"],\lib\model\types\BaseType::VALIDATION_MODE_NONE);
        // Tanto el container interno, como el externo, ni estan sucios, ni tienen valor.
        $this->assertEquals(false,$t->isDirty());
        $this->assertEquals(false,$t->__hasOwnValue());
        $this->assertEquals(false,$t->{"*inner"}->isDirty());
        $this->assertEquals(false,$t->{"*inner"}->__hasOwnValue());

        // Asignamos un campo del container interno.
        // Esto tiene que hacer que ambos containers se pongan a sucio, pero ninguno de los dos tiene valor
        $t->setValue(["inner"=>["one"=>"aa","two"=>"zzz"],"one"=>"aaa","two"=>"bbb"]);
        $this->assertEquals(true,$t->isDirty());
        $this->assertEquals(true,$t->{"*inner"}->isDirty());

        // Ademas, ambos campos tienen que tener campos sucios:
        // Desde el container externo, es el container interno el que esta sucio.
        $dFields=$t->getDirtyFields();
        $foundInner=false;
        for($k=0;$k<count($dFields);$k++){
            if($dFields[$k]->__getFieldName()=="inner")
                $foundInner=true;
        }
        $this->assertEquals(3,count($dFields));
        $this->assertEquals(true,$foundInner);

        // Desde el container interno, es el campo "/one" el que esta sucio.
        $dFields=$t->{"*inner"}->getDirtyFields();
        $foundOne=false;
        $foundTwo=false;
        for($k=0;$k<count($dFields);$k++){
            if($dFields[$k]->__getFieldName()=="one")
                $foundOne=true;
            if($dFields[$k]->__getFieldName()=="two")
                $foundTwo=true;
        }
        $this->assertEquals(2,count($dFields));
        $this->assertEquals(true,$foundOne);
        $this->assertEquals(true,$foundTwo);

    }
    /* Aqui se van a comprobar todas las funcionalidades basicas de estado. Se va a hacer en un solo test para
       no repetir continuamente la misma definicion, alargando este fichero */
    function testState()
    {
        $def=$this->getStateDefinition1();
        $t=new \lib\model\types\Container(["fieldName"=>"a","path"=>"/"],[
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                "two"=>["TYPE"=>"String","DEFAULT"=>"Hola"],
                "three"=>["TYPE"=>"String"],
                "four"=>["TYPE"=>"String"],
                "state"=>["TYPE"=>"State","VALUES"=>["E1","E2","E3"],"DEFAULT"=>"E1"]
            ],
            'STATES' => $def
        ]);
        $st=$t->getStateDef();
        $this->assertEquals(false,$st==null);
        // Primeros tests: testear todas las funcionades de informacion
        $this->assertEquals("E1",$st->getCurrentStateLabel());
        $this->assertEquals("state",$st->getStateField());
        $this->assertEquals(true,$st->hasStates());
        $this->assertEquals(json_encode($def["STATES"]),json_encode($st->getStates()));
        $this->assertEquals("E1",$st->getDefaultState());
        $this->assertEquals($t->{"*state"},$st->getStateFieldObj());
        $this->assertEquals(0, $st->getStateId("E1"));
        $this->assertEquals(2, $st->getStateId("E3"));
        $this->assertEquals(false,$st->isFinalState("E1"));
        $this->assertEquals(true,$st->isFinalState("E3"));
        $this->assertEquals("E1",$st->getStateLabel(0));
        $this->assertEquals("E3",$st->getStateLabel(2));
        $this->assertEquals("E1",$st->getCurrentStateLabel());
        // Nos saltamos por ahora los tests de checkState.
        $this->assertEquals(true,$st->isEditable("one"));
        $this->assertEquals(true, $st->isEditable("two"));
        // Los metodos de isRequired y isFixed, en realidad usan los siguientes metodos. Testear estos metodos es
        // "equivalente" a testear los otros.
        $this->assertEquals(true,$st->isEditableInState("one","E1"));
        $this->assertEquals(true,$st->isEditableInState("two","E1"));
        $this->assertEquals(false,$st->isEditableInState("one","E2"));
        // Comprobamos que tambien funcionan con paths
        $this->assertEquals(false,$st->isEditableInState("/one","E2"));
        $this->assertEquals(true,$st->isEditableInState("state","E2"));
        $this->assertEquals(false,$st->isRequiredForState("three","E1"));
        $this->assertEquals(true,$st->isRequiredForState("three","E3"));
        $this->assertEquals(false,$st->isRequiredForState("one","E1"));
        $this->assertEquals(false,$st->isRequiredForState("three","E1"));
        // Por ahora, no vamos a soportar campos FIXED. No esta clara su utilidad, y su especificacion es diferente
        // a EDITABLE y REQUIRED, lo que añade una ligera complicacion.
        //$this->assertEquals(true,$st->isFixedInState("four","E2"));
        //$this->assertEquals(false,$st->isFixedInState("four","E1"));
        // Todos los metodos anteriores, han hecho uso de existsFieldInStateDefinition, por lo que no lo probamos.

        $this->assertEquals(false,$st->isEditableInState("one","E2"));
        // El siguiente metodo descriptivo es:
        $this->assertEquals(null,$st->getStateTransitions(0));
        $this->assertEquals([0],$st->getStateTransitions(1));
        $this->assertEquals([1],$st->getStateTransitions(2));
        // Siguiente : canTranslateTo
        $this->assertEquals(true,$st->canTranslateTo(1));
        $this->assertEquals(false,$st->canTranslateTo(2));
        // Siguiente: getRequiredFields
        $this->assertEquals([],$st->getRequiredFields(0));
        $reqFields=$st->getRequiredFields("E3");
        $this->assertEquals("three",$reqFields[0]);

    }

    /* Aqui se van a comprobar todas las funcionalidades basicas de estado, una capa sobre las funcionalidades
       del primer test
     */
    function testFunctionalState()
    {
        $def = $this->getStateDefinition1();
        $fullDef= [
        "TYPE" => "Container",
        "FIELDS" => [
            "one" => ["TYPE" => "String", "MINLENGTH" => 2, "MAXLENGTH" => 10],
            "two" => ["TYPE" => "String", "DEFAULT" => "Hola"],
            "three" => ["TYPE" => "String"],
            "four" => ["TYPE" => "String"],
            "state" => ["TYPE" => "State", "VALUES" => ["E1", "E2", "E3"], "DEFAULT" => "E1"]
        ],
        'STATES' => $def
    ];
        $t = new \lib\model\types\Container(["fieldName" => "a", "path" => "/"],$fullDef);
        // Estamos en el estado inicial, que debe ser "E1", por ser el valor por defecto.
        // En este estado, "one" y "two" son editables:
        $t->setValue(["one"=>"AAA","two"=>"BBB"]);
        // Hasta aqui no deben haber saltado excepciones.De hecho, por debajo deberia haberse completado el estado,
        // Ahora si que deberia saltar una excepcion, al modificar un campo que no esta definido como editable:
        $thrown=0;
        try {
            $t->three = "CCC";
        }catch(\Exception $e)
        {
            $thrown=true;
            $className=get_class($e);
            $this->assertEquals('lib\model\BaseTypedException',$className);
            $this->assertEquals($e->getCode(),\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE);
        }
        $this->assertEquals(true,$thrown);

        // Ahora se comienza un cambio de estado.Aqui se va a probar un cambio de estado realizado a base de
        // asignar campos uno a uno, en vez de darle valor a todo el container.Eso tendra que hacerse en una prueba posterior.
        $t->state="E2";
        // Intentamos editar de nuevo el campo "one", pero no es editable en este estado.
        $thrown=0;
        try {
            $t->one = "CCC";
        }catch(\Exception $e)
        {
            $thrown=true;
            $className=get_class($e);
            $this->assertEquals('lib\model\BaseTypedException',$className);
            $this->assertEquals($e->getCode(),\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE);
        }
        $this->assertEquals(true,$thrown);

        // Ahora, se va a intentar cambiar al siguiente estado, E3, pero deberia dar una excepcion, ya que
        // el campo three es requerido en el estado E3:
        $thrown=0;
        try {
            $t->state="E3";
        }catch(\Exception $e)
        {
            $className=get_class($e);
            $this->assertEquals('lib\model\BaseTypedException',$className);
            $this->assertEquals($e->getCode(),\lib\model\BaseTypedException::ERR_REQUIRED_FIELD);
            $thrown=true;
        }
        $this->assertEquals(true,$thrown);

        // Rellenamos el campo faltante, y esperamos que esta vez si que se acepte el cambio de estado
        $t->three="www";
        $thrown=0;
        try {
            $t->state = "E3";
        }catch(\Exception $e)
        {
            $thrown=true;
        }
        $this->assertEquals(0, $thrown);
        $thrown=false;
        // Ahora estamos en un estado final. No deberia ser posible movernos de este estado, por dos motivos:
        // porque ningun otro estado lo tiene en el ALLOW_FROM, y porque es un estado final. Pero es esta
        // condicion la que debe saltar primero.
        try {
            $t->state = "E2";
        }catch(\Exception $e)
        {
            $className=get_class($e);
            $this->assertEquals('lib\model\BaseTypedException',$className);
            $this->assertEquals($e->getCode(),\lib\model\BaseTypedException::ERR_CANT_CHANGE_FINAL_STATE);
            $thrown=true;
        }
        $this->assertEquals(true, $thrown);
    }

    // En este test, se van a comprobar la misma funcionalidad anterior, pero con asignaciones completas del
    // container.El primer campo a asignar en e
    function testFunctionalState2()
    {
        $def = $this->getStateDefinition1();
        $fullDef= [
            "TYPE" => "Container",
            "FIELDS" => [
                "one" => ["TYPE" => "String", "MINLENGTH" => 2, "MAXLENGTH" => 10],
                "two" => ["TYPE" => "String", "DEFAULT" => "Hola"],
                "three" => ["TYPE" => "String"],
                "four" => ["TYPE" => "String"],
                "state" => ["TYPE" => "State", "VALUES" => ["E1", "E2", "E3"], "DEFAULT" => "E1"]
            ],
            'STATES' => $def
        ];
        $t = new \lib\model\types\Container(["fieldName" => "a", "path" => "/"],$fullDef);
        $thrown=false;
        try {
            $t->setValue([
                "state" => "E1",
                "one" => "AAA",
                "two" => "BBB"
            ]);
        }catch(\Exception $e)
        {
            $thrown=true;
        }
        $this->assertEquals(false,$thrown);
        $this->assertEquals(0,$t->state);
        $this->assertEquals("BBB",$t->two);

        // en setValue, no tiene por que haber problemas por establecer un valor incorrecto, siempre que la validacion este a NO_VALIDATION
        // Aqui, se establece un tipo incompleto (setValue)
        // Sin embargo, si que tiene que dar problemas si el tipo de validacion no es none:
        $thrown=false;
        try {
            $t->setValue(["state" => "E3", "one" => "AAA"], \lib\model\types\BaseType::VALIDATION_MODE_COMPLETE);
        }catch(\Exception $e)
        {
            $thrown=true;
            $className=get_class($e);
            $this->assertEquals('lib\model\BaseTypedException',$className);
            $this->assertEquals($e->getCode(),\lib\model\BaseTypedException::ERR_REQUIRED_FIELD);
        }
        $this->assertEquals(true,$thrown);

        // LLamando a apply, no a setValue, podemos poner el mismo valor, aunque sea incorrecto.
        // Aqui, no se está estableciendo el campo three, que es required en ese estado.
        $t->apply(["state"=>"E3","one"=>"AAA"],\lib\model\types\BaseType::VALIDATION_MODE_NONE);
        $this->assertEquals(2,$t->state);
        $this->assertEquals(null, $t->three);
        $this->assertEquals("AAA",$t->one);


    }

}
