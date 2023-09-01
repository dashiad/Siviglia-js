(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "one": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "two": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "three": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "four": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "five": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "status": {
          "TYPE": "State",
          "VALUES": [
            "None",
            "Other",
            "Another"
          ],
          "DEFAULT": "None"
        }
      },
      "STATES": {
        "STATES": {
          "None": {
            "FIELDS": {
              "EDITABLE": [
                "one",
                "three",
                "five"
              ]
            }
          },
          "Other": {
            "ALLOW_FROM": [
              "None"
            ],
            "FIELDS": {
              "EDITABLE": [
                "two"
              ],
              "FIXED": [
                "one"
              ]
            }
          },
          "Another": {
            "FIELDS": {
              "EDITABLE": [
                "one"
              ],
              "REQUIRED": [
                "three"
              ]
            }
          },
          "Last": {
            "FIELDS": {
              "EDITABLE": [
                "one"
              ],
              "REQUIRED": [
                "three"
              ]
            }
          }
        },
        "FIELD": "status",
        "DEFAULT": "None"
      }
    }
//definitionEnd

//codeInit

  var obj = new Siviglia.model.BaseTypedObject(def);
  obj.status = "None";
  var thrown = false;
  var result = true;

  try {
    obj.status = "Another";
  } catch (e) {
    thrown = true;
    var errored = obj.getErroredFields();
    result = result && 2 == errored.length;
    result = result && "/three" == errored[0].__getFieldPath();
    var exception = errored[0].__getError();
    result = result && exception.type == 'BaseTypedException';
    result = result && exception.code == Siviglia.model.BaseTypedException.ERR_REQUIRED_FIELD;

    exception = errored[1].__getError();
    result = result && exception.type == 'BaseTypedException';
    result = result && exception.code == Siviglia.model.BaseTypedException.ERR_INVALID_STATE_TRANSITION;
    result = result && exception.path === "/status";
    thrown = true;
  }


  // Ahora, establecemos el campo que nos falta, y vemos si al establecer ese campo, el cambio de estado
  // se completa, y el container pasa a tener cero errores.
  obj.three = "lala";
  var nErrors = obj.getErroredFields();

  result = result && true === thrown && nErrors.length === 0 && obj["*status"].__isErrored() === false;
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})