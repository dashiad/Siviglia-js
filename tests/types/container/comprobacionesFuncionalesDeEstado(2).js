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
  var thrown = false;
  var result = true;
  try {
    cnt.setValue({
      "state": "E1",
      "one": "AAA",
      "two": "BBB"
    });
  } catch (e) {
    thrown = true;
  }
  result = result && false === thrown;
  result = result && 0 === cnt.state;
  result = result && "BBB" === cnt.two;

  // en setValue, no tiene por que haber problemas por establecer un valor incorrecto, siempre que la validacion este a NO_VALIDATION
  // Aqui, se establece un tipo incompleto (setValue)
  // Sin embargo, si que tiene que dar problemas si el tipo de validacion no es none:
  thrown = false;
  try {
    cnt.setValue({"state": "E3", "one": "AAA"}, Siviglia.types.BaseType.VALIDATION_MODE_COMPLETE);
  } catch (e) {
    thrown = true;
    result = result && e.path === "/a/state" && (e.type == "BaseTypedException" && e.code == Siviglia.model.BaseTypedException.ERR_INVALID_STATE_TRANSITION);
  }
  result = result && true == thrown;

  // LLamando a apply, no a setValue, podemos poner el mismo valor, aunque sea incorrecto.
  // Aqui, no se está estableciendo el campo three, que es required en ese estado.
  cnt.apply({"state": "E3", "one": "AAA"}, Siviglia.types.BaseType.VALIDATION_MODE_NONE);
  result = result && 2 === cnt.state;
  result = result && null === cnt.three;
  result = result && "AAA" === cnt.one;
  cnt.destruct();
  return result;


//codeEnd
//callbackEnd
})