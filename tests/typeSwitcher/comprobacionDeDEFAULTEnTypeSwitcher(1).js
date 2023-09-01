(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "CONTENT_FIELD": "value",
          "ALLOWED_TYPES": {
            "TIPO3": {
              "TYPE": "Container",
              "FIELDS": {
                "sf3": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "sf4": {
                  "TYPE": "Integer"
                }
              }
            },
            "TIPO4": {
              "TYPE": "Container",
              "FIELDS": {
                "sf1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "sf2": {
                  "TYPE": "Integer"
                }
              }
            }
          },
          "DEFAULT": {
            "TYPE": "TIPO3",
            "value": {
              "sf3": "aaa",
              "sf4": 10
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);

  var v = t1.f3.value.sf4;
  var status = v == 10;
  t1.destruct();
  return status;

//codeEnd
//callbackEnd
})