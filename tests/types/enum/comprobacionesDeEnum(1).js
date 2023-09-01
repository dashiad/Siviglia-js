(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Enum",
          "VALUES": [
            "Value1",
            "Value2",
            "Value3"
          ],
          "DEFAULT": "Value3"
        }
      }
    }
//definitionEnd

//codeInit

  var t1 = new Siviglia.model.BaseTypedObject(def);

  var nChanges = 0;
  var errored = 0;
  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++
  });
  t1["*f3"].addListener("ERROR", null, function () {
    errored++;
  });
  var nExcp = 0;
  try {
    t1.f3 = "Value4";
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (errored == 1 && nChanges == 0 && nExcp == 1);
  t1.f3 = "Value1";
  var thirdStatus = (t1.f3 == 0 && t1["*f3"].getLabel() == "Value1" && nChanges == 1 && nExcp == 1);
  t1.f3 = 2;
  var fourthStatus = (t1["*f3"].getLabel() == "Value3" && nChanges == 2 && nExcp == 1);

  t1.destruct();
  return secondStatus && thirdStatus && fourthStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})