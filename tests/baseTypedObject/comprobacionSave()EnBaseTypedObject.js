(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "String",
          "REQUIRED": true
        },
        "f2": {
          "TYPE": "String"
        }
      }
    }
//definitionEnd

//codeInit
  var t2 = new Siviglia.model.BaseTypedObject(def);
  t2.f2 = "Lala";
  var e1 = 0;
  var errors = null;
  try {
    t2.save();

  } catch (e) {
    if (e.code && e.code == Siviglia.model.BaseTypedException.ERR_REQUIRED_FIELD)
      e1 = 1;

  }
  t2.f1 = "qqq";
  try {

    var errors = t2.save();
    if (errors && errors.length > 0)
      e1 = 2;
  } catch (e) {
  }
  t2.destruct();
  return e1 === 1;

//codeEnd
//callbackEnd
})