(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "String",
            "MINLENGTH": 2
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  var firstStatus = (t1.f3 === null);
  var nErrors = 0;
  var nChanges = 0;

  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++
  });
  var nExcp = 0;
  try {
    t1.f3 = ["u"];
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (nChanges == 1 && nExcp == 1);
  t1.f3 = ["uu"];
  var thirdStatus = (t1.f3.length == 1 && t1.f3[0] == "uu" && nChanges == 2);
  t1.destruct();
  return firstStatus && secondStatus && thirdStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})