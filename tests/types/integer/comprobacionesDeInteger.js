(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Integer",
          "MIN": 10,
          "MAX": 20,
          "DEFAULT": 15
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  var nChanges = 0;
  var nErrors = 0;
  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++

  });
  t1["*f3"].addListener("ERROR", null, function () {
    nErrors++
  });
  var nExcp = 0;

  var firstStatus = (t1.f3 == 15);
  try {
    t1.f3 = 8;
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (nChanges == 0 && nExcp == 1 && nErrors == 1);
  try {
    t1.f3 = 22;
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (nChanges == 0 && nExcp == 2 && nErrors == 2);
  t1.f3 = 16;
  var thirdStatus = (t1.f3 == 16 && nChanges == 1);


  t1.destruct();
  return firstStatus && secondStatus && thirdStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})