(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "Container",
            "FIELDS": {
              "sf1": {
                "TYPE": "String",
                "MINLENGTH": 2
              },
              "sf2": {
                "TYPE": "Integer"
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );

  var nErrors = 0;
  var nChanges = 0;

  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++
  });
  var nExcp = 0;
  try {
    t1.f3 = [{sf1: "a", sf2: 15}];
  } catch (e) {
    nExcp++;
  }
  var secondStatus = (nChanges == 1 && nExcp == 1);
  t1.f3 = [{sf1: "aaa", sf2: 20}];
  var thirdStatus = (t1.f3.length == 1 && nChanges == 2 && nExcp == 1);
  var curPath = t1.f3[0]["*sf1"].__getFieldPath();
  var fourthStatus = (curPath == "/f3/0/sf1");
  t1.destruct();
  return secondStatus && thirdStatus && fourthStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})