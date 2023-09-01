(function () {
//callbackInit
  def =
//definitionInit
    {
      "TYPE": "Container",
      "FIELDS": {
        "one": {
          "TYPE": "String",
          "REQUIRED": true
        },
        "two": {
          "TYPE": "String",
          "REQUIRED": true
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
  var status = cnt.isDirty() === false && cnt.__hasOwnValue() === false;
  cnt.setValue({"one": "aa", "two": "bbb"});
  status = status && cnt["*one"].isDirty();
  var dFields = cnt.getDirtyFields();
  var keys = [];
  for (var k = 0; k < dFields.length; k++)
    keys.push(dFields[k].__getFieldName());
  status = status && keys.length == 2;
  status = status && keys.indexOf("one") >= 0 && keys.indexOf("two") >= 0;
  cnt.destruct();
  return status;

//codeEnd
//callbackEnd
})