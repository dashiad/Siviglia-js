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
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.f3 = {TYPE: "TIPO3", value: {sf3: "lala"}};
  var path = t1.f3.value["*sf3"].__getFieldPath();
  t1.destruct();
  return path == "/f3/value/sf3";

//codeEnd
//callbackEnd
})