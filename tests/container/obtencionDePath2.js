(function () {
//callbackInit
  def =
//definitionInit
    {
      "TYPE": "Container",
      "FIELDS": {
        "one": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "Container",
            "FIELDS": {
              "f1": {
                "TYPE": "Dictionary",
                "VALUETYPE": {
                  "TYPE": "Container",
                  "FIELDS": {
                    "q1": {
                      "TYPE": "String"
                    },
                    "q2": {
                      "TYPE": "Integer"
                    }
                  }
                },
                "ALLOW_NULL_VALUES": false
              },
              "f2": {
                "TYPE": "TypeSwitcher",
                "TYPE_FIELD": "Type",
                "CONTENT_FIELD": "Value",
                "ALLOWED_TYPES": {
                  "String": {
                    "TYPE": "String"
                  },
                  "Integer": {
                    "TYPE": "Integer"
                  }
                }
              }
            }
          }
        },
        "two": {
          "TYPE": "String"
        }
      }
    }
//definitionEnd

//codeInit
  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  cnt.setValue(
    {
      "one": [
        {
          "f1": {
            "k1-1": {"q1": "1", "q2": 2},
            "k1-2": {"q1": "3", "q2": 4},
            "k1-3": {"q1": "5", "q2": 6}
          },
          "f2": {
            "Type": "String", "Value": "hola"
          }
        },

        {
          "f1": {
            "k2-1": {"q1": "7", "q2": 8},
            "k2-2": {"q1": "9", "q2": 10},
          },
          "f2": {
            "Type": "String", "Value": "hola"
          }
        }
      ],
      "two": "Lala"
    }
  );
  var val = cnt.getPath("#one/0/f1");
  var status = typeof val["k1-1"] !== "undefined" && typeof val["k1-2"] !== "undefined" && typeof val["k1-3"] !== "undefined";
  cnt.destruct();

  status = status && countListeners() == 0
  return status;

//codeEnd
//callbackEnd
})