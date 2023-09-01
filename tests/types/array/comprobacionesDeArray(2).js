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
    t1.f3 = [];
    t1.f3.push("a");
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (t1.f3.length == 1 && nChanges == 2 && nExcp == 1);
  t1.f3.push("aaa");
  var thirdStatus = (t1.f3.length == 2 && nChanges == 3 && nExcp == 1);
  t1.f3.shift();
  var fourthStatus = (t1.f3.length == 1 && nChanges == 4);
  t1.destruct();
  return firstStatus && secondStatus && thirdStatus && fourthStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})