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
  /* Aqui se van a comprobar todas las funcionalidades basicas de estado. Se va a hacer en un solo test para
no repetir continuamente la misma definicion, alargando este fichero. */
  var cnt = Siviglia.types.TypeFactory.getType({"fieldName": "a", "path": "/"}, def, null, null);
  // Se asigna un valor vacio para poder tener acceso a los campos.
  cnt.setValue({});
  var st = cnt.getStateDef();
  var status = st !== null;

  status = status && "E1" === st.getCurrentStateLabel();
  status = status && "state" === st.getStateField();
  status = status && true === st.hasStates();
  status = status && JSON.stringify(def["STATES"]) === JSON.stringify(st.getStates());
  status = status && "E1" === st.getDefaultState();
  status = status && cnt["*state"] === st.getStateType();
  status = status && 0 === st.getStateId("E1");
  status = status && 2 === st.getStateId("E3");
  status = status && false === st.isFinalState("E1");
  status = status && true === st.isFinalState("E3");
  status = status && "E1" === st.getStateLabel(0);
  status = status && "E3" === st.getStateLabel(2);
  status = status && "E1" === st.getCurrentStateLabel();
  // Nos saltamos por ahora los tests de checkState.
  status = status && true === st.isEditable("one");
  status = status && true === st.isEditable("two");
  // Los metodos de isRequired y isFixed, en realidad usan los siguientes metodos. Testear estos metodos es
  // "equivalente" a testear los otros.
  status = status && true === st.isEditableInState("one", "E1");
  status = status && true === st.isEditableInState("two", "E1");
  status = status && false === st.isEditableInState("one", "E2");
  // Comprobamos que tambien funcionan con paths
  status = status && false === st.isEditableInState("/one", "E2");
  status = status && true === st.isEditableInState("state", "E2");
  status = status && false === st.isRequiredForState("three", "E1");
  status = status && true === st.isRequiredForState("three", "E3");
  status = status && false === st.isRequiredForState("one", "E1");
  status = status && false === st.isRequiredForState("three", "E1");
  // Por ahora, no vamos a soportar campos FIXED. No esta clara su utilidad, y su especificacion es diferente
  // a EDITABLE y REQUIRED, lo que añade una ligera complicacion.
  //status = status && true,st.isFixedInState("four","E2");
  //status = status && false,st.isFixedInState("four","E1");
  // Todos los metodos anteriores, han hecho uso de existsFieldInStateDefinition, por lo que no lo probamos.

  status = status && false === st.isEditableInState("one", "E2");
  // El siguiente metodo descriptivo es:
  status = status && null === st.getStateTransitions(0);
  var st1 = st.getStateTransitions(1);
  status = status && st1.length == 1 && st1[0] == 0;
  var st2 = st.getStateTransitions(2);
  status = status && st2.length == 1 && st2[0] == 1;
  // Siguiente : canTranslateTo
  status = status && true === st.canTranslateTo(1);
  status = status && false === st.canTranslateTo(2);
  // Siguiente: getRequiredFields
  status = status && st.getRequiredFields("E1").length === 0;
  var reqFields = st.getRequiredFields("E3");
  status = status && "three" === reqFields[0];
  cnt.destruct();
  return status;

//codeEnd
//callbackEnd
})