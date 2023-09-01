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
        },
        "inner": {
          "TYPE": "Container",
          "REQUIRED": true,
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
      },
      "STATES": {
        "STATES": {
          "E1": {
            "FIELDS": {
              "EDITABLE": [
                "one",
                "two",
                "inner"
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


  // Asignamos un campo del container interno.
  // Esto tiene que hacer que ambos containers se pongan a sucio, pero ninguno de los dos tiene valor
  cnt.setValue({"inner": {"one": "aa", "two": "zzz"}, "one": "aaa", "two": "bbb"});
  status = status && cnt.isDirty() && cnt["*inner"].isDirty();

  // Ademas, ambos campos tienen que tener campos sucios:
  // Desde el container externo, es el container interno el que esta sucio.
  var dFields = cnt.getDirtyFields();
  var foundInner = false;
  for (var k = 0; k < dFields.length; k++) {
    if (dFields[k].__getFieldName() == "inner")
      foundInner = true;
  }
  status = status && foundInner && 3 === dFields.length;


  // Desde el container interno, es el campo "/one" el que esta sucio.
  dFields = cnt["*inner"].getDirtyFields();
  var foundOne = false;
  var foundTwo = false;
  for (var k = 0; k < dFields.length; k++) {
    if (dFields[k].__getFieldName() == "one")
      foundOne = true;
    if (dFields[k].__getFieldName() == "two")
      foundTwo = true;
  }
  status = status && dFields.length == 2 && foundOne && foundTwo;
  cnt.destruct();
  return status;

//codeEnd
//callbackEnd
})