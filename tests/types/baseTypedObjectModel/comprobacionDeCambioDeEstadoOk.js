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
  obj.three = "Test";
  obj.status = "Another";
  var result = "Another" == obj["*status"].getLabel();
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})