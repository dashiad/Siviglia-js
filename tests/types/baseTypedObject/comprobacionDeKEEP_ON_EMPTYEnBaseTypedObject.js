(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "String",
          "KEEP_KEY_ON_EMPTY": true
        },
        "f2": {
          "TYPE": "String",
          "KEEP_KEY_ON_EMPTY": false
        }
      }
    }
//definitionEnd

//codeInit
  var t2 = new Siviglia.model.BaseTypedObject(def);
  var r = t2.getPlainValue();
  var status = typeof r["f2"] == "undefined" && r.f1 === null;
  t2.destruct();
  return status;

//codeEnd
//callbackEnd
})