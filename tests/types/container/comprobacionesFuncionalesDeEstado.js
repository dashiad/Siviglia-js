(function () {
//callbackInit
  def =
//definitionInit
    {
      "TYPE": "Container",
      "FIELDS": {
        "one": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 10
        },
        "two": {
          "TYPE": "String",
          "DEFAULT": "Hola"
        },
        "three": {
          "TYPE": "String"
        },
        "four": {
          "TYPE": "String"
        },
        "state": {
          "TYPE": "State",
          "VALUES": [
            "E1",
            "E2",
            "E3"
          ],
          "DEFAULT": "E1"
        }
      },
      "STATES": {
        "STATES": {
          "E1": {
            "FIELDS": {
              "EDITABLE": [
                "one",
                "two"
              ]
            }
          },
          "E2": {
            "ALLOW_FROM": [
              "E1"
            ],
            "FIELDS": {
              "EDITABLE": [
                "two",
                "three"
              ]
            }
          },
          "E3": {
            "ALLOW_FROM": [
              "E2"
            ],
            "FINAL": true,
            "FIELDS": {
              "REQUIRED": [
                "three"
              ]
            }
          }
        },
        "FIELD": "state"
      }
    }
//definitionEnd

//codeInit

  var cnt = Siviglia.types.TypeFactory.getType({"fieldName": "a", "path": "/"}, def, null, null);
  // Se asigna un valor vacio para poder tener acceso a los campos.
  // Estamos en el estado inicial, que debe ser "E1", por ser el valor por defecto.
  // En este estado, "one" y "two" son editables:
  cnt.setValue({"one": "AAA", "two": "BBB"});
  // Hasta aqui no deben haber saltado excepciones.De hecho, por debajo deberia haberse completado el estado,
  // Ahora si que deberia saltar una excepcion, al modificar un campo que no esta definido como editable:
  var thrown = false;
  var result = true;
  try {
    cnt.three = "CCC";
  } catch (e) {
    thrown = true;
    result = result && e.path === "/a/three" && (e.type == "BaseTypedException" && e.code == Siviglia.model.BaseTypedException.ERR_NOT_EDITABLE_IN_STATE);
  }
  result = result && thrown;

  // Ahora se comienza un cambio de estado.Aqui se va a probar un cambio de estado realizado a base de
  // asignar campos uno a uno, en vez de darle valor a todo el container.Eso tendra que hacerse en una prueba posterior.
  cnt.state = "E2";
  // Intentamos editar de nuevo el campo "one", pero no es editable en este estado.
  thrown = false;
  try {
    cnt.one = "CCC";
  } catch (e) {
    thrown = true;
    result = result && (e.type == "BaseTypedException" && e.code == Siviglia.model.BaseTypedException.ERR_NOT_EDITABLE_IN_STATE);
  }
  result = result && thrown;

  // Ahora, se va a intentar cambiar al siguiente estado, E3, pero deberia dar una excepcion, ya que
  // el campo three es requerido en el estado E3:
  thrown = false;
  try {
    cnt.state = "E3";
  } catch (e) {
    // Ahora mismo, hay 3 campos con errores: one, (de arriba), state y three (que es requerido)
    result = result && e.path === "/a/state" && (e.type == "BaseTypedException" && e.code == Siviglia.model.BaseTypedException.ERR_INVALID_STATE_TRANSITION)
      && cnt.getErroredFields().length === 3;
    thrown = true;
  }
  result = result && thrown && cnt["*state"].__isErrored() == true;

  // Rellenamos el campo faltante, y esperamos que esta vez si que se acepte el cambio de estado
  cnt.three = "www";
  // Y en este punto, cnt.state debe haber completado su transicion:
  result = result && cnt.state === 2;

  // Ahora estamos en un estado final. No deberia ser posible movernos de este estado, por dos motivos:
  // porque ningun otro estado lo tiene en el ALLOW_FROM, y porque es un estado final. Pero es esta
  // condicion la que debe saltar primero.
  thrown = false;
  try {
    cnt.state = "E2";
  } catch (e) {
    result = result && (e.type == "BaseTypedException" && e.code == Siviglia.model.BaseTypedException.ERR_CANT_CHANGE_FINAL_STATE);
    thrown = true;
  }
  cnt.destruct();
  return result && thrown;

//codeEnd
//callbackEnd
})