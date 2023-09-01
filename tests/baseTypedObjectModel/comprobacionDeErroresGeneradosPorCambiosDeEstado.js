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

  var result = "status" == obj.getStateField();
  result = result && 'None' == obj["*status"].getLabel();
  obj.three = "thr";
  var thrown = false;
  obj.setValue({"status": "None", "three": "qq", "one": "lala"});
  try {
    obj.two = "hola";
  } catch (e) {
    thrown = true;
    result = result && (e.type == "BaseTypedException" && e.code == Siviglia.model.BaseTypedException.ERR_NOT_EDITABLE_IN_STATE);
    result = result && true == obj.__isErrored();
    erroredFields = obj.getErroredFields();
    result = result && 1 == erroredFields.length;
    thrown = true;
  }
  result = result && true == thrown;
  // Ese ultimo cambio de estado no deberia lanzar excepciones
  obj.status = "Other";
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})