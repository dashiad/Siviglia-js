(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f2": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "f4": {
                "TYPE": "Array",
                "ELEMENTS": {
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
      }
    }
//definitionEnd

//codeInit

  // Se crea una primera instancia, y se le asigna un valor:
  var t1 = new Siviglia.model.BaseTypedObject(def);
  var v = [{sf1: "aa", sf2: 5}, {sf1: "bb", sf2: 15}, {sf1: "cc", sf2: 25}];
  t1.f2 = {aa: {f4: v}};

  var vv = JSON.stringify(t1.getPlainValue());
  var asserts = (vv === "{\"f2\":{\"aa\":{\"f4\":[{\"sf1\":\"aa\",\"sf2\":5},{\"sf1\":\"bb\",\"sf2\":15},{\"sf1\":\"cc\",\"sf2\":25}]}}}");
  t1.destruct();
  return asserts && countListeners() == 0;

//codeEnd
//callbackEnd
})