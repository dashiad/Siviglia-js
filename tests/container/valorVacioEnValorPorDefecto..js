(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "q1": {
          "TYPE": "Container",
          "FIELDS": {
            "f1": {
              "TYPE": "String",
              "DEFAULT": "Value3"
            },
            "f2": {
              "TYPE": "String",
              "DEFAULT": "Value3"
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  var v = t1.getPlainValue();
  var status = (v === null);
  t1.q1 = {f1: "qq"};
  v = t1.getPlainValue();
  status = status && v.q1.f1 === "qq" && v.q1.f2 === "Value3";
  t1.destruct();

  return status;

//codeEnd
//callbackEnd
})