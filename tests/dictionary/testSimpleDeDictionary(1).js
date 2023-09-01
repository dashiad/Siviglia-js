(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Dictionary",
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
  var nChanges = 0;
  t1["*f3"].addListener("CHANGE", function () {
    nChanges++;
  })
  t1.f3 = {uno: {sf3: "a1"}, dos: {sf3: "a2"}};
  t1.f3.tres = {sf3: "a3"};
  t1.f3.cinco = {sf3: "a4"}
  var nKeys = 0;
  for (var k in t1.f3)
    nKeys++;
  var status = nKeys == 4 && t1.f3.cinco.sf3 == "a4" && nChanges == 3;
  t1.destruct();
  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})