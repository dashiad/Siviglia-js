(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "String",
          "SOURCE": {
            "TYPE": "Array",
            "VALUES": [
              "Value1",
              "Value2",
              "Value3"
            ],
            "LABEL": "LABEL",
            "VALUE": "LABEL"
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);

  var changed = 0;

  t1["*f3"].addListener("CHANGE", null, function (ev, params) {
    changed = 1;
  });
  var nExcp = 0;
  try {
    t1.f3 = "Value4";
    t1.save();

  } catch (e) {
    nExcp++;
  }
  var status = nExcp === 1;

  t1.f3 = "Value3";
  t1.save();
  status = status && t1.f3 === "Value3" && changed == 1;
  t1.destruct();
  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})