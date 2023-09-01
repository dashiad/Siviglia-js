(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 7,
          "DEFAULT": "Valido"
        },
        "f4": {
          "TYPE": "String",
          "REGEXP": "/aa/"
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
  t1["*f4"].addListener("CHANGE", null, function () {
    nChanges++
  });
  t1["*f4"].addListener("ERROR", null, function () {
    nErrors++
  });
  var nExcp = 0;
  var firstStatus = (t1.f3 == "Valido");
  try {
    t1.f3 = "a"
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (nErrors == 1 && nChanges == 0 && nExcp == 1);
  try {
    t1.f3 = "aaaaaaaa"
  } catch (e) {
    nExcp++;
  }
  var thirdStatus = (nErrors == 2 && nChanges == 0 && nExcp == 2);
  t1.f3 = "bbb";
  var fourthStatus = (t1.f3 == "bbb" && nChanges == 1 && nExcp == 2);
  try {
    t1.f4 = "ccc";
  } catch (e) {
    nExcp++;
  }
  var fifthStatus = (nErrors == 3 && nChanges == 1 && nExcp == 3);
  t1.f4 = "aaaa";

  var sixthStatus = (t1.f4 == "aaaa" && nChanges == 2 && nExcp == 3 && nErrors == 3);


  t1.destruct();
  return firstStatus && secondStatus && thirdStatus &&
    fifthStatus && sixthStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})