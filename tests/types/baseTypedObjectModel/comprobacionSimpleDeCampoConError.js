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
  obj.one = "one";
  var thrown = false;
  var result = true;
  try {
    obj.one = "w";
  } catch (e) {
    thrown = true;
    result = result && (e.type == "StringException" && e.code == Siviglia.types.StringException.ERR_TOO_SHORT);
  }
  result = result && true == thrown;
  result = result && true == obj.__isErrored();
  var errored = obj.getErroredFields();
  result = result && 1 == errored.length;
  result = result && true == obj["*one"].__isErrored();
  result = result && true == obj["*one"].__getError() !== null;
  result = result && "/one" == errored[0].__getFieldPath();
  // Le damos ahora un valor valido. Deberia borrarse el error.
  obj.one = "ssss";
  result = result && false == obj["*one"].__isErrored();
  result = result && null == obj["*one"].__getError();
  result = result && false == obj.__isErrored();
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})