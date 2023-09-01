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
  obj.three = "THR";
  obj.status = "Another";
  obj.save();
  var result = true;
  var thrown = false;
  try {
    obj.status = "Other";
  } catch (e) {
    thrown = true;

    var errored = obj.getErroredFields();
    result = result && errored.length === 1;

    var erroredField = errored[0];
    result = result && erroredField.__getFieldName() === "status";
  }
  obj.destruct();
  return result && thrown;

//codeEnd
//callbackEnd
})