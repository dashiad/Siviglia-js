(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "field1": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "F1": {
                "TYPE": "String"
              }
            }
          }
        },
        "field2": {
          "TYPE": "Dictionary",
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "F1": {
                "TYPE": "String",
                "MAXLENGTH": 3
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
  var dict = {"a": {"F1": "ab"}, "b": {"F1": "cdefgth"}, "c": {"F1": "yy"}, "d": {"F1": "uu"}};
  var f1_changes = 0;
  var f2_changes = 0;

  t1["*field1"].addListener("CHANGE", null, function (evName, param) {
    f1_changes++;
  })
  t1["*field2"].addListener("CHANGE", null, function (evName, param) {
    f2_changes++;
  })
  t1.field1 = dict;
  var status = f1_changes === 1;
  var exceptionThrown = false;
  try {
    t1.field2 = t1.field1;
  } catch (e) {
    exceptionThrown = true;
  }

  status = status && exceptionThrown && f1_changes == 1 && f2_changes == 1;

  var d1s = t1.field1;

  d1s["x"] = {"F1": "ttt"};
  status = status && f1_changes === 2;
  delete d1s["a"];
  status = status && f1_changes === 3 && typeof (t1.field1.a) === "undefined";

  var d2s = t1.field2;
  exceptionThrown = false;
  try {
    d2s["x"] = {"F1": "xxxxxxxx"}
  } catch (e) {
    exceptionThrown = true;
  }

  status = status && f2_changes === 2;

  t1.destruct();

  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})