(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "field1": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "Container",
            "FIELDS": {
              "F1": {
                "TYPE": "String"
              }
            }
          }
        },
        "field2": {
          "TYPE": "Array",
          "ELEMENTS": {
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
  var arr = [{"F1": "ab"}, {"F1": "cdefgth"}, {"F1": "yy"}, {"F1": "uu"}];
  var f1_changes = 0;
  var f2_changes = 0;

  t1["*field1"].addListener("CHANGE", null, function (evName, param) {
    f1_changes++;
  })
  t1["*field2"].addListener("CHANGE", null, function (evName, param) {
    f2_changes++;
  })
  t1.field1 = arr;
  var status = f1_changes === 1;
  var exceptionThrown = false;
  try {
    t1.field2 = t1.field1;
  } catch (e) {
    exceptionThrown = true;
  }

  status = status && exceptionThrown && f1_changes == 1 && f2_changes == 1;

  t1.destruct();
  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})