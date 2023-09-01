(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Dictionary",
          "ALLOW_EMPTY_VALUES": true,
          "SET_ON_EMPTY": true,
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "sf3": {
                "TYPE": "String",
                "MINLENGTH": 2
              }
            }
          }
        },
        "f4": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "sf3": {
                "TYPE": "String",
                "MINLENGTH": 2
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);

  t1.f3 = {};
  t1.f4 = {};
  var r = t1.getPlainValue();

  var status = typeof r.f3 == "object" && typeof r.f4 == "undefined";
  t1.destruct();
  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})