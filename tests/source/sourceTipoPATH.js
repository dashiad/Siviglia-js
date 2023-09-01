(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "String",
          "SOURCE": {
            "TYPE": "Path",
            "PATH": "#../f4/[[KEYS]]",
            "LABEL": "LABEL",
            "VALUE": "VALUE"
          }
        },
        "f4": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "String"
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  var nChanges3 = 0;
  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges3++
  });
  var nChanges4 = 0;
  t1["*f4"].addListener("CHANGE", null, function () {
    nChanges4++
  });
  var nExcp = 0;

  try {
    t1.f3 = "aa";
  } catch (e) {
    nExcp++;
  }
  var nSources = 0;
  t1["*f3"].__getSource().addListener("CHANGE", null, function () {
    nSources++;
  });
  var status = t1["*f3"].__isErrored();
  t1.f4 = {"aa": "uno", "bb": "dos"};
  // Al cambiar f4 a ese valor, f3 pasa a ser valido, asi que habra cambiado, y ya no tendra error
  status = status && t1["*f3"].__isErrored() === false && (nChanges4 == 1 && nChanges3 === 1 && nSources == 1);

  t1.save();
  status = status && (nChanges3 === 1 && t1.f3 == "aa");
  t1.f4["cc"] = "tres";
  status = status && (nChanges4 == 2 && nSources == 2);

  t1.f3 = "cc";
  t1.save();
  status = status && (nChanges3 === 2 && t1.f3 == "cc");
  t1.destruct();
  status = status && countListeners() === 0;
  return status;

//codeEnd
//callbackEnd
})